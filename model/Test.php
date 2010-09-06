<?php

#doc
#	classname:	Test
#	scope:		PUBLIC
#
#/doc

class Test extends Model
{
	#	internal variables
		var $attributes = array(
							array('id','INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY'),
							array('name','VARCHAR(255)'),
							array('value','VARCHAR(255)'),
							);

		//var $belongsTo = 'Job';



		var $table = 'Test';
	
	#	Constructor
	function __construct (  )
	{
		# code...
	}
	###	

}
###

?>