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
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../models/user.php');
require_once('abstract.action.php');
require_once('person.action.php');

class UserAction extends AbstractAction {
	
	public function __construct() {
	}
	
	public function execute($request) {
	
		if (isset($request['addScore'])) 
		{
			$userName = $request['userName'];
		    $level = $request['level'];
		    $score = $request['score'];
		    $time = $request['time'];
		    
		    return $this->save($userName, $level, $score, $time);
		}
	
		return true;
	}
	
	function save($userName, $level, $score, $time) {
		
		$user = $this->findByUserName($userName);
		if (!$user) {
			$sql = "INSERT INTO parabox_users (user_name, level, score, time) VALUES('$userName', '$level', '$score', '$time')";
			if (!Utility::getInstance()->getDbConnection()->insert($sql))
				return false;
		}
		else {
			$sql = "UPDATE parabox_users SET level = $level, score = $score, time = '$time' WHERE user_name='$userName'";
			if (!Utility::getInstance()->getDbConnection()->update($sql))
				return false;
		}
		return $this->findByUserName($userName);
	}
		
	public function findByUserName($userName) {
		
		$sql = "SELECT * FROM parabox_users WHERE user_name = '$userName'";
		$result = Utility::getInstance()->getDbConnection()->selectSingle($sql);
		if (!$result)
			return false;

		return new User($result['user_id'], $result['user_name'], $result['level'], $result['score'], $result['time']);
	}		
}
?>