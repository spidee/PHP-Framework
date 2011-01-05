<?php

class Page extends BaseClass {

  protected $_name = TBL_PAGES;
  public $id_column = "page_id";
  
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
      array_push($where, "menu = '" . DB_ENUM_TRUE . "'");
      array_push($where, "deleted = '" . DB_ENUM_FALSE . "'");
      array_push($where, "enabled = '" . DB_ENUM_TRUE . "'");
      
      return $page->search($where, "menu_order ASC");
  }
  
}

?>