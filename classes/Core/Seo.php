<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.2
*   Last update: 14.9.2009
*
*   TODO:
*/

class Seo {

    private $enableSeo;

    function __construct($enableSeo = true)
    {
      $this->enableSeo = $enableSeo;
    }

    public function prepareForUrl($name)
    {
      return Seo::stripChars($name);
    }

    public function getRecursivelyParentPagesLink(BaseClass $page)
    {
        $subpage = new Page();
        if ($page->parent)
        	$subpage = $subpage->searchById($page->parent);

        $return = "";

        if ($subpage->parent)
            $return = $this->getRecursivelyParentPagesLink($subpage);

        if ($subpage && $subpage->isValid() && $subpage->seoLink)
            $return .= $subpage->seoLink . "/";

        return  $return;
    }

    public function getUrl($module, $lang = null, $subpage = null, $page_num = null)
    {
        global $LANGUAGE;
        
        $languages = Language::getLanguages();

        if (!$lang && count($languages) > 1 && $LANGUAGE->isValid())
            $lang = $LANGUAGE->languagePrefix;

        if ($module instanceOf Page)
        	$page = clone $module;
        else
        {
			$_page = new Page();
			$page = $_page->searchSingle(["internalPointer = ?" => $module]);
        }
                
        if ($lang)
        {
            $_lang = new Language();
            $_lang = $_lang->searchSingle(["languagePrefix = ?" => $lang]);
            $page->setLanguage($_lang);
        }

        $ret = "/";

        if ($page->httpsRequired == DB_ENUM_TRUE)
            $ret = "https://" . $_SERVER["HTTP_HOST"] . "/";


        $action = $page->content && $page->content->seoLink ? $page->content->seoLink : $page->internalPointer;

        if ($page && $page->isValid() && $action)
        {
            if ($this->enableSeo && $page->seo == DB_ENUM_TRUE)
            {
                if ($lang)
                    $ret .= "{$lang}/";
                
                $ret .= $this->getRecursivelyParentPagesLink($page);
                $ret .= $action;

                if ($subpage)
                    $ret .= "/".$this->prepareForUrl($subpage);

                if ($page_num)
                    $ret .= SEO_PARSE_PAGING.$page_num;

                $ret .= URL_SUFFIX;
            }
            else
            {
                $ret .= "index.php?action={$action}";

                if ($subpage)
                    $ret .= "&id={$subpage}";

                if ($page_num)
                    $ret .= "&page={$page_num}";

                if ($lang)
                    $ret .= "&lang={$lang}";
            }
        }

        return $ret;
    }

    public function getUrlWithChangedPage($page)
    {
      $url = $_SERVER["REQUEST_URI"];
      $pieces = explode(SEO_PARSE_PAGING, $url);

      if (count($pieces) == 2)
      {
          $pieces[1] = preg_replace("/^[0-9]?/", "", $pieces[1]);
          return $pieces[0] . SEO_PARSE_PAGING . $page . $pieces[1];
      }


      $url = str_replace(URL_SUFFIX, SEO_PARSE_PAGING . $page . URL_SUFFIX, $url);
      return $url;
    }

    static function stripChars($string)
    {
      $string = trim($string);

      $specCharsToConvert = array(" ", "&", "+", ".");

      $trans = array(
        "Á" => "A", "á" => "a", "Č" => "C", "č" => "c", "Ď" => "D", "ď" => "d", "É" => "E", "é" => "e",
        "Ě" => "E", "ě" => "e", "Í" => "I", "í" => "i", "Ľ" => "l", "ľ" => "l", "Ň" => "N", "ň" => "n", "Ó" => "O", "ó" => "o",
        "Ř" => "R", "ř" => "r", "Š" => "S", "š" => "s", "Ť" => "T", "ť" => "t", "Ú" => "U", "ú" => "u",
        "Ů" => "U", "ů" => "u", "Ý" => "Y", "ý" => "y", "Ž" => "Z", "ž" => "z",
        "ä" => "a", "Ä" => "A", "ë" => "e", "Ë" => "E", "ö" => "o", "Ö" => "O", "ü" => "u", "Ü" => "U",
        "ô" => "o", "Ô" => "O",
        "&Aacute;" => "A", "&aacute;" => "a", "&Ccaron;" => "C", "&ccaron;" => "c", "&Dcaron;" => "D",
        "&dcaron;" => "d", "&Eacute;" => "E", "&eacute;" => "e", "&Ecaron;" => "E", "&ecaron;" => "e",
        "&Iacute;" => "I", "&iacute;" => "i", "&Ncaron;" => "N", "&ncaron;" => "n", "&Oacute;" => "O",
        "&oacute;" => "o", "&Rcaron;" => "R", "&rcaron;" => "r", "&Scaron;" => "S", "&scaron;" => "s",
        "&Tcaron;" => "T", "&tcaron;" => "t", "&Uacute;" => "U", "&uacute;" => "u", "&Uring;" => "U",
        "&uring;" => "u", "&Yacute;" => "Y", "&yacute;" => "y", "&Zcaron;" => "Z", "&zcaron;" => "z",
        "&#318;" => "l", "&#317;" => "l",
        "'" => "", "\"" => "", ":" => "", ")" => "", "(" => "", "{" => "", "}" => "", "[" => "", "]" => "",
        "<" => "", ">" => "", ";" => "", "°" => "", "~" => "", "´" => "", "^" => "", "%" => "", "?" => "",
        "*" => ""
    );


    $string = strtr($string, $trans);

    foreach ($specCharsToConvert as $str)
        $string = strtr($string, $str, "-");

    $string = mb_ereg_replace('\-+','-',$string);

    return $string;
    }

