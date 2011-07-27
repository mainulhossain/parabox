<?php

require_once('model.php');
/** 
 * Model for geographic bound
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class Bound extends Model
{
     public function __construct($request) 
     {
     	$this->values['NorthEastLat'] = $request["northEastLat"];
     	$this->values['NorthEastLng'] = $request["northEastLng"];
     	$this->values['SouthWestLat'] = $request["southWestLat"];
     	$this->values['SouthWestLng'] = $request["southWestLng"];
     }
}
?>