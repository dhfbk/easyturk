<?php

define("CURRENT_VERSION", "0.1.1");

function addVersion($version = "0.1") {
    return updateVersion($version, false);
}

function updateVersion($version = "0.1", $update = true) {
    global $DB;

    $data = array("id" => "version", "value" => $version);
    if ($update) {
        $DB->queryupdate("options", $data, array("id" => "version"));
    }
    else {
        $DB->queryinsert("options", $data);
    }
    return loadOptions();
}

if (!isset($Options['version'])) {
    $_SESSION['Options'] = addVersion();
    $Options = $_SESSION['Options'];
}

$upToDate = true;
switch ($Options['version']) {
    case "0.1":
        $upToDate = false;
        $query = "ALTER TABLE `institutions`
            ADD `confirmed_users` BOOLEAN NOT NULL DEFAULT FALSE AFTER `hidden`,
            ADD `allow_social_login` BOOLEAN NOT NULL DEFAULT TRUE AFTER `confirmed_users`;";
        $DB->query($query);
}

if (!$upToDate) {
    $_SESSION['Options'] = updateVersion(CURRENT_VERSION);
    $Options = $_SESSION['Options'];
}
