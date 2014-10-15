<?php

function getSession($name = null)
{
    return new Session($name ? $name : SESSION_DEFAULT);
}

function setFlashMessage($message)
{
    if (!$message)
        return;

    if (is_array($message))
        $message = implode("<br/>", $message);

    $f_message = getSession(SESSION_FLASH_MESSAGE);
    $f_message->message = base64_encode($message);
}

function getFlashMessage()
{
    $f_message = getSession(SESSION_FLASH_MESSAGE);
    $message = base64_decode($f_message->message);
    $f_message->unsetAll();

    return $message;
}

function setOnloadJS($js)
{
    if (!$js)
        return;

    $session = getSession();
    $session->onLoadJS = $js;
}


function getOnloadJS()
{
    $session = getSession();
    $js = $session->onLoadJS;
    $session->onLoadJS = null;

    return $js;
}

function reportDebugError($mess)
{
    throw new CustomException($mess);
}

function reloadPage($url)
{
    header("Location: {$url}");
    exit;
}

function getAuthSubRequestUrl()
{
    global $SEO;

    $next = $SEO->getUrl("youtube");
    $scope = 'http://gdata.youtube.com';
    $secure = false;
    $session = true;

    return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
}

function getAuthClient()
{
    $session = getSession("sessionToken");
    if ((!isset($session) || !isset($session->token)) && !isset($_GET['token']) )
    {
        reloadPage(getAuthSubRequestUrl());
        return;
    }
    else if ((!isset($session) || !isset($session->token)) && isset($_GET['token']))
        $session->token = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);

    return Zend_Gdata_AuthSub::getHttpClient($session->token);
}

function clearToken()
{
    $session = getSession("sessionToken");
    $session->unsetAll();
}

function dump($var, $withDie = false)
{
    
    echo "<pre>";
    var_dump($var);
    echo "</pre>";

    if ($withDie)
        die();
}

function getCzechDateFormat($sql_timestamp)
{
    return date("d.n.Y H:i:s", strtotime($sql_timestamp));
}

function compareTimeForFacebookSort($x, $y)
{
    $x = (int)$x["time"];
    $y = (int)$y["time"];

    if ($x == $y)
        return 0;
    else if ($x < $y)
        return 1;
    else
        return 0;
}

function getPropertyValueFromArrayOfObjects(array $array, $property)
{

}

function translate($params)
{
  if(isset($params['pointer']) && !empty($params['pointer']))
    return Translation::translate($params['pointer']);
  else
    return "";
}


// HELPER FUNCTION
function handleError($errno, $errstr, $errfile, $errline, $errcontext)
{
    if (!DEBUG)
        reloadPage("/internal.htm");

    CustomException::handleError($errno, $errstr, $errfile, $errline, $errcontext);
}

// HELPER FUNCTION
function handleExceptions(Exception $ex)
{
    if (!DEBUG)
        reloadPage("/internal.htm");

    if (!($ex instanceOf CustomException))
        $ex = new CustomException($ex->getMessage(), $ex->getCode(), $ex->getFile(), $ex->getLine());

    CustomException::handleExceptions($ex);
}

function fixXSS($prom)
{
	switch(gettype($prom))
	{
		case "integer":
			return intval($prom);
			break;
		case "double":
			return htmlspecialchars(strip_tags($prom), ENT_QUOTES);
			break;
		case "string":
			return htmlspecialchars(strip_tags($prom), ENT_QUOTES);
			break;
		case "array":
			foreach ($prom as $key => $value)
			{
				$prom[$key] = fixXSS($value);
			}
			return $prom;
			break;
		case "object":
			foreach ($prom as $key => $value)
			{
				$value = fixXSS($value);
			}
			return $prom;
			break;
		case "resource":
			return $prom;
			break;
		case "NULL":
			return null;
			break;
	}
}

?>
