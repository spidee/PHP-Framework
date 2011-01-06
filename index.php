<?php

$time1 = microtime();

require_once('global.inc.php');
    
$action = isset($_GET["action"]) ? $_GET["action"] : null;     
$page_in = $action ? "seoLink = '{$SEO->prepareForUrl($action)}'" : DEFAULT_PAGE_ID; 

try
{
    $page = new Page($page_in);
}
catch (Exception $ex)
{
    handleExceptions($ex);
}

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
    
    
try
{
    if ($page->phpExe)
    {
        if (file_exists(FOLDER_EXE_FILES.$page->phpExe))
        {
            include (FOLDER_EXE_FILES.$page->phpExe);
            exit;
        }
        else if (DEBUG)
            throw new Exception("Stranka {$page->id}-{$page->title} ma neexistujici EXE '{$page->phpExe}'");
    }

    if ($page->phpInclude && file_exists(FOLDER_PHP_FILES.$page->phpInclude))
        include_once (FOLDER_PHP_FILES.$page->phpInclude);
        
    $main_menu = Page::getMainMenu();
}
catch (Exception $ex)
{
    handleExceptions($ex);
}
  
$SMARTY->assign("main_menu", $main_menu);
$SMARTY->assign("main_menu", $main_menu);
$SMARTY->assign("PAGE", $page);

$time2 = microtime();
$SMARTY->assign("compileTime", round(($time2 - $time1), 2));
$SMARTY->display("main.tpl");


?>