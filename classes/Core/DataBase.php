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
            $this->connection = mysqli_connect($this->dbSettings["host"], $this->dbSettings["username"], $this->dbSettings["password"]);
        
        if (!$this->isConnected())
            throw new CustomException("Nelze se pripojit do DB!");
    }
    
    private function selectDatabase()
    {
        if (!array_key_exists("dbname", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'dbname'");
        
        $this->connection->select_db($this->dbSettings["dbname"]);
    }
    
    public function fetchAll(QueryBuilder $query)
    {
        return $this->cacheStore($query, true);
    }
    
    public function sanitizeInput($input)
    {
        return $this->connection->real_escape_string($input);
    }
    
    public function fetchSingle(QueryBuilder $query)
    {
        return $this->cacheStore($query, false);
    }
    
    public function query(QueryBuilder $query)
    {
        return $this->fetchSingle($query);
    }
    
    public function queryRawSql($query)
    {   
        if (!is_object($query) || !($query instanceOf QueryBuilder))
        	$query = new QueryBuilder($query);
        	
        return $this->fetchAll($query);
    }
    
    public function cacheStore(QueryBuilder $query, $fetchAll)
    {
		$cache = new Cache(CACHE_SQL_QUERY);
		$userCache = $cache->GetUserCache();
		
		if ($userCache)
			$userCache->SetTimeToLive(CACHE_SQL_QUERY_TIME_TO_LIVE);

	    if ($userCache && $SQLStore = $userCache->SQL)
	    {
			if (isset($SQLStore[sha1((string)$query)]))
				return $SQLStore[sha1((string)$query)];
	    }
	    
	    $result = $fetchAll ? new DBRowSet($this->_query($query)) : new DBRow($this->_query($query));
	    
	    if ($userCache && $query->getMode() == QueryBuilder::SELECT)
        {
			$SQLStore = array();
			if ($data = $userCache->SQL)
				$SQLStore = $data;
			
			$SQLStore[sha1((string)$query)] = $result;
							
			$userCache->SQL = $SQLStore;
        }
        
        return $result; 
    }
    
    private function _query(QueryBuilder $query)
    {
        $res = $this->connection->query($query);
        
        if (mysqli_errno($this->connection))
            throw new CustomException("SQL: " . mysqli_error($this->connection) . "<br/>" . PHP_EOL . $query, E_ERROR);
            
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
        return $this->connection && !$this->connection->connect_error;
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
        
        return $this->fetchAll($qb);
    }
    
    public function update(array $columnsValues, BaseClass $obj, $where = null)
    {      
        $qb = new QueryBuilder();
        $qb->table($obj->getTableName());
        
        $this->excludeNotExistedColumns($qb, $columnsValues);
                
        $qb->update($columnsValues);
        $qb->where($where);

        return $this->fetchAll($qb);
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
//                 if ($columnDB->Field == $column && !ereg("auto_increment", $columnDB->Extra))
                if ($columnDB->Field == $column && !preg_match("/auto_increment/", $columnDB->Extra))
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
        
        return $this->fetchAll($qb);;
    }
    
    public function delete($where, BaseClass $obj)
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table($obj->getTableName());                        
        $qb->where($where);

        return $this->fetchAll($qb);
    }
    
    public function lastInsertId()
    {
        //dump(mysqli_insert_id($this->connection));
        //dump($this->connection);
        $lid = $this->queryRawSql("SELECT LAST_INSERT_ID() as LID");
        return $lid->last()->LID;
//         return mysqli_insert_id($this->connection);
    }
    
}
?>
