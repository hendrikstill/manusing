<?php


/**
 * @author Hendrik
 * @package Lib
 * @abstract
 */
class Model
{
	#	internal variables

	#	Constructor
	function __construct (  )
	{
		# code...
	}
	###

	/**
	 * Uses PDO::quote function
	 */
	function quote( $string )
	{
		return DataConnector::getInstance( )->quote($string);
	}

	/**
	 * Creates or updates the instance on the database
	 */
	public function save ()
	{
		//Calling hook method
		$this->beforeSave();
		
		//Creating model
		if(!isset($this->id)){
			$this->create();
		}else{
			$this->update();
		}
	}

	/**
	 * Removes instance completly from the database
	 */
	public function delete ()
	{
		//Calling hook method
		$this->beforeDelete();
		
		$query = 'DELETE FROM '.$this->table.' WHERE id ='.$this->id.';';
		DataConnector::getInstance()->query($query);
	}

	/**
	 * Updates all attributes of the model defined in the model class
	 */
	public function update ()
	{
		//Calling hook method
		$this->beforeUpdate(); 
		
		//updates model
		$notFirst = false;
		$query = 'UPDATE '.$this->table.' SET ';
		foreach($this->attributes as $attribute){
			if($notFirst)
			{
				$query .= ',';
			}else{
				$notFirst = true;
			}
			$query .= $attribute[0].' = '.$this->quote($this->$attribute[0]);
		}
		$notFirst = false;
		$query .=' WHERE id = \''.$this->id.'\';';
		DataConnector::getInstance()->query($query);
	}

	/**
	 * Creates model in the database with a INSERT INTO query
	 */
	public function create ()
	{
		//Calling hook method
		$this->beforeCreate();
		
		$notFirst = false;
		$query = 'INSERT INTO '.$this->table.' (';
		foreach($this->attributes as $attribute){
			if($notFirst)
			{
				$query .= ',';
			}else{
				$notFirst = true;
			}
			$query .= $attribute[0];
		}
		$notFirst = false;
		$query .=') VALUES (';
		foreach($this->attributes as $attribute){

			if($notFirst)
			{
				$query .= ',';
			}else{
				$notFirst = true;
			}
			if($attribute[0] != 'id' && isset($this->$attribute[0])) //Attentions
			{
				$query .= $this->quote($this->$attribute[0]);
			}else{
				$query .= 'NULL';
			}
		}
		$query .= ');';
		DataConnector::getInstance()->query($query);

		$this->id = DataConnector::getInstance()->lastInsertId();
	}

	/**
	 * Returns a model object with the given id and loads all the attributes into the current instance.
	 * @param int $id
	 * @return model
	 */
	public function find ($id)
	{
		$query = 'SELECT * FROM '.$this->table.' WHERE id ='.DataConnector::getInstance()->quote($id).';';
		$result = DataConnector::getInstance()->query($query);
		if($result)
		{
			foreach($result as $row){

				foreach($this->attributes as $attribute){
					$this->{$attribute[0]} = $row[$attribute[0]];
				}
			}
			return $this;
		}else{
			return false;
		}
	}


	/**
	 * Returns single model from the database. You can define the where clause by youre selfe. Returns false incase of null selection
	 * @param string $where
	 * @return model
	 */
	public function findWhere ( $where )
	{
		$query = 'SELECT * FROM '.$this->table.' WHERE '.$where.' LIMIT 1;';
		$result = DataConnector::getInstance()->query($query);
		if($result)
		{
			foreach($result as $row){

				foreach($this->attributes as $attribute){
					$this->{$attribute[0]} = $row[$attribute[0]];
				}
			}
			return $this;
		}else{
			return false;
		}
	}

	/**
	 * Returns an array of all models from the Database which match the query
	 * Be patiant with the security of this query
	 * @return array of models
	 */
	public function findWithSQL ( $query )
	{
		$className = get_class($this);
		if($result)
		{
			foreach($result as $row){
				$element = new $className();

				foreach($this->attributes as $attribute){
					$element->{$attribute[0]} = $row[$attribute[0]];
				}
				$elements[$i] = $element;
				$i++;
			}
			return $elements;
		}else{
			return array();
		}

	}

	/**
	 * Returns numbers of models in the database
	 */
	public function count ()
	{
		$query = 'SELECT COUNT( * ) FROM  '.$this->table;

		$result = DataConnector::getInstance()->query($query);
		if($result)
		{
			foreach($result as $row){

				$count = $row[0];
			}
			return $count;
		}else{
			return 0;
		}

	}

	/**
	 * Returns an array of all models from the Database
	 * @return array
	 */
	public function findAll ( )
	{
		$className = get_class($this);
		$query = 'SELECT * FROM '.$this->table.';';
		$result = DataConnector::getInstance()->query($query);
		$i = 0;
		if($result)
		{
			foreach($result as $row){
				$element = new $className();

				foreach($this->attributes as $attribute){
					$element->{$attribute[0]} = $row[$attribute[0]];
				}
				$elements[$i] = $element;
				$i++;
			}
			return $elements;
		}else{
			return array();
		}
	}

	/**
	 * Returns an array of all models from the Database. Use the where clause to filter the results.
	 * @param string $where
	 * @return array
	 */
	public function findAllWhere($where)
	{
		$className = get_class($this);
		$query = 'SELECT * FROM '.$this->table.' WHERE '.$where.';';
		$result = DataConnector::getInstance()->query($query);
		if($result && $result->rowCount() > 0) //Check if we get a result
		{
			foreach($result as $row){
				$element = new $className();

				foreach($this->attributes as $attribute){
					$element->{$attribute[0]} = $row[$attribute[0]];
				}
				$elements[$i] = $element;
				$i++;
			}
			return $elements;
		}else{
			return false;
		}
	}

	/**
	 * Creats table of the model like it is defined.
	 * This is very usefull for the install process in your webapp.
	 */
	public function createDatabaseTable ()
	{
		$notFirst = false;
		$query = 'CREATE TABLE '.$this->table.' (';
		foreach($this->attributes as $attribute){
			if($notFirst)
			{
				$query .= ',';
			}else{
				$notFirst = true;
			}
			$query .= $attribute[0].' '.$attribute[1];
		}
		$query .= ');';
		DataConnector::getInstance()->query($query);
			
	}

	/**
	 * Returns the Model name
	 * @param string $name
	 * @return string
	 */
	static public function getName ( $name='' )
	{
		return ucfirst($name);
	}
	/**
	 *returns all attributes as an array.
	 *@return array
	 */
	public function getAttributes ()
	{
		return $this->attributes;
	}

	
	/**
	 * a hook method witch is called before every save to the persistence layer
	 * Overlode this.
	 */
	public function beforeSave(){
		//
	}
	/**
	 * a hook method witch is called before every update to the persistence layer
	 * Overlode this.
	 */
	public function beforeUpdate(){
		
	}
	
	/**
	 * a hook method witch is called before every create of element to the persistence layer
	 * Overlode this.
	 */
	public function beforeCreate(){
		
	}
	
	/**
	 * a hook method witch is called before every delete of element to the persistence layer
	 * Overlode this.
	 */
	public function beforeDelete(){
		
	}


}
###

?>