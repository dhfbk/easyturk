<?php

require_once("../inc/include.php");

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

$message = Message::fromRawPostData();

$validator = new MessageValidator();
if ($validator->isValid($message)) {
    $msg = $message->toArray();
    
    switch ($msg['Type']) {
        case "SubscriptionConfirmation":
            // Approve the subscription
            file_get_contents($msg['SubscribeURL']);
            break;

        case "Notification":
            $message = json_decode($msg['Message'], true);
            foreach ($message['Events'] as $event) {
                $hit_id = $event['HITId'] ?: false;
                if (!$hit_id) {
                    break;
                }

                updateHIT($hit_id, 2);

                // switch ($event['EventType']) {
                //     case "AssignmentAccepted":
                //     case "AssignmentSubmitted":
                //     case "AssignmentReturned":
                //     case "AssignmentAbandoned":
                //         $assignment_id = $event['AssignmentId'];
                //         break;
                //     case "HITExpired":
                //     case "HITReviewable":
                //         $hit_id = $event['HITId'];
                //         break;
                // }
            }
            break;
    }

    $msgTxt = print_r($msg, true);
    $DB->queryinsert("raw_notifications", array("content" => $msgTxt));
}
