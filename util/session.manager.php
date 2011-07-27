<?php
/** 
 * Manages sessions of the users
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
class SessionManager {
	
	private static $instance = null;
	private $user_attr = 'user';
	private $colorearth = 'path';
	
    private function __construct()
    {
    }

    public static function getInstance()
    {
      if(self::$instance == null)
      {
        self::$instance = new self;
      }

      return self::$instance;
    }
	
	/**
     * Set the logged user of a session
     * @param session object for which the user will be set
     * @param user user to be set for session
     */
    public function setSessionUser($user) {
    	
    	$_SESSION[$this->user_attr] = $user;
    }

    /**
     * Gets the logged user for a session
     *
     * @param session session whose user to get
     * @return user attached to session
     */
    public function getSessionUser() {
        return $_SESSION[$this->user_attr];
    }

    /**
     * Remove the logged user from this session.
     * @param session
     */
    public function removeSessionUser() {
        
    	unset($_SESSION[$this->user_attr]);
    }

    /**
     * Set a target page to redirect just after login.
     * @param session
     * @param loginTarget
     */
    public function setSessionLoginTarget($loginTarget) {
        $_SESSION[$TARGET_ATTRIBUTE] = $loginTarget;
    }

    /**
     * Get the target page name to be redirected just after login.
     * @param session
     * @param clear
     * @return target page after login
     */
    public function getSessionLoginTarget($clear) {
        $loginTarget = $_SESSION[$TARGET_ATTRIBUTE];
        if ($clear) {
            unset($_SESSION[$TARGET_ATTRIBUTE]);
        }
        return $loginTarget;
    }
    
    /**
     * Check whether a user is already logged into sessio.
     *
     * @param session 
     */
    public function CheckAuthentication()
    {
    	if (!isset($_SESSION))
    		return false;
        return isset($_SESSION[$this->user_attr]);
    }
    
/**
     * Set the logged user of a session
     * @param session object for which the user will be set
     * @param user user to be set for session
     */
    public function switchSessionColorEarth() 
    {
    	if ($this->isSessionColorEarthPath())
    		$_SESSION[$this->colorearth] = 'rank';
    	else
    		$_SESSION[$this->colorearth] = 'path';
    	
    	return $this->getSessionColorEarth();
    }

    /**
     * Gets the logged user for a session
     *
     * @param session session whose user to get
     * @return user attached to session
     */
    public function getSessionColorEarth() 
    {
    	if (!isset($_SESSION[$this->colorearth]))
    		$_SESSION[$this->colorearth] = 'path';
        return $_SESSION[$this->colorearth];
    }
    
    public function isSessionColorEarthPath()
    {
    	return $this->getSessionColorEarth() == 'path';
    }
}
?>