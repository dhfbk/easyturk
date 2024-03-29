<?php

require_once("config.php");
require_once("Mysql_connector.class.php");
require_once("vendor/autoload.php");

require_once("emoji.php");

define("SANDBOX_URL", "https://mturk-requester-sandbox.us-east-1.amazonaws.com");
define("MASTER_QUALIFICATION_ID", "2F1QJWKUDD8XADTFD2Q0G6UTO95ALH");
define("SB_MASTER_QUALIFICATION_ID", "2ARFPLSP75KLA8M8DH1HTEQVJT3SY6");
define("ADULT_QUALIFICATION_ID", "00000000000000000060");
define("LOCATION_QUALIFICATION_ID", "00000000000000000071");


$DB = new Mysql_connector($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
$DB->select_db($DB_NAME);
$DB->connection->set_charset("utf8mb4");
$mysqli = $DB->connection;

$DB->query("SET SESSION group_concat_max_len = 1000000");

// Functions

function checkBehaviorData($input, $r) {
    $toSave['rejectIfGoldWrong'] = boolval($input['rejectIfGoldWrong'] ?: false);
    $toSave['acceptIfGoldRight'] = boolval($input['acceptIfGoldRight'] ?: false);
    $toSave['reject_old'] = boolval($input['reject_old'] ?: false);
    $toSave['block_worker_fast'] = boolval($input['block_worker_fast'] ?: false);
    $toSave['block_worker_bad'] = boolval($input['block_worker_bad'] ?: false);

    if ($toSave['rejectIfGoldWrong']) {
        if (!preg_match("/[0-9]+/", $input['assignNumber'])) {
            throw new Exception("Invalid value {$input['assignNumber']} for assignNumber");
        }
        if ($input['assignNumber'] < $r['workers']) {
            throw new Exception("assignNumber must be at least {$r['workers']} in this project");
        }

        $toSave['rejectPay'] = boolval($input['rejectPay'] ?: false);
        $toSave['assignNumber'] = $input['assignNumber'];
        $toSave['rejectReason'] = $input['rejectReason'];
    }

    if ($toSave['block_worker_fast']) {
        if (!preg_match("/[0-9]+/", $input['rejectTime'])) {
            throw new Exception("Invalid value {$input['rejectTime']} for rejectTime");
        }
        if ($input['rejectTime'] < 10) {
            throw new Exception("Value {$input['rejectTime']} for rejectTime must be greater than 10");
        }

        $toSave['blockSeconds'] = boolval($input['blockSeconds'] ?: false);
        $toSave['rejectTime'] = $input['rejectTime'];
    }

    if ($toSave['block_worker_bad']) {
        if (!preg_match("/[0-9]+/", $input['missNumberTotal'])) {
            throw new Exception("Invalid value {$input['missNumberTotal']} for missNumberTotal");
        }
        if (!preg_match("/[0-9]+/", $input['missNumber'])) {
            throw new Exception("Invalid value {$input['missNumber']} for missNumber");
        }
        if ($input['missNumber'] > $input['missNumberTotal']) {
            throw new Exception("missNumber should be less than missNumberTotal");
        }

        $toSave['blockMisclass'] = boolval($input['blockMisclass'] ?: false);
        $toSave['missNumberTotal'] = $input['missNumberTotal'];
        $toSave['missNumber'] = $input['missNumber'];
    }

    $toSave['skip_check'] = $toSave['block_worker_bad'] || $toSave['block_worker_fast'] || $toSave['reject_old'];

    return $toSave;
}

function acceptOrReject($thisHit, $thisAssignment, $hit_details, $id_project, $type = 0, $overrideRejection = false) {
    global $mTurk, $DB;

    $accept = true;

    if ($hit_details['rejectIfGoldWrong']) {

        $goldInfo = false;
        $trainingInfo = false;
        $query = "SELECT * FROM project_files WHERE project_id = '{$id_project}' AND deleted = '0'";
        $DB->query($query, "acceptOrReject");
        while ($rr = $DB->fetch("acceptOrReject")) {
            if ($rr['is_gold']) {
                $goldInfo = $rr;
            }
            else {
                $trainingInfo = $rr;
            }
        }

        if ($goldInfo) {
            $trainingFields = unserialize($trainingInfo['fields']);
            $goldFields = unserialize($goldInfo['fields']);
            $goldFields = array_diff($goldFields, $trainingFields);

            // print("Assignment: " . $thisAssignment . "\n");
            // print_r($goldFields);

            $hit_to_line = getResults($id_project, $thisHit);
            foreach ($hit_to_line[$thisHit] as $lineInfo) {
                if ($lineInfo['isGold']) {
                    // print_r($lineInfo);
                    foreach ($goldFields as $goldVariable) {
                        if (!isset($lineInfo['answers'][$goldVariable])) {
                            continue;
                        }
                        foreach ($lineInfo['answers'][$goldVariable]['details'] as $annotation) {
                            // print_r($annotation);
                            if ($annotation['assignment_id'] == $thisAssignment) {
                                if ($annotation['value'] != $lineInfo['inputData'][$goldVariable]) {
                                    $accept = false;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    try {
        if ($accept) {
            $mTurk->approveAssignment(["AssignmentId" => $thisAssignment, "OverrideRejection" => $overrideRejection]);
            if ($type == 0) {
                print("Accepted {$thisAssignment} in {$thisHit}\n");
            }
        }
        else if (!$overrideRejection) {
            $mTurk->rejectAssignment(["AssignmentId" => $thisAssignment, "RequesterFeedback" => $hit_details['rejectReason']]);
            if ($type == 0) {
                print("Rejected {$thisAssignment} in {$thisHit}\n");
            }

            $response = $mTurk->getHIT(["HITId" => $thisHit]);
            $response = $response->toArray();

            $actualAssignments = $response['HIT']['MaxAssignments'];
            if ($actualAssignments < $hit_details['assignNumber']) {
                $mTurk->createAdditionalAssignmentsForHIT(["HITId" => $thisHit, "NumberOfAdditionalAssignments" => 1]);
                if ($type == 0) {
                    print("Extended {$thisHit}\n");
                }
            }
        }
    }
    catch (Exception $e) {
        if ($type == 0) {
            print_r($e->getMessage());
        }
        l($type, "accept_reject_assignment", $e->getMessage(), $thisAssignment);
    }
    l($type, "accept_reject_assignment", "", $thisAssignment);
    updateHIT($thisHit, $type);

    return $accept;
}

function getResults($projectID, $hitID = "") {
    global $DB;

    $hit_to_line = [];
    $projectID = addslashes($projectID);
    $hitID = addslashes($hitID);

    $hitPart = "AND h.id_hit IS NOT NULL";
    if ($hitID) {
        $hitPart = "AND h.id_hit = '$hitID'";
    }
    $query = "SELECT l.line_text, f.is_gold, cl.cluster_index, h.id_hit, f.fields
        FROM clusters cl
        LEFT JOIN file_lines l ON cl.line_id = l.id
        LEFT JOIN project_files f ON l.file_id = f.id
        LEFT JOIN cluster_to_hit h ON h.id_cluster = cl.cluster_index AND h.id_project = '{$projectID}' AND h.deleted = '0'
        WHERE f.deleted = '0' AND cl.deleted = '0'
            AND f.project_id = '{$projectID}'
            $hitPart
        ORDER BY cl.alea";
    $DB->query($query, "getResults");
    while ($row = $DB->fetch("getResults")) {
        if (!isset($hit_to_line[$row['id_hit']])) {
            $hit_to_line[$row['id_hit']] = [];
        }
        $encoding = mb_detect_encoding($row['line_text'], ["Windows-1252", 'UTF-8']);
        if ($encoding != "UTF-8") {
            $row['line_text'] = mb_convert_encoding($row['line_text'], "Windows-1252", 'UTF-8');
        }
        $line_text = @unserialize($row['line_text']);
        if (!$line_text) {
            continue;
        }
        // echo "\n\n";
        $fields = [];
        foreach ($line_text as $key => $value) {
            $fields[unserialize($row['fields'])[$key]] = $value;
        }
        $hit_to_line[$row['id_hit']][] = [
            "inputData" => $fields,
            "isGold" => $row['is_gold'],
            "hit_id" => $row['id_hit']
        ];
    }

    $query = "SELECT a.*, p.hit_details, p.params
        FROM assignments a
        LEFT JOIN cluster_to_hit h ON h.id_hit = a.hit_id
        LEFT JOIN projects p ON p.id = h.id_project
        WHERE h.deleted = '0' AND p.deleted = '0'
            $hitPart
            AND h.id_project = '{$projectID}'";
    $DB->query($query, "getResults");
    while ($row = $DB->fetch("getResults")) {
        $info = unserialize($row['assignment_info']);
        if (!isset($info['Answer'])) {
            continue;
        }

        $data = simplexml_load_string($info['Answer']);

        $answers = [];
        foreach ($data->Answer as $record) {
            $answers[(string) $record->QuestionIdentifier] = (string) $record->FreeText;
        }

        $hit_id = $row['hit_id'];
        $hit_details = unserialize($row['hit_details']);

        for ($i = 0; $i < $row['params']; $i++) {
            if (!isset($hit_to_line[$hit_id][$i]["answers"])) {
                $hit_to_line[$hit_id][$i]["answers"] = [];
            }
            foreach ($hit_details['answerData'] as $rule) {
                $varName = str_replace("#", $i + 1, $rule['varName']);
                if (!isset($hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']])) {
                    $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']] = [];
                    $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["details"] = [];
                    $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"] = [];
                    $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["winners"] = [];
                    $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"][$rule['varValueTo']] = 0;
                }
                if (@$answers[$varName] == $rule['varValue']) {
                    if ($row['status'] == "Approved") {
                        if (!isset($hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"][$rule['varValueTo']])) {
                            $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"][$rule['varValueTo']] = 0;
                        }
                        $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"][$rule['varValueTo']]++;
                    }
                    $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["details"][] = [
                        "value" => $rule['varValueTo'],
                        "status" => $row['status'],
                        "worker_id" => $row['worker_id'],
                        "assignment_id" => $row['assignment_id'],
                    ];
                }
                $maxVal = max($hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"]);
                $maxValKeys = array_keys($hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["values"], $maxVal);
                $hit_to_line[$hit_id][$i]["answers"][$rule['varNameTo']]["winners"] = $maxValKeys;
            }
        }
    }

    return $hit_to_line;
}

function getHITLayoutParameters($r, $project_id, $cluster_index, $goldFields, $toSave) {
    global $DB, $mysqli;

    $goldInfo = false;

    $cluster_data = array();
    $goldIndexes = [];
    $query = "SELECT l.line_text, f.fields, f.is_gold
        FROM clusters c
        LEFT JOIN file_lines l ON l.id = c.line_id
        LEFT JOIN project_files f ON f.id = l.file_id
        WHERE c.deleted = '0' AND f.deleted = '0'
            AND f.project_id = '{$project_id}' AND c.cluster_index = '{$cluster_index}'
        ORDER BY c.alea";
    $num = $DB->querynum($query, "getHITLayoutParameters");
    if (!$num) {
        throw new Exception("No clusters found ".$query);
        // $ret['result'] = "ERR";
        // $ret['error'] = "No clusters found";
        // break;
    }
    if ($num != $r['params']) {
        throw new Exception("Number of parameters does not match");
        // $ret['result'] = "ERR";
        // $ret['error'] = "Number of parameters does not match";
        // break;
    }
    while ($row = $DB->fetch("getHITLayoutParameters")) {
        $fields = unserialize($row['fields']);

        // if this happens, throw an error
        $data = unserialize($row['line_text']);
        if (!$data) {
            $cluster_data[] = array();
            continue;
        }
        $okData = array();
        for ($i = 0; $i < count($fields); $i++) {
            $okData[$fields[$i]] = $data[$i];
        }
        $cluster_data[] = $okData;
        if ($row['is_gold']) {
            $goldInfo = true;
            $goldIndexes[count($cluster_data)] = [];
            foreach ($goldFields as $goldField) {
                $thisGoldIndex = array_search($goldField, $fields);
                $goldIndexes[count($cluster_data)][$goldField] = $data[$thisGoldIndex];
            }
        }
    }

    $HITLayoutParameters = array();
    foreach ($toSave['layoutData'] as $layoutData) {
        if (substr($layoutData['field'], -1) !== "#") {
            $val = $layoutData['isHandWritten'] ? $layoutData['customValue'] : $r[substr($layoutData['valueFrom'], 1)];
            // $val = htmlentities($val);
            $val = wp_encode_emoji($val);
            $val = mb_encode_numericentity($val, array(0x0080, 0xffff, 0, 0xffff), 'UTF-8');
            // $val = preg_replace('/[\x{10000}-\x{10FFFF}]/u', "\xEF\xBF\xBD", $val);
            $HITLayoutParameters[] = [
                "Name" => $layoutData['field'],
                "Value" => $val
            ];
        }
        else {
            $field = substr($layoutData['field'], 0, strlen($layoutData['field']) - 1);
            for ($i = 1; $i <= $r['params']; $i++) {
                if (!isset($cluster_data[$i - 1][$layoutData['valueFrom']])) {
                    continue;
                }
                $value = $cluster_data[$i - 1][$layoutData['valueFrom']];
                // $value = htmlentities($value);
                $value = wp_encode_emoji($value);
                $value = mb_encode_numericentity($value, array(0x0080, 0x10ffff, 0, 0xffffff), 'UTF-8');
                # $value = preg_replace('/[\x{10000}-\x{10FFFF}]/u', "\xEF\xBF\xBD", $value);
                $HITLayoutParameters[] = [
                    "Name" => $field . $i,
                    "Value" => $value
                ];
            }
        }
    }
    $toSave['hitData']['HITLayoutParameters'] = $HITLayoutParameters;

    $AssignmentReviewPolicy = [];
    if (!$toSave['skip_check'] && $goldInfo && ($toSave['rejectIfGoldWrong'] || $toSave['acceptIfGoldRight'])) {
        $mapEntries = [];
        foreach ($goldIndexes as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($toSave['answerData'] as $answerData) {
                    if ($answerData['varNameTo'] == $key2 && $answerData['varValueTo'] == $value2) {
                        $mapEntries[] = ["Key" => str_replace("#", $key, $answerData['varName']), "Values" => [$answerData['varValue']]];
                    }
                }
            }
        }

        // if (!count($mapEntries)) {
        //     throw new Exception("No rules found");
        // }
    
        if ($toSave['rejectIfGoldWrong'] || $toSave['acceptIfGoldRight']) {
            $AssignmentReviewPolicy['PolicyName'] = "ScoreMyKnownAnswers/2011-09-01";
            $AssignmentReviewPolicy['Parameters'] = [];
            $AssignmentReviewPolicy['Parameters'][] = ["Key" => "AnswerKey", "MapEntries" => $mapEntries];
            if ($toSave['rejectIfGoldWrong']) {
                $AssignmentReviewPolicy['Parameters'][] = ["Key" => "RejectReason", "Values" => [$toSave['rejectReason']]];
                $AssignmentReviewPolicy['Parameters'][] = ["Key" => "RejectIfKnownAnswerScoreIsLessThan", "Values" => ["99"]];
                if ($toSave['assignNumber'] > $r['workers']) {
                    $AssignmentReviewPolicy['Parameters'][] = ["Key" => "ExtendMaximumAssignments", "Values" => ["{$toSave['assignNumber']}"]];
                    $AssignmentReviewPolicy['Parameters'][] = ["Key" => "ExtendIfKnownAnswerScoreIsLessThan", "Values" => ["99"]];
                }
            }
            if ($toSave['acceptIfGoldRight']) {
                $AssignmentReviewPolicy['Parameters'][] = ["Key" => "ApproveIfKnownAnswerScoreIsAtLeast", "Values" => ["100"]];
            }
        }
    }

    if (count($AssignmentReviewPolicy)) {
        $toSave['hitData']['AssignmentReviewPolicy'] = $AssignmentReviewPolicy;
    }

    return $toSave;
}

function updateHIT($hit_id, $code = 2) {
    global $DB, $mysqli;

    $DB->startTransaction();
    try {
        $query = "SELECT h.id, u.region_name, u.aws_access_key_id,
                u.aws_secret_access_key, u.use_sandbox, p.params, p.hit_details,
                h.id_project, h.id_cluster, p.user_id
            FROM `cluster_to_hit` h
            LEFT JOIN projects p ON p.id = h.id_project
            LEFT JOIN users u ON u.id = p.user_id
            WHERE h.deleted = '0' AND h.id_hit = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $hit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception('HIT not found.');
        }
        $taskInfo = $result->fetch_assoc();
        $layout_details = unserialize($taskInfo['hit_details']);

        $UseSandbox = $taskInfo['use_sandbox'];

        $mTurkOptions = [
            'version' => 'latest',
            'region'  => $taskInfo['region_name'],
            'credentials' => [
                'key' => $taskInfo['aws_access_key_id'],
                'secret' => $taskInfo['aws_secret_access_key'],
            ]
        ];
        if ($UseSandbox) {
            $mTurkOptions['endpoint'] = 'https://mturk-requester-sandbox.us-east-1.amazonaws.com';
        }
        $mTurk = new Aws\MTurk\MTurkClient($mTurkOptions);

        try {
            $hitResponse = $mTurk->getHIT(["HITId" => $hit_id]);
            $hitResponse = $hitResponse->toArray();
            l($code, "get_hit", $hitResponse, $hit_id);
            $data = [
                "hit_info" => serialize($hitResponse['HIT']),
                "hit_status" => $hitResponse['HIT']['HITStatus'],
                "max_assignments" => $hitResponse['HIT']['MaxAssignments'],
                "assignments_pending" => $hitResponse['HIT']['NumberOfAssignmentsPending'],
                "assignments_available" => $hitResponse['HIT']['NumberOfAssignmentsAvailable'],
                "assignments_completed" => $hitResponse['HIT']['NumberOfAssignmentsCompleted'],
            ];
            $DB->queryupdate("cluster_to_hit", $data, ["id_hit" => $hit_id], "updateHIT");
        }
        catch (Exception $e) {
            l($code, "get_hit", $e->getMessage(), $hit_id);
        }

        $nextToken = "";
        while (true) {
            $data = ["HITId" => $hit_id];
            if ($nextToken) {
                $data['NextToken'] = $nextToken;
            }

            try {
                $assignmentResponse = $mTurk->listAssignmentsForHIT($data);
            }
            catch (Exception $e) {
                l($code, "get_assignments_for_hit", $e->getMessage(), $hit_id);
                break;
            }
            $assignmentResponse = $assignmentResponse->toArray();

            if (!isset($assignmentResponse['NextToken']) || !$assignmentResponse['NextToken']) {
                break;
            }
            $nextToken = $assignmentResponse['NextToken'];
            l($code, "get_assignments_for_hit", $assignmentResponse, $hit_id);

            $line_ids = [];
            $query = "SELECT c.line_id
                FROM clusters c
                    LEFT JOIN file_lines l ON l.id = c.line_id
                    LEFT JOIN project_files f ON f.id = l.file_id
                WHERE f.deleted = '0' AND c.deleted = '0'
                    AND f.project_id = '{$taskInfo['id_project']}'
                    AND c.cluster_index = '{$taskInfo['id_cluster']}'
                ORDER BY c.alea";
            $DB->query($query, "updateHIT");
            while ($row = $DB->fetch("updateHIT")) {
                $line_ids[] = $row['line_id'];
            }

            foreach ($assignmentResponse['Assignments'] as $assignment) {
                $assignment_id = $assignment['AssignmentId'];
                // print("$assignment_id\n");
                $worker_id = $assignment['WorkerId'];
                $hit_id = $assignment['HITId'];
                $status = $assignment['AssignmentStatus'];

                $xml = simplexml_load_string($assignment['Answer']);
                $answers = [];
                foreach ($xml->Answer as $answer) {
                    $key = $answer->QuestionIdentifier->__toString();
                    $value = $answer->FreeText->__toString();
                    $answers[$key] = trim($value);
                }
                // print_r($answers);
                $a = array();
                for ($i = 0; $i < $taskInfo['params']; $i++) {
                    $a[$i] = [];
                    foreach ($layout_details['answerData'] as $ad) {
                        $varName = str_replace("#", $i + 1, $ad['varName']);
                        $varValue = $ad['varValue'];
                        if (isset($answers[$varName]) && $answers[$varName] == $varValue) {
                            $a[$i][$ad['varNameTo']] = $ad['varValueTo'];
                        }
                    }
                }
                for ($i = 0; $i < count($line_ids); $i++) {
                    foreach ($a[$i] as $key => $value) {
                        $data = [];
                        $data['line_id'] = $line_ids[$i];
                        $data['assignment_id'] = $assignment_id;
                        $data['label'] = $key;
                        $data['value'] = $value;
                        $DB->queryinsertodku("answers", $data, [], "updateHIT");
                    }
                }

                $assignment_info = serialize($assignment);
                $query = "INSERT INTO assignments (assignment_id, hit_id, worker_id, status, assignment_info)
                        VALUES(?, ?, ?, ?, ?)
                    ON DUPLICATE KEY
                    UPDATE
                        hit_id = ?,
                        worker_id = ?,
                        status = ?,
                        assignment_info = ?";
                if ($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param("sssssssss", $assignment_id, $hit_id, $worker_id, $status, $assignment_info, $hit_id, $worker_id, $status, $assignment_info);
                    $stmt->execute();
                    $stmt->close();
                }
            }        
        }

        // Check the project properties
        if ($layout_details['block_worker_fast'] && false) {
            $Seconds = $layout_details['rejectTime'];
            $Reason = "You answered too quickly to the HIT";

            $BlockList = [];
            $query = "SELECT *
                FROM assignments
                WHERE hit_id = ? AND status = 'Submitted'";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $hit_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($assignment = $result->fetch_assoc()) {
                    $assignmentInfo = unserialize($assignment['assignment_info']);
                    $start = strtotime($assignmentInfo['AcceptTime']);
                    $end = strtotime($assignmentInfo['SubmitTime']);

                    $timeInSeconds = $end - $start;
                    if ($timeInSeconds < $Seconds) {
                        $worker_id = $assignmentInfo['WorkerId'];
                        
                        $data = [];
                        $data['worker_id'] = $worker_id;
                        $data['user_id'] = $taskInfo['user_id'];
                        $DB->queryinsert("blocked_workers", $data);

                        try {
                            $hitResponse = $mTurk->createWorkerBlock(["Reason" => $Reason, "WorkerId" => $worker_id]);
                        }
                        catch (Exception $e) {
                            l($code, "block_worker_fast", $e->getMessage(), $worker_id);
                        }
                        $hitResponse = $hitResponse->toArray();
                        l($code, "block_worker_fast", $hitResponse, $worker_id);
                    }
                }        
            }
            else {
                if ($code == 0) {
                      print("prepare() failed: {$mysqli->error}\n");
                }
            }
        }

        $query = "UPDATE cluster_to_hit SET checked_at = NOW() WHERE id = '{$taskInfo['id']}'";
        $mysqli->query($query);

        $DB->commitTransaction();
    }
    catch (Exception $e) {
        $DB->rollbackTransaction();
    }
}

function testLayout($layout_id) {
    global $mTurk;
    $ret = ["result" => "OK"];

    try {
        $response = $mTurk->createHIT([
            "MaxAssignments" => 3,
            "LifetimeInSeconds" => 0,
            "Reward" => "0",
            "Title" => "Title",
            "Description" => "Description",
            "HITLayoutId" => $layout_id,
            "AssignmentDurationInSeconds" => 30
        ]);
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $ret['error_msg'] = $msg;
        if (preg_match("/Missing parameter names: ([^.]*)\./", $msg, $matches)) {
            $ret['layout_fields'] = str_replace(",", ", ", $matches[1]);
        }
        else {
            $ret['result'] = "ERR";
            $ret['error'] = "Invalid layout ID";
        }
    }

    return $ret;
}

function loadOptions() {
    global $mysqli;
    
    $Options = array();
    $query = "SELECT * FROM options";
    $result = $mysqli->query($query);
    while ($obj = $result->fetch_object()) {
        $Options[$obj->id] = $obj->value;
    }
    return $Options;
}

function l($origin, $action, $log_text, $id = "") {
    global $DB;

    $data = array();
    $data['origin'] = $origin;
    $data['action'] = $action;
    $data['id_mturk'] = $id;
    $data['log_text'] = print_r($log_text, true);
    $DB->queryinsert("logs", $data, "log");
}

function find($table, $id, $text) {
    global $mysqli;
    global $UserInfo;
    
    $stmt_up = $mysqli->prepare("SELECT * FROM {$table} WHERE id = ?");
    $stmt_up->bind_param("s", $id);
    $stmt_up->execute();
    $r = $stmt_up->get_result();
    if ($r->num_rows) {
        $row = $r->fetch_assoc();
        if (isset($row['user_id']) && $UserInfo['id'] != $row['user_id']) {
            $ret = array();
            $ret['result'] = "ERR";
            $ret['error'] = $text;
            echo json_encode($ret);
            exit();
        }
        return $row;
    }

    $ret = array();
    $ret['result'] = "ERR";
    $ret['error'] = $text;
    echo json_encode($ret);
    exit();
}

function isValidJSON($str) {
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
}
