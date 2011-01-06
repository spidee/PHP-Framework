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
    private $conn;
    private $dbType;
    private $dbSettings = array();
    
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
                throw new Exception("Neplatny fetch mode v setFetchMode()");
        }
    }
    */
    private function connect()
    {
        if (!array_key_exists("host", $this->dbSettings))
            throw new Exception("V nastaveni DB chybi polozka 'host'");
        if (!array_key_exists("username", $this->dbSettings))
            throw new Exception("V nastaveni DB chybi polozka 'username'");
        if (!array_key_exists("password", $this->dbSettings))
            throw new Exception("V nastaveni DB chybi polozka 'password'");
        
        if (!$this->isConnected())
            $this->conn = mysql_connect($this->dbSettings["host"], $this->dbSettings["username"], $this->dbSettings["password"]);
            
        if (!$this->isConnected())
            throw new Exception("Nelze se pripojit do DB!");
    }
    
    private function selectDatabase()
    {
        if (!array_key_exists("dbname", $this->dbSettings))
            throw new Exception("V nastaveni DB chybi polozka 'dbname'");
        
        mysql_select_db($this->dbSettings["dbname"], $this->conn);
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
        $res = mysql_query($query, $this->conn);

        if (mysql_errno($this->conn))
            throw new Exception("SQL: " . mysql_error($this->conn) . "<br/>" . PHP_EOL . $query . "Error");
        
        return $res;
    }
    
    public function isConnected()
    {
        return $this->conn && is_resource($this->conn);
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
