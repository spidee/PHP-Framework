<?php

$DB = new DataBase(DB_TYPE, $DB_SETTINGS);
$DB->query("SET NAMES 'UTF8'");
//$DB->setFetchMode(BaseClass::FETCH_ASSOC);

BaseClass::setDefaultAdapter($DB);

?>
