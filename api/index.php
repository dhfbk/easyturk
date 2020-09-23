<?php

/*

# nginx config
location / {
    if ($request_method = 'OPTIONS') {
        add_header 'Access-Control-Allow-Origin' '*';
        add_header 'Access-Control-Allow-Methods' "POST,GET,DELETE,PUT,OPTIONS";
        add_header 'Access-Control-Allow-Headers' 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type';
        add_header 'Access-Control-Max-Age' 1728000;
        return 204;
    }
}

*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Accept, Referer, User-Agent, Content-Type, Origin, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// ini_set("display_errors", "On");

// require_once 'vendor/autoload.php';
// print_r($_REQUEST);

$script_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}";

session_start();

require_once("config.php");
require_once("inc/include.php");
require_once("inc/Mysql_connector.class.php");
require_once("vendor/autoload.php");

$Action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$User = 1;

$DB = new Mysql_connector($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
$DB->select_db($DB_NAME);

$mysqli = $DB->connection;

$_SESSION['Options'] = loadOptions();
if (!isset($_SESSION['Options'])) {
    $_SESSION['Options'] = loadOptions();
}
$Options = $_SESSION['Options'];

// require_once("update.php");

$ret = array();
$ret['debug'] = array();
$ret['debug']['request'] = $_REQUEST;

$json_params = file_get_contents("php://input");
if (strlen($json_params) > 0 && isValidJSON($json_params)) {
    $values = json_decode($json_params, true);
    // $ret['values'] = print_r($values, true);
    $_POST = $values;
    foreach ($_POST as $key => $value) {
        $_REQUEST[$key] = $value;
    }
}

$Delimiters = array("tab" => '\t', "comma" => ',', "semicolon" => ';');
$Enclosures = array("single" => '\'', "double" => '\"', "none" => chr(8));

// $ret['comments'] = $json_params;
// $ret['post'] = print_r($_POST, true);

$UserInfo = find("users", $User, "User not found");
$UseSandbox = true;
$mTurkOptions = [
    'version' => 'latest',
    'region'  => $UserInfo['region_name'],
    'credentials' => [
        'key' => $UserInfo['aws_access_key_id'],
        'secret' => $UserInfo['aws_secret_access_key'],
    ]
];
if ($UseSandbox) {
    $mTurkOptions['endpoint'] = 'https://mturk-requester-sandbox.us-east-1.amazonaws.com';
}
$mTurk = new Aws\MTurk\MTurkClient($mTurkOptions);

switch ($Action) {
    case "listProjects":
    case "addProject":
    case "deleteProject":
    case "editProject":
    case "uploadFile":
    case "deleteFile":
    case "getData":
    case "getUserInfo":
    case "getProjectInfo":

        // if (!$_SESSION['Admin']) {
        //     $ret['result'] = "ERR";
        //     $ret['error'] = $Lang["not_logged"];
        //     break;
        // }

        switch ($Action) {

            case "getUserInfo":
                $ret['data'] = array();
                $balance = $mTurk->getAccountBalance()->get('AvailableBalance');
                $ret['data']['balance'] = $balance;
                $ret['data']['username'] = $UserInfo['username'];
                $ret['data']['common_name'] = $UserInfo['common_name'];
                $ret['data']['use_sandbox'] = $UseSandbox;
                $ret['result'] = "OK";
                break;

            case "getData":
                $isGold = boolval($_REQUEST['isGold']);
                $project = find("projects", $_REQUEST['id'], "Project not found");
                $isGold = $isGold ? 1 : 0;
                $howMany = $_REQUEST['howMany'] ? $_REQUEST['howMany'] : 50;
                $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;

                if (!is_numeric($howMany)) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "howMany parameter must be numeric.";
                    break;
                }

                if (!is_numeric($page)) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "page parameter must be numeric.";
                    break;
                }

                $offset = $howMany * ($page - 1);

                $query = "SELECT * FROM project_files
                    WHERE project_id = '{$project['id']}' AND deleted = 0 AND is_gold = '$isGold'";
                if (!$DB->querynum($query)) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "File not found.";
                    break;
                }
                $r = $DB->fetch();

                $query = "SELECT line_text FROM file_lines
                    WHERE file_id = '{$r['id']}'";
                if (!$num = $DB->querynum($query)) {
                    $ret['result'] = "ERR";
                    $ret['error'] = $DB->get_error();
                    break;
                }

                $query = "SELECT line_text FROM file_lines
                    WHERE file_id = '{$r['id']}'
                    ORDER BY id
                    LIMIT $offset, $howMany";
                $DB->query($query);

                $data = array();
                while ($row = $DB->fetch()) {
                    $data[] = unserialize($row['line_text']);
                }
                $ret['result'] = "OK";
                $ret['data'] = $data;
                $ret['num'] = $num;
                $ret['filename'] = $r['filename'];
                $ret['fields'] = unserialize($r['fields']);

                break;

            case "deleteFile":
                $isGold = boolval($_REQUEST['isGold']);
                $project = find("projects", $_REQUEST['id'], "Project not found");
                $isGold = $isGold ? 1 : 0;
                if ($stmt = $mysqli->prepare("UPDATE project_files SET deleted=1 WHERE project_id=? AND is_gold=?")) {
                    $stmt->bind_param("si", $_REQUEST['id'], $isGold);
                    $stmt->execute();
                    $stmt->close();
                }
                $ret['result'] = "OK";
                break;

            case "uploadFile":

                $delimiter = @$_REQUEST['char'];
                if (!$delimiter) {
                    $delimiter = "comma";
                }
                if (!isset($Delimiters[$delimiter])) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Invalid delimiter.";
                    break;
                }

                $enclosure = @$_REQUEST['enclosure'];
                if (!$enclosure) {
                    $enclosure = "double";
                }
                if (!isset($Enclosures[$enclosure])) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Invalid enclosure.";
                    break;
                }

                $fieldsTitlesInFirstLine = !! @$_REQUEST['fieldsTitlesInFirstLine'];
                $isGold = boolval($_REQUEST['isGold']);
                $isGold = $isGold ? 1 : 0;
                $fieldTitles = @$_REQUEST['fieldTitles'];
                $fileName = @$_FILES['csvFile']['name'];
                $type = @$_FILES['csvFile']['type'];
                $project = find("projects", $_REQUEST['id'], "Project not found");

                if (!$fileName) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Invalid file.";
                    break;
                }

                $delimiter = $Delimiters[$delimiter];
                $enclosure = $Enclosures[$enclosure];

                $fields = array();
                $fieldCount = -1;
                if (!$fieldsTitlesInFirstLine) {
                    $fields = explode(',', $fieldsTitles);
                    $fields = array_map('trim', $fields);
                    $fieldCount = count($fields);
                }

                // if ($type != "text/csv") {
                //     $ret['result'] = "ERR";
                //     $ret['error'] = "Invalid type ($type). Should be text/csv.";
                //     break;
                // }

                $okData = array();
                $line = 0;
                if (($handle = fopen($_FILES['csvFile']['tmp_name'], "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 0, $delimiter, $enclosure)) !== FALSE) {
                        $line++;
                        if ($fieldCount >= 0 && count($data) != $fieldCount) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Wrong field number on line $line.";
                            break 2;
                        }
                        if ($fieldCount == -1) {
                            $fieldCount = count($data);
                        }
                        $okData[] = $data;
                    }
                    fclose($handle);
                }

                if ($fieldsTitlesInFirstLine) {
                    $fields = array_shift($okData);
                }

                $DB->startTransaction();
                $success = true;

                if ($stmt = $mysqli->prepare("UPDATE project_files SET deleted=1 WHERE project_id=? AND is_gold=?")) {
                    $stmt->bind_param("si", $_REQUEST['id'], $isGold);
                    if (!$stmt->execute()) {
                        $success = false;
                    }
                    $stmt->close();
                }

                $data = array();
                $data['project_id'] = $project['id'];
                $data['filename'] = $fileName;
                $data['is_gold'] = $isGold;
                $data['fields'] = serialize($fields);
                if (!$DB->queryinsert("project_files", $data)) {
                    $success = false;
                }
                $file_id = $DB->last_id;

                foreach ($okData as $row) {
                    $data = array();
                    $data['file_id'] = $file_id;
                    $data['line_text'] = serialize($row);
                    if (!$DB->queryinsert("file_lines", $data)) {
                        $success = false;
                    }
                }

                if (!$success) {
                    $DB->rollbackTransaction();
                    $ret['result'] = "ERR";
                    $ret['error'] = $DB->get_error();
                    break;
                }

                $DB->commitTransaction();
                $ret['result'] = "OK";
                break;

            case "listProjects":
                $query = "SELECT * FROM projects WHERE deleted = 0";
                $res = $mysqli->query($query);
                $ret['result'] = "OK";
                $ret['values'] = $res->fetch_all(MYSQLI_ASSOC);
                break;

            case "deleteProject":
                $r = find("projects", $_REQUEST['id'], "Project not found");
                if ($stmt = $mysqli->prepare("UPDATE projects SET deleted=1 WHERE id=?")) {
                    $stmt->bind_param("s", $_REQUEST['id']);
                    $stmt->execute();
                    $stmt->close();
                }
                $ret['result'] = "OK";
                break;

            case "getProjectInfo":
                $r = find("projects", $_REQUEST['id'], "Project not found");

                $ret['issues'] = array();
                $hasData = false;
                $hasGold = false;
                $query = "SELECT * FROM project_files WHERE project_id = '{$r['id']}' AND deleted = '0'";
                $DB->query($query);
                while ($row = $DB->fetch()) {
                    if ($row['is_gold']) {
                        $hasGold = true;
                    }
                    else {
                        $hasData = true;
                    }
                }
                if (!$hasGold) {
                    $ret['issues'][] = "Gold file missing";
                }
                if (!$hasData) {
                    $ret['issues'][] = "Training file missing";
                }

                $ret['result'] = "OK";
                $ret['values'] = $r;
                break;

            // Deprecated
            case "editProject":
                $r = find("projects", $_REQUEST['id'], "Project not found");
                $ret['result'] = "OK";
                $ret['values'] = $r;
                break;

            case "addProject":
                $fields = array("name", "title", "description", "keywords", "reward",
                    "workers", "max_time", "expiry", "auto_approve", "layout_id", "params");
                $integers = array("workers", "max_time", "params", "auto_approve", "expiry");

                $r = NULL;
                if ($_REQUEST['id'] != 0) {
                    $r = find("projects", $_REQUEST['id'], "Project not found");
                }
                $data = array();
                foreach ($fields as $field) {
                    $data[$field] = $_REQUEST[$field];
                }

                foreach ($fields as $field) {
                    if (!trim($data[$field])) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "Field '$field' is mandatory";
                        break 2;
                    }
                }

                if (!is_numeric($data['reward'])) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Field 'reward' must be numeric";
                    break;
                }
                foreach ($integers as $field) {
                    if (!preg_match("/^[0-9]+$/", $data[$field])) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "Field '$field' must be integer";
                        break 2;
                    }
                }

                // $expiry = strtotime($data['expiry']);
                // if ($expiry === false) {
                //     $ret['result'] = "ERR";
                //     $ret['error'] = "Field '$field' must be a date/time (format 'Y-m-d h:m:s')";
                //     break;
                // }
                // $data['expiry'] = date('Y-m-d H:i:s', $expiry);

                if ($r === NULL) {
                    $DB->queryinsert("projects", $data);
                    $ret['result'] = "OK";
                    break;
                }
                else {
                    $where = array("id" => $_REQUEST['id']);
                    $DB->queryupdate("projects", $data, $where);
                    $ret['result'] = "OK";
                    break;
                }

                break;
        }
        
        break;

}

echo json_encode($ret, JSON_PRETTY_PRINT);
