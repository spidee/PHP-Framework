<?php /* Smarty version 2.6.26, created on 2011-01-05 10:42:21
         compiled from exceptions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'exceptions.tpl', 40, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Lang" content="cz">
<meta name="description" content="">
<meta name="keywords" content="">
<title>Error</title>
<link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body id="exceptionPage">
    
    <div class="exceptionError">CHYBA</div>
    
    <div id="exceptionGetCode" class="exceptionErrorMainDiv">
        <div class="exceptionTitle">Kód chyby:</div>
        <?php echo $this->_tpl_vars['Exception']->getCode(); ?>

    </div>
    
    <div id="exceptionGetFile" class="exceptionErrorMainDiv">
        <div class="exceptionTitle">Soubor:</div>
        <?php echo $this->_tpl_vars['Exception']->getFile(); ?>

    </div>
    
    <div id="exceptionGetLine" class="exceptionErrorMainDiv">
        <div class="exceptionTitle">Řádek:</div>
        <?php echo $this->_tpl_vars['Exception']->getLine(); ?>

    </div>
    
    <div id="exceptionGetMessage" class="exceptionErrorMainDiv">
        <div class="exceptionTitle">Zpráva:</div>
        <?php echo $this->_tpl_vars['Exception']->getMessage(); ?>

    </div>
    
    <div id="exceptionGetTraceAsString" class="exceptionErrorMainDiv">
        <div class="exceptionTitle">Stack trace:</div>
        <?php echo ((is_array($_tmp=$this->_tpl_vars['Exception']->getTraceAsString())) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

    </div>
    
</body>
</html>