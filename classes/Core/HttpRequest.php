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
    private $SERVERdata = array();

    private $selector;

    const GET  = 1;
    const POST = 2;
    const SERVER = 3;

    function __construct(array $GETdata = null, array $POSTdata = null, array $SERVERdata = null)
    {
        if ($GETdata && count($GETdata))
          $this->GETdata = $GETdata;
        else 
          $this->fillDataFromGET();
          
        if ($POSTdata && count($POSTdata))
          $this->POSTdata = $POSTdata;
        else 
          $this->fillDataFromPOST();
          
        if ($SERVERdata && count($SERVERdata))
          $this->SERVERdata = $SERVERdata;
        else 
          $this->fillDataFromSERVER();
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

        if (!$selector || $selector == self::GET)
            if (isset($this->GETdata[$name]))
                $value = $this->GETdata[$name]; 

        if (!$selector || $selector == self::POST)
            if (isset($this->POSTdata[$name]))
                $value = $this->POSTdata[$name];
                
        if (!$selector || $selector == self::SERVER)
            if (isset($this->SERVERdata[$name]))
                $value = $this->SERVERdata[$name];

        return $value;
    }

    function setValue($name, $value, $selector = null)
    {
        $selector  = $selector ? $selector : $this->selector;
        
        if (!$selector || $selector == self::GET)
            $this->GETdata[$name] = $value; 
          
        if ($selector == self::POST)
            $this->POSTdata[$name] = $value;
            
        if ($selector == self::SERVER)
            $this->SERVERdata[$name] = $value;
    }

    private function fillDataFromGET()
    {
        $this->GETdata = $_GET;
    }

    private function fillDataFromPOST()
    {
        $this->POSTdata = $_POST;
    }
    
    private function fillDataFromSERVER()
    {
        $this->SERVERdata = $_SERVER;
    }
    
    public function getGETdata()
    {
        return $this->GETdata;
    }

    public function getPOSTdata()
    {
        return $this->POSTdata;
    }
    
    public function getSEREVERdata()
    {
        return $this->SERVERdata;
    }

    public function setSelector($selector)
    {
        if ($selector != null || $selector != self::GET || $selector != self::POST || $selector != self::SERVER)
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
    
    public function getUserAgent()
    {
        $browser = null;
        
        if(stristr($this->getValue('HTTP_USER_AGENT', self::SERVER),'Opera Mini'))
        {
            if($this->getValue('HTTP_X_OPERAMINI_PHONE_UA', self::SERVER))
                $browser = addslashes(strip_tags($this->getValue('HTTP_X_OPERAMINI_PHONE_UA', self::SERVER)));
            else
                $browser = addslashes(strip_tags($this->getValue('HTTP_USER_AGENT', self::SERVER)));
        }
        else
            $browser = addslashes(strip_tags($this->getValue('HTTP_USER_AGENT', self::SERVER)));
        
        return $browser;
    }
    
    public function getUserIP()
    {
        $ip = null;
        
        if($this->getValue('HTTP_CLIENT_IP', self::SERVER))
            $ip = $this->getValue('HTTP_CLIENT_IP', self::SERVER);
        elseif($this->getValue('HTTP_FORWARDED_FOR', self::SERVER))
            $ip = $this->getValue('HTTP_FORWARDED_FOR', self::SERVER);
        elseif($this->getValue('HTTP_X_FORWARDED_FOR', self::SERVER))
            $ip = $this->getValue('HTTP_X_FORWARDED_FOR', self::SERVER);
        else   
            $ip = $this->getValue('REMOTE_ADDR', self::SERVER);
        
        return $ip;        
    }

}

?>