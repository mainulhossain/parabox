<?php
/** 
 * Entry page for the application. Calls are forwarded
 * to the front controller
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
if(!isset($_SESSION)) {
	session_start();
}
require_once('controller/front.controller.php');
FrontController::getInstance()->process();
?>