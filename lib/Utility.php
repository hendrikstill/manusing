<?php

/**
 * @author Hendrik
 * @package Lib
 * @static
 */
class Utility
{
	#	internal variables

	/**
	 * Returns url with the given parameters
	 * @param string $controller
	 * @param string $action
	 * @param string $id
	 * @return string
	 */
	public static function buildUrl($controller,$action,$id = "")
	{
		$string = "./index.php?controller=$controller&action=$action";
		if(!empty($id)){
			$string .= "&id=$id";
		}
		return $string;
	}

	/**
	 * Returns url to the show action of the model
	 * @param model $model
	 * @return string
	 */
	public static function buildUrlToObject($model)
	{
		return Utility::buildUrl(get_class($model), 'show',$model->id);
	}

	/**
	 * Returns simple href link. Use a params array to define tag attributes like array('class'=>'myclass','id'=>'myid')
	 * @param string $text
	 * @param string $link
	 * @param array $params
	 */
	public static function buildLink($text,$link,$params = null)
	{
		$string = '<a href="'.$link.'" ';
		if(isset($params))
		{
			foreach ($params as $key => $value)
			{
				$string .= $key.'="'.$value.'" ';
			}
		}
		$string .= '>'.$text.'</a>';

		return $string;

	}

	/**
	 * Generates html table with the meta information from the models and returns the string
	 * @param model $models
	 * @param boolean $showActions
	 * @param array $params
	 * @return string
	 */
	public static function buildTable($models,$showActions = true,$params = null)
	{
		$attributes = $models[null]->getAttributes();

		$string = '<table ';

		if(isset($params))
		{
			foreach ($params as $key => $value)
			{
				$string .= $key.'="'.$value.'" ';
			}
		}
		$string .= '>';

		//Creating table header
		$string .= '<tr>';
		foreach ($attributes as $attribute)
		{
			$string .= '<th>'.$attribute[0].'</th>';

		}
		if($showActions)
		{
			$string .= '<th> actions </th>';
		}
		$string .= '</tr>';

		//Adding model data to the table
		foreach ($models as $model)
		{

			$string .= '<tr>';
			foreach ($model->attributes as $attribute)
			{
				$string .= '<td>'.$model->{$attribute[0]}.'</td>';
			}
			if($showActions)
			{
				$string .= '<td>';
				$string .= Utility::buildLink('delete ', Utility::buildUrl(get_class($model), 'delete',$model->id));
				$string .= Utility::buildLink('show ', Utility::buildUrl(get_class($model), 'show',$model->id));
				$string .= Utility::buildLink('edit ', Utility::buildUrl(get_class($model), 'edit',$model->id));
				$string .= '</td>';
			}
			$string .= '</tr>';
		}

		$string .= '</table>';

		return $string;

	}

	/**
	 * Generates html form with the meta information from the model. Use $params as html parameters for the form and the $tableparams as parameters for the table
	 * @param model $model
	 * @param array $params
	 * @param array $tableparams
	 * @return string
	 */
	public static function buildForm($model,$params = null,$tableparams = null)
	{
		$string = '<form ';
		if(isset($params))
		{
			foreach ($params as $key => $value)
			{
				$string .= $key.'="'.$value.'" ';
			}
		}
		$string .= '>';
		$string .= '<table ';
		if(isset($tableparams))
		{
			foreach ($tableparams as $key => $value)
			{
				$string .= $key.'="'.$value.'" ';
			}
		}
		$string .= '>';

		foreach ($model->attributes as $attribute)
		{
			$string .= '<tr>';
			$string .= '<td>'.$attribute[0].'</td>';
			if($attribute[0] == 'id')
			{
				$string .= '<td>'.$model->{$attribute[0]}.'</td>';
			}else{
				$string .= '<td><input type="text" name="'.$attribute[0].'">'.$model->{$attribute[0]}.'</td>';
			}
			$string .= '</tr>';
		}

		$string .= '</table>';
		$string .= '<input type="submit">';
		$string .= '</form>';
		return $string;

	}

	/**
	 * Converts german formated date to mysql formated date
	 *
	 * e.g.: 22.03.2006 -> 2006-03-22
	 * http://www.selfphp.info/kochbuch/kochbuch.php?code=15
	 * @param string $date
	 * @return string mysql date
	 */
	public static function date_german2mysql($date) {
		$d    =    explode(".",$date);

		return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]);
	}

	/**
	 * Converts mysql formated date to german formated date
	 *
	 * e.g.: 2006-03-22 -> 22.03.2006
	 * http://www.selfphp.info/kochbuch/kochbuch.php?code=15
	 * @param string $date
	 * @return string german date
	 */
	public static function date_mysql2german($date) {
		$d    =    explode("-",$date);

		return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
	}

	/**
	 * Converts german formated date to mysql formated date
	 *
	 * e.g.: 20060322181602 -> 22.03.2006 18:16:02
	 * http://www.selfphp.info/kochbuch/kochbuch.php?code=15
	 * @param string $date
	 * @return int
	 */
	function timestamp_german2mysql($date) {

		$split = explode(" ",$date);
		$timestamp =    sprintf("%04d%02d%02d",
		substr($split[0], 6, 4),
		substr($split[0], 3, 2),
		substr($split[0], 0, 2));

		$timestamp .=    sprintf("%02d%02d%02d",
		substr($split[1], 0, 2),
		substr($split[1], 3, 2),
		substr($split[1], 6, 2));

		return $timestamp;
	}

	/**
	 * Converts mysql formated date to german formated date
	 *
	 * e.g.: 22.03.2006 18:16:02  -> 20060322181602
	 * http://www.selfphp.info/kochbuch/kochbuch.php?code=15
	 * @param int $date
	 * @return string
	 */
	function timestamp_mysql2german($date) {

		$stamp['date']    =    sprintf("%02d.%02d.%04d",
		substr($date, 6, 2),
		substr($date, 4, 2),
		substr($date, 0, 4));

		$stamp['time']    =    sprintf("%02d:%02d:%02d",
		substr($date, 8, 2),
		substr($date, 10, 2),
		substr($date, 12, 2));

		return $stamp;
	}

}
###
?>