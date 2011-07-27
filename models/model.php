<?php
/** 
 * Base class for all models
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/
abstract class Model {

   public $values = array();
	
   function __get($name)
   {
    	return $this->values[$name];
   }
   
   public function __set($name, $value) 
   {
    	
    	return $this->values[$name] = $value; 
   }
    
    public function getParameters()
    {
    	return clone $this->values;
    }
    
    public function setParameters($name_value)
    {
    	$this->values = clone $name_value;
    }
    
	function __clone() 
	{
        foreach ($this as $key => $val) 
        {
            if (is_object($val) || (is_array($val))) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }
}
?>