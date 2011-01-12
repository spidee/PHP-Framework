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
    private $rawSqlQuery;
    
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;
    
    function __construct($rawSqlQuery = null)
    {        
        $this->rawSqlQuery = $rawSqlQuery;        
    }
    
    function select(array $columns = array("*"))
    {
        $this->columns = $columns; 
        $this->mode = QueryBuilder::SELECT;
        return $this;
    }
    
    function insert(array $columnsValues = array())
    {
        $this->columns = $columnsValues; 
        $this->mode = QueryBuilder::INSERT;
        return $this;
    }
    
    function update($table)
    {
        $this->mode = QueryBuilder::UPDATE;
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
        if (is_numeric($count) && is_numeric($offset))
            $this->limit = array($count, $offset);           
        
        return $this;
    }
    
    function getSqlQuery()
    {
        // maybe validte SQL query first?
        if ($this->rawSqlQuery)
            return $this->rawSqlQuery;
        
        $from = true;
        
        switch ($this->mode)
        {
            case QueryBuilder::SELECT:
                $sql = "SELECT " . implode(",", $this->columns);
                break;
            case QueryBuilder::INSERT:
                $sql = "INSERT";
                $from = false;
                break;
            case QueryBuilder::DELETE:
                $sql = "UPDATE";
                break;
            case QueryBuilder::DELETE:
                $sql = "DELETE";
                break;
        }
        
        if ($from && $this->from)
            $sql .= " FROM {$this->from}";
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
