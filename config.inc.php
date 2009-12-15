<?php
ERROR_REPORTING(E_ALL);
define('DEBUG',TRUE);
define('CACHE',FALSE);

define('ICE_PROFILE','Mumble12');
define('SMARTY_CLASS','../../Smarty/Smarty.class.php');

define('SESSION_NAME','MUMBLE_INFO_SESS_ID');
define('IS_TALKING_IDLE_TIME',1);
define('SHOW_LOGIN_LINK',TRUE);
define('VIEWER_ONLY',FALSE); // Just for security, doesn't produce any visual difference
define('SHOW_SERVER',0); // Positive Value->Search by ID, negative Value or 0->Search by ArrayPos

class CStdConfig
{
	var $ShowTalking=1;

	var $ShowReg=1;
	var $ShowMute=1; // 1=Show, 2=Show as self muted
	var $ShowSuppress=1; // 1=Show, 2=Show as self muted
	var $ShowSelfMute=1;
	var $ShowDeaf=1; // 1=Show, 2=Show as self deafened
	var $ShowSelfDeaf=1;

	var $ShowSessionID=0;
	var $ShowUserID=0;
	var $ShowOnlineSecs=0;
	var $ShowBytesPerSec=1;
	var $ShowVersion=0;
	var $ShowOS=0;
	var $ShowOnlyTCP=1;
	var $ShowIdleSecs=0;
	var $ShowOnlineTime=1;
	var $ShowIdleTime=1;
	var $ShowComment=1;

	var $AllowLogin=1; // Is login allowed (only applies to CConfigReg and CConfigAdmin)

	var $ShowServerVersion=1; // Show server-version on page?

	var $CacheID='';
};

class CConfigUnreg extends CStdConfig {
	var $CacheID='unreg';
}

class CConfigReg extends CStdConfig {
	var $CacheID='reg';
}

class CConfigAdmin extends CStdConfig {
	var $ShowTalking=1;

	var $ShowReg=1;
	var $ShowMute=1;
	var $ShowSuppress=1;
	var $ShowSelfMute=1;
	var $ShowDeaf=1;
	var $ShowSelfDeaf=1;

	var $ShowSessionID=1;
	var $ShowUserID=1;
	var $ShowOnlineSecs=1;
	var $ShowBytesPerSec=1;
	var $ShowVersion=1;
	var $ShowOS=1;
	var $ShowOnlyTCP=1;
	var $ShowIdleSecs=1;
	var $ShowOnlineTime=1;
	var $ShowIdleTime=1;
	var $ShowComment=1;

	var $CacheID='admin';
}
?>
