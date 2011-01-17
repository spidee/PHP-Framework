<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.1
*   Last update: 17.1.2011
* 
*   TODO: 
*/

class LanguageHighlighter
{

    function __construct($enableSeo = true)
    {
      $this->enableSeo = $enableSeo;
    }
    
    public function parseMySQL($query)
    {
        $parser = new GeSHi($query, "mysql");
        return $parser->parse_code();
    }
    
    public function parsePHP($source)
    {
        $parser = new GeSHi($source, "php");
        return $parser->parse_code();
    }
}

?>