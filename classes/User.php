<?php

class User extends BaseClass 
{

    protected $tableName = TBL_USERS;
    
    public static function hashPassword($password)
    {
        return sha1($password);
    }
    
    public static function createPassword($length = 10)
    {
        $symbols = array_merge(range('a', 'zz'), range('A','ZZ'), range(0,9));
        
        $keys = array_rand($symbols, $length);
        $password = "";
        
        foreach($keys as $key)
          $password .= $symbols[$key];
        
        return $password;
    }
    
    public static function createUserName($email)
    {
        /*
        $email = explode("@", $email);
        return $email[0];
        */
        return $email;
    }
    
    public static function getLoggedUser()
    {
        $session = getSession();
        return $session->user; 
    }
    
    public function canEditOrderForm()
    {
        return $this->canChangeOrderForm && $this->canChangeOrderForm == DB_ENUM_TRUE;
    }
    
    public static function isLogged()
    {
        return (bool)self::getLoggedUser();
    }
    
    public static function logout()
    {
        $session = getSession();
        $session->user = null;
        unset ($session->user);
    }
    
    
   
}

?>
