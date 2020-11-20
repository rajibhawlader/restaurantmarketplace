<?php
class SessionManager {

   var $life_time;

   function SessionManager() {

      // Read the maxlifetime setting from PHP
      $this->life_time = get_cfg_var("session.gc_maxlifetime");

      // Register this object as the session handler
      session_set_save_handler( 
        array( &$this, "open" ), 
        array( &$this, "close" ),
        array( &$this, "read" ),
        array( &$this, "write"),
        array( &$this, "destroy"),
        array( &$this, "gc" )
      );

   }

   function open( $save_path, $session_name ) {
      global $sess_save_path;
      
      $sess_save_path = $save_path;
      // Don't need to do anything. Just return TRUE.
      return true;
   }

   function close() {
   	  global $mydatabase;
   	  
   	  if(isset($mydatabase)){ $mydatabase->close_connect(); }
   	  
      return true;
   }

   function read( $id ) {
      global $mydatabase;
      
      // Set empty result
      $data = '';

      // Fetch session data from the selected database
      $time = time();

      //$newid = mysql_real_escape_string($id,$mydatabase->CONN);
      $newid = filter_sql($id);
      $sql = "SELECT `session_data` FROM `livehelp_sessions` WHERE `session_id` = '$newid' AND `expires` > $time";
      $data = "";
      if(isset($mydatabase)){ 
        $rs = $mydatabase->query($sql);
        if($rs->numrows($rs) > 0) {
          $row = $rs->fetchRow(DB_FETCHMODE_ASSOC);
          $data = $row['session_data'];
        }
      }
      return $data;
   }

   function write( $id, $data ) {
      global $mydatabase;
      
      // Build query                
      $time = time() + $this->life_time;

 //     $newid = mysql_real_escape_string($id,$mydatabase->CONN);
        $newid = filter_sql($id);
  //    $newdata = mysql_real_escape_string($data,$mydatabase->CONN);
        $newdata = filter_sql($data);
        
      $sql = "REPLACE `livehelp_sessions` (`session_id`,`session_data`,`expires`) VALUES('$newid','$newdata',$time)";
      if(isset($mydatabase)){ $mydatabase->query($sql); }
      return true;
   }

   function destroy( $id ) {
      global $mydatabase;
      
      // Build query
//      $newid = mysql_real_escape_string($id,$mydatabase->CONN);
      $newid = filter_sql($id);
      
      $sql = "DELETE FROM `livehelp_sessions` WHERE `session_id` ='$newid'";
      if(isset($mydatabase)){ $mydatabase->query($sql); }

      return true;
   }

   function gc() {
      global $mydatabase;
      
      // Garbage Collection
 
      // Build DELETE query.  Delete all records who have passed the expiration time
      $sql = 'DELETE FROM `livehelp_sessions` WHERE `expires` < UNIX_TIMESTAMP()';
      if(isset($mydatabase)){ $mydatabase->query($sql); }

      // Always return TRUE
      return true;
   }

}
?>