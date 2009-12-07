<?php

class CServer
{
	var $Server=null;

	function GetMumbleServer()
	{	return $this->Server;	}

	function Init($Server)
	{
		$this->Server=$Server;
	}

	function IsRunning()
	{
		return $this->Server->isRunning();
	}

	function Start()
	{
		$this->Server->start();
	}

	function Stop()
	{
		$this->Server->stop();
	}

	function Delete()
	{
		$this->Server->delete();
	}

	function GetID()
	{
		return $this->Server->id();
	}

	function GetConf($Key=0)
	{
		if($Key===0)
		{
			return $this->Server->getAllConf();
		}
		return $this->Server->getConf($Key);
	}

	function SetConf($Key,$Val)
	{
		$this->Server->setConf($Key,$Val);
	}

	function SetSuperuserPassword($PW)
	{
		$this->Server->setSuperuserPassword($PW);
	}

	function GetLog($Start=0,$NumMsgs=30)
	{
		settype($Start,'int');
		settype($NumMsgs,'int');
		return $this->Server->getLog($Start,$NumMsgs);
	}

	function GetConnectedUsers()
	{
		return $this->Server->getUsers();
	}

	function GetChannels()
	{
		return $this->Server->getChannels();
	}

	function GetTree()
	{
		return $this->Server->getTree();
	}

	function GetBans()
	{
		return $this->Server->getBans();
	}

	function SetBans($Bans)
	{
		$this->SetBans($Bans);
	}

	function AddBan($Ban)
	{
		$Bans=$this->GetBans();
		$Bans[]=$Ban;
		$this->SetBans($Bans);
	}

	function KickUser($Session,$Reason)
	{
		settype($Session,'int');
		$this->Server->kickUser($Session,$Reason);
	}

	function GetUserState($Session)
	{
		settype($Session,'int');
		return $this->Server->getState($Session);
	}

	function SetUserState($User)
	{
		$this->Server->setState($User);
	}

	function SendMessage($Session,$Text)
	{
		settype($Session,'int');
		$this->Server->sendMessage($Session,$Text);
	}

	function UserHasPermission($Session,$ChanID,$Perm)
	{
		settype($Session,'int');
		settype($ChanID,'int');
		settype($Perm,'int');
		return $this->Server->hasPermission($Session,$Chan,$Perm);
	}

	function GetChannelState($ChanID)
	{
		settype($ChanID,'int');
		return $this->Server->getChannelState($ChanID);
	}

	function SetChannelState($Chan)
	{
		$this->Server->setChannelState($Chan);
	}

	function AddChannel($ParentID,$Name)
	{
		settype($ParentID,'int');
		return $this->Server->addChannel($Name,$ParentID);
	}

	function RemoveChannel($ChanID)
	{
		settype($ChanID,'int');
		$this->Server->removeChannel($ChanID);
	}

	function SendChannelMessage($ChanID,$Tree,$Text)
	{
		settype($ChanID,'int');
		settype($Tree,'bool');
		$this->Server->sendMessageChannel($ChanID,$Tree,$Text);
	}

	function GetACL($ChanID)
	{
		settype($ChanID,'int');
		$Ret=array('acls'=>array(),'groups'=>array(),'inherit'=>false);
		$this->Server->getACL($ChanID,$Ret['acls'],$Ret['groups'],$Ret['inherit']);
		return $Ret;
	}

	function SetACL($ChanID,$ACLs,$Groups,$Inherit)
	{
		settype($ChanID,'int');
		settype($Inherit,'bool');
		$this->Server->setACL($ChanID,$ACLs,$Groups,$Inherit);
	}

	function AddUserToGroupTemp($ChanID,$Session,$Group)
	{
		settype($ChanID,'int');
		settype($Session,'int');
		$this->Server->addUserToGroup($ChanID,$Session,$Group);
	}

	function RemoveUserFromGroupTemp($ChanID,$Session,$Group)
	{
		settype($ChanID,'int');
		settype($Session,'int');
		$this->Server->removeUserFromGroup($ChanID,$Session,$Group);
	}

	function RedirectWhisper($Session,$OrigTarget,$NewTarget)
	{
		settype($Session,'int');
		$this->Server->redirectWhisperGroup($Session,$OrigTarget,$NewTarget);
	}

	function GetUserNames($Sessions)
	{
		return $this->Server->getUserNames($Sessions);
	}

	function GetUserSessions($Names)
	{
		return $this->Server->getUserIds($Names);
	}

	function RegisterUser($UserInfo)
	{
		return $this->Server->registerUser($UserInfo);
	}

	function UnregisterUser($UserID)
	{
		settype($UserID,'int');
		$this->Server->unregisterUser($UserID);
	}

	function UpdateRegistration($UserID,$UserInfo)
	{
		settype($UserID,'int');
		$this->Server->updateRegistration($UserID,$UserInfo);
	}

	function GetUserRegistration($UserID)
	{
		settype($UserID,'int');
		return $this->Server->getRegistration($UserID);
	}

	function GetRegistredUsers($NameFilter)
	{
		return $this->Server->getRegisteredUsers($NameFilter);
	}

	function VerifyUser($Name,$PW)
	{
		return $this->Server->verifyPassword($Name,$PW);
	}

	function GetTexture($UserID)
	{
		settype($UserID,'int');
		return $this->Server->getTexture($UserID);
	}

	function SetTexture($UserID,$Texture)
	{
		settype($UserID,'int');
		$this->Server->setTexture($UserID,$Texture);
	}
};
?>
