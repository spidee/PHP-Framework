<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/
  
class QueryBuilder
{
    private $mode;
    private $from;
    private $columns;
    private $where;
    private $order;
    private $limit;
    
    const SELECT = 1;
    const INSERT = 2;
    
    function __constuct()
    {
        
    }
    
    function select(array $columns = array("*"))
    {
        $this->columns = $columns; 
        $this->mode = QueryBuilder::SELECT;
        return $this;
    }
    
    function insert()
    {
        $this->mode = QueryBuilder::INSERT;
        return $this;
    }
    
    function from($from)
    {
        $this->from = $from;
        return $this;        
    }
    
    function where($where)
    {
        $this->where = $where;
        return $this;
    }
    
    function order($order)
    {
        $this->order = $order;
        return $this;
    }
    
    function limit($count, $offset)
    {        
        if ($count && $offset)
            $this->limit = array($count, $offset);
            
        return $this;
    }
    
    function getSqlQuery()
    {
        switch ($this->mode)
        {
            case QueryBuilder::SELECT:
                $sql = "SELECT " . implode(",", $this->columns) . " FROM " . $this->from;
                break;
            case QueryBuilder::INSERT:
                $sql = "INSERT";
                break;
        }
        
        if ($this->where)
            $sql .= " WHERE {$this->where}";
        if ($this->order)
            $sql .= " ORDER BY {$this->order}";
        if ($this->limit && is_array($this->limit))
            $sql .= " LIMIT {$this->limit[0]}, {$this->limit[1]}";
        
        return $sql;
    }
    
    function __toString()
    {
        return $this->getSqlQuery();
    }
}
  
?>
