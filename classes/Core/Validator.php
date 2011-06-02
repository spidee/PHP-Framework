<?php
/*
*   Author: Jan Krizak (krizak@gmail.com)
*   Version: 0.1
*   Last update: 28.4.2011
* 
*   TODO: add more rules
*/

class Validator
{
    private $dataToValidate;
    private $validationRules = array();
    private $errorMessages = array();
    
    const NOT_EMPTY_OR_NULL     = 1;
    const EMAIL                 = 2;
    const MUST_BE_STRING        = 4;
    const MUST_BE_NUMERIC       = 8;
    const MUST_CONTAIN_CHARS    = 16;
    const MIN_STRING_LENGTH     = 32;
    const MAX_STRING_LENGTH     = 64;
    const REGULAR_EXPRESSION    = 128;
    const EXACT_STRING_LENGTH   = 256;
    
    public function __construct($dataToValidate = null)
    {
        if ($dataToValidate)
            $this->setDataToValidate($dataToValidate);
    }
    
    public function setDataToValidate($dataToValidate = null)
    {
        $this->dataToValidate = $dataToValidate;
    }
    
    public function setRule($itemKey, $mode, $miscValue = null, $errorMsg = null)
    {
        if (!isset($this->validationRules[$itemKey]) || !is_array($this->validationRules[$itemKey]))
            $this->validationRules[$itemKey] = array();
        
        $object = new stdClass();
        $object->mode = $mode;
        $object->errorMsg = $errorMsg;
        $object->miscValue = $miscValue;
        $object->isValid = true; 
        
        array_push($this->validationRules[$itemKey], $object);
    }
    
    public function validate()
    {
        foreach($this->validationRules as $dataKey=>&$value)
        {  
            foreach($value as $key=>&$validationRule)
            {
                $valid = true;
                
                if ($validationRule->mode & self::NOT_EMPTY_OR_NULL)
                    $valid &= $this->getValidateDataValue($dataKey) != null;
                
                if ($validationRule->mode & self::EMAIL)
                    $valid &= $this->isValidEmail($this->getValidateDataValue($dataKey), $validationRule->miscValue);

                if ($validationRule->mode & self::MUST_BE_STRING)
                    $valid &= $this->isString($this->getValidateDataValue($dataKey), $validationRule->miscValue);
                    
                if ($validationRule->mode & self::MUST_BE_NUMERIC)
                    $valid &= $this->isNumeric($this->getValidateDataValue($dataKey), $validationRule->miscValue);

                if ($validationRule->mode & self::MIN_STRING_LENGTH)
                    $valid &= ($validationRule->miscValue && mb_strlen($this->getValidateDataValue($dataKey), "utf-8") >= (int)$validationRule->miscValue);

                if ($validationRule->mode & self::MAX_STRING_LENGTH)
                    $valid &= ($validationRule->miscValue && mb_strlen($this->getValidateDataValue($dataKey), "utf-8") <= (int)$validationRule->miscValue);
                
                if ($validationRule->mode & self::EXACT_STRING_LENGTH)
                    $valid &= ($validationRule->miscValue && mb_strlen($this->getValidateDataValue($dataKey), "utf-8") == (int)$validationRule->miscValue);

                if ($validationRule->mode & self::REGULAR_EXPRESSION)
                    $valid &= $this->regularExpressionValidation($this->getValidateDataValue($dataKey), $validationRule->miscValue);
                                             
                if (!$valid)
                    $this->setError($validationRule);
                        
            }
        }
        
        return $this->isValid();
        
    }
    
    public function getValidateDataValue($dataKey)
    {
        if (is_array($this->dataToValidate))
            return $this->dataToValidate[$dataKey];
            
        if (is_object($this->dataToValidate))
            return $this->dataToValidate->{$dataKey};
            
        return null;        
    }
    
    public function isValid()
    {
        $valid = true;
        
        foreach($this->validationRules as $dataKey=>&$value)
            foreach($value as $key=>&$validationRule)
                $valid &= $validationRule->isValid;
        
        return $valid;
    }
    
    private function setError(&$validationRule)
    {
        if ($validationRule->errorMsg)
            array_push($this->errorMessages, $validationRule->errorMsg);
            
        $validationRule->isValid = false;
    }
    
    public function getErrorMessages()
    {
        return $this->errorMessages; 
    }
    
    public static function isValidEmail($data, $miscValue = "")
    {    
        if ($data)
            return preg_match('(^[-a-z0-9!#$%&\'*+/=?^_`{|}~]+(\.[-a-z0-9!#$%&\'*+/=?^_`{|}~]+)*@([a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?\.)+[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])$)i', $data);
        
        return false;        
    }
    
    public static function isString($data, $miscValue = "")
    {    
        if ($data)
        {
            $data = iconv('utf-8', 'ascii//IGNORE', $data);
            
            if (!$data)
                return true;
            
            return preg_match("/^[[:alpha:]]+$/", $data);
        }
        
        return false;        
    }
    
    public static function isNumeric($data, $miscValue = "")
    {    
        if ($data)
            return preg_match("/^[0-9]+$/", $data);
        
        return false;        
    }
    
    public static function regularExpressionValidation($data, $miscValue = "")
    {    
        if ($data && $miscValue)
            return preg_match("/{$miscValue}/", $data);
        
        return false;        
    }    
    
    
     /*
    public static function isValid()
    {
        //TODO
    }
      */
}

?>
