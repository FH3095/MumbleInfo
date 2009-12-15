<?php

require_once('config.inc.php');
Ice_loadProfile(ICE_PROFILE);

require_once('mumble.inc.php');
require_once('mumble_info.inc.php');
require_once(SMARTY_CLASS);

$Smarty=new Smarty;
header('Content-Type: text/html; charset=UTF-8');
//Ice_dumpProfile();
$Mumble=new CMumble();
$Mumble->Init();
$Mumble->LoadServers();


$Config=null;
if(VIEWER_ONLY===FALSE)
{
	if(SESSION_NAME!='')
	{
		session_name(SESSION_NAME);
	}
	session_start();
	/*if(SHOW_LOGIN_LINK)
	{
		$Smarty->assign('LoginLink','Login');
	}
	if(isset($_SESSION['LoggedIn']) AND $_SESSION['LoggedIn']==1)
	{
		$Config=new CConfigReg();
	}*/
}
if($Config==null)
{
	$Config=new CConfigUnreg();
}


/*echo '<pre>';
echo "Servers:\n";
print_r($Servers);

echo "Version:\n";
print_r($Mumble->GetVersion());
echo '</pre>';

echo "Default:\n";
print_r($Mumble->GetDefaultConf());*/

$MumbleInfo=new CMumbleInfo();
$MumbleInfo->InitOutput($Smarty,$Config);

$Server=null;
if(SHOW_SERVER>0)
{
	$Server=$MumbleInfo->GetServByID($Mumble,SHOW_SERVER);
}
else
{
	$Server=$MumbleInfo->GetServByArrayPos($Mumble,SHOW_SERVER);
}

$Smarty->assign('RawDebug','<pre>MemUsage: '.memory_get_usage().'<br>RealMemUsage: '.memory_get_usage(true).'</pre>');
if(VIEWER_ONLY!==TRUE)
{
	$MumbleInfo->DoOutput($Server,$Mumble,false);
	$Smarty->display('main.tpl');
}
else
{
	$MumbleInfo->DoOutput($Server,$Mumble);
}
//$Serv->SendChannelMessage(0,true,"Test-Msg");
?>
