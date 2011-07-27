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
require_once('abstract.action.php');
require_once('person.action.php');

class BlockAction extends AbstractAction {
	
	public function __construct() {
	}
	
	public function execute($request) {
	
		if (isset($request['level'])) 
		{
			$this->Data = $this->findByLevel($request['level']);
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
		
	public function findByLayoutId($level) {
		
		$sql = "SELECT * FROM layouts WHERE Id = $level";
		return $this->getFromDb($sql);
	}
}
?>