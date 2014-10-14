<?php

class Cache
{
	private $CacheClassName;
	
	public function __construct($CacheClassName)
	{
		if (!USE_CACHE || !$CacheClassName)
			return;
		
		if (!class_exists($CacheClassName))
			throw new CustomException("Cache class '{$CacheClassName}' doesn't exists!!", E_ERROR, __FILE__, __LINE__);
			
		$this->CacheClassName = $CacheClassName;
	}
	
	/**
	* @returns UserCache
	*/ 
	public function GetUserCache()
	{
		if ($this->CacheClassName && $object = new $this->CacheClassName())
			if ($object instanceOf UserCache)
				return $object;
		
		return null;
	}
}

interface UserCache
{
	public function SetTimeToLive($Seconds);
	public function StoreVariable($Name, $Value, $TimeToLive);
	public function FetchVariable($Name);
	
	public function __get($Name);	
	public function __set($Name, $Value);
}

?>
