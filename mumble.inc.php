<?php
require_once('server.inc.php');

class CMumble
{
	var $Base=null;
	var $Meta=null;
	var $Servers=null;

	function GetBase()
	{ return $this->Base; }
	function GetMeta()
	{ return $this->Meta; }
	function GetServers()
	{ return $this->Servers; }

	function Init($Host='127.0.0.1',$Port=6502,$OnlyBooted=true,$ProxyParams='')
	{
		global $ICE;
		$this->Base = $ICE->stringToProxy('Meta:tcp -h '.$Host.' -p '.$Port.' '.$ProxyParams);
		$this->Meta = $this->Base->ice_checkedCast('::Murmur::Meta');

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
			$this->Servers[$i]->Init($CurServer);
			$i++;
		}
	}

	function GetServer($ID)
	{
		settype($ID,'int');
		$MumbleServer=$this->Meta->getServer($ID);
		if(!$MumbleServer)
		{	return null;	}
		$Server=new CServer();
		$Server->Init($MumbleServer);
		return $Server;
	}

	function NewServer()
	{
		$MumbleServer=$this->Meta->newServer();
		if(!$MumbleServer)
		{	return null;	}
		$Server=new CServer();
		$Server->Init($MumbleServer);
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
