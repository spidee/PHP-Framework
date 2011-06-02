<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

class DataBase 
{
    /*
    const FETCH_OBJ = 1;
    const FETCH_ASSOC = 2;
    
    private $fetch_mode;
    */
    private $connection;
    private $dbType;
    private $dbSettings = array();
    private $lastQuery;
    private $debug = false;
    
    public $allQueries = array();
    
    function __construct($dbType, array $dbSettings)
    {
        $this->dbType = $dbType;
        $this->dbSettings = $dbSettings;
        $this->debug = DEBUG;
        
        $this->connect();
        $this->selectDatabase();
    }
    /*
    public function setFetchMode($mode)
    {        
        switch ($mode)
        {
            case DataBase::FETCH_OBJ:
            case DataBase::FETCH_ASSOC:
                    $this->fetch_mode = $mode;
                break;
            default:
                throw new CustomException("Neplatny fetch mode v setFetchMode()");
        }
    }
    */
    private function connect()
    {
        if (!array_key_exists("host", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'host'");
        if (!array_key_exists("username", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'username'");
        if (!array_key_exists("password", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'password'");        
        
        if (!$this->isConnected())
            $this->connection = mysql_connect($this->dbSettings["host"], $this->dbSettings["username"], $this->dbSettings["password"]);
            
        if (!$this->isConnected())
            throw new CustomException("Nelze se pripojit do DB!");
    }
    
    private function selectDatabase()
    {
        if (!array_key_exists("dbname", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'dbname'");
        
        mysql_select_db($this->dbSettings["dbname"], $this->connection);
    }
    
    public function fetchAll(QueryBuilder $query)
    {
        return new DBRowSet($this->_query($query));
    }
    
    public function fetchSingle(QueryBuilder $query)
    {
        return new DBRow($this->_query($query));
    }
    
    public function query(QueryBuilder $query)
    {
        return new DBRow($this->_query($query));
    }
    
    public function queryRawSql($query)
    {   
        $query = new QueryBuilder($query);
        return new DBRowSet($this->_query($query));
    }
    
    private function _query(QueryBuilder $query)
    {
        $res = mysql_query($query, $this->connection);
        
        if (mysql_errno($this->connection))
            throw new CustomException("SQL: " . mysql_error($this->connection) . "<br/>" . PHP_EOL . $query, E_ERROR);
            
        $this->lastQuery = $query;
        $this->getDebugInfo();
        
        return $res;
    }
    
    public function getDebugInfo()
    {
        if (!$this->debug || !$this->lastQuery)
            return;
            
        $lastQuery = $this->lastQuery;
        
        $this->debug = false;

        $dbg = null;
        $describeRes = null;
        $duration = null;

        try
        {
            $__dbg = $this->queryRawSql("SET PROFILING = 1");
            $_dbg = $this->queryRawSql("SELECT QUERY_ID, SUM(DURATION) AS SUM_DURATION FROM INFORMATION_SCHEMA.PROFILING GROUP BY QUERY_ID");
            
            $describe = new QueryBuilder();
            $describe->describeQuery($lastQuery);
            $describeRes = $this->fetchSingle($describe);
            
            if ($_dbg->count())
            {
                $duration = $_dbg->last()->SUM_DURATION;
                $dbg = $this->queryRawSql("SELECT STATE AS `Status`, ROUND(SUM(DURATION),7) AS `Duration`, CONCAT(ROUND(SUM(DURATION)/0.000175*100,3), '%') AS `Percentage` FROM INFORMATION_SCHEMA.PROFILING WHERE QUERY_ID=".$_dbg->last()->QUERY_ID." GROUP BY STATE");
            }
        }
        catch(CustomException $ex)
        {            
        }
            
        $this->debug = DEBUG;

        $lastQuery->debugInfo = new stdClass();
        $lastQuery->debugInfo->profiling = $dbg;
        $lastQuery->debugInfo->describe = $describeRes;
        $lastQuery->debugInfo->duration = $duration;
                
        array_push($this->allQueries, $lastQuery);
        
    }
        
    public function isConnected()
    {
        return $this->connection && is_resource($this->connection);
    }
    
    public function select(array $columns = array("*"))
    {
        $qb = new QueryBuilder();
        return $qb->select($columns);
    }
    
    public function insert(array $columnsValues, BaseClass $obj)
    {        
        $qb = new QueryBuilder();
        $qb->table($obj->getTableName());
        
        $this->excludeNotExistedColumns($qb, $columnsValues);
        
        $qb->insert($columnsValues);
        
        return new DBRowSet($this->_query($qb));
    }
    
    public function update(array $columnsValues, BaseClass $obj, $where = null)
    {      
        $qb = new QueryBuilder();
        $qb->table($obj->getTableName());
        
        $this->excludeNotExistedColumns($qb, $columnsValues);
                
        $qb->update($columnsValues);
        $qb->where($where);

        return new DBRowSet($this->_query($qb));
    } 
    
    private function excludeNotExistedColumns(QueryBuilder $qb, array &$columnsValues)
    {
        $d = $qb->describeTable();
        $d = new DBRowSet($this->_query($d));
        
        foreach ($columnsValues as $column=>$value)
        {
            $unsetThis = true;
            foreach ($d as $columnDB)
            {
                if ($columnDB->Field == $column && !ereg("auto_increment", $columnDB->Extra))
                    $unsetThis = false;
            }
            
            if ($unsetThis)
                unset($columnsValues[$column]);
        }
    }
    
    public function find($id, BaseClass $obj)
    {
        $qb = $this->select();
        $qb->from($obj->getTableName());
        $qb->where($obj->getIdColumn() . "=" .$id);
        
        return new DBRowSet($this->_query($qb));
    }
    
    public function delete($where, BaseClass $obj)
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table($obj->getTableName());                        
        $qb->where($where);

        return new DBRowSet($this->_query($qb));
    }
    
    public function lastInsertId()
    {
        return mysql_insert_id($this->connection);
    }
    
}
?>
