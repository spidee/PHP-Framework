<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: get rid of Zend_Session_Namespace
*/

class Session implements ArrayAccess {

    private $sessionData = array();
    private $nameSpace;
    private $useSerialization = false;

    function __construct($name, $useSerialization = false)
    {
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
        if ($offset === null) 
            $this->sessionData[$this->nameSpace][] = $data;
        else         
            $this->sessionData[$this->nameSpace][$offset] = $this->useSerialization ? serialize($data) : $data;
    }
        
    public function offsetGet($offset)
    {         
        if (!isset($this->sessionData[$this->nameSpace]) || !isset($this->sessionData[$this->nameSpace][$offset]))
            return null;
        
        $return = $this->sessionData[$this->nameSpace][$offset];    
        
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
        return !is_array($this->sessionData[$this->nameSpace]) || !count($this->sessionData[$this->nameSpace]); 
    }
}

?>