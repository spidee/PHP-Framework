<?php

class User extends BaseClass {
    
    protected $_name = TBL_USERS;
    
    function __construct($in = NULL)
    {
        parent::__construct($in);    
    }    
   
}

?>
