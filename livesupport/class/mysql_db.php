<?php
//****************************************************************************************/
// Library : Mysql_DB  :    version 2.4 (01/03/2005): Eric Gerdes ( CraftySyntax.com )    
//======================================================================================
/**
  * Mysql DB class by Eric Gerdes:
  *
  *   This class is used to create a workable recordset connection with a mysql database.
  *    It is designed to be a simple alternative to PEAR DB with no hassle of dependence
  *    or backwards compatability... HOWEVER I do recommend using PEAR DB located at
  *   http://pear.php.net/package/DB/ rather then this class.. 
  *
  * BASIC PEAR LIKE EXAMPLE :
  *<code>
  *   $mydatabase = new MySQL_DB;
  *    $dsn = "mysql://username:password@hostspec/database";
  *   $mydatabase->connect($dsn);
  *
  *   $query = "SELECT * FROM mytable";
  *    $result = $mydatabase->query($query);
  *   while($row = $result->fetchRow(DB_FETCHMODE_ASSOC) ){
  *      // do something with associative array $row[]
  *   }
  *   $query = "INSERT INTO mytable (this,that) VALUES ('somthing','somethingelse');
  *   $mydatabase->query($query);
  *</code>
  *
  * ALTERNATIVE EXAMPLE :
  *<code>
  *   $mydatabase = new MySQL_DB;
  *   $mydatabase->connectdb($server,$user,$pass);
  *   $mydatabase->selectdb($dbase);
  *
  *   $query = "SELECT * FROM mytable";
  *   $rs = $mydatabase->getrecordset($query);
  *   while($rs->next()){
  *      $row = $rs->getCurrentValuesAsHash();
  *      // do something with associative array $row[]
  *   } 
  *   $query = "INSERT INTO mytable (this,that) VALUES ('somthing','somethingelse');
  *   $primary_key_id = $mydatabase->insert($query);
  *</code>
  */
// CLASS MYSQL_DB FUNCTION LIST:
//      function MYSQL_DB()                     - The constructor for this class.   
//      function connect($dsn)                  - opens the database connection to a dsn
//      function connectdb($server,$user,$pass) - opens the database connection.
//      function getconnid()
//      function selectdb($dbase)               - selects out the database 
//      function getrecordset($sql="")         - opens a record set and returns it.
//      function insert($sql="")               - inserts a row into the database.
//      function sql_query($sql="")            - run a general query.
//      function fetchRow($type)                - fetch next row and move to next record.
//      function showdbs()                     - lists the databases for the MYSQL 
//      function showtables($dbname)           - lists the tables for the database in an array.
//      function error($text)                   - Shows the error message if any occur from sql query.
//      function close_connect()               - closes the connection. 

// CLASS MySQL_RS FUNCTION LIST:
//      function MySQL_RS($conn='')        - constructor for the class..
//      function setrecordset()            - set the recordset.
//      function next()                    - moves the recordset up one element.
//      function field()                   - return one element.
//      function getCurrentValuesAsHash()  - Returns an array of the current recordset row.
//      function numrows()                - number of rows.

// ORIGINAL CODE: 
// ---------------------------------------------------------
// Eric Gerdes    
// GNU General Public License
//
//=====================***  MySQL_DB   ***======================================

define('DB_FETCHMODE_ORDERED', 1);
define('DB_FETCHMODE_ASSOC', 2);
define('DB_DEBUG', false);

