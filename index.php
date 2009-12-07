<?php

require_once('config.inc.php');
require_once('mumble.inc.php');
require_once('../../Smarty/Smarty.class.php');
$Config=new CConfigUnreg();
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
	global $Config;
	foreach($Tree->users AS $User)
	{
		if($Config->ShowTalking)
			$UsersAddon[$User->session]['isTalking']=$User->idlesecs<IS_TALKING_IDLE_TIME;

		if($Config->ShowSessionID)
			$UsersAddon[$User->session]['InfoData']['SessionID']=$User->session;
		if($Config->ShowUserID)
			$UsersAddon[$User->session]['InfoData']['UserID']=$User->userid;
		if($Config->ShowOnlineSecs)
			$UsersAddon[$User->session]['InfoData']['OnlineSecs']=$User->onlinesecs;
		if($Config->ShowBytesPerSec)
			$UsersAddon[$User->session]['InfoData']['BytesPerSec']=$User->bytespersec;
		if($Config->ShowVersion)
			$UsersAddon[$User->session]['InfoData']['Version']=$User->release.' ('.$User->version.')';
		if($Config->ShowOS)
			$UsersAddon[$User->session]['InfoData']['OS']=$User->os.' '.$User->osversion;
		if($Config->ShowOnlyTCP)
			$UsersAddon[$User->session]['InfoData']['Only TCP']=$User->tcponly ? 'true' : 'false';
		if($Config->ShowIdleSecs)
			$UsersAddon[$User->session]['InfoData']['IdleSecs']=$User->idlesecs;
		if($Config->ShowOnlineTime)
			$UsersAddon[$User->session]['InfoData']['IdleTime']=ConvertSecondsToHMS($User->idlesecs);
		if($Config->ShowIdleTime)
			$UsersAddon[$User->session]['InfoData']['OnlineTime']=ConvertSecondsToHMS($User->onlinesecs);
		if($Config->ShowComment && $User->comment!='')
			$UsersAddon[$User->session]['InfoData']['Comment']=$User->comment;

		if(!$Config->ShowReg)
			$User->userid=-1;
		if(!$Config->ShowMute)
			$User->mute=0;
		if($Config->ShowMute==2 && $User->mute)
		{
			$User->mute=0;
			$User->selfMute=1;
		}
		if(!$Config->ShowSuppress)
			$User->suppress=0;
		if($Config->ShowSuppress==2 && $User->suppress)
		{
			$User->suppress=0;
			$User->selfMute=1;
		}
		if(!$Config->ShowSelfMute)
			$User->selfMute=0;
		if(!$Config->ShowDeaf)
			$User->deaf=0;
		if($Config->ShowDeaf==2 && $User->deaf)
		{
			$User->deaf=0;
			$User->selfDeaf=1;
		}
		if(!$Config->ShowSelfDeaf)
			$User->selfDeaf=0;
	}
	foreach($Tree->children AS $NewTree)
	{
		ConvertTreeForOutput($NewTree,$UsersAddon);
	}
}

$Smarty=new Smarty;
header('Content-Type: text/html; charset=UTF-8');
$Smarty->security=true;
$Smarty->security_settings['INCLUDE_ANY']=TRUE;
$Smarty->security_settings['PHP_TAGS']=FALSE;
$Smarty->php_handling=SMARTY_PHP_PASSTHRU;
$Smarty->debbuging=false;
$Smarty->compile_check=true;
$Smarty->caching=0;
if(defined('DEBUG') AND TRUE==DEBUG)
{
	$Smarty->force_compile = true;
	$Smarty->debugging_ctrl  =  'URL';
	$Smarty->debbuging=true;
}
elseif(defined('CACHE') AND CACHE>0)
{
	$Smarty->compile_check=false;
	$Smarty->caching=1;
	$Smarty->cache_lifetime=CACHE;
	if($Smarty->is_cached('main.tpl'))
	{
		$Smarty->display('main.tpl');
		exit();
	}
}
$Smarty->assign('SELF',$_SERVER['REQUEST_URI']);

Ice_loadProfile('Mumble12');
//Ice_dumpProfile();
$Mumble=new CMumble();
$Mumble->Init();

$Servers=$Mumble->GetServers();
/*echo '<pre>';
echo "Servers:\n";
print_r($Servers);

echo "Default:\n";
print_r($Mumble->GetDefaultConf());

echo "Version:\n";
print_r($Mumble->GetVersion());
echo '</pre>';*/

$Serv=$Servers[0];

$Tree=$Serv->GetTree();
$UsersAddon=array();
ConvertTreeForOutput($Tree,$UsersAddon);


//PrintLayer($Tree);
//$Serv->SendChannelMessage(0,true,"Test-Msg");

//$Smarty->assign('RawDebug','<pre>'.print_r($Tree,true).'</pre>');
$Smarty->assign('Tree',$Tree);
$Smarty->assign('UsersAddon',$UsersAddon);
$Smarty->display('main.tpl');
?>
