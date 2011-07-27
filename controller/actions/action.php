<?php
/** 
 * Interface for the action classes of MVC framework
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/

abstract class Action {
	
	public $view;
	public $model;
	public function execute();
}
?>