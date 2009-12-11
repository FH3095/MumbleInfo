<?php

require_once('config.inc.php');
Ice_loadProfile(ICE_PROFILE);

require_once('mumble.inc.php');
require_once('mumble_info.inc.php');
require_once('../../Smarty/Smarty.class.php');
$Config=new CConfigUnreg();


//Ice_dumpProfile();
$Mumble=new CMumble();
$Mumble->Init();

/*echo '<pre>';
echo "Servers:\n";
print_r($Servers);

echo "Default:\n";
print_r($Mumble->GetDefaultConf());

echo "Version:\n";
print_r($Mumble->GetVersion());
echo '</pre>';*/

$Smarty=new Smarty;
header('Content-Type: text/html; charset=UTF-8');

$MumbleInfo=new CMumbleInfo();
$MumbleInfo->InitOutput($Smarty,$Config);
$MumbleInfo->DoOutput($MumbleInfo->GetServByArrayPos($Mumble,0),$Mumble);

//PrintLayer($Tree);
//$Serv->SendChannelMessage(0,true,"Test-Msg");

//$Smarty->assign('RawDebug','<pre>'.print_r($Tree,true).'</pre>');
?>
