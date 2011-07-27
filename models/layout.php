<?php

require_once('model.php');
/** 
 * Model for personal information of registered users
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class Layout extends Model 
{
     public function __construct($id, $name, $width, $height, $backcolor, $backimage) {
        
        $this->values['Id'] = $id;
    	$this->values['Name'] = $name;
        $this->values['Width'] = $width;
        $this->values['Height'] = $height;
        $this->values['Backcolor'] = $backcolor;
        $this->values['$Backimage'] = $backimage;
    }
}
?>