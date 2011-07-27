<?php
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../config/configuration.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../db/mysql.db.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../models/bound.php');

/** 
 * Contains various utility functions
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class Utility {
	
	private $mysqldb;
	private static $instance = null;

    private function __construct()
    {
    	$this->mysqldb = new mysql_db;
		if (!$this->mysqldb->connect(Config::getInstance()->Server, Config::getInstance()->UserName, Config::getInstance()->Password, Config::getInstance()->Database, true))
		 	$mysqldb->write_error();
    }

    public static function getInstance()
    {
      if(self::$instance == null)
      {
        self::$instance = new self;
      }

      return self::$instance;
    }


    public function getDbConnection() {
    	
    	if (!isset($this->mysqldb))
    		return false;
    	return $this->mysqldb;
    }
/**
     * Returns true if a string value is nonempty, otherwise returns false.
     * @param strValue string to check the validity
     * @return true/false
     */
    public function isStringValid($strValue) {
    	if (!isset($strValue)){
    		return false;
    	}
        
    	return !empty($strValue);
    }
    
    public function isEmailValid($email) {
    	
    	return filter_val($email, FILTER_VALIDATE_EMAIL);

    }
    
    public function getRealIpAddr() {
    	
	    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	    {
	      $ip=$_SERVER['HTTP_CLIENT_IP'];
	    }
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	    {
	      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    else
	    {
	      $ip=$_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}
	
	public function convert_by_dom_func($table, $item, $rows) 
	{
		// Start XML file, create parent node
		$dom = new DOMDocument("1.0", "UTF-8");
		$dom->formatOutput = true;
		$node = $dom->createElement($table);
		$parnode = $dom->appendChild($node); 
		
	
	// Iterate through the rows, adding XML nodes for each
		while ($row = mysql_fetch_assoc($rows)){
		  $node = $dom->createElement($item);
		  $newnode = $parnode->appendChild($node);
		
		  foreach($row as $key => $value) {
		  	$newnode->setAttribute($key, $value);
		  }
		}
		
		// $dom->documentElement as parameter is necessary. Otherwise,
		// DOM parser can't parser the document
		return $dom->saveXML($dom->documentElement); 
	}
	
	public function convert_by_dom_xml($table, $item, $rows) 
	{
		// Start XML file, create parent node
		$doc = domxml_new_doc("1.0");
		$node = $doc->create_element($table);
		$parnode = $doc->append_child($node);
		
		// Iterate through the rows, adding XML nodes for each
		while ($row = mysql_fetch_assoc($result)){
		  $node = $doc->create_element($item);
		  $newnode = $parnode->append_child($node);
		
		  foreach($row as $key => $value) {
		  	$newnode->set_attribute($key, $value);
		  }
		}
		
		$xmlfile = $doc->dump_mem();
		echo $xmlfile;
	}

	public function convert_args_by_dom_func($table, $item, $row) 
	{
		// Start XML file, create parent node
		$dom = new DOMDocument("1.0", "UTF-8");
		$dom->formatOutput = true;
		$node = $dom->createElement($table);
		$parnode = $dom->appendChild($node); 
		
		$this->convert_args_to_dom_node($dom, $parnode, $item, $row);

		// $dom->documentElement as parameter is necessary. Otherwise,
		// DOM parser can't parser the document
		return $dom->saveXML($dom->documentElement); 
	}
	
	
	public function convert_args_to_dom_node($dom, $parnode, $item, $value) 
	{
	  $node = $dom->createElement($item);
	  $newnode = $parnode->appendChild($node);
	  foreach($value as $key => $newValue) 
	  {
	  	if (is_array($newValue))
	  	{
	  		$this->convert_args_to_dom_node($dom, $newnode, $key, $newValue);
	  	}
	  	else
	  	{
	  		$newnode->setAttribute($key, $newValue);
	  	}
	  }
	  return $newnode;
	}
	
	function getBounds($request)
	{
		if (isset($request["northEastLat"]) && isset($request["northEastLng"]) && isset($request["southWestLat"]) && isset($request["southWestLng"]))
		{
			return new Bound($request);
		}		
	}
}
?>