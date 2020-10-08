<?php

require_once("config.php");
require_once("Mysql_connector.class.php");
require_once("vendor/autoload.php");

$DB = new Mysql_connector($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
$DB->select_db($DB_NAME);
$mysqli = $DB->connection;

// Functions

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
