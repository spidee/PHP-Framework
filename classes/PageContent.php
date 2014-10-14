<?php

class PageContent extends BaseClass 
{

    protected $tableName = TBL_PAGE_CONTENT;

    function __construct($in = NULL)
    {
        parent::__construct($in);
    }
    
    static function searchPageContentFor($seoLink = null, $pageId = null, $language = null)
    {
        global $LANGUAGE;
        
        if (!$language)
            $language = $LANGUAGE; 
        
        $subpage = new PageContent();
        $where = array("enabled = '" . DB_ENUM_TRUE . "'");
        
        if ($seoLink)
            array_push($where, "seoLink = '{$seoLink}'");
            
        if ($pageId)
            array_push($where, "pageId = '{$pageId}'");
        
        $lang = "( languagueId is NULL"; 
        
        if ($language && $language->isValid())
            $lang .= " or languagueId = {$language->getId()}";
                        
        $lang .= " )"; 
        
        array_push($where, $lang);               
        
        return $subpage->search($where, null, null, null, "pageId");        
    }
   
}

?>
