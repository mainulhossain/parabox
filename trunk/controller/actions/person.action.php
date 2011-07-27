<?php
/** 
 * Personal information
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../config/configuration.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../util/utility.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../models/person.php');

class PersonAction {

	public function __construct() {
	}
	
	public function execute($request) {
	
	}
	
	function createPerson($personId, $age, $sex, $ip, $email) {
		
		$person = new Person(0, $age, $sex, $ip, $email);
		
		return save($person);
	}
	
	function save($person) {
		
		
		$sql = "INSERT INTO person NAMES(person_age, person_sex, person_ip, person_email) VALUES($person->Age, $person->Sex, $person->IP, $person->Email)";
		
		$id = Utility::getInstance()->getDbConnection()->insert(sql);
		
		if (!$id)
			return false;
		
		$person->PersonID = $Id;
		return $person;
	}
	
	function getFromDb($sql) {
		
		$result = Utility::getInstance()->getDbConnection()->selectSingle($sql);
		if (!$result)
			return false;
		
		return new Person($result['person_id'], $result['person_age'], $result['person_sex'], $result['person_ip'], $result['person_mail']);
	}

	function getAllFromDb($sql) {
		
		$result = Utility::getInstance()->getDbConnection()->select($sql);
		if (!$result)
			return false;
		
		$items = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($result))
		{
			$items[$i++] = new Person($row['person_id'], $row['person_age'], $row['person_sex'], $row['person_ip'], $row['person_mail']);
		}
		
		return $items;
	}
	
	public function findByPersonID($personId) {
		
		$sql = "SELECT * FROM person WHERE person_id=$personId";
		return $this->getFromDb($sql);
	}
	
	public function findByIP($personIP) {

		$sql = "SELECT * FROM person WHERE person_ip='$personIP'";
		return $this->getAllFromDb($sql);
	}
}
?>