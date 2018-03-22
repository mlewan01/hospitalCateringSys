<?php
class myDB extends mysqli {

	/**
	 * Connects to the database server and selects a database
	 * PHP4 compatibility layer for calling the PHP5 constructor.
	 * @uses myDB::__construct()
	 */
	function myDB() {
		return $this->__construct();
	}

	public function __construct(){
		// call to parent constructor
		parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		// use $this to access mysqli members
		// detectin errors in connecton to db
		if ($this->connect_errno){
			exit($this->connect_errno);
		}
	}

	public function myQuery($sql){
		// using the query method of parent object
		$result = $this->query($sql);
		if($result === false){
			if(DEV){// Get error string from object property
				echo "blad query<br/>";
				echo $this->error;
			}
			return false;
		} else {
			// if successful returns $result
			//echo 'Query successful ! ! !'.'</br>';  // <<<<<<<<<<<<<<<<<<<<<
			return $result;
		}
	}

	/**
	 * Clean the array using mysql_real_escape_string
	 *
	 * Cleans an array by array mapping mysql_real_escape_string
	 * onto every item in the array.
	 *
	 * @param array $array The array to be cleaned
	 * @return array $array The cleaned array
	 */
	function clean($array) { //TODO use before saving to database
		global $link;
		return array_map('mysqli_real_escape_string', $array);
	}

	/**
	 * Create a secure hash
	 *
	 * Creates a secure copy of the user password for storage
	 * in the database.
	 *
	 * @param string $password The user's created password
	 * @param string $nonce A user-specific NONCE
	 * @return string $secureHash The hashed password
	 */
	function hash_password($password, $nonce) {
	  $secureHash = hash_hmac('sha512', $password . $nonce, SITE_KEY);

	  return $secureHash;
	}

	/**
	 * Insert data into the database
	 *
	 * Does the actual insertion of data into the database.
	 *
	 * @param resource $link The MySQL Resource link
	 * @param string $table The name of the table to insert data into
	 * @param array $fields An array of the fields to insert data into
	 * @param array $values An array of the values to be inserted
	 */
	function insert($table, $fields, $values) {
		global $link;
		$fields = implode(", ", $fields);
		$values = implode("', '", $values);
		$sql="INSERT INTO $table (id, $fields) VALUES ('', '$values')";

		if (!mysqli_query($link, $sql)) {
			die('Error: ' . mysqli_error($link));
		} else {
			return TRUE;
		}
	}

	/**
	 * Select data from the database
	 *
	 * Grabs the requested data from the database.
	 *
	 * @param string $table The name of the table to select data from
	 * @param string $columns The columns to return
	 * @param array $where The field(s) to search a specific value for
	 * @param array $equals The value being searched for
	 */
	function select($sql) {
		// global $link;
		// $results = mysqli_query($link, $sql);

		// return $results;

		return $this->myQuery($sql);
	}

	/**
	 * Helps in formating INSERT queries
	 *
	 * @param String $dbwhere The name of the database table where to insert data
	 * @param Array $data The associative array with table columns as indexes and data to be inserted to the database
	 *
	 * @return String $sql the formated query redy to be executed
	 */
	function makeInsertSql($dbwhere, $data){
		$sql = sprintf(
				'insert into '.$dbwhere.' (%s) values ("%s")',
				implode(',',array_keys($data)),
				implode('","',array_values($data)) );

		return $sql;
	}

	function logDB($msg, $userId, $type, $sql='', $date=0){
		$logTable = 'logs';
		$logData = myLog($msg, $userId, $type, $sql, $date);

		$sql2 = $this->makeInsertSql($logTable, $logData);

		$this->myQuery($sql2);
		return 'logged <br/>';
	}
}

?>
