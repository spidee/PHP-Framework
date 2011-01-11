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
  
}

?>