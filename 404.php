<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.4
*   Last update: 15.9.2009
* 
*   TODO: predelat stankovani na jeste hezci format > /neco/1/blabla.html
*/

require_once("config/config_global.php");  
define ("FROM_404", true);

$srv = substr(URL, 0, strlen(URL)-1);//"http://".$_SERVER['SERVER_NAME'];

$url = $srv.$_SERVER["REQUEST_URI"];
$url = str_replace($srv."/","",$url);
$url_parse = explode("/",$url);

$action = "";
$__action = "";
$page = 0;

if (in_array(end(explode(".", $url)), $EXT404EXCEPTION))
{
    header(HTTP_HEADER_404_NOT_FOUND);
    die();
}

for ($i = (count($url_parse) - 1); $i >= 0; --$i)
{
    $action = str_replace(URL_SUFFIX, "", $url_parse[$i]);
    
    $idFound = false;

    $matches_query = array();
    if (preg_match("/\?.+/", $action, $matches_query))
    {
        $_tmp = str_replace("?", "", $matches_query[0]);
        $_tmp = explode("&", $_tmp);
        foreach($_tmp as $key=>$value)
        {
            $__tmp = explode("=", $value);
            if (isset($__tmp[0]) && isset($__tmp[1]))
                $_GET[$__tmp[0]] = $__tmp[1];
        }
        
        $action = str_replace($matches_query[0], "", $action);
    }
    
    $tmp_paging = explode(SEO_PARSE_PAGING,$action); 

    if (count($tmp_paging) > 1 ) 
    {
      $tmp_str = intval($tmp_paging[ (count($tmp_paging)-1) ]);
      if ($tmp_str) 
      {
        $page = $tmp_str; 
        $action = str_replace(SEO_PARSE_PAGING.$page,"",$action);
      }
    }
    
    // idcko
    $matches = array();
    if ($i != 0)
    {
        if (preg_match("/^[0-9]+/", $action, $matches))
        {
            if ($__id = intval($matches[0]))
            {
                $id = $__id;
                $idFound = true;
                $action = preg_replace("/^{$__id}/","",$action);         
            }
        } 
    }
    
    if (!$idFound) 
      $__action = trim($action).($__action ? "/" : "" ).$__action;   
 
}

if (URL_SUFFIX_STRICT_REQURED)
{
    $__str = end($url_parse);
    if (substr($__str, strlen($__str) - strlen(URL_SUFFIX)) != URL_SUFFIX)
        $__action = "dfk23Fds475";
}

$_GET[GET_ACTION_LINK] = $__action;
$_GET[GET_PAGING_LINK] = $page;
if (isset($id))
    $_GET[GET_ID_LINK] = $id; 

//print_r($_GET);
//die();


require_once("index.php");

?>