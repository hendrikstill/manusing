<?php

header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
set_error_handler('errorHandler',E_ALL & ~E_NOTICE);

session_start();
include_once('./lib/Manusing.php');

$manusing = Manusing::getInstance();

function errorHandler($errno, $errstr, $errfile, $errline){
	debug_print_backtrace();
	echo $errfile.'('.$errline.'): '.$errstr;
}

$manusing->run();



?>