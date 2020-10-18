<?php

require_once("config.php");
require_once("Mysql_connector.class.php");
require_once("vendor/autoload.php");

$DB = new Mysql_connector($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
$DB->select_db($DB_NAME);
$mysqli = $DB->connection;

// Functions

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
        $data = unserialize($row['line_text']);
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
            $HITLayoutParameters[] = [
                "Name" => $layoutData['field'],
                "Value" => $val
            ];
        }
        else {
            $field = substr($layoutData['field'], 0, strlen($layoutData['field']) - 1);
            for ($i = 1; $i <= $r['params']; $i++) {
                $value = $cluster_data[$i - 1][$layoutData['valueFrom']];
                $HITLayoutParameters[] = [
                    "Name" => $field . $i,
                    "Value" => $value
                ];
            }
        }
    }
    $toSave['hitData']['HITLayoutParameters'] = $HITLayoutParameters;

    $AssignmentReviewPolicy = [];
    if ($goldInfo && ($toSave['rejectIfGoldWrong'] || $toSave['acceptIfGoldRight'])) {
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

    $query = "SELECT h.id, u.region_name, u.aws_access_key_id,
            u.aws_secret_access_key, u.use_sandbox, p.params, p.hit_details,
            h.id_project, h.id_cluster
        FROM `cluster_to_hit` h
        LEFT JOIN projects p ON p.id = h.id_project
        LEFT JOIN users u ON u.id = p.user_id
        WHERE h.deleted = '0' AND h.id_hit = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $hit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return;
    }
    $taskInfo = $result->fetch_assoc();

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
    }
    catch (Exception $e) {
        l($code, "get_hit", $e->getMessage(), $hit_id);
    }
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
            $layout_details = unserialize($taskInfo['hit_details']);
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


    $query = "UPDATE cluster_to_hit SET checked_at = NOW() WHERE id = '{$taskInfo['id']}'";
    $mysqli->query($query);
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

function generaPassword() {
	$parole = array(
		"topolino",
		"pippo",
		"paperino",
		"gambadilegno",
		"macchianera",
		"bandabassotti",
		"nonnapapera",
		"paperina",
		"minnie",
		"pluto",
		"archimede",
		"clarabella",
		"orazio",
		"ziopaperone",
		"gastone",
		"paperoga",
		"paperina",
		"battista"
		);
	if (file_exists("random_words.txt")) {
		$fn = fopen("random_words.txt", "r");
		$parole = array();

		while(!feof($fn))  {
			$result = fgets($fn);
			$result = trim($result);
			if (strlen($result) > 0) {
				$parole[] = $result;
			}
		}

		fclose($fn);
	}
	$n = $parole[rand(0, count($parole) - 1)];
	$n .= str_pad(rand(1, 999), 3, "0", STR_PAD_LEFT);
	$n .= randomPassword();
	return $n;
}

function randomPassword($len = 1) {
	$alphabet = '!@#?%$';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < $len; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function isValidJSON($str) {
	json_decode($str);
	return json_last_error() == JSON_ERROR_NONE;
}
