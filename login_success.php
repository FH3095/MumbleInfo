<?
session_start();
if(!session_is_registered(myusername)){
header("location:index.php");
}
?>


<body ALINK="#000000" VLINK="#000000" LINK="#000000">

</body>


<?

Ice_loadProfile();

if ($argc > 0) {
  for($i=1;$i<$argc;$i++) {
    parse_str($argv[$i], $tmp);
    $_REQUEST=array_merge($_REQUEST, $tmp);
  }
}

function ucmp($a, $b) {
  if (($a->playerid == 0) || ($b->playerid == 0)) {
    return ($a->playerid - $b->playerid);
  }
  return strcasecmp($a->name, $b->name);
}

$confitems = array();
$confbig = array();
$confitems['host']="IP Address to bind to";
$confitems['port']="Port to use";
$confitems['password']="Password for unregistered users";
$confitems['timeout']="Timeout before kicking dead connections";
$confitems['bandwidth']="Maximum bandwidth in bytes/sec";
$confitems['users']="Maximum number of users on server";
$confitems['welcometext']="Welcome message";
$confbig['welcometext']=1;
$confitems['registername']="Server Name";
$confitems['registerpassword']="Password for Global Server List";
$confitems['registerhostname']="Hostname for Global Server List";
$confitems['registerurl']="HTTP URL for Global Server List";
$confitems['certificate']="PEM Encoded SSL Certificate";
$confbig['certificate']=1;
$confitems['key']="PEM Encoded SSL Key";
$confbig['key']=1;