Class MySQL_DB
{
	
	// The host,password,username needed to connect to the database
	// all values are set by the constructor..
	var $HOST = "";
  var $PASS = "";
	var $USER = "";
	
  // the connection id for the connection.
	var $CONN   = "";      
	
	// current selected database.
	var $DATABASE = ""; 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * constructor for Class.
  *
  * @param string $host  
  * @param string $user 
  * @param string $password 
  */ 
  function MYSQL_DB($host='localhost',$user='',$pass='') {
          $this->USER = $user;
          $this->PASS = $pass;
          $this->HOST = $host; 
  }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * open the Database connection and set connection id.
  *
  * @param string $host  
  * @param string $user 
  * @param string $password 
  *
  * @return true if valid connection is made.. errors and exists if fails.
  */   
  function connectdb($host='',$user='',$pass=''){
	   if($host!=""){ $this->HOST = $host; }
	   if($user!=""){ $this->USER = $user; }
	   if($pass!=""){ $this->PASS = $pass; }
	   
	   $conn = mysql_connect($this->HOST,$this->USER,$this->PASS); 		
	   if(!$conn) {
		    $this->error("Mysql Connection failed");
		    return false;
	    }
	    $this->CONN = $conn;
	    return true;
	}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * connects to database given a DSN string. Skip connection if it already exists.
  *
  * @param string $dsn  
  */ 
  function connect($dsn){
	   
	   $dsn_parsed = $this->parseDSN($dsn);
	   
	   // if we are not already connected to the database...
	   if($this->CONN == "")
  	      $this->connectdb($dsn_parsed['hostspec'],$dsn_parsed['username'],$dsn_parsed['password']);
	   // if we should select a database...
	   if($dsn_parsed['database'] != ""){
	      $this->selectdb($dsn_parsed['database']);
	   }
	}
	
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * get the current connection id.. errors and exits if fails..
  *
  * @return mixed  connection id if successful or false if fail
  */        
  function getconnid(){
      if($this->CONN) { 
           return $this->CONN; 
      } else {
           return false;
      }
  }
        
  
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * select the database.
  *
  * @param string  database name.
  */     
  function selectdb($dbase){
     $conn = $this->CONN; 	
     if(!mysql_select_db($dbase,$conn)) {
		    $this->error("Dbase Select failed");
	    }        	
  }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Figures out what type of query to run given an sql string and then runs it.
  *
  * @param string  sql to run.
  * @param bool  debug - default false.. if set to true writes query to "sqllog.txt"
  */         
  function query($sql="",$debug=DB_DEBUG){
      if(empty($sql)){ 
            if($debug){
        $fa = fopen("sqllog.txt", 'a+');
        fwrite( $fa, date("YmdHis") . " " . $_SERVER['PHP_SELF'] . " $sql\n", 1000);
        fclose($fa);
      }  
            exit;
      }
          
      // Dubugging queries:
      if($debug){
        $fa = fopen("sqllog.txt", 'a+');
        fwrite( $fa, date("YmdHis") . " " . $_SERVER['PHP_SELF'] . " $sql\n", 1000);
        fclose($fa);
      }   
         
      if (eregi("^SELECT",$sql)){
          return $this->getrecordset($sql);
      } elseif (eregi("^insert",$sql)){
             return $this->insert($sql);
      } else {      
             return $this->sql_query($sql);
      }	        
  }
        
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * select out the recordset for the selected Query.
  *
  * @param string  sql to run.
  */         
  function getrecordset($sql=""){
	   if(empty($sql)) { $this->error("No SQL query sent to getrecordset"); }	
	   if(empty($this->CONN)) { $this->error("No Mysql Connection"); }
	   $conn = $this->CONN;
	   $rs = new MySQL_RS;
           $rs->setrecordset($conn,$sql);	  
	  return $rs;			
	}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * insert a row into the database.
  *
  * @param string  sql to run.
  * 
  * @return mysql_insert_id
  */ 
	function insert($sql=""){
		if(empty($sql)) { return false; }
		if(!eregi("^insert",$sql)){
			//echo "<H2>Wrong function expected insert query. </H2>\n";
			return false;
		}
		if(empty($this->CONN)){
			echo "<H2>No connection!</H2>\n";
			return false;
		}
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results) {
			//echo "<H2>No results!</H2>\n";
			//echo mysql_errno($conn).":  ".mysql_error($conn)."<P>";
			return false;
		}
		$results = mysql_insert_id($conn);
		return $results;
	}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * run a general sql query return the results..
  *
  * @param string  sql to run.
  * 
  * @return $results.
  */	
	function sql_query($sql=""){
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results){
			//echo "<H2>Query went bad!</H2>\n";
			//echo mysql_errno($this->CONN).":  ".mysql_error($this->CONN)."<P>";
			
			// Dubugging queries:
     // $fa = @fopen("sqllog.txt", 'a+');
     // if($fa){
     //   fwrite( $fa, date("YmdHis") . " " . $_SERVER['PHP_SELF'] . mysql_errno($this->CONN).":  $sql ".mysql_error($this->CONN)."\n", 1000);
     //   fclose($fa);
     //}
			
			return false;
		}
		return $results;
	}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * show databases from a connection.
  *
  * @return $results.
  */
   function showdbs(){     	
           $count=0;
           $conn = $this->CONN;          
           $db_list = mysql_list_dbs($conn);
           while ($row = mysql_fetch_object($db_list)) {
              $data[$count] = $row->Database;
	      $count++;
           }
          return $data;	
        }	
        	
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * show tables from a database.
  *
  * @return $results.
  */      	
   function showtables($dbname){        	
          $conn = $this->CONN;
          $results = mysql_list_tables($dbname,$conn);
          $count = 0;
          if (!$results) {
            // print "DB Error, could not list tables\n";
            // print 'MySQL Error: ' . mysql_error($this->CONN);
             exit;
          }
    
          while ($row = mysql_fetch_row($results)) {
              $data[$count] = $row[0];
	            $count++;
          }

           mysql_free_result($results);
	  
          return $data;	
        }
      
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * show the error of the Database query.
  *
  * @param $text to show..
  */     
  function error($text) {
		$no = mysql_errno($this->CONN);
		$msg = mysql_error($this->CONN);
		echo "[$text] ( $no : $msg )<BR>\n";
		exit;
	}
		

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * close the DB
  *
  */  		
   function close_connect(){
	   $conn = $this->CONN;		
	   if(is_resource($conn))
	      mysql_close($conn);	
	}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Parse a data source name.
  *
  * Additional keys can be added by appending a URI query string to the
  * end of the DSN.
  *
  * The format of the supplied DSN is in its fullest form:
  * <code>
     *  phptype(dbsyntax)://username:password@protocol+hostspec/database?option=8&another=true
     * </code>
     *
     * Most variations are allowed:
     * <code>
     *  phptype://username:password@protocol+hostspec:110//usr/db_file.db?mode=0644
     *  phptype://username:password@hostspec/database_name
     *  phptype://username:password@hostspec
     *  phptype://username@hostspec
     *  phptype://hostspec/database
     *  phptype://hostspec
     *  phptype(dbsyntax)
     *  phptype
     * </code>
     *
     * @param string $dsn Data Source Name to be parsed
     *
     * @return array an associative array with the following keys:
     *  + phptype:  Database backend used in PHP (mysql, odbc etc.)
     *  + dbsyntax: Database used with regards to SQL syntax etc.
     *  + protocol: Communication protocol to use (tcp, unix etc.)
     *  + hostspec: Host specification (hostname[:port])
     *  + database: Database to use on the DBMS server
     *  + username: User name for login
     *  + password: Password for login
     *
     * @author Tomas V.V.Cox <cox@idecnet.com>
     */
    function parseDSN($dsn){
        $parsed = array(
            'phptype'  => false,
            'dbsyntax' => false,
            'username' => false,
            'password' => false,
            'protocol' => false,
            'hostspec' => false,
            'port'     => false,
            'socket'   => false,
            'database' => false,
        );

        if (is_array($dsn)) {
            $dsn = array_merge($parsed, $dsn);
            if (!$dsn['dbsyntax']) {
                $dsn['dbsyntax'] = $dsn['phptype'];
            }
            return $dsn;
        }

        // Find phptype and dbsyntax
        if (($pos = strpos($dsn, '://')) !== false) {
            $str = substr($dsn, 0, $pos);
            $dsn = substr($dsn, $pos + 3);
        } else {
            $str = $dsn;
            $dsn = null;
        }

        // Get phptype and dbsyntax
        // $str => phptype(dbsyntax)
        if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
            $parsed['phptype']  = $arr[1];
            $parsed['dbsyntax'] = !$arr[2] ? $arr[1] : $arr[2];
        } else {
            $parsed['phptype']  = $str;
            $parsed['dbsyntax'] = $str;
        }

        if (!count($dsn)) {
            return $parsed;
        }

        // Get (if found): username and password
        // $dsn => username:password@protocol+hostspec/database
        if (($at = strrpos($dsn,'@')) !== false) {
            $str = substr($dsn, 0, $at);
            $dsn = substr($dsn, $at + 1);
            if (($pos = strpos($str, ':')) !== false) {
                $parsed['username'] = rawurldecode(substr($str, 0, $pos));
                $parsed['password'] = rawurldecode(substr($str, $pos + 1));
            } else {
                $parsed['username'] = rawurldecode($str);
            }
        }

        // Find protocol and hostspec

        // $dsn => proto(proto_opts)/database
        if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
            $proto       = $match[1];
            $proto_opts  = $match[2] ? $match[2] : false;
            $dsn         = $match[3];

        // $dsn => protocol+hostspec/database (old format)
        } else {
            if (strpos($dsn, '+') !== false) {
                list($proto, $dsn) = explode('+', $dsn, 2);
            }
            if (strpos($dsn, '/') !== false) {
                list($proto_opts, $dsn) = explode('/', $dsn, 2);
            } else {
                $proto_opts = $dsn;
                $dsn = null;
            }
        }

        // process the different protocol options
        $parsed['protocol'] = (!empty($proto)) ? $proto : 'tcp';
        $proto_opts = rawurldecode($proto_opts);
        if ($parsed['protocol'] == 'tcp') {
            if (strpos($proto_opts, ':') !== false) {
                list($parsed['hostspec'], $parsed['port']) = explode(':', $proto_opts);
            } else {
                $parsed['hostspec'] = $proto_opts;
            }
        } elseif ($parsed['protocol'] == 'unix') {
            $parsed['socket'] = $proto_opts;
        }

        // Get dabase if any
        // $dsn => database
        if ($dsn) {
            // /database
            if (($pos = strpos($dsn, '?')) === false) {
                $parsed['database'] = $dsn;
            // /database?param1=value1&param2=value2
            } else {
                $parsed['database'] = substr($dsn, 0, $pos);
                $dsn = substr($dsn, $pos + 1);
                if (strpos($dsn, '&') !== false) {
                    $opts = explode('&', $dsn);
                } else { // database?param1=value1
                    $opts = array($dsn);
                }
                foreach ($opts as $opt) {
                    list($key, $value) = explode('=', $opt);
                    if (!isset($parsed[$key])) {
                        // don't allow params overwrite
                        $parsed[$key] = rawurldecode($value);
                    }
                }
            }
        }

        return $parsed;
    }
    	
}	//	End Class

