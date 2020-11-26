<?php

$parent_folder = dirname(dirname(__FILE__));
require_once($parent_folder . "/inc/include.php");

$createHITs = true;
$updateHITs = true;
$updateBlockedWorkers = true;
$updateApprovalRejection = true;

$ManageAllSubmitted = false;

$IntervalForUpdateHits = 60;
$IntervalForBlockedWorkers = 300;
$IntervalForApprovalRejection = 60;
$IntervalInDaysForBlockingBadWorkers = 10;
$IntervalForApprovalRejectionOnExpiration = 2 * ($IntervalForUpdateHits + $IntervalForApprovalRejection);

while (true) {

    $query = "SELECT * FROM users WHERE deleted = '0'";
    $DB->query($query, "users");
    while ($UserInfo = $DB->fetch("users")) {
        $UserID = $UserInfo['id'];
        $UseSandbox = $UserInfo['use_sandbox'];

        $UserMoreInfo = [];
        if ($UserInfo['user_info']) {
            $UserMoreInfo = unserialize($UserInfo['user_info']);
        }

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

        if ($updateApprovalRejection) {

            $DoIt = true;

            if (isset($UserMoreInfo['last_approval_rejection_update'])) {
                $last_approval_rejection_update = strtotime($UserMoreInfo['last_approval_rejection_update']);
                if (time() < $last_approval_rejection_update + $IntervalForApprovalRejection) {
                    $DoIt = false;
                }
            }

            if ($DoIt) {
                $UserMoreInfo['last_approval_rejection_update'] = date("c");
                $DB->queryupdate("users", ["user_info" => serialize($UserMoreInfo)], ["id" => $UserID]);

                $query = "SELECT a.*, h.id_project, p.hit_details
                    FROM `assignments` a
                    LEFT JOIN cluster_to_hit h ON a.hit_id = h.id_hit
                    LEFT JOIN projects p ON p.id = h.id_project
                    WHERE p.deleted = '0' AND h.deleted = '0'
                        AND a.status = 'Submitted' AND p.user_id = '$UserID'";
                $DB->query($query, 2);
                while ($row = $DB->fetch_a(2)) {

                    $hit_details = unserialize($row['hit_details']);

                    if ($hit_details['reject_old']) {
                        $query = "SELECT * FROM blocked_workers
                            WHERE user_id = '$UserID' AND worker_id = '{$row['worker_id']}'";
                        if ($DB->querynum($query)) {
                            try {
                                $mTurk->rejectAssignment(["AssignmentId" => $row['assignment_id'], "RequesterFeedback" => "User has been blocked"]);
                                print("Rejected {$row['assignment_id']} in {$row['hit_id']} (blocked)\n");

                                $response = $mTurk->getHIT(["HITId" => $row['hit_id']]);
                                $response = $response->toArray();

                                $actualAssignments = $response['HIT']['MaxAssignments'];
                                if (isset($hit_details['assignNumber']) && $actualAssignments < $hit_details['assignNumber']) {
                                    $mTurk->createAdditionalAssignmentsForHIT(["HITId" => $row['hit_id'], "NumberOfAdditionalAssignments" => 1]);
                                    print("Extended {$row['hit_id']}\n");
                                }
                            }
                            catch (Exception $e) {
                                print_r($e->getMessage());
                                l(0, "accept_reject_assignment", $e->getMessage(), $row['assignment_id']);
                            }
                            l(0, "accept_reject_assignment", "", $row['assignment_id']);
                            updateHIT($row['hit_id'], 0);

                            continue;
                        }
                    }

                    // todo: check settings in project

                    $assignment_info = unserialize($row['assignment_info']);
                    $approval_time = strtotime($assignment_info['AutoApprovalTime']);
                    if ($approval_time - time() < $IntervalForApprovalRejectionOnExpiration || $ManageAllSubmitted) {
                        $thisHit = $row['hit_id'];
                        $thisAssignment = $row['assignment_id'];
                        acceptOrReject($thisHit, $thisAssignment, $hit_details, $row['id_project'], 0);
                    }
                }

                $query = "SELECT a.worker_id, h.id_project, p.hit_details, COUNT(*) num,
                        bl.updated_at blocked,
                        GROUP_CONCAT(h.id_hit ORDER BY a.created_at DESC SEPARATOR '|') hits,
                        GROUP_CONCAT(a.assignment_id ORDER BY a.created_at DESC SEPARATOR '|') assignments,
                        GROUP_CONCAT(a.status ORDER BY a.created_at DESC SEPARATOR '|') statuses
                    FROM assignments a
                    LEFT JOIN cluster_to_hit h ON h.id_hit = a.hit_id
                    LEFT JOIN projects p ON p.id = h.id_project
                    LEFT JOIN blocked_workers bl ON bl.worker_id = a.worker_id AND bl.user_id = '$UserID'
                    WHERE h.deleted = '0' AND p.deleted = '0' AND p.user_id = '$UserID'
                    AND DATE_ADD(a.created_at, INTERVAL $IntervalInDaysForBlockingBadWorkers DAY) > NOW()
                    GROUP BY worker_id, h.id_project
                    ORDER BY num DESC";
                $DB->query($query, 2);
                while ($row = $DB->fetch_a(2)) {
                    $hit_details = unserialize($row['hit_details']);
                    $missNumber = $hit_details['missNumber'];
                    $missNumberTotal = $hit_details['missNumberTotal'];

                    $allHits = explode('|', $row['hits']);
                    $allAssignments = explode('|', $row['assignments']);
                    $allStatuses = explode('|', $row['statuses']);

                    $hits = array_slice($allHits, 0, $missNumberTotal);

                    $remainingHits = array_slice($allHits, $missNumberTotal);
                    $remainingAssignments = array_slice($allAssignments, $missNumberTotal);
                    $remainingStatuses = array_slice($allStatuses, $missNumberTotal);

                    $correct = 0;
                    foreach ($hits as $hit) {
                        $hit_to_line = getResults($row['id_project'], $hit);
                        foreach ($hit_to_line[$hit] as $hit_info) {
                            if ($hit_info['isGold']) {
                                $thisCorrect = true;
                                foreach (array_keys($hit_info['inputData']) as $key) {
                                    if (isset($hit_info[$key])) {
                                        foreach ($hit_info[$key]['details'] as $answer) {
                                            if ($answer['worker_id'] == $row['worker_id']) {
                                                $thisCorrect = $thisCorrect && ($answer['value'] == $hit_info['inputData'][$key]);
                                            }
                                        }
                                    }
                                }
                                if ($thisCorrect) {
                                    $correct++;
                                }
                            }
                        }
                    }
                    $wrong = min($row['num'], $missNumberTotal) - $correct;

                    if ($hit_details['block_worker_bad']) {
                        echo "{$row['worker_id']}\t{$row['id_project']}\t{$row['num']}\t($wrong)";
                        if ($row['blocked']) {
                            echo "\tBLOCKED!";
                        }
                        echo "\n";
                        if ($row['blocked']) {
                            continue;
                        }

                        if ($row['num'] < $missNumberTotal) {
                            continue;
                        }

                        if ($wrong >= $missNumber) {
                            $worker_id = $row['worker_id'];
                            $Reason = "You missed $missNumber out of $missNumberTotal gold records.";
                            print("Blocking worker {$worker_id}\n");
                            
                            $data = [];
                            $data['worker_id'] = $worker_id;
                            $data['user_id'] = $UserID;
                            $DB->queryinsert("blocked_workers", $data);

                            try {
                                $hitResponse = $mTurk->createWorkerBlock(["Reason" => $Reason, "WorkerId" => $worker_id]);
                            }
                            catch (Exception $e) {
                                l(0, "block_worker_bad", $e->getMessage(), $worker_id);
                            }
                            $hitResponse = $hitResponse->toArray();
                            l(0, "block_worker_bad", $hitResponse, $worker_id);
                        }
                        else {

                            for ($i = 0; $i < count($remainingAssignments); $i++) {
                                $thisHit = $remainingHits[$i];
                                $thisAssignment = $remainingAssignments[$i];
                                $thisStatus = $remainingStatuses[$i];

                                if ($thisStatus != "Submitted") {
                                    continue;
                                }

                                acceptOrReject($thisHit, $thisAssignment, $hit_details, $row['id_project'], 0);
                            }
                        }
                    }
                }
            }
        }

        if ($updateBlockedWorkers) {

            $DoIt = true;

            if (isset($UserMoreInfo['last_blocked_update'])) {
                $last_blocked_update = strtotime($UserMoreInfo['last_blocked_update']);
                if (time() < $last_blocked_update + $IntervalForBlockedWorkers) {
                    $DoIt = false;
                }
            }

            if ($DoIt) {
                $UserMoreInfo['last_blocked_update'] = date("c");
                $DB->queryupdate("users", ["user_info" => serialize($UserMoreInfo)], ["id" => $UserID]);

                print("Updating blocked workers for user {$UserID}\n");
                $BlockedList = [];

                $nextToken = "";
                while (true) {
                    $data = ["MaxResults" => 100];
                    if ($nextToken) {
                        $data['NextToken'] = $nextToken;
                    }
                    $response = $mTurk->listWorkerBlocks($data);
                    $response = $response->toArray();

                    if (!isset($response['NextToken']) || !$response['NextToken']) {
                        break;
                    }
                    $nextToken = $response['NextToken'];

                    foreach ($response['WorkerBlocks'] as $workerBlock) {
                        $BlockedList[] = $workerBlock['WorkerId'];
                    }
                }

                $DB->startTransaction();
                $query = "DELETE FROM blocked_workers WHERE user_id = '$UserID'";
                $DB->query($query);
                foreach ($BlockedList as $worker_id) {
                    $DB->queryinsert("blocked_workers", ["user_id" => $UserID, "worker_id" => $worker_id]);
                }
                $DB->commitTransaction();
            }
        }

        if ($createHITs) {
            $query = "SELECT h.*,
                    p.name, p.title, p.description, p.keywords, p.hit_details,
                    p.reward, p.id, p.params, p.workers, p.id project_id, h.id id_h
                FROM cluster_to_hit h
                LEFT JOIN projects p ON p.id = h.id_project
                WHERE h.id_hit IS NULL AND h.deleted = '0' AND p.user_id = '{$UserID}'
                AND hit_details IS NOT NULL AND h.hit_status != 'Error'
                LIMIT 1
                FOR UPDATE";
            $DB->query($query, 2);
            while ($rowH = $DB->fetch(2)) {
                $hit_details = unserialize($rowH['hit_details']);

                $goldInfo = false;
                $trainingInfo = false;
                $query = "SELECT * FROM project_files WHERE project_id = '{$rowH['project_id']}' AND deleted = '0'";
                $DB->query($query);
                while ($row = $DB->fetch()) {
                    if ($row['is_gold']) {
                        $goldInfo = $row;
                    }
                    else {
                        $trainingInfo = $row;
                    }
                }
                $trainingFields = unserialize($trainingInfo['fields']);
                $goldFields = unserialize($goldInfo['fields']);
                $goldFields = array_diff($goldFields, $trainingFields);

                $hit_details = getHITLayoutParameters($rowH, $rowH['project_id'], $rowH['id_cluster'], $goldFields, $hit_details);

                $hit_data = $hit_details['hitData'];
                $hit_data['Title'] = $rowH['title'];
                $hit_data['Description'] = $rowH['description'];
                $hit_data['Keywords'] = $rowH['keywords'];
                $hit_data['Reward'] = "{$rowH['reward']}";

                try {
                    $response = $mTurk->createHIT($hit_data);
                }
                catch (Exception $e) {
                    print_r($e->getMessage());
                    print_r($hit_data);
                    l(0, "create_hit", $e->getMessage(), $r['id']);
                    $DB->queryupdate("cluster_to_hit", array("hit_status" => "Error"), array("id" => $rowH['id_h']));
                    break;
                }
                $response = $response->toArray();
                l(0, "create_hit", $response, $response['HIT']['HITId']);

                $toUpdate = ["hit_status" => $response['HIT']["HITStatus"],
                        "id_hit" => $response['HIT']["HITId"],
                        "hit_info" => serialize($response['HIT'])];
                $DB->queryupdate("cluster_to_hit", $toUpdate, ["id" => $rowH['id_h']]);

                try {
                    $tmpResponse2 = $mTurk->updateNotificationSettings([
                        'Active' => true,
                        'HITTypeId' => $response['HIT']['HITTypeId'],
                        'Notification' => [
                            'Destination' => 'arn:aws:sns:us-east-2:477794837227:mturk_frontend',
                            'EventTypes' => ["AssignmentAccepted", "AssignmentAbandoned", "AssignmentReturned", "AssignmentSubmitted", "HITReviewable", "HITExpired"],
                            'Transport' => 'SNS',
                            'Version' => '2006-05-05',
                        ],
                    ]);
                    $tmpResponse2 = $tmpResponse2->toArray();
                    l(0, "update_notification_hit", $tmpResponse2, $response['HIT']['HITTypeId']);
                }
                catch (Exception $e) {
                    l(0, "update_notification_hit", $e->getMessage(), $response['HIT']['HITTypeId']);
                }

                print("Added {$response['HIT']['HITId']}\n");
            }
            $DB->commitTransaction();
        }

        if ($updateHITs) {
            $query = "SELECT * FROM cluster_to_hit
                WHERE
                    hit_status != 'Reviewable'
                    AND hit_status != 'Disposed'
                    AND hit_status != 'Inserted'
                    AND (checked_at IS NULL OR DATE_ADD(checked_at, INTERVAL $IntervalForUpdateHits SECOND) < NOW())
                    AND deleted = '0'
                    AND (assignments_pending > 0 OR max_assignments > assignments_pending + assignments_available + assignments_completed)
                LIMIT 1
                FOR UPDATE";
            // $query = "SELECT * FROM cluster_to_hit
            //     WHERE deleted = '0'
            //     LIMIT 1
            //     FOR UPDATE";
            $DB->query($query, "updateHIT_server");
            while ($row = $DB->fetch("updateHIT_server")) {
                print("Updating {$row['id_hit']}\n");
                updateHIT($row['id_hit'], 0);
            }
            $DB->commitTransaction();
        }
    }

    sleep(1);
    // break;
}
