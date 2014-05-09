<?php

/**	THSIA CLASS MANAGES MYSQL DATABASE CONNECTIONS
*	THIS CLASS IS STATIC TO IMPROVE PERFOMANCE AND MEMORY
*	REQUIRES settings.php
*/

require_once "settings.php";

class Database {
	
	private static $link;
	
	// CREATES A CONNECTION TO MySQL DATABASE, MUST BE THE TO BE CALLED ABOVE ALL FUNCTIONS
	public static function connect() {
		$host = MYSQL_HOST;
		$user = MYSQL_USER;
		$pass = MYSQL_PASS;
		$database = MYSQL_DATABASE;
		
		if (empty($host))
			$host = "localhost";
		if (empty($user))
			$user = "root";
		if (empty($pass))
			$pass = "";
		if (empty($database))
			$database = "firstserve";
        
		Database::$link = new mysqli($host, $user, $pass, $database);
		if (Database::$link->connect_error) {
			if (!headers_sent()) {
				header("Location: error.php?fatalError=".Database::$link->connect_error);
			}else{
				echo "Database Error";
			}
			exit;
		}
	}
	
	// EXECUTE QUERIES THAT DOES NOT RETURN RESULTS i.e. INSERT, UPDATE, DELETE, CREATE
	// RETURNS A BOOLEAN INDICATING SUCCESS OR FAILURE
	public static function executeNonQuery($query) {
		return Database::$link->real_query($query);
	}
	
	//  EXECUTE QUERIES THAT RETURN RESULTS i.e. SELECT
	// RETURNS mysqli_result OBJECT CONTAINING THE RESULTS OF THE QUERY
	public static function executeRealQuery($query) {
		return Database::$link->query($query);
	}
	
	//returns whether the database is initialized or not, returns true if connected or false if not connected
	public static function status() {
		return !empty(Database::$link);
	}
	
	//Returns the last error from the previous query executed
	public static function lastError() {
		return Database::$link->error;
	}
	
	//Returns cleaned data for insertion in query, warning do not place a query through this function
	public static function clean($data) {
		return Database::$link->real_escape_string($data);
	}
	
	// Explicitly closes the database connection
	public static function close() {
		Database::$link->close();
	}

}

?>