//=====================***  MySQL_RS   ***======================================

Class MySQL_RS
{
	// database connection ID
	var $CONN   = "";
	// current recordset array.. 
        var $RECORDSET = array();
        // query id.
        var $QID = "";
        
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * the constructor for this class..
  *
  * @param object connection 
  */         
  function MySQL_RS($conn=''){
           $this->CONN = $conn;           
  }
  
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * open the recordset connection.
  *
  * @param object connection 
  * @param string sql 
  */               
	function setrecordset($conn,$sql)
	{
		$this->CONN = $conn;
		$this->QID = mysql_query($sql,$conn);
		if( (!$this->QID) or (empty($this->QID)) ) {
			$this->error($sql);
			return false;
		}	       
		return true;
	}
                
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * returns the next recordset array
  *
  * @param int Fetch mode 
  *
  * @param array recordset.
  */  
  function next($fetchmode=DB_FETCHMODE_ASSOC){
       if($fetchmode==DB_FETCHMODE_ORDERED)
         $this->RECORDSET = mysql_fetch_array($this->QID,MYSQL_NUM);        
       else
         $this->RECORDSET = mysql_fetch_array($this->QID,MYSQL_ASSOC); 

       return $this->RECORDSET;
  }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * return the value of the field 
  *
  * @param string fieldname
  */          
  function field($fieldname){
        return $this->RECORDSET[$fieldname];
  }
                
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * return an array of the current fetched items of the recordset.
  *
  * @return array
  */  
  function getCurrentValuesAsHash(){
       return $this->RECORDSET;
  }

 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * fetchRow  - select out the next associative array result row..      
  *
  * @param notused notused - here for backward compatability 
  */          
  function fetchRow($fetchmode=DB_FETCHMODE_ASSOC){  	       
  	       $this->next($fetchmode);
           return $this->RECORDSET; 
  }

       
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Returns the number of rows in query     
  *
  * @return int number o rows in a record set.
  */         
  function numrows() {     
            if ($this->QID) { 
               return mysql_numrows($this->QID); 
            } else { 
              return 0; 
            } 
  }
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * show the error of the sql query.
  *
  * @param string text to show..
  */ 
	function error($text)
	{
		$no = mysql_errno($this->CONN);
		$msg = mysql_error($this->CONN);
		echo "[$text] ( $no : $msg )<BR>\n";
		exit;
	}
	
}//	End Class
?>