try {
  $base = $ICE->stringToProxy("Meta:tcp -h 127.0.0.1 -p 6502");
  $meta = $base->ice_checkedCast("::Murmur::Meta");
  
  $default = $meta->getDefaultConf();

  if (! is_null($_REQUEST['newserver'])) {
    $meta->newServer();
  } else if (! is_null($_REQUEST['delserver'])) {
    $meta->getServer($_REQUEST['delserver'] + 0)->delete();
  } else if (! is_null($_REQUEST['stop'])) {
    $meta->getServer($_REQUEST['stop'] + 0)->stop();
  } else if (! is_null($_REQUEST['start'])) {
    $meta->getServer($_REQUEST['start'] + 0)->start();
  } else if (! is_null($_REQUEST['action'])) {
    $server = $meta->getServer($_REQUEST['action'] + 0);
    if (! is_null($_REQUEST['kick'])) {
      $server->kickPlayer($_REQUEST['kick'] + 0, "Mushroom");
    }
  } else if (! is_null($_REQUEST['uedit'])) {
    $server = $meta->getServer($_REQUEST['uedit'] + 0);
    if (isset($_REQUEST['newplayer'])) {
      $_REQUEST['uid'] = $server->registerPlayer($_REQUEST['newplayer']);
    }
    if (! is_null($_REQUEST['deleteplayer'])) {
      $server->unregisterPlayer($_REQUEST['deleteplayer'] + 0);
    }
    if (! is_null($_REQUEST['uid'])) {
      $user = $server->getRegistration($_REQUEST['uid'] + 0);
      if (! is_null($_REQUEST['set'])) {
        $user->email = $_REQUEST['email'];
        $user->pw = $_REQUEST['pw'];
        $server->updateRegistration($user);
      } else {
        echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
        echo "<p>\n";
        echo "<b>Name:</b> $user->name<br />\n";
        echo "<input type=\"hidden\" name=\"set\" value=\"1\" />\n";
        echo "<input type=\"hidden\" name=\"uedit\" value=\"".$server->id()."\" />\n";
        echo "<input type=\"hidden\" name=\"uid\" value=\"$user->playerid\" />\n";
        echo "<b>Email:</b> <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"128\" value=\"".htmlspecialchars($user->email)."\" /><br />\n";
        echo "<b>New password:</b> <input type=\"password\" name=\"pw\" size=\"30\" maxlength=\"128\" /><br />\n";
        echo "<input type=\"submit\" />\n";
        echo "</p>\n";
        echo "</form>\n";
      }
    }
    echo "<h1>Registered User List</h1>\n";
    echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
    echo "<p>\n";
    echo "<input type=\"hidden\" name=\"uedit\" value=\"".$server->id()."\" />\n";
    echo "<b>New User:</b>";
    echo "<input type=\"text\" name=\"newplayer\" size=\"30\" maxlength=\"60\" />";
    echo "<input type=\"submit\" />\n";
    echo "</p>\n";
    echo "</form>\n";
    echo "<table>\n";
    echo "<tr><th>UserName</th><th>Email</th><th></th></tr>\n";
    $users = $server->getRegisteredPlayers("");
    usort($users, "ucmp");
    foreach($users as $u) {
      echo "<tr><td>$u->name</td><td>".$u->email."</td><td>";
      echo "<a href=\"?uedit=".$server->id()."&amp;uid=".$u->playerid."\">[Edit]</a> ";
      echo "<a href=\"?uedit=".$server->id()."&amp;deleteplayer=".$u->playerid."\">[Unregister]</a> ";
      echo "</td></tr>\n";
    }
    echo "</table>\n";
  } else if (! is_null($_REQUEST['server'])) {
    $server = $meta->getServer($_REQUEST['server'] + 0);
    if (! is_null($_REQUEST['set'])) {
      foreach($confitems as $key=>$desc) {
        $server->setConf($key, $_REQUEST[$key]);
      }
    } else {
      $conf = $server->getAllConf();
      $default['port'] += $server->id() - 1;
      echo "<h1>Server Configuration</h1>\n";
      echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
      echo "<div>\n";
      echo "<input type=\"hidden\" name=\"server\" value=\"".$server->id()."\" />\n";
      echo "<input type=\"hidden\" name=\"set\" value=\"1\" />\n";
      echo "</div>\n";
      echo "<table><tr><th>Description</th><th>Default</th><th>Value</th></tr>\n";
      foreach($confitems as $key=>$desc) {
        if (! isset($confbig[$key])) {
          echo "<tr><td>$desc</td><td>".htmlspecialchars($default[$key])."</td><td>";
          echo "<input type=\"text\" name=\"$key\" size=\"30\" maxlength=\"64000\" value=\"".htmlspecialchars($conf[$key])."\" />";
          echo "</td></tr>\n";
        } else {
          echo "<tr><td>$desc</td><td colspan=\"2\">".htmlspecialchars($default[$key])."</td></tr>";
          echo "<tr><td>&nbsp;</td><td colspan=\"2\"><textarea rows=\"5\" cols=\"80\" name=\"$key\">".htmlspecialchars($conf[$key])."</textarea>";
          echo "</td></tr>\n";
        }
      }
      echo "</table>\n";
      echo "<div><input type=\"submit\" /></div>\n";
      echo "</form>\n";
    }
  }
  
  $servers = $meta->getAllServers();
  $booted = $meta->getBootedServers();
  
  echo "<h1>Murmur ICE Interface : Welcome</h1>\n";
  echo "</p>\n";
  echo "<p>\n";
  echo "<a href=\"?newserver\">[Start New Server]</a>\n";
  echo "</p>\n";
  foreach($servers as $s) {
    $name = $s->getConf("registername");
    if (! $name) {
      $name =  $default["registername"];
    }
    $id = $s->id();
    echo "<h1>SERVER #" . $id . " " .$name ."</h1>\n";
    echo "<p>\n";
    echo "<a href=\"?server=".$id."\">[Config]</a> ";
    if (in_array($s, $booted)) {
      echo "<a href=\"?uedit=".$id."\">[Manage Users]</a> ";
      echo "<a href=\"?stop=".$id."\">[Stop]</a> ";
    } else {
      echo "<a href=\"?delserver=".$id."\">[Delete Server]</a> ";
      echo "<a href=\"?start=".$id."\">[Start]</a> ";
    }
    echo "</p>\n";
    if (in_array($s, $booted)) {
      echo "<table><tr><th>Name</th><th>Channel</th><th>Actions</th></tr>\n";

      $channels = $s->getChannels();
      $players = $s->getPlayers();

      foreach($players as $pid => $state) {
        $chan = $channels[$state->channel];
        echo "<tr><td>".$state->name."</td><td>".$chan->name."</td><td>";
        echo "<a href=\"?action=".$id."&amp;kick=$state->session\">[Kick]</a>";
        echo "</td></tr>\n";
      }
      echo "</table>\n";
    }
  }
} catch (Ice_Exception $ex) {
  echo "<p>\n<pre>\n";
  print_r($ex);
  echo "</pre>\n</p>\n";
}


?>


<script language="javascript">
<!--

function NewPage() {
  document.location.href= "logout.php"
}

//-->
</script>

<body>

<input type="button" value="Logout" onClick="NewPage()">

</body></html>