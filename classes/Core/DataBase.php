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
    
    function __construct($dbType, array $dbSettings)
    {
        $this->dbType = $dbType;
        $this->dbSettings = $dbSettings;
        
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
    
    public function query(QueryBuilder $query)
    {   
        return new DBRow($this->_query($query));
    }
    
    public function queryRawSql($query)
    {   
        $query = new QueryBuilder($query);
        return new DBRow($this->_query($query));
    }
    
    private function _query(QueryBuilder $query)
    {
        $res = mysql_query($query, $this->connection);

        if (mysql_errno($this->connection))
            throw new CustomException("SQL: " . mysql_error($this->connection) . "<br/>" . PHP_EOL . $query);
            
        $this->lastQuery = $query;
        
        return $res;
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
    
    public function find($id, BaseClass $obj)
    {
        $qb = $this->select();
        $qb->from($obj->getTableName());
        $qb->where($obj->getIdColumn() . "=" .$id);
        
        return new DBRowSet($this->_query($qb));
    }
    
}
?>
