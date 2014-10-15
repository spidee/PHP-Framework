<?php

$DB = new DataBase(DB_TYPE, $DB_SETTINGS);
$query = new QueryBuilder("SET NAMES 'UTF8'");
$DB->queryRawSql($query);

//$DB->setFetchMode(BaseClass::FETCH_ASSOC);

BaseClass::setDefaultAdapter($DB);

?>
