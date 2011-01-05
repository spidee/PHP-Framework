<?php

/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.2
*   Last update: 14.9.2009
* 
*   TODO: 
*/

class Seo {
    
  private $enable_seo;
    
  function __construct($enable_seo = true)
  {
      $this->enable_seo = $enable_seo;           
  }
  
  public function prepareForUrl($name)
  {
      return Seo::strip_chars($name);      
  }
  
  public function getUrl($module, $subpage = null, $page_num = null)
  {     
      $ret = URL;
       
      $page = new Page("pointer = '{$module}'");
      
      if ($page && $page->id && $page->link)
      {
          if ($this->enable_seo && $page->seo)
          {
            $ret .= $page->link;
          
            if ($subpage)
                $ret .= "/".$this->prepareForUrl($subpage);
               
            if ($page_num)
                $ret .= SEO_PARSE_PAGING.$page_num;
               
            $ret .= URL_SUFFIX;
          }
          else
          {
            $ret .= "index.php?action={$page->link}";
            if ($subpage)
                $ret .= "&id={$subpage}";
            if ($page_num)
                $ret .= "&page={$page_num}";            
          }
      }
      
      return $ret; 
  }
  
  public function getUrlWithChangedPage($page)
  {
      $url = $_SERVER["REQUEST_URI"];
      $pieces = explode(SEO_PARSE_PAGING, $url);
      
      if (count($pieces) == 2)
          return $pieces[0] . SEO_PARSE_PAGING . $page . URL_SUFFIX;  
      
      $url = str_replace(URL_SUFFIX, "", $url);
      $url .= SEO_PARSE_PAGING . $page . URL_SUFFIX;
      return $url;    
  }
  
  static function strip_chars($string)
  {      
      $string = trim($string);
      
      $specCharsToConvert = array(" ", "&", "+", ".");
      
      $trans = array(
        "Á" => "A", "á" => "a", "Č" => "C", "č" => "c", "Ď" => "D", "ď" => "d", "É" => "E", "é" => "e",
        "Ě" => "E", "ě" => "e", "Í" => "I", "í" => "i", "Ľ" => "l", "ľ" => "l", "Ň" => "N", "ň" => "n", "Ó" => "O", "ó" => "o",
        "Ř" => "R", "ř" => "r", "Š" => "S", "š" => "s", "Ť" => "T", "ť" => "t", "Ú" => "U", "ú" => "u",
        "Ů" => "U", "ů" => "u", "Ý" => "Y", "ý" => "y", "Ž" => "Z", "ž" => "z",
        "ä" => "a", "Ä" => "A", "ë" => "e", "Ë" => "E", "ö" => "o", "Ö" => "O", "ü" => "u", "Ü" => "U",
        "ô" => "o", "Ô" => "O",
        "&Aacute;" => "A", "&aacute;" => "a", "&Ccaron;" => "C", "&ccaron;" => "c", "&Dcaron;" => "D", 
        "&dcaron;" => "d", "&Eacute;" => "E", "&eacute;" => "e", "&Ecaron;" => "E", "&ecaron;" => "e", 
        "&Iacute;" => "I", "&iacute;" => "i", "&Ncaron;" => "N", "&ncaron;" => "n", "&Oacute;" => "O", 
        "&oacute;" => "o", "&Rcaron;" => "R", "&rcaron;" => "r", "&Scaron;" => "S", "&scaron;" => "s",
        "&Tcaron;" => "T", "&tcaron;" => "t", "&Uacute;" => "U", "&uacute;" => "u", "&Uring;" => "U",
        "&uring;" => "u", "&Yacute;" => "Y", "&yacute;" => "y", "&Zcaron;" => "Z", "&zcaron;" => "z",
        "&#318;" => "l", "&#317;" => "l",
        "'" => "", "\"" => "", ":" => "", ")" => "", "(" => "", "{" => "", "}" => "", "[" => "", "]" => "",
        "<" => "", ">" => "", ";" => "", "°" => "", "~" => "", "´" => "", "^" => "", "%" => "", "?" => "",
        "*" => ""
    );
    
    $string = mb_ereg_replace('\-+','-',$string);
    $string = strtr($string, $trans);
    
    foreach ($specCharsToConvert as $str)
        $string = strtr($string, $str, "-");
    
    return $string; 
  }
}

?>