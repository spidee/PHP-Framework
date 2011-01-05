<?php

/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.7
*   Last update: 14.9.2009
*/

class BaseClass extends Zend_Db_Table_Abstract {

  private $data;
  public $id_column = "id";

  function __construct($in = NULL)
  {
    parent::__construct();

    $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
    if (is_array($in))
    {
        foreach ($in as $key=>$value)
            $this->$key = $value;            
        
    }
    else if (is_numeric($in))
    {
        $rowset  = $this->find($in);
        if ($rowset && $rowset->count())
            $this->data = $rowset->current()->toArray();
    }
    else if (is_string($in))
    {
        $select = $this->select()->where($in);       
        $rowset = $this->fetchAll($select);
        if ($rowset && $rowset->count())
            $this->data = $rowset->current()->toArray();
    }       
  }

  function __clone()
  {
      unset($this->id);
  }
  
  function search($array_where = NULL, $order = NULL, $offset = NULL, $count = NULL, $group_by = NULL)
  {
      $where = NULL;
      
      if ($array_where && is_array($array_where))
      {
          if (count($array_where) == 1)
              $where = $array_where[0];
          else 
              $where = implode(" AND ", $array_where); 
      }
 
      $select   = $this->select();
      
      if ($where)
          $select = $select->where($where);
              
      $select   = $select->order($order)->limit($count, $offset);
      
      if ($group_by)
          $select = $select->group($group_by);
               
      $rowset   = $this->fetchAll($select);
      
      $array_ret = array();
      
      $class = get_class($this);
      foreach ($rowset as $item)
          $array_ret[] = new $class($item->toArray());                              
      
      return $array_ret;       
  }
  
  function __get($name)
  {
      return $this->data && isset($this->data[$name]) ? $this->data[$name] : NULL; 
  }
    
  function __set($name, $value)
  {
      $this->data[$name] = $value;        
  }
  
  function save($overideId = false)
  {
      //$this->dt_updated = date("Y-m-d H:s:i");
      if ($overideId)
          $this->insert($this->data);
      
      else if ($this->id)
          $this->update($this->data, "{$this->id_column} = {$this->id}");
      
      else
      {
        $this->insert($this->data);
        $this->id = $this->getAdapter()->lastInsertId();      
      }
  }
  
  public function isValid()
  {
      return (bool)$this->getId();      
  }
  
  public function getId()
  {
      return $this->{$this->id_column};      
  }
  
  function update_column($column_name, $value)
  {
      $this->update(array($column_name => $value), "{$this->id_column} = {$this->id}");      
  }
  
  function update_columns(array $data)
  {
      $this->update($data, "{$this->id_column} = {$this->id}");      
  }  

  function destroy()
  {
      return $this->delete("{$this->id_column} = {$this->id}");
  }
}

?>