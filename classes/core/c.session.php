<?php

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