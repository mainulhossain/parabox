<?php
/** 
 * Base class for the action classes of the MVC framework
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, mainulhossain@gmail.com, 2009
 * @version 0.1
 */
abstract class AbstractAction
{
	protected $model;
	protected $view;
	protected $data;
	
	public function __get($name)
	{
		if ($name == 'Model' && isset($this->model))
		{
			return $this->model;
		}
		else if ($name == 'View'  && isset($this->view))
		{
			return $this->view;
		}
		else if ($name == 'Data' && isset($this->data))
		{
			return $this->data;
		}
	}
	
	abstract public function execute($request);
}
?>