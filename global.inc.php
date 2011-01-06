<?php
    
require_once("config/config_global.php");

$ROOT = realpath(dirname(__FILE__));
set_include_path(get_include_path().
                 PATH_SEPARATOR.$ROOT."\\".FOLDER_LIBS.
                 PATH_SEPARATOR.$ROOT."\\".FOLDER_CLASSES);

define ("ROOT", $ROOT);

require_once("functions.php");

require_once("Zend/Loader/Autoloader.php");
$AUTOLOADER = Zend_Loader_Autoloader::getInstance();

require_once("Smarty/Smarty.class.php");

// CORE
require_once("Core/Session.php");
require_once("Core/DB/DBInterface.php"); 
require_once("Core/DB/QueryBuilder.php");
require_once("Core/DB/Row.php");
require_once("Core/DB/RowSet.php");
require_once("Core/DataBase.php");
require_once("Core/BaseClass.php");
require_once("Core/QueryString.php ");
require_once("Core/Seo.php");

// OLD FACEBOOK API
//require_once("Facebook/c.facebook.php");
//require_once("Facebook/c.facebookapi_php5_restlib.php");
//require_once("FaceBook.php");

// NEW FACEBOOK API
require_once("Facebook/SDK/src/facebook.php");


require_once("db.php");
require_once("seo.php");
require_once("smarty.php");

// OTHER
require_once("classes/Page.php");
require_once("classes/User.php");


?>