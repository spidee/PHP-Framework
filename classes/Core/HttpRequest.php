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
        return $this->getValue($name);
    }

    function __set($name, $value)
    {
        $this->setValue($name, $value);
    }

    function getValue($name, $selector = null)
    {
        $value = null;

        $selector  = $selector ? $selector : $this->selector;

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
        $selector  = $selector ? $selector : $this->selector;
        
        if ($selector != HttpRequest::$POST)
            $this->GETdata[$name] = $value; 
          
        if ($selector == HttpRequest::$POST)
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

    public static function generateUrl($host, array $params)
    {
        $url_orig = parse_url($host);
        
        $url = isset($url_orig["scheme"]) ? $url_orig["scheme"] : "http";
        $url .= "://";          
        $url .= $url_orig["host"].$url_orig["path"];

        $query = false;
        if (isset($url_orig["query"]))
        {
            $url .= "?" . $url_orig["query"];
            $query = true;
        }
            
        if (count($params))
        {
            $url .= $query ? "&" : "?";
            $i = 0;
            foreach ($params as $key=>$value)
            {
                $url .= $key."=".$value;
                ++$i;
                
                if ($i < count($params))
                    $url .= "&";
            }
        }

        return $url;
    }

}

?>