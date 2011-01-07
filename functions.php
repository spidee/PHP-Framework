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
        $message = implode("\\n", $message);

    $f_message = getSession(SESSION_FLASH_MESSAGE);
    $f_message->message = $message;
}
                
function getFlashMessage() 
{
    $f_message = getSession(SESSION_FLASH_MESSAGE);
    $message = $f_message->message;
    $f_message->unsetAll();

    return $message;
}

function reportDebugError($mess)
{
    throw new Exception($mess);
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

function dump($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
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

// HELPER FUNCTION
function handleError($errno, $errstr, $errfile, $errline, $errcontext)
{
    CustomException::handleError($errno, $errstr, $errfile, $errline, $errcontext);
}

// HELPER FUNCTION
function handleExceptions(CustomException $ex)
{
    CustomException::handleExceptions($ex);
} 
?>
