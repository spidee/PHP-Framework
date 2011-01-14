<?php

class User extends BaseClass 
{

    protected $tableName = TBL_USERS;

    function __construct($in = NULL)
    {
        parent::__construct($in);
    }
   
}

?>
