<?php

$time1 = microtime(true);

require_once('global.inc.php');

SEO::parseUrl($HTTP_REQUEST);
$LANGUAGE = Language::getActualLanguage($HTTP_REQUEST);

$action = $HTTP_REQUEST->GET->action ?
          $HTTP_REQUEST->GET->action : null;

$page = new Page();
$page = $action ? $page->getPageByLinkWithSubpages($action) :
                  new Page(DEFAULT_PAGE_ID);

if ($page)
{
    if (!$HTTP_REQUEST->SERVER->HTTPS || $HTTP_REQUEST->SERVER->HTTPS != "on")
        if ($page->httpsRequired == DB_ENUM_TRUE)
            reloadPage($SEO->getUrl($page));

    $page = $page->checkPageContentAndGetChild();
}

if (!$page || !$page->isValid() || (!$page->phpExe && !$page->content))
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

if ($page->internalPointer != "login" && $page->getId() != ERROR_PAGE_ID &&
    $page->loginRequired == DB_ENUM_TRUE && !User::isLogged())
    reloadPage($SEO->getUrl("login"));

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

$session = getSession();
 
if ($page->phpInclude && file_exists(FOLDER_PHP_FILES.$page->phpInclude))
    include (FOLDER_PHP_FILES.$page->phpInclude);


$main_menu = Page::getMainMenu();
$pages = Page::getPagesInfo();

$SMARTY->assign("main_menu", $main_menu);
$SMARTY->assign("PAGE", $page);
$SMARTY->assign("PAGES", $pages);
$SMARTY->assign("USER", User::getLoggedUser());
$SMARTY->assign("LANGUAGE", $LANGUAGE);

$time2 = microtime(true);
$AllSQLQueries = BaseClass::getDefaultAdapter()->allQueries;
$CompileTime = $time2 - $time1;
$SMARTY->assign("compileTime", round(($CompileTime), 6));
$SMARTY->assign("AllSQLQueries", $AllSQLQueries);
$SMARTY->display("main.tpl");

$session->lastPage = $page->internalPointer;

?>