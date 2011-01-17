<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

class DBRowSet implements Iterator, Countable, ArrayAccess
{
    private $items = array();
    private $position = 0;
    
    function __construct($data)
    {
        while ($data && ($item = new DBRow($data)) && !$item->isEmpty())
            array_push($this->items, $item);
    }
    
    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->items[$this->position];
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }
    
    function prev()
    {
        --$this->position;
    }
    
    function last()
    {
        return end($this->items);
    }

    function valid()
    {
        return isset($this->items[$this->position]);
    }
    
    public function count()
    {
        return count($this->items);
    }
    
    public function offsetSet($offset, $data)
    {
        if ($offset === null) 
            $this->items[] = $data;
        else 
            $this->items[$offset] = $data;
    }

    public function toArray() 
    {
        return $this->items;
    }
        
    public function offsetGet($offset)
    { 
        return $this->items[$offset];
    }
    
    public function offsetExists($offset) 
    {
        return isset($this->items[$offset]); 
    }
    
    public function offsetUnset($offset) 
    { 
        unset($this->items[$offset]);
    }
    
    public function isEmpty()
    {
        return !count($this->items);
    }
}

?>
