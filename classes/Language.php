<?php

class Language extends BaseClass 
{
    protected $tableName = TBL_LANGUAGE;
    
    public static $languages;

    function __construct($in = NULL)
    {
        parent::__construct($in);
    }
    
    public static function getDefaultLanguage()
    {        
        $languages = self::getLanguages();
            
        $ret = null;
        foreach ($languages as $lang)
            if ($lang->default == DB_ENUM_TRUE && (!$ret || $ret->getId() > $lang->getId()))
                $ret = $lang; 
        
        return $ret;
        
    }
    
    public static function getLanguages()
    {
        if (self::$languages)
            return self::$languages;
        
        $lang = new Language();
        return self::$languages = $lang->search(array("enabled = '".DB_ENUM_TRUE."'"));
    }
    
    public static function getActualLanguage(HttpRequest $HttpRequest)
    {
        $session = getSession();
        $language = new Language();

        if ($HttpRequest->GET->lang)
        {
            $languages = self::getLanguages();
            
            foreach ($languages as $lang)
                if ($lang->languagePrefix == $HttpRequest->GET->lang)
                    $language = $lang;
        }            
        else if ($session->language)
            $language = $session->language;
            
        if (!$language->isValid())
            $language = self::getDefaultLanguage();
            
        $session->language = $language;
        
        return $language;        
    }
   
}

?>
