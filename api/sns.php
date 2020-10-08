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
    }

    $msgTxt = print_r($msg, true);
    $DB->queryinsert("raw_notifications", array("content" => $msgTxt));
}
