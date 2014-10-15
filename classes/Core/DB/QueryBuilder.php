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
    private $table;
    private $rawSqlQuery;
    private $insertSQL;
    private $updateSQL;
    private $selectSQL;
    private $deleteSQL;
    private $describeSQL;
    private $queryString;
    
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;
    
    function __construct($rawSqlQuery = null)
    {        
        $this->rawSqlQuery = $rawSqlQuery;
        
        $this->insertSQL    = "[INSERT INTO {table}] [SET {updateColumns}]";
        $this->updateSQL    = "[UPDATE {table}] [SET {updateColumns}] [WHERE {where}] [LIMIT {limit}]";
        $this->selectSQL    = "[SELECT {columns}] [FROM {from}] [LEFT JOIN {leftJoinTable} ON {leftJoin}] [WHERE {where}] [GROUP BY {groupBy}] [ORDER BY {order}] [LIMIT {limit}]";
        $this->describeSQL  = "[DESCRIBE {table}]";
        $this->deleteSQL    = "[DELETE FROM {table}] [WHERE {where}] [LIMIT {limit}]";
        
    }
    
    function getMode()
    {
		if ($this->mode)
			return $this->mode;
			
		$sql = $this->getSqlQuery();
		if ($sql)
		{
			if (preg_match("/^SELECT/i", $sql))
				$this->mode = QueryBuilder::SELECT;
			if (preg_match("/^UPDATE/i", $sql))
				$this->mode = QueryBuilder::UPDATE;
			if (preg_match("/^INSERT/i", $sql))
				$this->mode = QueryBuilder::INSERT;
			if (preg_match("/^DELETE/i", $sql))
				$this->mode = QueryBuilder::DELETE;
				
			if ($this->mode)
				return $this->mode;
		}
    }
    
    function select(array $columns = array("*"))
    {
        $this->columns = $columns;
        $this->mode = QueryBuilder::SELECT;
        $this->queryString = $this->selectSQL;
        return $this;
    }
    
    function insert(array $columnsValues, $table = null)
    {
        $this->updateColumns = $columnsValues; 
        
        if ($table)
            $this->table = $table;
        
        $this->mode = QueryBuilder::INSERT;
        $this->queryString = $this->insertSQL;
        return $this;
    }
    
    function update(array $columnsValues, $table = null)
    {
        $this->updateColumns = $columnsValues; 
        
        if ($table)
            $this->table = $table;
            
        $this->mode = QueryBuilder::UPDATE;
        $this->queryString = $this->updateSQL;
        return $this;
    }
    
    function delete($table = null)
    {
        if ($table)
            $this->table = $table;
            
        $this->mode = QueryBuilder::DELETE;
        $this->queryString = $this->deleteSQL;
        return $this;
    }
    
    function describeTable($table = null)
    {
        if ($table)
            $this->table = $table;
            
        $this->queryString = $this->describeSQL;
        return $this;
    }
    
    function describeQuery($query = null)
    {
        if ($query)
            $this->table = $query;
            
        $this->queryString = $this->describeSQL;
        return $this;
    }
    
    function from($from)
    {
        $this->from = $from;
        $this->table = $from;
        return $this;
    }
    
    function table($table)
    {
        $this->table = $table;
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
    
    function group($group)
    {
        $this->groupBy = $group;
        return $this;
    }
    
    function limit($count, $offset)
    {
        if (is_numeric($count) && is_numeric($offset))
            $this->limit = array($count, $offset);
        
        return $this;
    }
    
    private function formattedValues($name, $value)
    {
        switch ($name)
        {
            case "columns":
            case "limit":
                if (is_array($value) && count($value))
                    $value = implode(", ", $value);
                break;
                
            case "updateColumns":
                $ret = array();
                if (is_array($value) && count($value))
                    foreach ($value as $key=>$update)
                        array_push($ret, "{$key} = " . self::prepareValues($update));
                        
                if (count($ret))
                    $value = implode(", ", $ret);
                break;
            case "where":
            	if (is_array($value) && count($value))
					$value = $this->prepareWhere($value);
            break;
        }
        
        return $value;
    }
    
    public function prepareWhere(array &$values, $__key = null)
    {
        foreach ($values as $key => &$value)
        {			
			if (is_array($value))
			{
				$this->prepareWhere($value, $key);
				$value = "(" . implode(" {$key} ", $value) . ")";
			}
			else
				$value = "(" . str_replace("?", self::prepareValues($value), $key) . ")";
        }
        return $value;
    }
    
    public static function prepareValues($value)
    {        
        if (is_string($value))
        {
            $value = addslashes($value);
            $value = "'{$value}'";
		}
        return $value;
    }
    
    private function prepareStatement($query)
    {
        $matches = array();
        
        if (preg_match_all("/\[.*?\]/", $query, $matches))
        {
            foreach ($matches[0] as $part)
            {
                $includeThisPart = false;
                $return = $part;
                $replaceMatches = array();
                
                if (preg_match_all("/\{[a-zA-Z0-9_]*?\}/", $part, $replaceMatches))
                {
                    foreach ($replaceMatches[0] as $replace)
                    {                        
                        $replace = str_replace("}", "", $replace);
                        $replace = str_replace("{", "", $replace);
                        $includeThisPart = false;
                        
                        if (isset($this->{$replace}))
                        {
                            $dataItem = $this->formattedValues($replace, $this->{$replace});
                            $return = preg_replace("/\{{$replace}\}/", $dataItem, $return);
                            $includeThisPart = true;
                        }
                    }                    
                    $return = str_replace("[", "", $return); 
                    $return = str_replace("]", "", $return);                    
                }
                
                $toReplace = $includeThisPart ? $return : "";
                $query = str_replace($part, $toReplace, $query);
            }
        }
        
        $query = trim($query);
        return $this->queryString = $query;
    }
    
    function getSqlQuery()
    {
        // maybe validte SQL query first?
        if ($this->rawSqlQuery)
            return $this->rawSqlQuery;     
                 
        return $this->prepareStatement($this->queryString);
    }
    
    function __toString()
    {
        return $this->getSqlQuery();
    }
}
  
?>
