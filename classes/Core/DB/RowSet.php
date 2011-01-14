<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

class DBRowSet implements Iterator, Countable
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

    function valid()
    {
        return isset($this->items[$this->position]);
    }
    
    public function count()
    {
        return count($this->items);
    }
}

?>
