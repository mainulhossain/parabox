<?php 
/** 
 * This is the font controller for the FontController
 * pattern of the MVC framework
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
define("ACTION_DIR", dirname(__FILE__) . "/actions");
define("CONFIG_DIR", dirname(__FILE__ . "../config"));
define("ROOT", dirname(dirname(__FILE__)));
define("DS", DIRECTORY_SEPARATOR);

require_once( str_replace('//','/',dirname(__FILE__).'/') .'../util/session.manager.php');

class FrontController {
	
	private static $instance = null;

    private function __construct()
    {
    	ini_set('display_errors', 1);
    	error_reporting(E_ALL);
    	date_default_timezone_set('Europe/Berlin');
    }

    public static function getInstance()
    {
      if(self::$instance == null)
      {
        self::$instance = new self;
      }

      return self::$instance;
    }
    
    public function process()
    {
    	$actionBase;
    	if (filter_has_var(INPUT_GET, 'action'))
    	{
    		$actionBase = filter_input(INPUT_GET, 'action');
    	}
    	else if (filter_has_var(INPUT_POST, 'action'))
    	{
    		$actionBase = filter_input(INPUT_POST, 'action');
    	}
    	
	    if (!isset($actionBase))
		{
			/*if (SessionManager::getInstance()->CheckAuthentication())
			{
				header("Location:".str_replace('//','/',ROOT.DS).'colormap.php');
			}
			else
			{*/
				//exit("Location:".str_replace('//','/',ROOT.DS).'survey.php');
				//header("Location:".str_replace('//','/',ROOT.DS).'survey.php');
			//}
			//$def = str_replace('//','/',dirname($_SERVER['PHP_SELF'])).'survey.php';
			//echo $def;
			//header("Location:".$def);
			
			header("Location:".str_replace('//','/',dirname($_SERVER['PHP_SELF']).'/').'parabox.php');
		}
		else
		{
			$class = ucfirst($actionBase) . "Action";
			$file = ACTION_DIR ."/" . $actionBase . ".action." . "php";
//			$file = str_replace('\\','/',$file);
			if (!is_file($file)) {
		      exit("Action not available:" . $file);
		    }
		    require_once($file);
	    		    
    		$action = new $class();
    		$action->execute($_REQUEST);
    		if (isset($action->View))
    		{
    			$model = $action->Model;
    			header("Location:".$action->View);
    		}
    		else if (isset($action->Data))
    		{
    			echo $action->Data;
    		}
		}
		exit(0);
    }
}

?>
