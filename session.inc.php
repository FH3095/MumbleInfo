<?php

class CSession
{
	var $UserID=-1;
	var $UserName='';

	function GetUserID()
	{	return $this->UserID;	}
	function GetUserName()
	{	return $this->UserName;	}

	function Start()
	{
		if(defined('SESSION_NAME') AND SESSION_NAME!='')
		{
			session_name(SESSION_NAME);
		}
		session_set_cookie_params(SESSION_DURATION);
		session_start();
	}

	function Stop()
	{
		session_write_close();
	}

	function CheckLogin(&$Server,&$SQL,$User='',$PW='')
	{
		if(isset($_SESSION['sid']))
		{
			$Res=$SQL->Query('SELECT userid,username FROM '.SQL_PREFIX.'_session '.
							 'WHERE sess_id=\''.$DB->Escape($_SESSION['sid']).'\' AND '.
							 'TIMESTAMPDIFF(SECOND,last_access,CURRENT_TIMESTAMP)<\''.SESSION_DURATION.'\'');
			if(!$Res OR $SQL->NumRows($Res)>1)
			{	return $DB->Error();	}
			$Row=$SQL->Fetch($Res);
			$this->UserID=$Row['userid'];
			$this->UserName=$Row['username'];
			$SQL->Free($Res);
			$SQL->Query('UPDATE '.SQL_PREFIX.'_session SET last_access=CURRENT_TIMESTAMP '.
						'WHERE sess_id=\''.$_SESSION['sid'].'\'');
		}
		elseif(!empty($User))
		{
			$User=str_replace('%','\\%',$User);
			$Res=$SQL->Query('SELECT ID,username FROM '.SQL_PREFIX.'_user '.
							 'WHERE name LIKE '.$DB->Escape($User).' AND '.
							 'pw=SHA1('.$DB->Escape($PW).')');
			if(!$Res OR $SQL->NumRows($Res)>1)
			{	return $DB->Error();	}
			if($SQL->NumRows($Res)<1)
			{	return false;	}
			$Row=$SQL->Fetch($Res);
			$this->UserID=$Row['ID'];
			$this->UserName=$Row['username'];
			$SQL->Free($Res);

			$SQL->Query('DELETE FROM '.SQL_PREFIX.'_session WHERE '.
						'userid=\''.$Row['ID'].'\'');
			$Res=$SQL->Error();
			if($Res['code']!=0)
			{	return $Res;	}

			$SessID=MakeSessID($PW);
			$SQL->Query('INSERT INTO '.SQL_PREFIX.'_session (sess_id,userid,username) VALUES ('.
						'\''.$SessID.'\',\''.$Row['ID'].'\',\''.$Row['username'].'\')');
			$Res=$SQL->Error();
			if($Res['code']!=0)
			{	return $Res;	}


			$_SESSION['sid']=$SessID;
		}
		else
		{
			return false;
		}
		return true;
	}

	function MakeSessID($PW)
	{
		return sha1($PW.microtime());
	}
};
?>
