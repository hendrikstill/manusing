<?php

/**
 * @author Hendrik
 * @package Lib
 */
class Log
{
	#States
	const INFO = 'info';
	const WARNING = 'warning';
	const ERROR = 'error';

	const FILE = 'file';
	const DISPLAY = 'display';
	const DATABASE = 'database';
	const FIREBUG = 'firebug';

	#	internal variables

	static private $instance;
	static public function getInstance()
	{
		if (null === self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	#	Constructor
	function __construct (  )
	{
		# code...
	}
	###

	/**
	 * Logs to database, csv or shows it
	 * Use as $type only the types defined in the Log class.
	 * Log::INFO, Log::WARNING, Log::ERROR
	 * @param string $msg
	 * @param LOG_LEVEL $type
	 */
	public function event ($msg,$type){
		switch ( LOG_LEVEL )
		{
			case Log::INFO:
				$this->log($msg,$type);
				break;
			case Log::WARNING:
				if($type!=Log::INFO)
				$this->log($msg,$type);
				break;
			case Log::ERROR:
				if($type==Log::ERROR)
				$this->log($msg,$type);
				break;
		}
	}

	public function trace(){
		
		switch( LOG_TYPE ){
			case Log::DISPLAY:
				break;
			case Log::FIREBUG:
				$firephp = FirePHP::getInstance(true);
				$firephp->trace("Manusing Trace");
				break;
				
		}
		
	}

	private function log($msg,$type){
		switch ( LOG_TYPE )
		{
			case Log::DISPLAY:
				$this->logToDisplay($msg,$type);
				break;
			case Log::FILE:
				$this->logToFile($msg,$type);
				break;
			case Log::DATABASE:
				$this->logToDatabase($msg, $type);
				break;
			case Log::FIREBUG:
				$this->logToFirebug($msg, $type);
				break;

		}
	}

	private function logToFile($msg,$type){
			
	}

	private function logToDisplay($msg,$type){
		echo "$type Log: $msg";
	}

	private function logToDatabase($msg,$type){
		DataConnector::getInstance()->query("INSERT INTO log (id,time,type,message,url) VALUES  (NULL,NOW(),$type,$msg,".$_SERVER['REQUEST_URI'].")");
	}

	private function logToFirebug($msg,$type){
		try{
		$firephp = FirePHP::getInstance(true);
		$firephp->setEnabled(true);
		$firephp->log($msg,$type);
		}catch(Exception $e){
			
		}

	}

}
###

?>