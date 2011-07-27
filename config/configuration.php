<?php
/** 
 * General configuration system variables
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, mainulhossain@gmail.com, 2009
 * @version 0.1
 */
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../logger.php');

class Config {
	
	private $logger;
	private static $instance = null;
	
	private $config = array(
				'Home' => 'colormap.php',
				'Register' => 'register.php',
				'Login' => 'login.php',
				/*'Server'   => "localhost",
				'UserName' => "root",
				'Password' => "mmhliton",
				'Database' => "keensocial_com",*/
				'Server'   => "keensocial.com.mysql",
				'UserName' => "keensocial_com",
				'Password' => "mmhliton",
				'Database' => "keensocial_com",
				'Traceable' => true,
				'TraceLogger' => 'trace.log'
	);
	
    private function __construct()
    {
    	$this->logger = new SimpleFileLogger($this->config['TraceLogger']);
    }

    public function __destruct()
    {
    	if ($this->logger)
			$this->logger->close();  	
    }
    
    public static function getInstance()
    {
      if(self::$instance == null)
      {
        self::$instance = new self;
      }

      return self::$instance;
    }
    
    function __get($name)
    {
    	return $this->config[$name];
    }
    
    function trace($msg)
    {
    	if (!$this->logger)
    		$this->logger = new SimpleFileLogger($this->config['TraceLogger']);
    	$this->logger->add(date('F j, Y, g:i a') . "-----------------------------------\n$msg\n");                 // March 10, 2001, 5:16 pm$msg);
    }
}
?>
