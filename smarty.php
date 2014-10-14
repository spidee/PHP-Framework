<?php

$SMARTY = new Smarty();
$SMARTY->compile_dir = "{$ROOT}/".FOLDER_LIBS."/Smarty/templates_c";
$SMARTY->template_dir = "{$ROOT}/templates";
$SMARTY->debugging = SMARTY_DEBUG;
//$SMARTY->caching = true;
//$SMARTY->cache_lifetime = 120;

$SMARTY->assign("flash_message", getFlashMessage());
$SMARTY->assign("SEO", $SEO);
$SMARTY->assign("HTTP_REQUEST", $HTTP_REQUEST);
$SMARTY->assign("LANGUAGE_HIGHLIGHTER", $LANGUAGE_HIGHLIGHTER);

?>