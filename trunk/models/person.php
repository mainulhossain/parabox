<?php

require_once('model.php');
/** 
 * Model for personal information
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class Person extends Model
{

     public function __construct($personId, $age, $sex, $ip, $email) 
     {
     	$this->values['ID'] = $personId;
        $this->values['Age'] = $age;
        $this->values['sex'] = $sex;
        $this->values['IP'] = $ip;
        $this->values['Email'] = $email;
    }
}
?>