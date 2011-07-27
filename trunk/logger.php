<?php
/** 
 * Geographic Color Preferences
 *
 * A simple logger
 *
 * @author Tahmineh Sanamrad
 * @copyright Tahmineh Sanramrad, ETH Zurich 2008
 */
 
/*
 * Abstract class for simple logging
 */

abstract class SimpleLogger {
	public function add($msg){}
}

/*
 * This logger does not log anything
 */

class NullLogger extends SimpleLogger
{
}

/*
 * A simple logger using files
 */

class SimpleFileLogger extends SimpleLogger
{
	private $file;

	public function __construct($file_path)
	{
		$this->file = @fopen($file_path,'a+');
	}

	public function add($msg)
	{
		@fwrite($this->file,$msg);
	}
	
	public function close()
	{
		@fclose($this->file);
	}
}

?>