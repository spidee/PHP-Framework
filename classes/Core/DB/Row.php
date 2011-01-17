<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

class DBRow implements ArrayAccess, Iterator
{
    private $data = array();
    private $position = 0;
    
    public function __construct($data) 
    {
        if(is_resource($data))
            $this->data = mysql_fetch_assoc($data);
    }
    
    function __get($name)
    {
        return $this->data && isset($this->data[$name]) ? $this->data[$name] : null; 
    }
    
    function __set($name, $value)
    {
        throw new CustomException("DB Row je pouze pro cteni!");
        //$this->data[$name] = $value;
    }

    public function offsetSet($offset, $data) 
    {
        throw new CustomException("DB Row je pouze pro cteni!");
        /*
        if ($offset === null) 
            $this->data[] = $data;
        else 
            $this->data[$offset] = $data;
        */
    }

    public function toArray() 
    {
        return $this->data;
    }
        
    public function offsetGet($offset)
    { 
        return $this->data[$offset];
    }
    
    public function offsetExists($offset) 
    {
        return isset($this->data[$offset]); 
    }
    
    public function offsetUnset($offset) 
    { 
        throw new CustomException("DB Row je pouze pro cteni!");
    }
    
    public function isEmpty()
    {
        return !is_array($this->data) || !count($this->data); 
    }
    
    function rewind() 
    {
        return reset($this->data);
    }
    
    function current() 
    {
        return current($this->data);
    }
    
    function key() 
    {
        return key($this->data);
    }
    
    function next() 
    {
        return next($this->data);
    }
    
    function valid() 
    {
        return key($this->data) !== null;
    }
}

?>
