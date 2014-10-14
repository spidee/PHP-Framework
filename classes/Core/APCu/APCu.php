<?php

class APCu implements UserCache
{
	private $timeToLive = 0;
	
	public function APCu()
	{
		if (!extension_loaded('apcu'))
			throw new CustomException("APCu enabled, but extension doesn't exists!!");
	}
	
	public function __get($Name)
	{
		return $this->FetchVariable($Name);
	}
	
	public function __set($Name, $Value)
	{
		$this->StoreVariable($Name, $Value, $this->timeToLive);
	}
	
	public function SetTimeToLive($Seconds)
	{
		$this->timeToLive = $Seconds;
	}
	public function StoreVariable($Name, $Value, $TimeToLive)
	{
		return apc_store($Name, $Value ,$TimeToLive);
	}
	public function FetchVariable($Name)
	{
		return apc_fetch($Name);
	}
}

?>
