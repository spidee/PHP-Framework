<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.7
*   Last update: 29.9.2011
*
*   TODO: separate class for DB row and table - this should be DB row, in property "table" should be some class
*         for table with methods such as search(), getDBRowCount() and so on
*/

class BaseClass implements ArrayAccess, Iterator, Countable
{

	private $data;
	private $encoded = false;
	public $id_column = "id";
	private $position = 0;

	public static $dbDefaultAdapter;
	private $dbAdapter;

	function __construct(DataBase $dbAdapter = NULL, array $data = NULL)
	{
		$this->dbAdapter = $dbAdapter ? $dbAdapter : self::$dbDefaultAdapter;

		//TODO: HACK!! - doufejme, ze PHP zavede vlastnosti objektu v interface
		if (!isset($this->tableName) || empty($this->tableName))
			throw new CustomException("Trida '" . get_class($this) . "' nema nastavenou vlastnost 'tableName'");

		if ($data)
		{
			foreach ($data as $key=>$value)
			    $this->$key = $value;
		}/*
		else if (is_numeric($in))
		{
			$rowset  = $this->getAdapter()->find($in, $this);
			if ($rowset && $rowset->count())
			    $this->data = $rowset->current()->toArray();
		}
		else if (is_string($in))
		{
			$select = $this->getAdapter()->select()->from($this->tableName)->where($in);
			$rowset = $this->getAdapter()->fetchAll($select);
			if ($rowset && $rowset->count())
			    $this->data = $rowset->current()->toArray();
		}*/
	}

	function __clone()
	{
	  	unset($this->id);
	}  

	function search(array $where = NULL, $order = NULL, $offset = NULL, $count = NULL, $group_by = NULL)
	{
		$select   = $this->getAdapter()->select()->from($this->tableName);

		if ($where)
		  $select = $select->where($where);

		$select   = $select->order($order)->limit($count, $offset);

		if ($group_by)
			$select = $select->group($group_by);

		$rowset   = $this->getAdapter()->fetchAll($select);

		$array_ret = array();

		$class = get_class($this);
		foreach ($rowset as $item)
			$array_ret[] = new $class($this->getAdapter(), $item->toArray());

		return $array_ret;
	}

	function searchSingle(array $where = NULL, $order = NULL, $offset = NULL, $count = NULL, $group_by = NULL)
	{
		$return = $this->search($where, $order, $offset, $count, $group_by);
		if ($return && is_array($return) && count($return))
			return current($return);

		return null;
	}

	function searchById($id)
	{      
		return $this->searchSingle([$this->getIdColumn() . " = ?" => $id]);
	}

	function __get($name)
	{
		return $this->offsetGet($name);
	}

	function __set($name, $value)
	{
		return $this->offsetSet($name, $value);
	}

	public function offsetSet($name, $data)
	{
	    $this->data[$name] = $data;
	}

	public function toArray()
	{
	    return $this->data;
	}

	public function offsetGet($name)
	{
	    return $this->data && isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function offsetExists($name)
	{
	    return isset($this->data[$name]);
	}

	public function offsetUnset($offset)
	{
	    unset($this->data[$offset]);
	}

	public function isEmpty()
	{
	    return !is_array($this->data) || !count($this->data);
	}

	public function encodeData()
	{
	    foreach ($this->data as $key=>$value)
	        $this->data[$key] = base64_encode($value);
	        
	    $this->encoded = true;
	}

	public function decodeData()
	{
	    if (!$this->encoded)
	        return;
	    
	    $dec = array();
	    foreach ($this->data as $key=>$value)
	        $dec[$key] = base64_decode($value);
	        
	    $this->data = $dec;
	    $this->encoded = false;
	}

	function save($overideId = false)
	{
		//$this->dt_updated = date("Y-m-d H:s:i");
		if ($overideId)
			$this->getAdapter()->insert($this->data, $this);

		else if ($this->id)
			$this->getAdapter()->update($this->data, $this, "{$this->id_column} = {$this->id}");

		else
		{
			$this->getAdapter()->insert($this->data, $this);
			$this->id = $this->getAdapter()->lastInsertId();
		}
	}

	public function isValid()
	{
		return (bool)$this->getId();
	}

	public function getId()
	{
		return (int)$this->{$this->id_column};
	}

	function updateDBcolumn($column_name, $value)
	{
		$this->getAdapter()->update(array($column_name => $value), $this, "{$this->id_column} = {$this->id}");
	}

	function updateDBcolumns(array $data)
	{
		$this->getAdapter()->update($data, $this, "{$this->id_column} = {$this->id}");
	}

	function destroy()
	{
		return $this->getAdapter()->delete("{$this->id_column} = {$this->id}", $this);
	}

	public static function setDefaultAdapter(DataBase $db)
	{
		self::$dbDefaultAdapter = $db;
	}

	public static function getDefaultAdapter()
	{
	   return self::$dbDefaultAdapter;
	}

	public function getAdapter()
	{
		return $this->dbAdapter;
	}

	public function setAdapter(DataBase $adapter)
	{
		$this->dbAdapter = $adapter;
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function getIdColumn()
	{
		return $this->id_column;
	}

	function getDBRowCount($where = null)
	{
		$s = $this->getAdapter()->select(array("count(*) as total"));
		$s = $s->from($this->getTableName());

		if ($where)
			$s = $s->where($where);

		$count = $this->getAdapter()->fetchSingle($s);
		return (int)$count->total;
	}
  
	function rewind()
	{
	    reset($this->data);
	}

	function current()
	{
	    return current($this->data);
	}

	function key()
	{
	    return key($this->data);
	}

	function next()
	{
	    next($this->data);
	}

	function prev()
	{
	    prev($this->data);
	}

	function end()
	{
	    return end($this->data);
	}
	
	function last()
	{
	    return end($this->data);
	}

	function valid()
	{
	    return key($this->data) !== null;
	}

	public function count()
	{
	    return count($this->data);
	}

}

?>