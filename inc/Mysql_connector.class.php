<?php

class Mysql_connector {
	var $host, $user, $password;
	var $connection;
	var $query_result;
	var $error, $last_id;
	var $debug;
	var $W_last_query;
	
	function __construct($host, $user, $password) {
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->debug = FALSE;
		$this->errorlog = FALSE;
		$this->connect();
	}

	function startTransaction() {
		// return mysqli_autocommit($this->connection, FALSE);
		return mysqli_begin_transaction($this->connection);
	}
	
	function rollbackTransaction() {
		return mysqli_rollback($this->connection);
	}
	
	function commitTransaction() {
		return mysqli_commit($this->connection);
	}
	
	function connect() {
		$this->connection = mysqli_connect($this->host, $this->user, $this->password);
		return $this->connection;
	}
	
	function close() {
		return mysqli_close($this->connection);
	}
	
	function select_db($database) {
		return mysqli_select_db($this->connection, $database);
	}

	function data_seek($buff = 0, $row_num = 0) {
		return mysqli_data_seek($this->query_result[$buff], $row_num);
	}
	
	function queryupdate($tabella, $arrayvalori, $where, $buff = 0) {
		$nuovoarray = array();
		foreach ($arrayvalori as $indice => $valore) {
			$nuovoarray[] = "`".$indice."` = '".addslashes($valore)."'";
		}
		if (is_array($where)) {
			$nuovoarraywhere = array();
			foreach ($where as $indice => $valore) {
				$nuovoarraywhere[] = "`".$indice."` = '".addslashes($valore)."'";
			}
			$nwhere = implode(" AND ", $nuovoarraywhere);
		}
		else {
			$nwhere = $where;
		}
		$query = "UPDATE ".$tabella." SET ".
			implode(", ", $nuovoarray)." WHERE ".$nwhere;
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function queryinsert($tabella, $arrayvalori, $buff = 0) {
		$array1 = $array2 = array();
		foreach ($arrayvalori as $indice => $valore) {
			$array1[] = "`".$indice."`";
			$array2[] = "'".addslashes($valore)."'";
		}
		$query = "INSERT INTO ".$tabella." (".
			implode(", ", $array1).") VALUES (".
			implode(", ", $array2).")";
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function queryreplace($tabella, $arrayvalori, $buff = 0) {
		$array1 = $array2 = array();
		foreach ($arrayvalori as $indice => $valore) {
			$array1[] = "`".$indice."`";
			$array2[] = "'".addslashes($valore)."'";
		}
		$query = "REPLACE INTO ".$tabella." (".
			implode(", ", $array1).") VALUES (".
			implode(", ", $array2).")";
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function queryinsertodku($tabella, $arrayvalori, $campidatogliere = array(), $buff = 0) {
		$array1 = $array2 = array();
		$nuovoarray = array();
		foreach ($arrayvalori as $indice => $valore) {
			$array1[] = "`".$indice."`";
			$array2[] = "'".addslashes($valore)."'";
			if (!in_array($indice, $campidatogliere)) {
				$nuovoarray[] = "`".$indice."` = '".addslashes($valore)."'";
			}
		}
		$query = "INSERT INTO ".$tabella." (".
			implode(", ", $array1).") VALUES (".
			implode(", ", $array2).") ON DUPLICATE KEY UPDATE
			".implode(", ", $nuovoarray);
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}

	function query($query, $buff = 0) {
		if ($this->debug) {
			$medium = "style='font-size: 12px; font-family: Trebuchet MS; padding: 5px; border: 1px solid green; background: rgb(255,255,0); color: black; margin: 5px;'";
			echo "<div " . ($medium) . "><strong>" . $buff . "</strong>\t-> <em>query</em>\t-> " . $query;
			echo "</div>";
		}
		$this->W_last_query = $query;
		$this->query_result[$buff] = mysqli_query($this->connection, $query);
		$this->last_id = mysqli_insert_id($this->connection);
		$temp = $this->query_result[$buff];
		if ($this->debug) {
			$good = "style='font-size: 12px; font-family: Trebuchet MS; padding: 5px; border: 1px solid green; background: rgb(200,255,200); color: green; margin: 5px;'";
			$bad = "style='font-size: 12px; font-family: Trebuchet MS; padding: 5px; border: 1px solid red; background: rgb(255,200,200); color: red; margin: 5px'";
			echo "<div " . ($temp ? $good : $bad) . "><strong>" . $buff . "</strong>\t-> <em>query</em>\t-> " . $query;
			if (!$temp) {
				echo "<br /><strong>" . $this->get_error() . "</strong>";
			}
			echo "</div>";
		}
		if ($this->errorlog) {
			error_log($buff." ".$query, 0);
		}
		return $temp;
	}
	
	function fetch($buff = 0) {
		if (!$this->query_result[$buff]) {
			echo "{$this->W_last_query}\n";
		}
		$ret = mysqli_fetch_array($this->query_result[$buff]);
		return $ret;
	}
	
	function fetch_a($buff = 0) {
		if (!$this->query_result[$buff]) {
			echo "{$this->W_last_query}\n";
		}
		$ret = mysqli_fetch_assoc($this->query_result[$buff]);
		return $ret;
	}
	
	function querynum($query, $buff = 0) {
		$this->query($query, $buff);
		return $this->num_rows($buff);
	}
	
	function queryfetch($query, $buff = 0) {
		$this->query($query, $buff);
		return $this->fetch($buff);
	}
	
	function get_error() {
		$this->error = "Error " . mysqli_errno($this->connection) . " : " . 
			mysqli_error($this->connection);
		return $this->error;
	}
	
	function num_rows($buff = 0) {
		return mysqli_num_rows($this->query_result[$buff]);
	}
}

