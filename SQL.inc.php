<?php

class CSQL
{
	var $Conn=false;
	var $QueryRes=false;

	function Connect($Server,$User,$PW,$DB,$Port=false,$Persistance=false,$NewLink=false)
	{
		if($Port!==true)
		{
			$Server.=':'.$Port;
		}

		$this->Conn = ($Persistance) ? @mysql_pconnect($Server, $User, $PW, $NewLink) :
									   @mysql_connect ($Server, $User, $PW, $NewLink);
		if(!$this->Conn)
		{	return $this->Error();	}
		@mysql_query("SET NAMES 'utf8'", $this->Conn);
		if($DB!="")
		{
			if(!@mysql_select_db($DB,$this->Conn))
			{
				$Error=$this->Error();
				$this->Close();
				return $Error;
			}
		}
		return true;
	}

	function IsConnected()
	{
		return $this->Conn!=false;
	}

	function Close()
	{
		if($this->QueryRes)
		{	$this->Free();	}
		$Ret=@mysql_close($this->Conn);
		$this->Conn=false;
		return $Ret;
	}

	function SelectDB($DB)
	{
		return @mysql_select_db($DB,$this->Conn);
	}

	function Error()
	{
		if(!$this->Conn)
		{
			return array('msg'=>@mysql_error(),'code'=>@mysql_errno());
		}
		return array('msg'=>@mysql_error($this->Conn),'code'=>@mysql_errno($this->Conn));
	}

	function Escape($Str)
	{
		if(!$this->Conn)
		{	return @mysql_real_escape_string($Str);	}
		return @mysql_real_escape_string($Str,$this->Conn);
	}

	function GetInsertID()
	{
		if(!$this->Conn)
		{	return false;	}
		return @mysql_insert_id($this->Conn);
	}

	function Free($QueryRes=false)
	{
		$Ret=@mysql_free_result($QueryRes===false ? $this->QueryRes : $QueryRes);
		if($QueryRes===false)
		{	$this->QueryRes=false;	}
		return $Ret;
	}

	function AffectedRows()
	{
		if(!$this->Conn)
		{	return false;	}
		return @mysql_affected_rows($this->Conn);
	}

	function Query($Str)
	{
		if($Str=='' OR !$this->Conn)
		{	return false;	}
		$this->QueryRes=@mysql_query($Str,$this->Conn);
		return $this->QueryRes;
	}

	function Fetch(&$QueryRes)
	{
		if((!$QueryRes && !$this->QueryRes) || !$this->Conn)
		{	return false;	}
		elseif(!$QueryRes)
		{	$QueryRes=$this->QueryRes;	}
		return @mysql_fetch_assoc($QueryRes);
	}

	function SeekRow($RowNum,&$QueryRes)
	{
		if((!$QueryRes && !$this->QueryRes) || !$this->Conn)
		{	return false;	}
		elseif(!$QueryRes)
		{	$QueryRes=$this->QueryRes;	}
		return @mysql_data_seek($QueryRes,$RowNum);
	}

	function NumRows($QueryRes=false)
	{
		if((!$QueryRes && !$this->QueryRes) || !$this->Conn)
		{	return false;	}
		elseif(!$QueryRes)
		{	$QueryRes=$this->QueryRes;	}
		return @mysql_num_rows($QueryRes);
	}

	function NumFields($QueryRes=false)
	{
		if((!$QueryRes && !$this->QueryRes) || !$this->Conn)
		{	return false;	}
		elseif(!$QueryRes)
		{	$QueryRes=$this->QueryRes;	}
		return @mysql_num_fields($QueryRes);
	}

	function ServerInfo()
	{
		if(!$this->Conn)
		{	return false;	}
		$Res=@mysql_query("SELECT VERSION() AS version",$this->Conn);
		$Row=@mysql_fetch_assoc($Res);
		@mysql_free_result($Res);
		return $Row['version'];
	}

}

?>
