<?

require_once "config.php";

function db_connect($host, $user, $pass, $db) {
	if (DB_TYPE == "pgsql") {	
			  
		$string = "dbname=$db user=$user password=$pass";
		
		if ($host) {
			$string .= " host=$host";
		}

		$link = pg_connect($string);

		if (!$link) {
			die("Connection failed: " . pg_last_error($link));
		}

		return $link;

	} else if (DB_TYPE == "mysql") {
		$link = mysql_connect($host, $user, $pass);
		if ($link) {
			$result = mysql_select_db($db, $link);			
			if (!$result) {
				die("Can't select DB: " . mysql_error($link));
			}			
			return $link;
		} else {
			die("Connection failed: " . mysql_error($link));
		}
	}
}

function db_escape_string($s) {
	if (DB_TYPE == "pgsql") {	
		return pg_escape_string($s);
	} else {
		return mysql_real_escape_string($s);
	}
}

/* I hate MySQL :( */

function db_escape_string_2($s, $link) {
	if (DB_TYPE == "pgsql") {	
		return pg_escape_string($s);
	} else {
		return mysql_real_escape_string($s, $link);
	}
}

function db_query($link, $query) {
	if (DB_TYPE == "pgsql") {
		$result = pg_query($link, $query);
		if (!$result) {
			$query = htmlspecialchars($query); // just in case
		  	die("Query <i>$query</i> failed: " . pg_last_error($link));			
		}
		return $result;
	} else if (DB_TYPE == "mysql") {
		$result = mysql_query($query, $link);
		if (!$result) {
			$query = htmlspecialchars($query);
		  	die("Query <i>$query</i> failed: " . mysql_error($link));
		}
		return $result;
	}
}

function db_query_2($query) {
	if (DB_TYPE == "pgsql") {
		return pg_query($query);
	} else if (DB_TYPE == "mysql") {
		return mysql_query($link);
	}
}

function db_fetch_assoc($result) {
	if (DB_TYPE == "pgsql") {
		return pg_fetch_assoc($result);
	} else if (DB_TYPE == "mysql") {
		return mysql_fetch_assoc($result);
	}
}


function db_num_rows($result) {
	if (DB_TYPE == "pgsql") {
		return pg_num_rows($result);
	} else if (DB_TYPE == "mysql") {
		return mysql_num_rows($result);
	}
}

function db_fetch_result($result, $row, $param) {
	if (DB_TYPE == "pgsql") {
		return pg_fetch_result($result, $row, $param);
	} else if (DB_TYPE == "mysql") {
		// I hate incoherent naming of PHP functions
		return mysql_result($result, $row, $param);
	}
}

function db_close($link) {
	if (DB_TYPE == "pgsql") {

		return pg_close($link);

	} else if (DB_TYPE == "mysql") {
		return mysql_close($link);
	}
}
