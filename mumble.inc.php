<?php
require_once('server.inc.php');
require_once('class_base.inc.php');

class CMumble extends CClassBase
{
	var $Base=null;
	var $Meta=null;
	var $Servers=null;

	function &GetBaseRef()
	{ return $this->Base; }
	function &GetMetaRef()
	{ return $this->Meta; }
	function &GetServersRef()
	{ return $this->Servers; }

	function Init($Host='127.0.0.1',$Port=6502,$ProxyParams='')
	{
		global $ICE;
		$this->Base = $ICE->stringToProxy('Meta:tcp -h '.$Host.' -p '.$Port.' '.$ProxyParams);
		$this->Meta = $this->Base->ice_checkedCast('::Murmur::Meta');
	}

	function LoadServers($OnlyBooted=true)
	{
		$this->Servers=array();

		$RawServers=null;
		if($OnlyBooted)
		{
			$RawServers=$this->Meta->getBootedServers();
		}
		else
		{
			$RawServers=$this->Meta->getAllServers();
		}
		$i=0;
		foreach($RawServers AS $CurServer)
		{
			$this->Servers[$i]=new CServer();
			$this->Servers[$i]->Init($CurServer,$this);
			$i++;
		}
	}

	function &GetServByID($ID)
	{
		settype($ID,'int');
		foreach($this->Servers AS $Serv)
		{
			if($Serv->GetID()==$ID)
			{	return $Serv;	}
		}
		return $this->GetNullRef();
	}

	function &GetServByArrayPos($Pos)
	{
		settype($Pos,'int');
		if(isset($this->Servers[$Pos]))
		{
			return $this->Servers[$Pos];
		}
		return $this->GetNullRef();
	}

	function NewServer($AddToArray=true)
	{
		$MumbleServer=$this->Meta->newServer();
		if(!$MumbleServer)
		{	return null;	}
		$Server=new CServer();
		$Server->Init($MumbleServer,$this);
		if($AddToArray)
		{	$this->Servers[]=$Server;	}
		return $Server;
	}

	function GetDefaultConf()
	{
		return $this->Meta->getDefaultConf();
	}

	function GetVersion()
	{
		$Ret=array('major'=>0,'minor'=>0,'patch'=>0,'text'=>'');
		$this->Meta->getVersion($Ret['major'],$Ret['minor'],$Ret['patch'],$Ret['text']);
		return $Ret;
	}
};
?>
