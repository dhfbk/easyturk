<?php

$parent_folder = dirname(dirname(__FILE__));
require_once($parent_folder . "/inc/include.php");

// $createQualificationTypes = false;
$createHITs = true;
$updateHITs = true;

while (true) {

    $query = "SELECT * FROM users WHERE deleted = '0'";
    $DB->query($query, "users");
    while ($UserInfo = $DB->fetch("users")) {
        $UserID = $UserInfo['id'];
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

        // if ($createQualificationTypes) {
        //     $query = "SELECT * FROM projects
        //         WHERE status >= 3 AND user_id = '{$UserID}' AND deleted = '0'";
        //     $DB->query($query, 3);
        //     while ($ProjectInfo = $DB->fetch(3)) {
        //         $ProjectID = $ProjectInfo['id'];

        //         $DB->startTransaction();
        //         $query = "SELECT l.id
        //             FROM `file_lines` l
        //             LEFT JOIN project_files f ON f.id = l.file_id
        //             WHERE
        //                 f.project_id = '{$ProjectID}'
        //                 AND f.deleted = '0'
        //                 AND f.is_gold = '1'
        //                 AND qualification IS NULL
        //                 LIMIT 10
        //                 FOR UPDATE";
        //         if ($DB->querynum($query, 4)) {
        //             while ($row = $DB->fetch(4)) {
        //                 $rowID = $row['id'];
        //                 print("$rowID\n");
        //                 $response = $mTurk->createQualificationType([
        //                     "Description" => "Turk has already answered to gold question {$rowID} on project: {$ProjectInfo['title']}",
        //                     "Name" => "$ProjectID-$rowID",
        //                     "QualificationTypeStatus" => "Active"
        //                 ]);
        //                 if (isset($response['QualificationType']['QualificationTypeId'])) {
        //                     $DB->queryupdate("file_lines", ["qualification" => $response['QualificationType']['QualificationTypeId']], ["id" => $rowID]);
        //                     l(0, "set_qualification_type", $response, $response['QualificationType']['QualificationTypeId']);
        //                 }
        //                 else {
        //                     l(0, "set_qualification_type", $response);
        //                 }
        //             }
        //         }
        //         $DB->commitTransaction();
        //     }
        // }

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

                $continue = true;
                // $qualifications = array();
                // $query = "SELECT * FROM clusters c
                //     LEFT JOIN file_lines l ON l.id = c.line_id
                //     LEFT JOIN project_files f ON f.id = l.file_id
                //     WHERE c.cluster_index = '{$rowH['id_cluster']}'
                //         AND c.deleted = '0'
                //         AND f.deleted = '0'
                //         AND f.project_id = '{$rowH['id_project']}'";
                // $DB->query($query);
                // while ($row = $DB->fetch()) {
                //     if ($row['is_gold']) {
                //         if (!$row['qualification']) {
                //             $continue = false;
                //             break;
                //         }
                //         else {
                //             $qualifications[] = $row['qualification'];
                //         }
                //     }
                // }

                if ($continue) {
                    // $start_array = $hit_data['QualificationRequirements'] ?: array();
                    // foreach ($qualifications as $qualification) {
                    //     $a = array();
                    //     $a['QualificationTypeId'] = $qualification;
                    //     $a['Comparator'] = "DoesNotExist";
                    //     $a['ActionsGuarded'] = "DiscoverPreviewAndAccept";
                    //     $start_array[] = $a;
                    // }
                    // $hit_data['QualificationRequirements'] = $start_array;

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
                    // print($response['HIT']['HITId'] . "\n");
                }
                // print_r($hit_details['hitData']);
            }
            $DB->commitTransaction();
        }

        if ($updateHITs) {
            $query = "SELECT * FROM cluster_to_hit
                WHERE
                    hit_status != 'Reviewable'
                    AND hit_status != 'Disposed'
                    AND hit_status != 'Inserted'
                    AND (checked_at IS NULL OR DATE_ADD(checked_at, INTERVAL 5 MINUTE) < NOW())
                    AND deleted = '0'
                    AND assignments_pending > 0
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
