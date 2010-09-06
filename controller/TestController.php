<?php

#doc
#	classname:	TestController
#	scope:		PUBLIC
#
#/doc

class TestController extends Controller
{
	#	internal variables
	var $tests;
	#	Constructor
	function __construct (  )
	{
		$this->useModel('test');
	}
	###	
	
	function show ( )
	{
		$id = $_GET['id'];
		$this->test->find($id);
	}
	
	function listAll ( )
	{
		$this->tests = $this->test->findAll();
	}
	
	function create ( )
	{
		if(isset($_POST['name'])){
			$this->test->name = $_POST['name'];
			$this->test->save();
			RoutingEngine::getInstance()->redirect('test','show',$this->test->id);
			$this->flash('New test created');
		}
	}
	
	function delete ( )
	{
		$this->test->find($_GET['id']);
		$this->test->delete();
		RoutingEngine::getInstance()->redirect('test','listAll');
	}
	
	public function edit ()
	{
		$this->test->find($_GET['id']);
		if($_POST['action']=='update'){
			$this->test->name = $_POST['name'];
			$this->test->save();
			RoutingEngine::getInstance()->redirect('test','show',$this->test->id);
		}
	}

}
###

?>