    public static function parseUrl(HttpRequest $HttpRequest)
    {
        global $EXT404EXCEPTION;

        $lang = array();
        $languages = Language::getLanguages();

        foreach($languages as $language)
            if (array_push($lang, $language->languagePrefix));
        
        $url = $HttpRequest->GET->seoUrl;

        $matches_query = array();
        if (preg_match("/\?.+/", $url, $matches_query))
        {
            $_tmp = str_replace("?", "", $matches_query[0]);
            $_tmp = explode("&", $_tmp);
            foreach($_tmp as $key=>$value)
            {
                $__tmp = explode("=", $value);
                if (isset($__tmp[0]) && isset($__tmp[1]))
                    $HttpRequest->setValue($__tmp[0], $__tmp[1], HttpRequest::GET);
            }

            $url = str_replace($matches_query[0], "", $url);
        }

        if (substr($url, 0, 1) == "/")
            $url = substr($url, 1, strlen($url) - 1);

        $url_parse = explode("/",$url);

        $action = "";
        $__action = "";
        $language = null;
        $page = 0;

        $exploded = explode(".", $url);
        if (in_array(end($exploded), $EXT404EXCEPTION))
        {
            header(HTTP_HEADER_404_NOT_FOUND);
            die();
        }

        for ($i = (count($url_parse) - 1); $i >= 0; --$i)
        {
            $action = str_replace(URL_SUFFIX, "", $url_parse[$i]);

            $idFound = false;


            $tmp_paging = explode(SEO_PARSE_PAGING,$action);

            if (count($tmp_paging) > 1 )
            {
              $tmp_str = intval($tmp_paging[ (count($tmp_paging)-1) ]);
              if ($tmp_str)
              {
                $page = $tmp_str;
                $action = str_replace(SEO_PARSE_PAGING.$page,"",$action);
              }
            }

            $matches = array();
            if ($i != 0)
            {
                if (preg_match("/^[0-9]+/", $action, $matches))
                {
                    if ($__id = intval($matches[0]))
                    {
                        $id = $__id;
                        $idFound = true;
                        $action = preg_replace("/^{$__id}/","",$action);                        
                    }
                }
            }
            else
                if (in_array($action, $lang))
                    $language = $action;

            if (!$idFound && !$language)
              $__action = trim($action).($__action ? "/" : "" ).$__action;

        }
        
        //hack for 404
        if (URL_SUFFIX_STRICT_REQURED)
        {
            $__str = end($url_parse);
            if (substr($__str, strlen($__str) - strlen(URL_SUFFIX)) != URL_SUFFIX)
                $__action = "dfk23Fds475";
        }
        
        $HttpRequest->setValue(GET_ACTION_LINK, $__action, HttpRequest::GET);
        $HttpRequest->setValue(GET_PAGING_LINK, $page, HttpRequest::GET);
        if (isset($id))
            $HttpRequest->setValue(GET_ID_LINK, $id, HttpRequest::GET);
        if ($language)
            $HttpRequest->setValue(GET_LANGUAGE_LINK, $language, HttpRequest::GET);            
    }

    public static function generateUrl($host, array $params)
    {
        $url_orig = parse_url($host);

        $url = isset($url_orig["scheme"]) ? $url_orig["scheme"] : "http";
        $url .= "://";
        $url .= $url_orig["host"].$url_orig["path"];

        $query = false;
        if (isset($url_orig["query"]))
        {
            $url .= "?" . $url_orig["query"];
            $query = true;
        }

        if (count($params))
        {
            $url .= $query ? "&" : "?";
            $i = 0;
            foreach ($params as $key=>$value)
            {
                $url .= $key."=".$value;
                ++$i;

                if ($i < count($params))
                    $url .= "&";
            }
        }

        return $url;
    }
}

?>
