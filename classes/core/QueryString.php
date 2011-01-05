<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 19.8.2010
* 
*   TODO: 
*/

class HttpRequest 
{

  private $GETdata = array();
  private $POSTdata = array();
  
  private $selector;
  
  public static $GET  = 1;
  public static $POST = 2;
  
  function __construct(array $GETdata = null, array $POSTdata = null)
  {
      if ($GETdata && count($GETdata))
          $this->GETdata = $GETdata;
      else 
          $this->fillDataFromGET();
          
      if ($POSTdata && count($POSTdata))
          $this->POSTdata = $POSTdata;
      else 
          $this->fillDataFromPOST();
  }
  
  function __get($name)
  {
      return $this->getValue($name, $this->selector);
  }
    
  function __set($name, $value)
  {
      $this->setValue($name, $value, $this->selector);
  }
  
  function getValue($name, $selector = null)
  {
      $value = null;
      
      if ($selector != HttpRequest::$POST)
          if (isset($this->GETdata[$name]))
              $value = $this->GETdata[$name]; 
          
      if ($selector != HttpRequest::$GET)
          if (isset($this->POSTdata[$name]))
              $value = $this->POSTdata[$name];  
      
      return $value;
  }
  
  function setValue($name, $value, $selector = null)
  {
      if ($this->selector != HttpRequest::$POST)
          $this->GETdata[$name] = $value; 
          
      if ($this->selector == HttpRequest::$POST)
          $this->POSTdata[$name] = $value;      
  }
  
  private function fillDataFromGET()
  {
      $this->GETdata = $_GET;      
  }
  
  private function fillDataFromPOST()
  {
      $this->POSTdata = $_POST;      
  }
  
  public function setSelector($selector)
  {
      if ($selector != HttpRequest::$GET || $selector != HttpRequest::$POST)
        return;
        
     $this->selector = $selector;
  }
  
}

?>