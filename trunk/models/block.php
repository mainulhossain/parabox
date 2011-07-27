<?php

require_once('model.php');
/** 
 * Model for personal information of registered users
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class Block extends Model 
{
     public function __construct($id, $layoutId, $score, $weight, $backcolor, $backimage, $x, $y, $width, $height) {
        
        $this->values['ID'] = $id;
    	$this->values['LayoutID'] = $layoutId;
    	$this->values['Score'] = $score;
    	$this->values['Weight'] = $weight;
        $this->values['Backcolor'] = $backcolor;
        $this->values['Backimage'] = $backimage;
    	$this->values['X'] = $x;
        $this->values['Y'] = $y;
        $this->values['Width'] = $width;
        $this->values['Height'] = $height;
     }
}
?>