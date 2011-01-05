<?php /* Smarty version 2.6.26, created on 2011-01-05 12:06:50
         compiled from main.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head>

    <?php if ($this->_tpl_vars['PAGE']->htmlMetaDescription != ''): ?>
        <meta name="description" content="<?php echo $this->_tpl_vars['PAGE']->htmlMetaDescription; ?>
" />
    <?php else: ?>
        <meta name="description" content="<?php echo @SERVER_TITLE; ?>
" />
    <?php endif; ?>
    <?php if ($this->_tpl_vars['PAGE']->htmlMetaKeywords != ''): ?>
        <meta name="keywords" content="<?php echo $this->_tpl_vars['PAGE']->htmlMetaKeywords; ?>
" />
    <?php else: ?>
        <meta name="keywords" content="<?php echo @SERVER_TITLE; ?>
" />
    <?php endif; ?>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <meta http-equiv="content-language" content="cs" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
    <meta http-equiv="X-UA-Compatible" content="IE=8;FF=3;OtherUA=4" />
    <meta name="googlebot" content="snippet,archive"/>
    <meta name="robots" content="noodp,noydir" />
    <meta name="cache-control" content="no-cache" />
    <meta name="resource-type" content="document" />

    <?php if ($this->_tpl_vars['PAGE']->htmlTitle != ''): ?> 
        <title><?php echo $this->_tpl_vars['PAGE']->htmlTitle; ?>
</title>
    <?php else: ?>
        <title><?php echo @SERVER_TITLE; ?>
</title>
    <?php endif; ?>

    <meta name="copyright" content="" /> 
    <meta name="author" content="" />

    <!-- STYLES -->
    <link rel="stylesheet" type="text/css" media="screen,projection" href="css/styles.css" />

    <!-- SCRIPTS -->
    <script type="text/javascript" src="js/scripts.js"></script>

    <?php if ($this->_tpl_vars['flash_message']): ?>
        <script type="text/javascript" language="JavaScript">
        <!--
            alert('<?php echo $this->_tpl_vars['flash_message']; ?>
');
        //-->
        </script>
    <?php endif; ?>        
        
</head>
<body>

<div class="MainContent">

    <?php $this->assign('page', $this->_tpl_vars['PAGE']->tplInclude); ?>     
    <?php if ($this->_tpl_vars['page']): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "body/".($this->_tpl_vars['page']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
    <?php else: ?>
        <?php echo $this->_tpl_vars['PAGE']->htmlContent; ?>

    <?php endif; ?>
    
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>     

</div>


</body>
</html>        
