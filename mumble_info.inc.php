<?php

class CMumbleInfo
{
	var $Config=null;
	var $Smarty=null;

	/*
	usort($Tree->children,"TreeChildrenCmp");
	function TreeChildrenCmp($a,$b)
	{
		if($a->c->position < $b->c->position)
		{	return -1;	}
		if($a->c->position > $b->c->position)
		{	return 1;	}

		return strcmp($a->c->name,$b->c->name);
	}

	function ChannelUsersCmp($a,$b)
	{
	}
	*/

	function ConvertSecondsToHMS($Seconds,$LeadingZero=true)
	{
		$Hours=floor($Seconds/3600);
		$Seconds-=$Hours*3600;
		$Minutes=floor($Seconds/60);
		$Seconds-=$Minutes*60;
		return str_pad($Hours,2,'0',STR_PAD_LEFT).':'.str_pad($Minutes,2,'0',STR_PAD_LEFT).
			':'.str_pad($Seconds,2,'0',STR_PAD_LEFT);
	}

	function ConvertTreeForOutput(&$Tree,&$UsersAddon)
	{
		foreach($Tree->users AS $User)
		{
			if($this->Config->ShowTalking)
				$UsersAddon[$User->session]['isTalking']=$User->idlesecs<IS_TALKING_IDLE_TIME;

			if($this->Config->ShowSessionID)
				$UsersAddon[$User->session]['InfoData']['SessionID']=$User->session;
			if($this->Config->ShowUserID)
				$UsersAddon[$User->session]['InfoData']['UserID']=$User->userid;
			if($this->Config->ShowOnlineSecs)
				$UsersAddon[$User->session]['InfoData']['OnlineSecs']=$User->onlinesecs;
			if($this->Config->ShowBytesPerSec)
				$UsersAddon[$User->session]['InfoData']['BytesPerSec']=$User->bytespersec;
			if($this->Config->ShowVersion)
				$UsersAddon[$User->session]['InfoData']['Version']=$User->release.' ('.$User->version.')';
			if($this->Config->ShowOS)
				$UsersAddon[$User->session]['InfoData']['OS']=$User->os.' '.$User->osversion;
			if($this->Config->ShowOnlyTCP)
				$UsersAddon[$User->session]['InfoData']['Only TCP']=$User->tcponly ? 'true' : 'false';
			if($this->Config->ShowIdleSecs)
				$UsersAddon[$User->session]['InfoData']['IdleSecs']=$User->idlesecs;
			if($this->Config->ShowOnlineTime)
				$UsersAddon[$User->session]['InfoData']['IdleTime']=$this->ConvertSecondsToHMS($User->idlesecs);
			if($this->Config->ShowIdleTime)
				$UsersAddon[$User->session]['InfoData']['OnlineTime']=$this->ConvertSecondsToHMS($User->onlinesecs);
			if($this->Config->ShowComment && $User->comment!='')
				$UsersAddon[$User->session]['InfoData']['Comment']=$User->comment;

			if(!$this->Config->ShowReg)
				$User->userid=-1;
			if(!$this->Config->ShowMute)
				$User->mute=0;
			if($this->Config->ShowMute==2 && $User->mute)
			{
				$User->mute=0;
				$User->selfMute=1;
			}
			if(!$this->Config->ShowSuppress)
				$User->suppress=0;
			if($this->Config->ShowSuppress==2 && $User->suppress)
			{
				$User->suppress=0;
				$User->selfMute=1;
			}
			if(!$this->Config->ShowSelfMute)
				$User->selfMute=0;
			if(!$this->Config->ShowDeaf)
				$User->deaf=0;
			if($this->Config->ShowDeaf==2 && $User->deaf)
			{
				$User->deaf=0;
				$User->selfDeaf=1;
			}
			if(!$this->Config->ShowSelfDeaf)
				$User->selfDeaf=0;
		}
		foreach($Tree->children AS $NewTree)
		{
			$this->ConvertTreeForOutput($NewTree,$UsersAddon);
		}
	}

	function InitOutput(&$Smarty,&$Config)
	{
		$Smarty->security=true;
		$Smarty->security_settings['INCLUDE_ANY']=TRUE;
		$Smarty->security_settings['PHP_TAGS']=FALSE;
		$Smarty->php_handling=SMARTY_PHP_PASSTHRU;
		$Smarty->debbuging=false;
		$Smarty->compile_check=true;
		$Smarty->caching=0;
		$Smarty->force_compile=false;
		if(defined('DEBUG') AND TRUE==DEBUG)
		{
			$Smarty->force_compile = true;
			$Smarty->debugging_ctrl  =  'URL';
			$Smarty->debbuging=true;
		}
		elseif(defined('CACHE') AND CACHE>0)
		{
			$Smarty->caching=1;
			$Smarty->cache_lifetime=CACHE;
			if($Smarty->is_cached('main.tpl',$Config->CacheID))
			{
				$Smarty->display('main.tpl',$Config->CacheID);
				exit();
			}
		}

		$Smarty->assign('SELF',$_SERVER['REQUEST_URI']);

		$this->Smarty=&$Smarty;
		$this->Config=&$Config;
	}

	function DoOutput(&$Serv,$Display=true)
	{
		if($Serv==null)
		{
			$this->Smarty->assign('NoServer',true);
		}
		else
		{
			$Tree=$Serv->GetTree();
			$UsersAddon=array();
			$this->ConvertTreeForOutput($Tree,$UsersAddon);
			$this->Smarty->assign('Tree',$Tree);
			$this->Smarty->assign('UsersAddon',$UsersAddon);
		}
		if($Display)
		{
			$this->Display();
		}
	}

	function Display()
	{
		$this->Smarty->display('main.tpl',$this->Config->CacheID);
	}

	function GetServByID(&$Mumble,$ID)
	{
		settype($ID,'int');
		$Servers=$Mumble->GetServers();
		foreach($Servers AS $Serv)
		{
			if($Serv->id()==$ID)
			{	return $Serv;	}
		}
		return null;
	}

	function GetServByArrayPos(&$Mumble,$Pos)
	{
		settype($Pos,'int');
		$Servers=$Mumble->GetServers();
		if(isset($Servers[$Pos]))
		{
			return $Servers[$Pos];
		}
		return null;
	}
};
?>
