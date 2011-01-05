<?php

$SMARTY = new Smarty();
$SMARTY->compile_dir = "{$ROOT}/".FOLDER_LIBS."/Smarty/templates_c";
$SMARTY->template_dir = "{$ROOT}/templates";

$SMARTY->assign("flash_message", getFlashMessage());
$SMARTY->assign("SEO", $SEO);

?>