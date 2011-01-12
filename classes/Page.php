<?php

class Page extends BaseClass {

    protected $tableName = TBL_PAGES;
    public $id_column = "id";

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
      $pages = new Page();
      
      $where = array();
      array_push($where, "deleted = '" . DB_ENUM_FALSE . "'");
      array_push($where, "enabled = '" . DB_ENUM_TRUE . "'");
      
      $pages = $pages->search($where);
        
      $return = array();
      foreach ($pages as $page)
          $return[$page->internalPointer] = $page;

      return $return;
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
        }
    }  
}

?>