<?php

class Page extends BaseClass {

    protected $tableName = TBL_PAGES;
    public $id_column = "id";
    public static $pagesInfo;

    public function isActive()
    {
      return $this->isEnabled() && !$this->isDeleted();
    }

    public function isEnabled()
    {
      return $this->enabled == DB_ENUM_TRUE; 
    }

    public function isDeleted()
    {
      return $this->deleted == DB_ENUM_TRUE;
    }

    public static function getMainMenu()
    {
      $page = new Page();

      $where = array();
      array_push($where, "mainMenu = '" . DB_ENUM_TRUE . "'");
      array_push($where, "deleted = '" . DB_ENUM_FALSE . "'");
      array_push($where, "enabled = '" . DB_ENUM_TRUE . "'");

      return $page->search($where, "menuOrder ASC");
    }

    public static function getPagesInfo()
    {
      if (Page::$pagesInfo)
        return Page::$pagesInfo;
        
      $pages = new Page();

      $where = array();
      array_push($where, "deleted = '" . DB_ENUM_FALSE . "'");
      array_push($where, "enabled = '" . DB_ENUM_TRUE . "'");

      $pages = $pages->search($where);

      $return = array();
      foreach ($pages as $page)
          $return[$page->internalPointer] = $page;

      Page::$pagesInfo = $return;
      
      return $return;
    }
    
    public function getUrl($fresh = false)
    {
        $seo = new Seo();
        return $seo->getUrl($this);
    }
    
    public function getParentPages(array &$output)
    {
        $return = array();
        $parent = $this->getParentPage();

        if ($parent && $parent->isValid())
        {
            array_push($output, $parent);
            $parent->getParentPages($output);
        }
    }
    
    public function getParentPage()
    {
        $pages = Page::getPagesInfo();
        
        foreach ($pages as $page)
            if ($page->getId() == $this->parent)
                return $page;
                
        return null; 
    }
    
    public function getChildPages(array &$output)
    {
        throw new Exception("Page::getChildPages() has not been implemented yet!", E_ERROR);
    }
    
    public function getChildPage()
    {
        $pages = Page::getPagesInfo();
        $return = null;
        
        foreach ($pages as $page)
        {
            if ($page->isActive() && $page->parent == $this->getId())
            {
                if (!$return)
                    $return = $page;
                    
                else if ($page->menuOrder < $return->menuOrder)
                    $return = $page;
                    
                else if ($page->menuOrder = $return->menuOrder && $return->getId() < $page->getId())
                    $return = $page;
            }
        }
        return $return; 
    }
    
    public function isActivePage($internalPointer, $mayBeParent = true)
    {
        $pages = $this->getPagesInfo();
        
        if ($pages && is_array($pages) && count($pages) && isset($pages[$internalPointer]))
        {
            $page = $pages[$internalPointer];
            if ($page->isValid() && $this->getId() == $page->getId())
                return true;
            
            if ($mayBeParent)
            {
                $output = array();
                $this->getParentPages($output);
                
                foreach ($output as $pag)                
                    if ($pag->isValid() && $page->getId() == $pag->getId())
                        return true;
            }
        }
        
        return false;
    }
        
    public function getPageByLinkWithSubpages($action)
    {
        global $SEO;

        $action = explode("/", $action);

        if (count($action))
        {
            $action = array_reverse($action);

            for ($i = 0; $i < count($action); ++$i)
            {
                $item = $action[$i];

                if(!trim($item))
                    continue;

                $subpage = new Page();
                $res = $subpage->search(array("seoLink = '{$item}'"));

                if (count($res))
                {
                    if (count($res) == 1)
                        return $res[0];
                    else
                    {
                        foreach ($res as $ress)
                        {
                            $array = array_slice($action, $i + 1);
                            $array = array_reverse($array);
                            $arrayString = implode("/", $array)."/";

                            if ($arrayString == $SEO->getRecursivelyParentPagesLink($ress))
                                return $ress;
                        }
                    }
                }
                
                return null;
            }
            return null;            
        }
        return null;
    }
    
    public function checkPageContentAndGetChild()
    {
        if (!$this->phpExe && !$this->tplInclude && $childPage = $this->getChildPage())
            return $childPage;
        
        return $this;
    }
}

?>