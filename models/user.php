<?php

require_once('model.php');
/** 
 * Model for personal information of registered users
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class User extends Model 
{
    /**
     * Constructor
     */
     public function __construct($userId, $userName, $level, $score, $time) {
        
        $this->values['PersonID'] = $userId;
    	$this->values['UserName'] = $userName;
        $this->values['level'] = $level;
        $this->values['score'] = $score;
        $this->values['time'] = $time;
    }    
}
?>