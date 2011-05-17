<?php

/**
 * @author Hendrik
 * @package Lib
 */
class Classloader
{
	#	internal variables
	var $loadedClasses;

	static private $instance;
	static public function getInstance()
	{
		if (null === self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	#	Constructor
	private function __construct (  )
	{
		# code...
	}

	/**
	 * Loads all initial needed classes 
	 */
	public function initLoadLib()
	{
		$this->loadClass(dirname(__FILE__).'/../config.php');
		$this->loadClass(dirname(__FILE__).'/DataConnector.php');
		$this->loadClass(dirname(__FILE__).'/Controller.php');
		$this->loadClass(dirname(__FILE__).'/Model.php');
		$this->loadClass(dirname(__FILE__).'/RoutingEngine.php');
		$this->loadClass(dirname(__FILE__).'/TemplateEngine.php');
		$this->loadClass(dirname(__FILE__).'/FirePHP.php');
		$this->loadClass(dirname(__FILE__).'/Log.php');
		$this->loadClass(dirname(__FILE__).'/Plugin.php');
		$this->loadClass(dirname(__FILE__).'/Utility.php');
			
	}

	/**
	 * Loads model class from the folder /model
	 * @param string $modelName
	 */
	public function loadModel ( $modelName='' )
	{
		$modelName = ucfirst(strtolower($modelName));
		$this->loadClass(dirname(__FILE__).'/../model/'.$modelName.'.php');
	}

	/**
	 * Loads controller class from the folder /controller.
	 * Only use the real name of the controller without the postfix 'Controller'
	 * e.g.: loadController('Post'); loads the /controller/PostController.php file
	 * @param string $controllerName
	 */
	public function loadController ( $controllerName='' )
	{
		$controllerName = ucfirst(strtolower($controllerName));
		$this->loadClass(dirname(__FILE__).'/../controller/'.$controllerName.'Controller.php');
			
	}

	/**
	 * Loads plugin class.
	 * Attention: Linux systems are case sensitiv
	 * @param string $pluginName
	 */
	public function loadPlugin ( $pluginName='' )
	{
		$this->loadClass(dirname(__FILE__).'/../plugins/'.$pluginName.'/'.$pluginName.'.php');
	}

	/**
	 * Loads file with require_once function.
	 * @param string $className
	 */
	public function loadClass ( $className='' )
	{
		require_once($className);
		$this->loadedClasses[] = $className;
	}




}
###

?>