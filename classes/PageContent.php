<?php

class PageContent extends BaseClass 
{

    protected $tableName = TBL_PAGE_CONTENT;

    static function searchPageContentFor($seoLink = null, $pageId = null, $language = null)
    {
        global $LANGUAGE;
        
        if (!$language)
            $language = $LANGUAGE; 
        
        $subpage = new PageContent();
  
        $where = [ "AND" => [] ];
        $where["AND"]["enabled = ?"] = DB_ENUM_TRUE;
        
        if ($seoLink)
            $where["AND"]["seoLink = ?"] = $seoLink;
            
        if ($pageId)
            $where["AND"]["pageId = ?"] = intval($pageId);

        $where["AND"]["OR"] = [];        
        $where["AND"]["OR"]["languagueId is NULL"] = null;
        
        if ($language && $language->isValid())
        	$where["AND"]["OR"]["languagueId = ?"] = $language->getId();
        
        return $subpage->search($where, null, null, null, "pageId");        
    }
   
}

?>
