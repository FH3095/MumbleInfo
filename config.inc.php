<?php
ERROR_REPORTING(E_ALL);
define('DEBUG',TRUE);
define('CACHE',FALSE);

define('IS_TALKING_IDLE_TIME',1);
class CConfigUnreg {
	var $ShowTalking=0;

	var $ShowReg=1;
	var $ShowMute=2; // 1=Show, 2=Show as self muted
	var $ShowSuppress=2; // 1=Show, 2=Show as self muted
	var $ShowSelfMute=1;
	var $ShowDeaf=2; // 1=Show, 2=Show as self deafened
	var $ShowSelfDeaf=1;

	var $ShowSessionID=1;
	var $ShowUserID=1;
	var $ShowOnlineSecs=0;
	var $ShowBytesPerSec=1;
	var $ShowVersion=1;
	var $ShowOS=1;
	var $ShowOnlyTCP=1;
	var $ShowIdleSecs=0;
	var $ShowOnlineTime=1;
	var $ShowIdleTime=1;
	var $ShowComment=1;
}

class CConfigReg {

}

class CConfigAdmin {
}
?>
