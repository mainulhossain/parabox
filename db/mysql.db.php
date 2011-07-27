<?php
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../config/configuration.php');

/** 
 * A simple class to encapsulate mysql operations
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class mysql_db {
	
	public $host;
	public $user;
	public $password;
	public $database;

	public $connection;
	public $error;
	var $num_row;
	
	function init() {
		
		$num_row = 0;
		$error = "";
	}
	
	function connect($host='', $user='', $password='', $database='', $persist=true)
	{
		if (!empty($host)) $this->host=$host;
		if (!empty($user)) $this->user=$user;
		if (!empty($password)) $this->password=$password;

		// Establish the connection.
      if ($persist) 
         $this->connection = mysql_pconnect($this->host, $this->user, $this->password);
      else 
         $this->connection = mysql_connect($this->host, $this->user, $this->password);
 
      // Check for an error establishing a connection
      if (!$this->connection) {
         $this->error = mysql_error();
         return false;
      } 
  
      if (!$this->use_db($database))
      	return false;
      
      Config::getInstance()->trace("msg:" . "connect to database $this->host successful");
      
      return $this->connection;  // success
	}
	
	function use_db($database='')
	{
		if (!empty($database)) $this->database = $database; 
      
      	if (!mysql_select_db($this->database)) {
         $this->error = mysql_error();
         return false;
      }
 
      return true;
	}
	
	function select($sql) {

		Config::getInstance()->trace('sql:' . $sql);
		
		$this->init();
      	$result = mysql_query($sql);
      	if (!$result) {
        	$this->error = mysql_error();
         	return false;
      	}
      	
      	$this->num_row = mysql_num_rows($result);
      	
      	return $result;
   }
   
	function selectSingle($sql) {
   	
		Config::getInstance()->trace('sql:' . $sql);
   		$r = mysql_query($sql);
		if (!$r) {
		   $this->error = mysql_error();
		   return false;
		}
		
      if (mysql_num_rows($r) > 1) {
         $this->error = "Query returned multiple results";
         return false;     
      }
      if (mysql_num_rows($r) < 1) {
         $this->error = "Query returned no result";        
         return false;
      }
      
      return mysql_fetch_assoc($r);
      /*$ret = mysql_result($r, 0);
      mysql_free_result($r);
      return stripslashes($ret);*/
   }
   
	function insert($sql) {
       
	  Config::getInstance()->trace('sql:' . $sql);
      $r = mysql_query($sql);
      if (!$r) {
         $this->error = mysql_error();
         return false;
      }
      
      $id = mysql_insert_id();
      if ($id == 0) return true;
      else return $id; 
   }
   
	function update($sql) {
 
	  Config::getInstance()->trace('sql:' . $sql);
      $r = mysql_query($sql);
      if (!$r) {
         $this->error = mysql_error();
         return false;
      }
      
      $rows = mysql_affected_rows();
      if ($rows == 0) return true;  // no rows were updated
      else return $rows;
   }
   
	function write_error() 
   {
   		Config::getInstance()->trace('error:' . $this->error);
   }
   
}
?>