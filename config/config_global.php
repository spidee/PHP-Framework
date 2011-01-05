<?php

if ( $_SERVER['SERVER_NAME']=="localhost" ||      
     $_SERVER['SERVER_NAME']=="127.0.0.1" ) 
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
        'dbname'           => ''
    );
    
    define ("URL", "");
    
}
else 
{
    $DB_SETTINGS = array(
        'host'             => '',
        'username'         => '',
        'password'         => '',
        'dbname'           => ''
    );    
    
    define ("URL", "");
}
define("DB_TYPE", "Mysql");

define ("DB_ENUM_TRUE", "yes");
define ("DB_ENUM_FALSE", "no");

define ("HTTP_HEADER_404_NOT_FOUND", "HTTP/1.1 404 Not Found");
define ("HTTP_HEADER_403_FORBIDDEN", "HTTP/1.1 403 Forbidden");
define ("HTTP_HEADER_200_OK", "HTTP/1.1 200 OK");
define ("HTTP_HEADER_500_INTERNAL_SERVER_ERROR", "HTTP/1.1 500 Internal Server Error");

define ("SEO", true);
define ("DEBUG", $_debug);
define ("LOCALHOST", $_localhost);
define ("DEFAULT_PAGE_ID", 1);
define ("ERROR_PAGE_ID", 404);
define ("FORBIDDEN_PAGE_ID", 404);
define ("FOLDER_PHP_FILES", "php/");
define ("FOLDER_EXE_FILES", "exe/");
define ("FOLDER_LIBS", "libs");
define ("FOLDER_CLASSES", "classes");

define ("URL_SUFFIX", ".html");
define ("URL_SUFFIX_STRICT_REQURED", false);
define ("GET_PAGING_LINK", "page");
define ("GET_ACTION_LINK", "action");
define ("GET_ID_LINK", "id");
define ("SEO_PARSE_PAGING","__");
define ("SERVER_TITLE", "");

//TABLES
define ("TBL_USERS", "users");
define ("TBL_PAGES", "pages");


//SESSIONS
define ("SESSION_DEFAULT", "Default");
define ("SESSION_FLASH_MESSAGE", "flashMessage");



//MAIL
define ("MAIL_DEFAULT_FROM", "");
define ("MAIL_DEFAULT_FROM_NAME", "");


//COMMON
define ("COOKIE_NAMESPACE", "");
define ("COOKIE_EXPIRE", time() + 24 * 3600);
define ("SESSION_EXPIRATION_TIME", 60 * 10);

$EXT404EXCEPTION = array(
    "js",
    "css",
    "jpg",
    "jpeg",
    "gif",
    "png",
    "flv"
)

?>
