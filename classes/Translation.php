<?php

class Translation extends BaseClass 
{
    private static $stack;
    
    protected $tableName = TBL_TRANSLATION;

    function __construct($in = NULL)
    {
        parent::__construct($in);
    }
    
    public static function translate()
    {        
        global $LANGUAGE;
        
        $args = func_get_args();
        
        if (!count($args))
            throw new CustomException("Translation::translate() has no arguments!", E_ERROR, __FILE__, __LINE__);
        
        $pointer = $args[0];
        
        array_shift($args);
         
        if ($ret = self::searchInStack($pointer)) 
        {
            if (count($args))
            {
                array_unshift($args, $ret->translation);
                return call_user_func_array('sprintf',$args);
            }            
            return $ret->translation;
        }
                           
        $trans = new Translation();
        $trans = $trans->search(array("pointer = '{$pointer}'", "languageId = {$LANGUAGE->id}"));
              
        if (count($trans))
        {
            if (count($args))
            {
                array_unshift($args, current($trans)->translation);
                return sprintf(current($trans)->translation , $args);
            }
            
            return current($trans)->translation;
            /*
            array_unshift($args, current($trans)->translation);
            return call_user_func_array("sprintf", $args); 
            */
        }
            
        return "";
    }
    
    private static function searchInStack($pointer)
    {
        if (!self::$stack)
            self::fillLanguageStack();
        
        foreach (self::$stack as $item)
            if ($item->pointer == $pointer)
                return $item;
                
        return null;
    }
    
    public static function fillLanguageStack()
    {
        global $LANGUAGE;
        
        $trans = new Translation();
        $trans = $trans->search(array("languageId = {$LANGUAGE->id}")); 
        
        self::$stack = $trans;
    }
    
}

?>
