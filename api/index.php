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

// header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Accept, Referer, User-Agent, Content-Type, Origin, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// ini_set("display_errors", "On");

// require_once 'vendor/autoload.php';
// print_r($_REQUEST);

$script_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}";

session_start();

require_once("../inc/include.php");

$Action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$User = 1;

$_SESSION['Options'] = loadOptions();
if (!isset($_SESSION['Options'])) {
    $_SESSION['Options'] = loadOptions();
}
$Options = $_SESSION['Options'];

// require_once("update.php");

$ret = array();
$ret['debug'] = array();

$json_params = file_get_contents("php://input");
if (strlen($json_params) > 0 && isValidJSON($json_params)) {
    $values = json_decode($json_params, true);
    // $ret['values'] = print_r($values, true);
    $_POST = $values;
    foreach ($_POST as $key => $value) {
        $_REQUEST[$key] = $value;
    }
}

$ret['debug']['request'] = $_REQUEST;

$Delimiters = array("tab" => '\t', "comma" => ',', "semicolon" => ';');
$Enclosures = array("single" => '\'', "double" => '\"', "none" => chr(8));

// $ret['comments'] = $json_params;
// $ret['post'] = print_r($_POST, true);

$UserInfo = find("users", $User, "User not found");
$UseSandbox = $UserInfo['use_sandbox'];
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

    case "emptyTrash":
        $success = true;
        $db_error = "";

        $DB->startTransaction();
        $query = "DELETE FROM clusters WHERE deleted = '1'";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }
        $query = "DELETE FROM cluster_to_hit WHERE deleted = '1'";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }
        $query = "DELETE FROM assignments
            WHERE hit_id != ALL (
                SELECT id_hit FROM cluster_to_hit
            )";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }
        $query = "DELETE FROM answers
            WHERE assignment_id != ALL (
                SELECT assignment_id FROM assignments
            )";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }
        $query = "DELETE l FROM `file_lines` l
            LEFT JOIN project_files f ON f.id = l.file_id
            WHERE f.deleted = '1'";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }
        $query = "DELETE FROM projects WHERE deleted = '1'";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }
        $query = "DELETE FROM project_files WHERE deleted = '1'";
        if (!$DB->query($query)) {
            $success = false;
            $db_error = $DB->get_error();
        }

        if ($success) {
            $DB->commitTransaction();
            $ret['result'] = "OK";
        }
        else {
            $DB->rollBackTransaction();
            $ret['result'] = "ERR";
            $ret['error'] = $db_error;
        }

        break;

    case "getOptions":
        $ret['defaults'] = array();
        $ret['defaults']['reward'] = "0.05";
        $ret['defaults']['assignments'] = 3;
        $ret['defaults']['time_per_worker'] = 60;
        $ret['defaults']['survey_expiration'] = 60 * 24;
        $ret['defaults']['auto_approve'] = 10;
        $ret['defaults']['examples_per_hit'] = 5;
        $ret['defaults']['gold_data_per_hit'] = 1;
        $ret['defaults']['shuffle_base_data'] = 1;
        $ret['defaults']['shuffle_gold_data'] = 1;
        $ret['defaults']['delete_exceeding_values'] = 0;
        $ret['defaults']['separator'] = "comma";
        $ret['defaults']['delimiter'] = "double";
        $ret['defaults']['reject_reason'] = "Sorry, you did not answer correctly to the gold question(s).";
        $ret['result'] = "OK";

        break;

    case "listProjects":
    case "addProject":
    case "deleteProject":
    case "editProject":
    case "uploadFile":
    case "deleteFile":
    case "getData":
    case "getUserInfo":
    case "getProjectInfo":
    case "updateProjectStatus":
    case "getHits":
    case "getHitInfo":
    case "testLayout":

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

                // $query = "SELECT l.line_text, GROUP_CONCAT(c.cluster_index) cluster_index, h.id_hit
                //     FROM file_lines l
                //     LEFT JOIN clusters c ON c.line_id = l.id AND c.deleted = 0
                //     LEFT JOIN cluster_to_hit h ON c.cluster_index = h.id_cluster AND h.id_project = '{$project['id']}'
                //     LEFT JOIN project_files f ON f.id = l.file_id
                //     WHERE f.deleted = '0' AND f.project_id = '{$project['id']}' AND f.is_gold = '$isGold'
                //     GROUP BY l.id
                //     LIMIT $offset, $howMany";
                $query = "SELECT l.line_text, GROUP_CONCAT(c.cluster_index) cluster_index,  GROUP_CONCAT(DISTINCT h.id_hit) id_hit,
                        SUM(IF(a.status = 'Approved', 1, 0)) assignments_approved,
                        SUM(IF(a.status = 'Rejected', 1, 0)) assignments_rejected,
                        SUM(IF(a.status = 'Submitted', 1, 0)) assignments_pending
                    FROM file_lines l
                        LEFT JOIN clusters c ON c.line_id = l.id AND c.deleted = 0
                        LEFT JOIN cluster_to_hit h ON c.cluster_index = h.id_cluster AND h.id_project = '{$project['id']}' AND h.deleted = '0'
                        LEFT JOIN assignments a ON a.hit_id = h.id_hit
                        LEFT JOIN project_files f ON f.id = l.file_id
                    WHERE f.deleted = '0' AND f.project_id = '{$project['id']}' AND f.is_gold = '$isGold'
                    GROUP BY l.id
                    LIMIT $offset, $howMany";

                // $query = "SELECT line_text FROM file_lines
                //     WHERE file_id = '{$r['id']}'
                //     ORDER BY id
                //     LIMIT $offset, $howMany";
                $DB->query($query);

                $data = array();
                $cluster_indexes = array();
                $results = array();
                while ($row = $DB->fetch()) {
                    $data[] = unserialize($row['line_text']);

                    $hits = explode(",", $row['id_hit']);
                    $hits = array_map("trim", $hits);

                    $cluster_indexes[] = $row['cluster_index'];
                    $result = [];
                    $result['hit_ids'] = $hits;
                    $result['assignments_approved'] = $row['assignments_approved'];
                    $result['assignments_pending'] = $row['assignments_pending'];
                    $result['assignments_rejected'] = $row['assignments_rejected'];
                    $results[] = $result;
                }
                $ret['result'] = "OK";
                $ret['data'] = $data;
                $ret['cluster_indexes'] = $cluster_indexes;
                $ret['results'] = $results;
                $ret['num'] = $num;
                $ret['filename'] = $r['filename'];
                $ret['fields'] = unserialize($r['fields']);

                break;

            case "deleteFile":
                $isGold = boolval($_REQUEST['isGold']);
                $project = find("projects", $_REQUEST['id'], "Project not found");
                if ($project['status'] > 0) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Files can be uploaded only in projects with status 0.";
                    break;
                }
                $isGold = $isGold ? 1 : 0;
                if ($stmt = $mysqli->prepare("UPDATE project_files SET deleted=1 WHERE project_id=? AND is_gold=?")) {
                    $stmt->bind_param("si", $_REQUEST['id'], $isGold);
                    $stmt->execute();
                    $stmt->close();
                }
                $ret['result'] = "OK";
                break;

            case "uploadFile":
                $r = find("projects", $_REQUEST['id'], "Project not found");
                if ($r['status'] > 0) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Files can be uploaded only in projects with status 0.";
                    break;
                }

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

                $fields = array();
                $fieldCount = -1;
                if (!$fieldsTitlesInFirstLine) {
                    $fields = explode(',', $fieldsTitles);
                    $fields = array_map('trim', $fields);
                    $fieldCount = count($fields);
                }

                $delimiter = $Delimiters[$delimiter];
                $enclosure = $Enclosures[$enclosure];

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

                if ($isGold) {
                    $query = "SELECT * FROM project_files WHERE project_id = '{$r['id']}' AND deleted = '0' AND is_gold = '0'";
                    if (!$DB->querynum($query)) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "You must insert training file before gold file.";
                        break;
                    }
                    $rT = $DB->fetch();
                    $fieldsT = unserialize($rT['fields']);
                    if (count(array_intersect($fieldsT, $fields)) != count($fieldsT)) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "Fields in the training file are not included in the gold file.";
                        break;
                    }
                    $diff = array_diff($fields, $fieldsT);
                    if (!count($diff)) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "The gold file must contain at least one field more than the training file.";
                        break;
                    }
                }

                $DB->startTransaction();
                $success = true;
                $db_error = "";

                if ($stmt = $mysqli->prepare("UPDATE project_files
                        SET deleted = 1
                        WHERE project_id = ? AND is_gold = ?")) {
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
                    $db_error = $DB->get_error();
                }
                $file_id = $DB->last_id;

                foreach ($okData as $row) {
                    $data = array();
                    $data['file_id'] = $file_id;
                    $data['line_text'] = serialize($row);
                    if (!$DB->queryinsert("file_lines", $data)) {
                        $success = false;
                        $db_error = $DB->get_error();
                    }
                }

                if (!$success) {
                    $DB->rollbackTransaction();
                    $ret['result'] = "ERR";
                    $ret['error'] = $db_error;
                    break;
                }

                $DB->commitTransaction();
                $ret['result'] = "OK";
                break;

            case "listProjects":
                $query = "SELECT p.*,
                        (SELECT COUNT(fl1.id) FROM file_lines fl1
                         LEFT JOIN project_files pf1 ON pf1.id = fl1.file_id
                         WHERE pf1.is_gold = '0' AND pf1.deleted = '0' AND pf1.project_id = p.id) count_train,
                        (SELECT COUNT(fl2.id) FROM file_lines fl2
                         LEFT JOIN project_files pf2 ON pf2.id = fl2.file_id
                         WHERE pf2.is_gold = '1' AND pf2.deleted = '0' AND pf2.project_id = p.id) count_gold,
                        COALESCE((SELECT COUNT(h.id IS NOT NULL) hits_submitted
                         FROM `clusters` c
                         LEFT JOIN file_lines l ON c.line_id = l.id
                         LEFT JOIN project_files f ON f.id = l.file_id
                         LEFT JOIN cluster_to_hit h ON h.id_cluster = c.cluster_index AND h.deleted = '0'
                         WHERE f.project_id = p.id AND c.deleted = 0 AND h.id IS NOT NULL AND h.id_project = p.id
                         GROUP BY (h.id IS NOT NULL)), 0) DIV p.params hits_submitted,
                        COALESCE((SELECT COUNT(*)
                         FROM `clusters` c
                         LEFT JOIN file_lines l ON c.line_id = l.id
                         LEFT JOIN project_files f ON f.id = l.file_id
                         WHERE f.project_id = p.id AND c.deleted = 0), 0) DIV p.params hits_total
                    FROM projects p
                    WHERE p.deleted = 0 AND user_id = '{$UserInfo['id']}'
                    ORDER BY p.created_at DESC";
                // $query = "SELECT * FROM projects
                //     WHERE deleted = 0 AND user_id = '{$UserInfo['id']}'";
                $res = $mysqli->query($query);
                $ret['result'] = "OK";
                $ret['values'] = $res->fetch_all(MYSQLI_ASSOC);
                break;

            case "deleteProject":
                $r = find("projects", $_REQUEST['id'], "Project not found");
                if ($r['status'] > 2) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "A project with status greater than 2 cannot be deleted.";
                    break;
                }
                if ($stmt = $mysqli->prepare("UPDATE projects SET deleted = 1 WHERE id = ?")) {
                    $stmt->bind_param("s", $_REQUEST['id']);
                    $stmt->execute();
                    $stmt->close();
                }
                $ret['result'] = "OK";
                break;

            case "updateProjectStatus":
                $r = find("projects", $_REQUEST['id'], "Project not found");

                $toStatus = $_REQUEST['toStatus'];
                $params = $r['params'];

                switch ($toStatus) {
                    case "0":
                        if ($r['status'] != 1) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Cannot proceed to status 0 for a project with status {$r['status']}";
                            break;
                        }

                        $query = "UPDATE `clusters` c
                            LEFT JOIN file_lines l ON l.id = c.line_id
                            LEFT JOIN project_files f ON f.id = l.file_id
                            SET c.deleted = 1
                            WHERE c.deleted = 0 AND f.project_id = '{$r['id']}'";
                        $DB->query($query);

                        $DB->queryupdate("projects", array("status" => 0), array("id" => $r['id']));
                        $ret['result'] = "OK";

                        break;

                    case "1":

                        $goldSize = $_REQUEST['goldSize'];
                        $deleteExceedingValues = boolval($_REQUEST['deleteExceedingValues']);
                        $shuffleGold = boolval($_REQUEST['shuffleGold']);
                        $shuffleData = boolval($_REQUEST['shuffleData']);

                        if ($r['status'] != 0 && $r['status'] != 2) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Cannot proceed to status 1 for a project with status {$r['status']}";
                            break;
                        }

                        if ($r['status'] == 2) {
                            $query = "UPDATE projects SET hit_details = NULL, status = 1 WHERE id = '{$r['id']}'";
                            $DB->query($query);
                            $ret['result'] = "OK";
                            break;
                        }

                        if (!preg_match("/[0-9]+/", $goldSize)) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Invalid gold size";
                            break;
                        }
                        if ($goldSize >= $params) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Gold size must be less than the number of params";
                            break;
                        }

                        $dataBunch = $params - $goldSize;

                        $goldData = array();
                        $trainData = array();
                        $query = "SELECT l.line_text, f.is_gold, l.id
                            FROM file_lines l
                            LEFT JOIN project_files f ON l.file_id = f.id
                            WHERE f.project_id = '{$r['id']}' AND f.deleted = '0'
                            ORDER BY l.id";
                        $DB->query($query);
                        while ($row = $DB->fetch()) {
                            if ($row['is_gold']) {
                                $goldData[] = $row;
                            }
                            else {
                                $trainData[] = $row;
                            }
                        }

                        if (!count($trainData)) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Project has no data";
                            break;
                        }
                        if (!count($goldData) && $goldSize > 0) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Project has no gold data, but gold data is needed for the task";
                            break;
                        }

                        if ($shuffleData) {
                            shuffle($trainData);
                        }
                        if ($shuffleGold) {
                            shuffle($goldData);
                        }

                        $remain = count($trainData) % $dataBunch;
                        if ($deleteExceedingValues) {
                            for ($i = 0; $i < $remain; $i++) {
                                array_pop($trainData);
                            }
                        }
                        else {
                            for ($i = 0; $i < $dataBunch - $remain; $i++) {
                                $trainData[] = $trainData[$i];
                            }
                        }

                        // $ret['dataBunch'] = $dataBunch;
                        // $ret['trainData'] = $trainData;
                        // $ret['goldData'] = $goldData;
                        // $ret['trainDataSize'] = count($trainData);
                        // $ret['goldDataSize'] = count($goldData);

                        $DB->startTransaction();
                        $success = true;

                        // todo: mix

                        $goldIndex = 0;
                        for ($i = 0; $i < count($trainData); $i += $dataBunch) {
                            $clusterIndex = floor($i / $dataBunch) + 1;
                            for ($j = 0; $j < $dataBunch; $j++) {
                                $index = $i + $j;
                                $data = array();
                                $data['cluster_index'] = $clusterIndex;
                                $data['line_id'] = $trainData[$index]['id'];
                                $data['alea'] = mt_rand() / mt_getrandmax();
                                if (!$DB->queryinsert("clusters", $data)) {
                                    $success = false;
                                }

                                // print("T{$trainData[$index]['id']}\n");
                            }
                            for ($j = 0; $j < $goldSize; $j++) {
                                $index = $goldIndex++ % count($goldData);
                                $data = array();
                                $data['cluster_index'] = $clusterIndex;
                                $data['line_id'] = $goldData[$index]['id'];
                                $data['alea'] = mt_rand() / mt_getrandmax();
                                if (!$DB->queryinsert("clusters", $data)) {
                                    $success = false;
                                }

                                // print("G{$goldData[$index]['id']}\n");
                            }
                        }

                        if (!$DB->queryupdate("projects", array("status" => 1), array("id" => $r['id']))) {
                            $success = false;
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

                    case "2":
                        if ($UseSandbox && $r['status'] == 3) {
                            $query = "UPDATE cluster_to_hit SET deleted = '1' WHERE id_project = '{$r['id']}'";
                            $DB->query($query);

                            $query = "UPDATE projects SET status = 2 WHERE id = '{$r['id']}'";
                            $DB->query($query);

                            $ret['result'] = "OK";
                            break;
                        }

                        if ($r['status'] != 1) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Cannot proceed to status 2 for a project with status {$r['status']}";
                            break;
                        }

                        if (!$r['layout_id']) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Layout ID missing";
                            break;
                        }

                        if (!count($_REQUEST['layoutData'])) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "No layoutData found";
                            break;
                        }

                        $goldInfo = false;
                        $trainingInfo = false;
                        $query = "SELECT * FROM project_files WHERE project_id = '{$r['id']}' AND deleted = '0'";
                        $DB->query($query);
                        while ($row = $DB->fetch()) {
                            if ($row['is_gold']) {
                                $goldInfo = $row;
                            }
                            else {
                                $trainingInfo = $row;
                            }
                        }

                        $projectFields = array();
                        $projectFields[] = "_name";
                        $projectFields[] = "_title";
                        $projectFields[] = "_description";
                        $projectFields[] = "_keywords";
                        $allowedFields = array_merge($projectFields, unserialize($trainingInfo['fields']));

                        $layout_fields = explode(",", $r['layout_fields']);
                        $layout_fields = array_map('trim', $layout_fields);

                        if (!count($_REQUEST['layoutData'])) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "No layout data specified";
                            break;
                        }

                        $outFields = array();
                        foreach ($_REQUEST['layoutData'] as $layoutData) {
                            if (!$layoutData['field']) {
                                $ret['result'] = "ERR";
                                $ret['error'] = "Field in layoutData is missing";
                                break 2;
                            }
                            if (!in_array($layoutData['field'], $layout_fields)) {
                                $ret['result'] = "ERR";
                                $ret['error'] = "Unable to find {$layoutData['field']} in layout fields";
                                break 2;
                            }

                            if (in_array($layoutData['field'], $outFields)) {
                                $ret['result'] = "ERR";
                                $ret['error'] = "Duplicate entry {$layoutData['field']}";
                                break 2;
                            }
                            $outFields[] = $layoutData['field'];

                            if ($layoutData['isHandWritten']) {
                                if (!$layoutData['customValue']) {
                                    $ret['result'] = "ERR";
                                    $ret['error'] = "Custom value is mandatory for handwritten fields";
                                    break 2;
                                }
                                if (substr($layoutData['field'], -1) === "#") {
                                    $ret['result'] = "ERR";
                                    $ret['error'] = "Fields ending with # cannot be user in handwritten mode";
                                    break 2;
                                }
                            }
                            else {
                                if (substr($layoutData['field'], -1) === "#" && in_array($layoutData['valueFrom'], $projectFields)) {
                                    $ret['result'] = "ERR";
                                    $ret['error'] = "Fields ending with # can be used only with CSV fields";
                                    break 2;
                                }
                                if (substr($layoutData['field'], -1) !== "#" && !in_array($layoutData['valueFrom'], $projectFields)) {
                                    $ret['result'] = "ERR";
                                    $ret['error'] = "Fields not ending with # can be used only with project fields";
                                    break 2;
                                }
                                if (!in_array($layoutData['valueFrom'], $allowedFields)) {
                                    $ret['result'] = "ERR";
                                    $ret['error'] = "Invalid field name {$layoutData['valueFrom']}";
                                    break 2;
                                }
                            }
                        }

                        $difference = array_diff($layout_fields, $outFields);
                        if (count($difference)) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Missing template fields: " . implode(', ', $difference);
                            break 2;
                        }

                        $trainingFields = unserialize($trainingInfo['fields']);
                        $goldFields = unserialize($goldInfo['fields']);
                        $goldFields = array_diff($goldFields, $trainingFields);

                        $answerDataAll = isset($_REQUEST['answerData']) ? $_REQUEST['answerData'] : [];

                        if (count($answerDataAll)) {
                            foreach ($answerDataAll as $answerData) {
                                $containSomething = false;
                                $pars = ["varName", "varValue", "varNameTo", "varValueTo"];
                                foreach ($pars as $key) {
                                    if (isset($answerData[$key]) && trim($answerData[$key])) {
                                        $containSomething = true;
                                    }
                                }
                                if ($containSomething) {
                                    if (strpos($answerData['varName'], "#") === false) {
                                        $ret['result'] = "ERR";
                                        $ret['error'] = "Field varName must contain #";
                                        break 2;
                                    }
                                    foreach ($pars as $key) {
                                        if (!isset($answerData[$key]) || strlen($answerData[$key]) == 0) {
                                            $ret['result'] = "ERR";
                                            $ret['error'] = "Field $key is not defined";
                                            break 3;
                                        }
                                    }
                                }
                            }
                        }

                        $toSave = array();
                        $toSave['layoutData'] = $_REQUEST['layoutData'];
                        $toSave['answerData'] = $answerDataAll;

                        $toSave['rejectIfGoldWrong'] = boolval($_REQUEST['rejectIfGoldWrong'] ?: false);
                        $toSave['acceptIfGoldRight'] = boolval($_REQUEST['acceptIfGoldRight'] ?: false);

                        if ($toSave['rejectIfGoldWrong']) {
                            if (!preg_match("/[0-9]+/", $_REQUEST['assignNumber'])) {
                                throw new Exception("Invalid value {$_REQUEST['assignNumber']} for assignNumber");
                            }
                            if ($_REQUEST['assignNumber'] < $r['workers']) {
                                throw new Exception("assignNumber must be at least {$r['workers']} in this project");
                            }

                            $toSave['assignNumber'] = $_REQUEST['assignNumber'];
                            $toSave['rejectReason'] = $_REQUEST['rejectReason'];
                        }

                        $toSave['hitData'] = [
                            "AssignmentDurationInSeconds" => $r['max_time'] * 60,
                            "AutoApprovalDelayInSeconds" => $r['auto_approve'] * 60,
                            "Description" => $r['description'],
                            "HITLayoutId" => $r['layout_id'],
                            "Keywords" => $r['keywords'],
                            "Reward" => "0", // normal value: "{$r['reward']}",
                            "Title" => $r['title'],
                            "LifetimeInSeconds" => $r['expiry'] * 60,
                            "MaxAssignments" => $r['workers'],
                        ];

                        try {
                            $toSave = getHITLayoutParameters($r, $r['id'], 1, $goldFields, $toSave);
                        }
                        catch (Exception $e) {
                            $ret['result'] = "ERR";
                            $ret['error'] = $e->getMessage();
                            break;
                        }

                        // $ret['toSave'] = $toSave;
                        // $ret['result'] = "ERR";
                        // $ret['error'] = "Work in progress";
                        // break;

                        // $cluster_data = array();
                        // $goldIndexes = [];
                        // $query = "SELECT l.line_text, f.fields, f.is_gold
                        //     FROM clusters c
                        //     LEFT JOIN file_lines l ON l.id = c.line_id
                        //     LEFT JOIN project_files f ON f.id = l.file_id
                        //     WHERE c.deleted = '0' AND f.deleted = '0'
                        //         AND f.project_id = '{$r['id']}' AND c.cluster_index = '1'
                        //     ORDER BY c.alea";
                        // $num = $DB->querynum($query);
                        // if (!$num) {
                        //     $ret['result'] = "ERR";
                        //     $ret['error'] = "No clusters found";
                        //     break;
                        // }
                        // if ($num != $r['params']) {
                        //     $ret['result'] = "ERR";
                        //     $ret['error'] = "Number of parameters does not match";
                        //     break;
                        // }
                        // while ($row = $DB->fetch()) {
                        //     $fields = unserialize($row['fields']);
                        //     $data = unserialize($row['line_text']);
                        //     $okData = array();
                        //     for ($i = 0; $i < count($fields); $i++) {
                        //         $okData[$fields[$i]] = $data[$i];
                        //     }
                        //     $cluster_data[] = $okData;
                        //     if ($row['is_gold']) {
                        //         $goldIndexes[count($cluster_data)] = [];
                        //         foreach ($goldFields as $goldField) {
                        //             $thisGoldIndex = array_search($goldField, $fields);
                        //             $goldIndexes[count($cluster_data)][$goldField] = $data[$thisGoldIndex];
                        //         }
                        //     }
                        // }

                        // $HITLayoutParameters = [];
                        // foreach ($_REQUEST['layoutData'] as $layoutData) {
                        //     if (substr($layoutData['field'], -1) !== "#") {
                        //         $val = $layoutData['isHandWritten'] ? $layoutData['customValue'] : $r[substr($layoutData['valueFrom'], 1)];
                        //         $HITLayoutParameters[] = [
                        //             "Name" => $layoutData['field'],
                        //             "Value" => $val
                        //         ];
                        //     }
                        //     else {
                        //         $field = substr($layoutData['field'], 0, strlen($layoutData['field']) - 1);
                        //         for ($i = 1; $i <= $r['params']; $i++) {
                        //             $value = $cluster_data[$i - 1][$layoutData['valueFrom']];
                        //             $HITLayoutParameters[] = [
                        //                 "Name" => $field . $i,
                        //                 "Value" => $value
                        //             ];
                        //         }
                        //     }
                        // }

                        // $AssignmentReviewPolicy = [];
                        // if ($goldInfo && ($toSave['rejectIfGoldWrong'] || $toSave['acceptIfGoldRight'])) {
                        //     $toSave['assignNumber'] = 0;
                        //     $toSave['rejectReason'] = "";

                        //     if ($toSave['rejectIfGoldWrong']) {
                        //         if (!preg_match("/[0-9]+/", $_REQUEST['assignNumber'])) {
                        //             $ret['result'] = "ERR";
                        //             $ret['error'] = "Invalid value {$_REQUEST['assignNumber']} for assignNumber";
                        //             break;
                        //         }
                        //         if ($_REQUEST['assignNumber'] < $r['workers']) {
                        //             $ret['result'] = "ERR";
                        //             $ret['error'] = "assignNumber must be at least {$r['workers']} in this project";
                        //             break;
                        //         }

                        //         $toSave['assignNumber'] = $_REQUEST['assignNumber'];
                        //         $toSave['rejectReason'] = $_REQUEST['rejectReason'];
                        //     }

                        //     $mapEntries = [];
                        //     foreach ($goldIndexes as $key => $value) {
                        //         foreach ($value as $key2 => $value2) {
                        //             foreach ($answerDataAll as $answerData) {
                        //                 if ($answerData['varNameTo'] == $key2 && $answerData['varValueTo'] == $value2) {
                        //                     $mapEntries[] = ["Key" => str_replace("#", $key, $answerData['varName']), "Values" => [$answerData['varValue']]];
                        //                 }
                        //             }
                        //         }
                        //     }
                        //     $ret['mapEntries'] = $mapEntries;
    
                        //     if ($toSave['rejectIfGoldWrong'] || $toSave['acceptIfGoldRight']) {
                        //         $AssignmentReviewPolicy['PolicyName'] = "ScoreMyKnownAnswers/2011-09-01";
                        //         $AssignmentReviewPolicy['Parameters'] = [];
                        //         $AssignmentReviewPolicy['Parameters'][] = ["Key" => "AnswerKey", "MapEntries" => $mapEntries];
                        //         if ($toSave['rejectIfGoldWrong']) {
                        //             $AssignmentReviewPolicy['Parameters'][] = ["Key" => "RejectReason", "Values" => [$toSave['rejectReason']]];
                        //             $AssignmentReviewPolicy['Parameters'][] = ["Key" => "RejectIfKnownAnswerScoreIsLessThan", "Values" => ["99"]];
                        //             if ($toSave['assignNumber'] > $r['workers']) {
                        //                 $AssignmentReviewPolicy['Parameters'][] = ["Key" => "ExtendMaximumAssignments", "Values" => ["{$toSave['assignNumber']}"]];
                        //                 $AssignmentReviewPolicy['Parameters'][] = ["Key" => "ExtendIfKnownAnswerScoreIsLessThan", "Values" => ["99"]];
                        //             }
                        //         }
                        //         if ($toSave['acceptIfGoldRight']) {
                        //             $AssignmentReviewPolicy['Parameters'][] = ["Key" => "ApproveIfKnownAnswerScoreIsAtLeast", "Values" => ["100"]];
                        //         }
                        //     }
                        // }

                        // $toSave['hitData']['HITLayoutParameters'] = $HITLayoutParameters;
                        // if (count($AssignmentReviewPolicy)) {
                        //     $toSave['hitData']['AssignmentReviewPolicy'] = $AssignmentReviewPolicy;
                        // }

                        $masterQualification = "2F1QJWKUDD8XADTFD2Q0G6UTO95ALH";
                        if ($UseSandbox) {
                            $masterQualification = "2ARFPLSP75KLA8M8DH1HTEQVJT3SY6";
                        }
                        $qualificationRequirements = [];
                        $qualifications = unserialize($r['qualifications']);
                        if ($qualifications['adult'] ?: 0) {
                            $a = array();
                            $a['QualificationTypeId'] = "00000000000000000060";
                            $a['Comparator'] = "EqualTo";
                            $a['IntegerValues'] = [1];
                            $a['ActionsGuarded'] = "DiscoverPreviewAndAccept";
                            $qualificationRequirements[] = $a;
                        }
                        if ($qualifications['master'] ?: 0) {
                            $a = array();
                            $a['QualificationTypeId'] = $masterQualification;
                            $a['Comparator'] = "Exists";
                            $a['ActionsGuarded'] = "DiscoverPreviewAndAccept";
                            $qualificationRequirements[] = $a;
                        }
                        if ($qualifications['countries'] ?: 0) {
                            $countries = [];
                            foreach ($qualifications['countries'] as $country) {
                                $countries[] = ["Country" => $country];
                            }
                            if (count($countries)) {
                                $a = array();
                                $a['QualificationTypeId'] = "00000000000000000071";
                                $a['Comparator'] = "In";
                                $a['LocaleValues'] = $countries;
                                $a['ActionsGuarded'] = "DiscoverPreviewAndAccept";
                                $qualificationRequirements[] = $a;
                            }
                        }
                        $toSave['hitData']['QualificationRequirements'] = $qualificationRequirements;

                        try {
                            $response = $mTurk->createHIT($toSave['hitData']);
                        }
                        catch (Exception $e) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "AWS error (see logs for details)";
                            $ret['hitData'] = $toSave['hitData'];
                            $ret['error_message'] = $e->getMessage();
                            l(1, "create_preview_hit", $e->getMessage(), $r['id']);
                            break;
                        }

                        $response = $response->toArray();
                        $toSave['HIT_response'] = $response;
                        l(1, "create_preview_hit", $response, $response['HIT']['HITId']);

                        try {
                            $tmpResponse1 = $mTurk->updateExpirationForHIT([
                                "ExpireAt" => 0,
                                "HITId" => $response['HIT']['HITId']
                            ]);
                            $tmpResponse1 = $tmpResponse1->toArray();
                            $toSave['expiration_response'] = $tmpResponse1;
                            l(1, "update_preview_hit", $tmpResponse1, $response['HIT']['HITId']);
                        }
                        catch (Exception $e) {
                            l(1, "update_preview_hit", $e->getMessage(), $response['HIT']['HITId']);
                        }

                        $ret['debug']['toSave'] = $toSave;

                        // $ret['result'] = "ERR";
                        // $ret['error'] = "Work in progress";
                        // break;

                        $DB->queryupdate("projects", array("hit_details" => serialize($toSave), "status" => 2), array("id" => $r['id']));
                        $ret['result'] = "OK";

                        break;

                    case 3:
                        if ($r['status'] != 2 && $r['status'] != 3) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Cannot proceed to status 3 for a project with status {$r['status']}";
                            break;
                        }

                        $num = intval($_REQUEST['num']);
                        if ($num <= 0) {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Invalid value for num";
                            break;
                        }

                        $DB->queryupdate("projects", array("status" => 3), array("id" => $r['id']));

                        $DB->startTransaction();
                        $query = "SELECT DISTINCT c.cluster_index
                            FROM `clusters` c
                            LEFT JOIN file_lines l ON c.line_id = l.id
                            LEFT JOIN project_files f ON f.id = l.file_id
                            LEFT JOIN cluster_to_hit h ON h.id_cluster = c.cluster_index AND h.id_project = '{$r['id']}' AND h.deleted = '0'
                            WHERE f.project_id = '{$r['id']}' AND c.deleted = 0 AND h.id IS NULL
                            FOR UPDATE";
                        $DB->query($query, 2);
                        $index = 0;
                        while ($row = $DB->fetch(2)) {
                            if ($index++ >= $num) {
                                break;
                            }
                            $cluster_index = $row['cluster_index'];

                            $data = array();
                            $data['id_cluster'] = $cluster_index;
                            $data['id_project'] = $r['id'];
                            $DB->queryinsert("cluster_to_hit", $data);
                        }
                        $DB->commitTransaction();

                        $ret['result'] = "OK";

                        break;

                    default:
                        $ret['result'] = "ERR";
                        $ret['error'] = "Unknown status";
                        break;
                }
                break;

            case "getProjectInfo":
                $r = find("projects", $_REQUEST['id'], "Project not found");
                $qualificationRequirements = unserialize($r['qualifications']);
                if (!$qualificationRequirements) {
                    $qualificationRequirements = array();
                }
                $r = array_merge($r, $qualificationRequirements);

                $ret['issues'] = array();

                if (!$r['layout_id']) {
                    $ret['issues'][] = "Layout ID missing";
                }

                $numData = false;
                $numGold = false;
                $query = "SELECT * FROM file_lines l
                    LEFT JOIN project_files f ON l.file_id = f.id
                    WHERE f.project_id = '{$r['id']}' AND f.deleted = '0' AND f.is_gold = '0'";
                $numData = $DB->querynum($query);
                if (!$numData) {
                    $ret['issues'][] = "Training file missing";
                }
                $query = "SELECT * FROM file_lines l
                    LEFT JOIN project_files f ON l.file_id = f.id
                    WHERE f.project_id = '{$r['id']}' AND f.deleted = '0' AND f.is_gold = '1'";
                $numGold = $DB->querynum($query);

                $ret['goldFields'] = array();
                $ret['dataFields'] = array();
                $query = "SELECT * FROM project_files WHERE project_id = '{$r['id']}' AND deleted = '0'";
                $DB->query($query);
                while ($row = $DB->fetch()) {
                    if ($row['is_gold']) {
                        $ret['goldFields'] = unserialize($row['fields']);
                    }
                    else {
                        $ret['dataFields'] = unserialize($row['fields']);
                    }
                }
                $ret['goldFields'] = array_diff($ret['goldFields'], $ret['dataFields']);

                $ret['hits_total'] = 0;
                $ret['hits_submitted'] = 0;
                $ret['hits_inserted'] = 0;

                $query = "SELECT DISTINCT c.cluster_index, h.id
                    FROM `clusters` c
                    LEFT JOIN file_lines l ON c.line_id = l.id
                    LEFT JOIN project_files f ON f.id = l.file_id
                    LEFT JOIN cluster_to_hit h ON h.id_cluster = c.cluster_index AND h.id_project = '{$r['id']}' AND h.deleted = '0'
                    WHERE f.project_id = '{$r['id']}' AND c.deleted = 0";
                $DB->query($query);
                while ($row = $DB->fetch()) {
                    $ret['hits_total']++;
                    if ($row['id']) {
                        $ret['hits_inserted']++;
                    }
                }

                // $ret['hits'] = [];
                $summary = [];
                $query = "SELECT h.*,
                        SUM(IF(a.status = 'Approved', 1, 0)) assignments_approved,
                        SUM(IF(a.status = 'Rejected', 1, 0)) assignments_rejected,
                        SUM(IF(a.status = 'Submitted', 1, 0)) assignments_pending
                    FROM `cluster_to_hit` h
                    LEFT JOIN assignments a ON a.hit_id = h.id_hit
                    WHERE h.id_project = '{$r['id']}' AND h.deleted = 0 AND h.id_hit IS NOT NULL
                    GROUP BY h.id_hit";
                $DB->query($query);
                while ($row = $DB->fetch_a()) {
                    $ret['hits_submitted']++;
                    $key = implode(" ", [
                        $row['max_assignments'],
                        $row['assignments_available'],
                        $row['assignments_completed'],
                        $row['assignments_pending'],
                        $row['assignments_approved'],
                        $row['assignments_rejected']
                    ]);
                    if (!isset($summary[$key])) {
                        $summary[$key] = 0;
                    }
                    $summary[$key]++;
                    // $ret['hits'][] = $row;
                }

                // $ret['summary_tmp'] = $summary;
                $ret['summary'] = [];
                foreach ($summary as $key => $value) {
                    $values = explode(" ", $key);
                    $s = [];
                    $s['max_assignments'] = $values[0];
                    $s['assignments_available'] = $values[1];
                    $s['assignments_completed'] = $values[2];
                    $s['assignments_pending'] = $values[3];
                    $s['assignments_approved'] = $values[4];
                    $s['assignments_rejected'] = $values[5];
                    $s['count'] = $value;
                    $ret['summary'][] = $s;
                }

                $ret['numGold'] = $numGold;
                $ret['numData'] = $numData;

                $r['qualifications'] = unserialize($r['qualifications']);
                $r['hit_details'] = unserialize($r['hit_details']);

                $ret['result'] = "OK";
                $ret['values'] = $r;
                break;

            case "testLayout":
                if (!isset($_REQUEST['layout_id'])) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Layout ID missing";
                    break;
                }
                $out_params = testLayout($_REQUEST['layout_id']);

                if ($out_params['result'] == "ERR") {
                    $ret['result'] = "ERR";
                    $ret['error'] = $out_params['error'];
                    break;
                }

                $ret['result'] = "OK";
                $ret['examples_per_hit'] = 0;
                $ret['params_fields'] = "";
                if (isset($out_params['layout_fields'])) {
                    $fields = explode(',', $out_params['layout_fields']);
                    $fields = array_map('trim', $fields);
                    $endings = array();
                    $max = 0;
                    foreach ($fields as $field) {
                        if (preg_match("/^(.*[^0-9])([0-9]+)$/", $field, $match)) {
                            if (!isset($endings[$match[1]])) {
                                $endings[$match[1]] = array();
                            }
                            $endings[$match[1]][] = $match[2];
                            if ($match[2] > $max) {
                                $max = $match[2];
                            }
                        }
                    }
                    $candidates = array_keys($endings);
                    $new_max = $max;
                    for ($i = 1; $i <= $max; $i++) {
                        $to_remove = [];
                        foreach ($endings as $key => $value) {
                            if (!in_array($i, $value)) {
                                $to_remove[] = $key;
                            }
                        }
                        if (count($to_remove) != count($candidates)) {
                            foreach ($to_remove as $key) {
                                if (($k = array_search($key, $candidates)) !== false) {
                                    unset($candidates[$k]);
                                }
                            }
                        }
                        else {
                            $new_max = $i - 1;
                            break;
                        }
                    }

                    // $ret['endings'] = $endings;
                    $ret['params_fields'] = implode(', ', $candidates);
                    $ret['examples_per_hit'] = $new_max;
                }
                break;

            case "getHits":
                $r = find("projects", $_REQUEST['id'], "Project not found");
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

                $data = [];
                $query = "SELECT * FROM cluster_to_hit
                    WHERE id_project = '{$r['id']}' AND deleted = '0'
                    LIMIT $offset, $howMany";
                $DB->query($query);
                while ($row = $DB->fetch_a()) {
                    unset($row['hit_info']);
                    $data[] = $row;
                }

                $ret['result'] = "OK";
                $ret['values'] = $data;
                break;

            case "getHitInfo":
                $hitID = addslashes($_REQUEST['hitID']);
                $leaveQuestion = boolval($_REQUEST['leaveQuestion'] ?: false);
                $leaveAnswer = boolval($_REQUEST['leaveAnswer'] ?: false);
                $query = "SELECT h.*
                    FROM cluster_to_hit h
                    LEFT JOIN projects p ON h.id_project = p.id
                    WHERE p.deleted = '0' AND h.deleted = '0' AND p.user_id = '$User' AND h.id_hit = '$hitID'";
                if (!$DB->querynum($query)) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "HIT not found.";
                    break;
                }

                $row = $DB->fetch_a();
                $row['hit_info'] = unserialize($row['hit_info']);
                if (!$leaveQuestion) {
                    unset($row['hit_info']['Question']);
                }

                $assignments = [];
                $query = "SELECT * FROM assignments
                    WHERE hit_id = '$hitID'";
                $DB->query($query);
                while ($rowA = $DB->fetch_a()) {
                    $rowA['assignment_info'] = unserialize($rowA['assignment_info']);
                    if (!$leaveAnswer) {
                        unset($rowA['assignment_info']['Answer']);
                    }
                    $assignments[] = $rowA;
                }

                $ret['result'] = "OK";
                $ret['values'] = $row;
                $ret['assignments'] = $assignments;
                break;

            case "addProject":
                $fields = array("name", "title", "description", "keywords", "reward",
                    "workers", "max_time", "expiry", "auto_approve", "layout_id", "params",
                    "params_fields");
                $mandatory_fields = array("name", "title", "description", "keywords", "reward",
                    "workers", "max_time", "expiry", "auto_approve", "params");
                $integers = array("workers", "max_time", "params", "auto_approve", "expiry");

                $r = NULL;
                if ($_REQUEST['id'] != 0) {
                    $r = find("projects", $_REQUEST['id'], "Project not found");
                    if ($r['status'] > 2) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "A project with status {$r['status']} cannot be modified";
                        break;
                    }

                    //todo: clean this up
                    if ($r['status'] == 1) {
                        if (($key = array_search("params", $fields)) !== false) {
                            unset($fields[$key]);
                        }
                        if (($key = array_search("params", $mandatory_fields)) !== false) {
                            unset($mandatory_fields[$key]);
                        }
                        if (($key = array_search("params", $integers)) !== false) {
                            unset($integers[$key]);
                        }
                        if (($key = array_search("layout_id", $fields)) !== false) {
                            unset($fields[$key]);
                        }
                        if (($key = array_search("layout_id", $mandatory_fields)) !== false) {
                            unset($mandatory_fields[$key]);
                        }
                        if (($key = array_search("layout_id", $integers)) !== false) {
                            unset($integers[$key]);
                        }
                        if (($key = array_search("params_fields", $fields)) !== false) {
                            unset($fields[$key]);
                        }
                    }
                    if ($r['status'] == 2) {
                        $fields = array("name", "title", "description", "keywords");
                        $mandatory_fields = $fields;
                        $integers = array();
                    }
                }

                // Important to avoid that malicious $_REQUEST indexes
                // are added to the SQL insert statement
                $data = array();
                foreach ($fields as $field) {
                    $data[$field] = $_REQUEST[$field];
                }

                foreach ($mandatory_fields as $field) {
                    if (!trim($data[$field])) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "Field '$field' is mandatory";
                        break 2;
                    }
                }

                if ($r['status'] < 2) {
                    $qualificationRequirements = array();
                    $qualificationRequirements['adult'] = $_REQUEST['adult'] ? 1 : 0;
                    $qualificationRequirements['master'] = $_REQUEST['master'] ? 1 : 0;
                    $qualificationRequirements['countries'] = [];
                    foreach ($_REQUEST['countries'] as $country) {
                        if (preg_match('/^[A-Z]{2}$/', $country)) {
                            $qualificationRequirements['countries'][] = $country;
                        }
                        else {
                            $ret['result'] = "ERR";
                            $ret['error'] = "Invalid country $country";
                            break;
                        }
                    }
                    $data['qualifications'] = serialize($qualificationRequirements);
                }

                if (isset($data['reward']) && !is_numeric($data['reward'])) {
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
                if (isset($data['params']) && $data['params'] < 1) {
                    $ret['result'] = "ERR";
                    $ret['error'] = "Invalid number of params";
                    break;
                }

                // A fake HIT is created to get information about the layout
                if (isset($data['layout_id'])) {
                    if (!isset($data['params_fields']) || !$data['params_fields']) {
                        $ret['result'] = "ERR";
                        $ret['error'] = "Layout fields are required when a layout ID is specified";
                        break;
                    }
                    $out_params = testLayout($data['layout_id']);
                    if ($out_params['result'] == "ERR") {
                        $ret['result'] = "ERR";
                        $ret['error'] = $out_params['error'];
                        break;
                    }
                    if (isset($out_params['layout_fields'])) {
                        $data['layout_fields'] = $out_params['layout_fields'];
                    }
                }

                if (isset($data['layout_fields'])) {
                    $layout_fields = explode(",", $data['layout_fields']);
                    $layout_fields = array_map("trim", $layout_fields);
                    $params_fields = explode(",", $data['params_fields']);
                    $params_fields = array_map("trim", $params_fields);

                    $all_included = true;
                    foreach ($params_fields as $param_field) {
                        for ($i = 1; $i <= $data['params']; $i++) {
                            $p = $param_field . $i;
                            $found_key = array_search($p, $layout_fields);
                            if ($found_key === false) {
                                $all_included = false;
                                $ret['result'] = "ERR";
                                $ret['error'] = "$p is not included in layout";
                                break 3;
                            }
                            array_splice($layout_fields, $found_key, 1);
                        }
                        $layout_fields[] = $param_field . "#";
                    }

                    $data['layout_fields'] = implode(', ', $layout_fields);
                    $data['params_fields'] = implode(', ', $params_fields);
                }

                // $ret['data'] = $data;
                // $ret['result'] = "ERR";
                // $ret['error'] = "Test";
                // break;

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

        default:
            $ret['result'] = "ERR";
            $ret['error'] = "Invalid action";
            break;

}

echo json_encode($ret, JSON_PRETTY_PRINT);
