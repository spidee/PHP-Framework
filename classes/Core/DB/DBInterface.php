<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

abstract class DBInterface
{

    private $connection;
    private $dbType;
    private $dbSettings = array();
    
    function __construct($dbType, array $dbSettings)
    {
        $this->dbType = $dbType;
        $this->dbSettings = $dbSettings;
        
        $this->connect();
        $this->selectDatabase();
    }

    public function connect()
    {
        if (!array_key_exists("host", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'host'");
        if (!array_key_exists("username", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'username'");
        if (!array_key_exists("password", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'password'");
        
        if (!$this->isConnected())
            $this->connection = $this->_connectToDB($this->dbSettings["host"], $this->dbSettings["username"], $this->dbSettings["password"]);
            
        if (!$this->isConnected())
            throw new CustomException("Nelze se pripojit do DB!");
    }
    
    abstract protected function  _connectToDB($host, $username, $password);
    abstract protected function  _selectDatabase($db, $connection);
    abstract public function isConnected();
    abstract protected function _query($query);
    
    private function selectDatabase()
    {
        if (!array_key_exists("dbname", $this->dbSettings))
            throw new CustomException("V nastaveni DB chybi polozka 'dbname'");
            
        if (!$this->isConnected())
            throw new CustomException("Nelze vybrat databazi, pokud neni pripojeni");
        
        $this->_selectDatabase($this->dbSettings["dbname"], $this->connection);
    }
    
    public function fetchAll($query)
    {
        return new DBRowSet($this->_query($query));
    }
    
    public function query($query)
    {   
        return new DBRow($this->_query($query));
    }
    
    private function _query($query)
    {
        $res = mysql_query($query, $this->connection);

        if (mysql_errno($this->connection))
            throw new CustomException("SQL: " . mysql_error($this->connection) . "<br/>" . PHP_EOL . $query . "Error");
        
        return $res;
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
