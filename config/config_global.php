<?php

$localHosts = array(
	"localhost",
	"127.0.0.1",
	"t-mobile.loc"
);

if ( in_array($_SERVER['SERVER_NAME'], $localHosts) ) 
{
    $_debug             = true;
    $_localhost         = true;
}
else {
    $_debug             = false;
    $_localhost         = false;
}

error_reporting(E_ALL);
ini_set('display_errors', $_debug ? 1 : 0);


// Nastaveni pro databazi  
if ( $_localhost ) 
{
    $DB_SETTINGS = array(
        'host'             => 'localhost',
        'username'         => 'root',
        'password'         => '',
        'dbname'           => 't-mobile'
    );
    
    //define ("URL", "");
    
}
else 
{
    $DB_SETTINGS = array(
        'host'             => '',
        'username'         => '',
        'password'         => '',
        'dbname'           => ''
    );    
    
    //define ("URL", "");
}

$link =  "//{$_SERVER["HTTP_HOST"]}";
define ("URL", $link);

define("DB_TYPE", "Mysql");

define ("DB_ENUM_TRUE", "yes");
define ("DB_ENUM_FALSE", "no");

define ("HTTP_HEADER_404_NOT_FOUND", "HTTP/1.1 404 Not Found");
define ("HTTP_HEADER_403_FORBIDDEN", "HTTP/1.1 403 Forbidden");
define ("HTTP_HEADER_200_OK", "HTTP/1.1 200 OK");
define ("HTTP_HEADER_500_INTERNAL_SERVER_ERROR", "HTTP/1.1 500 Internal Server Error");

define ("SEO", true);
define ("DEBUG", $_debug);
define ("SMARTY_DEBUG", false /*DEBUG*/);
define ("LOCALHOST", $_localhost);
define ("DEFAULT_TIMEZONE", "Europe/Prague");
define ("DEFAULT_LOCALE", "cs_CZ.utf8");
define ("DEFAULT_PAGE_ID", 1);
define ("ERROR_PAGE_ID", 2);
define ("FORBIDDEN_PAGE_ID", ERROR_PAGE_ID);
define ("FOLDER_PHP_FILES", "php/");
define ("FOLDER_EXE_FILES", "exe/");
define ("FOLDER_LIBS", "libs");
define ("FOLDER_CLASSES", "classes");

define ("URL_SUFFIX", ".html");
define ("URL_SUFFIX_STRICT_REQURED", false);
define ("GET_PAGING_LINK", "page");
define ("GET_ACTION_LINK", "action");
define ("GET_LANGUAGE_LINK", "lang");
define ("GET_ID_LINK", "id");
define ("SEO_PARSE_PAGING","__");
define ("SERVER_TITLE", "");

//CACHE
//available: APCu
define ("USE_CACHE", false);
define ("CACHE_SQL_QUERY", "APCu");
define ("CACHE_SQL_QUERY_TIME_TO_LIVE", 10); //seconds
define ("CACHE_VARIBLE_CACHE", "APCu");
define ("CACHE_VARIBLE_CACHE_TIME_TO_LIVE", 10); //seconds

//TABLES
define ("TBL_USERS", "users");
define ("TBL_PAGES", "pages");
define ("TBL_PAGE_CONTENT", "pagesContent");
define ("TBL_LANGUAGE", "languages");


//SESSIONS
define ("SESSION_USE_DB", true);
define ("SESSION_DEFAULT", "Default");
define ("SESSION_FLASH_MESSAGE", "flashMessage");
define ("SESSION_EXPIRATION_TIME", 60 * 20);



//MAIL
define ("MAIL_DEFAULT_FROM", "");
define ("MAIL_DEFAULT_FROM_NAME", "");


//COMMON
define ("COOKIE_NAMESPACE", "");
define ("COOKIE_EXPIRE", time() + 24 * 3600);

$EXT404EXCEPTION = array(
    "js",
    "css",
    "jpg",
    "jpeg",
    "gif",
    "png",
    "flv",
    "xml"
)

?>