<?php

$time1 = microtime();

require_once('global.inc.php');

$action =  $HTTP_REQUEST->action ? $HTTP_REQUEST->action : null;     

$page = new Page();
$page = $action ? $page->getPageByLinkWithSubpages($action) : new Page(DEFAULT_PAGE_ID);

if (!$page || !$page->isValid())
{
    $page = new Page(ERROR_PAGE_ID);
    header(HTTP_HEADER_404_NOT_FOUND);  
}
else if (!$page->isActive())
{
    $page = new Page(FORBIDDEN_PAGE_ID);
    header(HTTP_HEADER_403_FORBIDDEN);
}
else
    header(HTTP_HEADER_200_OK);

    
if ($page->phpExe)
{
    if (file_exists(FOLDER_EXE_FILES.$page->phpExe))
    {
        include (FOLDER_EXE_FILES.$page->phpExe);
        exit;
    }
    else if (DEBUG)
        throw new CustomException("Stranka {$page->id}-{$page->title} ma neexistujici EXE '{$page->phpExe}'", E_ERROR);
}

if ($page->phpInclude && file_exists(FOLDER_PHP_FILES.$page->phpInclude))
    include (FOLDER_PHP_FILES.$page->phpInclude);
    
    
$main_menu = Page::getMainMenu();
$pages = Page::getPagesInfo();
 
$SMARTY->assign("main_menu", $main_menu);
$SMARTY->assign("PAGE", $page);
$SMARTY->assign("PAGES", $pages);

$time2 = microtime();
$AllSQLQueries = BaseClass::getDefaultAdapter()->allQueries; 
$SMARTY->assign("compileTime", round(($time2 - $time1), 2));
$SMARTY->assign("AllSQLQueries", $AllSQLQueries);
$SMARTY->display("main.tpl");


?>