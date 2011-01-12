<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.0.1
*   Last update: 7.1.2011
* 
*   TODO: rewrite :)
*/

class CustomException extends Exception
{
    private $context;

    public function __construct($message, $code, $file = __FILE__, $line = 0, $context = null)
    {
        parent::__construct($message, $code);
        
        $this->file = $file;
        $this->line = $line;
        
        $this->context = $context;
    }
    
    public static function handleError($errno, $errstr, $errfile, $errline, $errcontext)
    {
        CustomException::handleExceptions(new CustomException($errstr, $errno, $errfile, $errline, $errcontext));
    }

    public static function handleExceptions(CustomException $ex)
    {
        if (!error_reporting())
            return;
        
        global $SMARTY;   
        
        header(HTTP_HEADER_500_INTERNAL_SERVER_ERROR);

        if (!$SMARTY || !is_object($SMARTY))
            die($ex->getMessage(). "<br>" . $ex->getLine(). "<br>" . $ex->getFile(). "<br>" . $ex->getTraceAsString());
        
        $SMARTY->assign("Exception", $ex);
        $SMARTY->display("exceptions.tpl");
        die();
    }
    
    public function getStringCode()
    {
        $code = $this->getCode();
        
        if (is_string($code))
            return $code;
            
        switch ($code)
        {            
            case E_ALL:
                $code = "E_ALL";
                break;
            case E_COMPILE_ERROR:
                $code = "E_COMPILE_ERROR";
                break;
            case E_COMPILE_WARNING:
                $code = "E_COMPILE_WARNING";
                break;
            case E_CORE_ERROR:
                $code = "E_CORE_ERROR";
                break;
            case E_CORE_WARNING:
                $code = "E_CORE_WARNING";
                break;
            case E_ERROR:
                $code = "E_ERROR";
                break;
            case E_NOTICE:
                $code = "E_NOTICE";
                break;
            case E_PARSE:
                $code = "E_PARSE";
                break;
            case E_RECOVERABLE_ERROR:
                $code = "E_RECOVERABLE_ERROR";
                break;
            case E_STRICT:
                $code = "E_STRICT";
                break;
            case E_USER_ERROR:
                $code = "E_USER_ERROR";
                break;
            case E_USER_WARNING:
                $code = "E_USER_WARNING";
                break;
            case E_WARNING:
                $code = "E_WARNING";
                break;
        }        
        return $code; 
    }
}

?>
