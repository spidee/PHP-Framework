<?php

require_once("config/config_global.php");

date_default_timezone_set(DEFAULT_TIMEZONE);
setlocale(LC_CTYPE, DEFAULT_LOCALE);

$ROOT = realpath(dirname(__FILE__));
set_include_path(get_include_path().
                 PATH_SEPARATOR.$ROOT.DIRECTORY_SEPARATOR.FOLDER_LIBS.
                 PATH_SEPARATOR.$ROOT.DIRECTORY_SEPARATOR.FOLDER_CLASSES);

define ("ROOT", $ROOT);

session_set_cookie_params(0, "/", null, isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
ini_set("session.gc_maxlifetime", SESSION_EXPIRATION_TIME);

require_once("Core/Exceptions.php");
require_once("functions.php");

set_exception_handler("handleExceptions");
set_error_handler("handleError");

require_once("Smarty/Smarty.class.php");
require_once("Geshi/geshi.php");

// CORE
require_once("Core/Exceptions.php");
require_once("Core/Cache.php");
require_once("Core/APCu/APCu.php");
require_once("Core/Session.php");
//require_once("Core/DB/DBInterface.php");
require_once("Core/DB/QueryBuilder.php");
require_once("Core/DB/Row.php");
require_once("Core/DB/RowSet.php");
require_once("Core/DataBase.php");
require_once("Core/BaseClass.php");
require_once("Core/HttpRequest.php");
require_once("Core/Validator.php");

require_once("Core/LanguageHighlighter.php");
require_once("Core/Seo.php");

// OTHER
require_once("classes/Language.php");
require_once("classes/PageContent.php");
require_once("classes/Page.php");
require_once("classes/User.php");

$HTTP_REQUEST = new HttpRequest();
header('X-Frame-Options: SAMEORIGIN');

//if (!empty($_SERVER['HTTPS']))	

// OLD FACEBOOK API
//require_once("Facebook/c.facebook.php");
//require_once("Facebook/c.facebookapi_php5_restlib.php");
//require_once("FaceBook.php");

// NEW FACEBOOK API
//require_once("Facebook/SDK/src/facebook.php");

// MUST BE IN THIS ORDER;
require_once("languageHighlighter.php");
require_once("seo.php");
require_once("smarty.php");
require_once("db.php");

?>
