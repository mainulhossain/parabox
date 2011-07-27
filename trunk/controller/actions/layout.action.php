<?php
/** 
 * Registration and signing operations
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/

require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../config/configuration.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../util/utility.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../util/session.manager.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../models/layout.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../models/block.php');
require_once('abstract.action.php');
require_once('person.action.php');

class LayoutAction extends AbstractAction {
	
	public function __construct() {
	}
	
	public function execute($request) {
	
		if (isset($request['layout'])) 
		{
			$this->Data = $this->findByLevel($request['layout']);
			return true;
		}
		else if (isset($request['blocks'])) 
		{
			$this->Data = $this->findBlocksByLevel($request['blocks']);
			return true;
		}
		return true;
	}
	
	function getFromDb($sql) {
		
		$result = Utility::getInstance()->getDbConnection()->selectSingle($sql);
		if (!$result)
			return false;

		return new Layout($result['Id'], $result['Name'], $result['Width'], $result['Height'], $result['Backcolor'], $result['Backimage']);
	}
		
	public function findByLevel($level) {
		
		$sql = "SELECT * FROM layouts LIMIT $level, 1";
		$layouts = array();
		$result = $this->getFromDb($sql);
		if (!$result)
			return false;
		return json_encode($result);
	}
	
	public function findBlocksByLevel($level) {
		
		$sql = "SELECT * FROM blocks WHERE LayoutId = $level";
		$result  = Utility::getInstance()->getDbConnection()->select($sql);
		if (!$result)
			return false;

		$blocks = array();
		while ($row = mysql_fetch_assoc($result)){
			$blocks[] = new Block($row['Id'], $row['LayoutId'], $row['Score'], $row['Weight'], $row['Backcolor'], $row['Backimage'], $row['X'], $row['Y'], $row['Width'], $row['Height']);
		}
		//return $blocks;
		return json_encode($blocks);
	}
}
?>