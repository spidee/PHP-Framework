<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

class Session extends Zend_Session_Namespace {

  function __construct($name)
  {
      parent::__construct($name);
  }
  
  public function fromArray(array $array)
  {
      foreach ($array as $key=>$value)
        $this->$key = $value;
        
      return $this;
  }   
}

?>