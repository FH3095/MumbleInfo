<?php

require_once('config.inc.php');
Ice_loadProfile(ICE_PROFILE);

require_once('mumble.inc.php');
require_once('mumble_info.inc.php');
require_once(SMARTY_CLASS);

$Smarty=new Smarty;
$Smarty->assign('SELF',$_SERVER['REQUEST_URI']);
header('Content-Type: text/html; charset=UTF-8');
//Ice_dumpProfile();
$Mumble=new CMumble();
$Mumble->Init();
$Mumble->LoadServers();
$Action=isset($_GET['do']) ? $_GET['do'] : '';

$Config=null;
$Session=null;
if(VIEWER_ONLY==false)
{
	require_once('session.inc.php');

	$Session=new CSession();
	$Session->Start();
	if($Action=='login')
	{
		if(isset($_POST['post_data']) AND 1==$_POST['post_data'])
		{
		}
		else
		{
			$Smarty->display('login.tpl');
		}
	}
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
	$Server=$Mumble->GetServByID(SHOW_SERVER);
}
else
{
	$Server=$Mumble->GetServByArrayPos(-SHOW_SERVER);
}

$Smarty->assign('RawDebug','<pre>MemUsage: '.memory_get_usage().'<br>RealMemUsage: '.memory_get_usage(true).'</pre>');
if(VIEWER_ONLY!==true)
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
