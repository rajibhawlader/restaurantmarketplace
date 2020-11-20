<?php
class Db {
    /* Connection Parameters */
    /**
	 * Database host name 
	 * @var string
	 */
	var $hostname = '';
	/**
	 * Database user name 
	 * @var string
	 */
	var $username = '';
	/**
	 * Database Password
     * @var string
	 */
	var $password = '';
	/**
	 * Db database name
	 * @var string
	 */
	var $databasename = '';
	/**
	 * Db connection handle
	 * @var boolean
	 */
	var $linkid = false;
	/**
	 * @var boolean
	 */
	var $result = false;
	/**
	* constructor
	*/
	function Db($hostname, $username, $password, $databasename, $linkid = false) {
		$this->hostname     = $hostname;
		$this->username     = $username;
		$this->password     = $password;
		$this->databasename = $databasename;
		$this->linkid       = $linkid;
		// Auto connect to the server
		$this->connect();
	}   // End constructor

	/**
	 * Establish connection with the server and database
	 * @return resource on success else boolean
	 */
	function connect() {
		$this->linkid = @mysql_connect($this->hostname, $this->username, $this->password);
		if(!$this->linkid){
			$this->error("Could not connect with server!");
			return false;
		} else {
			$check = @mysql_select_db($this->databasename, $this->linkid);
			if ($check) {
			    return $this->linkid;
			} else {
				$this->error("Could not connect with database!");
				return false;
			}
		}
	} // End function connect
    
	/**
	 * used to exeute sql statement
	 * @param string $query
	 * @return resource
	 */
	function execQuery($query) {
		$this->result = mysql_query($query,$this->linkid);
		if (!$this->result) {
			$this->error("Query execution failed!".$query);
		}
		if ($this->result)
			return $this->result;
		else
			return NULL;
	} // End function execQuery()
	
	/**
	 * Check the data exists or not
	 * @param string $query
	 * @return boolean
	 */
	function checkExists($query) {
		$this->result = $this->execQuery($query);
		$rows = mysql_num_rows($this->result);
		if($rows != 0)
			return true;
		else
			return false;
	} // End function checkExists()
	
	/**
	 * Access the resulting array data
	 * @return array
	 */
	function resultArray() {
		$arinfo = array();
		$i = 0;
		while($data = mysql_fetch_assoc($this->result)) {
			while(list($key,$value) = each($data))
				$arinfo[$i][$key] = $value;
			$i++;
		}
		//$this->freeResult($this->result);
		return $arinfo;
	} // End function result2array()
	
	/**
	 * Access the resulting row data
	 * @return array
	 */
	function row() {
		$row = array();
		$row = mysql_fetch_assoc($this->result);		
		return $row;
	} // End function result row()    
	
	
	/**
	 * Access the resulting data
	 * @return result data
	 */
	function result() {		
		$value = false;
		if($data = mysql_fetch_assoc($this->result)){		
			list($key, $value)= each($data);
		}
		return $value;
	} // End function result row()    
	
	
	/**
	 * get last record
	 * @return int
	 */
	function lastInsert($result){
		$insertid = NULL;
		$insertid = @mysql_insert_id($this->linkid);
		return $insertid;
	} 
	
	/**
	 * Release all memory associated with resultset
	 * @return int
	 */
	function freeResult() {
		return @mysql_free_result($this->result);
	} // End function freeResult()
	
	/**
	 * Closes non persistent link
	 * @return boolean
	 */
	function close() {
		return @mysql_close($this->linkid);
	} //End function close()
	
	/**
	 * Display error message
	 * @param string $message
	 */
	function error($message) {
		$this->error = $message.' '.mysql_error().'.';
		echo $this->error;
	} // End function error
}
?>
