<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: get rid of Zend_Session_Namespace
*/

class Session implements ArrayAccess {

    private $_sess_db;
    private $sessionData = array();
    private $nameSpace;
    private $useSerialization = false;

    function __construct($name, $useSerialization = false)
    {
		if (SESSION_USE_DB)
			session_set_save_handler(array($this, 'open'),
				                 	array($this, 'close'),
				                 	array($this, 'read'),
				                 	array($this, 'write'),
				                 	array($this, 'destroy'),
				                 	array($this, 'gc'));

		$this->useSerialization = $useSerialization;
		$this->initSession($name);
    }

    public function fromArray(array $array)
    {
      foreach ($array as $key=>$value)
        $this->$key = $value;

      return $this;
    }
    
    public function toArray() 
    {
        return $this->sessionData[$this->nameSpace];
    }

    private function initSession($nameSpace)
    {
      	if (!session_id())
        	session_start();
        	        
        /*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) >= (SESSION_EXPIRATION_TIME / 2))
			session_regenerate_id(true);*/
        		
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) >= SESSION_EXPIRATION_TIME) 
		{
			session_unset();     // unset $_SESSION variable for the run-time 
			session_destroy();   // destroy session data in storage  
			
		}
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

		$this->nameSpace = $nameSpace;

		if (!isset($_SESSION[$this->nameSpace]))
			$_SESSION[$this->nameSpace] = array();

		$this->sessionData = &$_SESSION[$this->nameSpace];
    }  

    function __get($name)
    {
        return $this->offsetGet($name);
    }

    function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
    
    public function offsetSet($offset, $data)
    {    
        if (is_object($data) && $data instanceOf BaseClass)
        {
        	$data = clone $data;
        	$data->encodeData();        	
		}		
        
        if ($offset === null)
            $this->sessionData[$this->nameSpace][] = $data;
        else
            $this->sessionData[$this->nameSpace][$offset] =
            $this->useSerialization ? serialize($data) : $data;
    }

    public function offsetGet($offset)
    {
        if (!isset($this->sessionData[$this->nameSpace]) || 
            !isset($this->sessionData[$this->nameSpace][$offset]))
            return null;

        $return = $this->sessionData[$this->nameSpace][$offset];

        if (is_object($return) && $return instanceOf BaseClass)
        {
        	$return = clone $return;
        	$return->decodeData();
		}

        return $this->useSerialization ? unserialize($return) : $return;
    }

    public function offsetExists($offset) 
    {
        return isset($this->sessionData[$this->nameSpace][$offset]);
    }

    public function offsetUnset($offset) 
    {
        unset($this->sessionData[$this->nameSpace][$offset]);
    }

    public function unsetAll()
    {
        unset($this->sessionData[$this->nameSpace]);
    }

    public function isEmpty()
    {
        return !is_array($this->sessionData[$this->nameSpace]) ||
               !count($this->sessionData[$this->nameSpace]);
    }
    
    public function open()
    {
        global $DB_SETTINGS;
        if ($this->_sess_db = mysqli_connect($DB_SETTINGS['host'],
                                            $DB_SETTINGS['username'],
                                            $DB_SETTINGS['password'])) 
        {
            return $this->_sess_db->select_db($DB_SETTINGS['dbname']);
        }   

        return false;
    }

    /**
     * Close the session
     * @return bool
     */
    public function close()
    {
        return $this->_sess_db->close();
    }

    /**
     * Read the session
     * @param int session id
     * @return string string of the sessoin
     */
    public function read($id)
    {        
        $id = $this->_sess_db->real_escape_string($id);
        $sql = sprintf("SELECT `data` FROM `sessions` " .
                       "WHERE id = '%s'", $id);

        if ($result = $this->_sess_db->query($sql))
		{
            if (mysqli_num_rows($result))
			{
                $record = mysqli_fetch_assoc($result);
                return $record['data'];
            }
        }
        
        return '';
    }

    /**
     * Write the session
     * @param int session id
     * @param string data of the session
     */
    public function write($id, $data)
    {
        $sql = sprintf("REPLACE INTO `sessions` VALUES('%s', '%s', '%s')",
                       mysqli_real_escape_string($this->_sess_db, $id),
                       mysqli_real_escape_string($this->_sess_db, $data),
                       mysqli_real_escape_string($this->_sess_db, time()));
        return mysqli_query($this->_sess_db, $sql);
    }

    /**
     * Destoroy the session
     * @param int session id
     * @return bool
     */
    public function destroy($id)
    {
        $sql = sprintf("DELETE FROM `sessions` WHERE `id` = '%s'", $id);
        return mysqli_query($this->_sess_db, $sql);
    }

    /**
     * Garbage Collector
     * @param int life time (sec.)
     * @return bool
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability    1
     * @usage execution rate 1/100
     *        (session.gc_probability/session.gc_divisor)
     */
    public function gc($max)
    {
        $sql = sprintf("DELETE FROM `sessions` WHERE `timestamp` < '%s'",
                       mysqli_real_escape_string($this->_sess_db, time() - $max));
        return mysqli_query($this->_sess_db, $sql);
    }
}

?>
