<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head>

    {if $PAGE->htmlMetaDescription neq ''}
        <meta name="description" content="{$PAGE->htmlMetaDescription}" />
    {else}
        <meta name="description" content="{$smarty.const.SERVER_TITLE}" />
    {/if}
    {if $PAGE->htmlMetaKeywords neq ''}
        <meta name="keywords" content="{$PAGE->htmlMetaKeywords}" />
    {else}
        <meta name="keywords" content="{$smarty.const.SERVER_TITLE}" />
    {/if}
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

    {if $PAGE->htmlTitle neq ''} 
        <title>{$PAGE->htmlTitle} | {$smarty.const.SERVER_TITLE}</title>
    {else}
        <title>{$PAGE->title} | {$smarty.const.SERVER_TITLE}</title>
    {/if}

    <meta name="copyright" content="" /> 
    <meta name="author" content="" />

    <!-- STYLES -->
    <link rel="stylesheet" type="text/css" media="screen,projection" href="/css/styles.css" />

    <!-- SCRIPTS -->
    <script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
    <link href="/css/jquery.bubblepopup.v2.3.1.css" rel="stylesheet" type="text/css" />
    <script src="/js/jquery.bubblepopup.v2.3.1.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/scripts.js"></script>

    {if $flash_message}
        <script type="text/javascript" language="JavaScript">
        <!--
            alert('{$flash_message}');
        //-->
        </script>
    {/if}        
        
</head>
<body>

<div id="MainContent">

    <div id="InnerContent">

        {assign var="page" value=$PAGE->tplInclude}     
        {if $page}
            {include file="body/$page"} 
        {else}
            {$PAGE->htmlContent}
        {/if}

    </div>

</div>

{include file="footer/footer.tpl"}
    
</body>
</html>        

