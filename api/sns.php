<?php

require_once("../inc/config.php");
require_once("../inc/include.php");
require_once("../inc/Mysql_connector.class.php");
require_once("../inc/vendor/autoload.php");

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

$DB = new Mysql_connector($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
$DB->select_db($DB_NAME);

// $mysqli = $DB->connection;
 
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
