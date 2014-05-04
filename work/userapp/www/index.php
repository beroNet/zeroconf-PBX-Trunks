<?php


function get_userapp_name () {
	$ret = "Unknown UserAppFS";
	if (($fp = fopen("/apps/asterisk/VERSION", "r"))) {
		$buf = fread($fp, 1024);
		fclose($fp);
		if (preg_match("/NAME=.+\n/", $buf, $matches)) {
			$ret = trim(substr($matches[0], strpos($matches[0], '=') + 1), "\"\n");
		}
	}
	return($ret);
}
$userapp_n	= get_userapp_name();

if ($_GET['action'] == "reload" ) {
    exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "core reload"');
}

exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "sip show peers" | sed "s/Dyn Forcerport ACL//" | sed "s/ D //" | sed "s/ N //" | sed "s/OK (\(.*\) ms)/OK-(\1_ms)/"  | grep -v "Monitored:" | sed "s/[ ]*/<\/td><td>/g"  | sed "s/^<td>//" | sed "s/^<\/td>//" | sed "s/<td>$//"', $tmppeers);
$sippeers=implode("<tr></tr>", $tmppeers);
$tmppeers="";
exec('/apps/asterisk/bin/asterisk -C /apps/asterisk/etc/asterisk/asterisk.conf -rnx "iax2 show peers" | sed "s/ (S) //" | sed "s/OK (\(.*\) ms)/OK-(\1_ms)/"  | grep -v "unmonitored" | sed "s/[ ]*/<\/td><td>/g"  | sed "s/^<td>//" | sed "s/^<\/td>//" | sed "s/<td>$//"', $tmppeers);
$iaxpeers=implode("<tr></tr>", $tmppeers);

?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
 <head>
  <link type="text/css" href="./include/css/berofog.css" rel="Stylesheet" />
  <title><?php echo $userapp_n ?> </title>
 </head>

 <body>
  <div class='main'>
  <div class='top'><img src="./include/images/bg_top.png"/></div>
  <div class='left'>

  <h1> <?php echo $userapp_n ?>  </h1>
  <hr noshade/>
  <div>Go to: 
   <table><tr>
    <td><a href="/app/berogui/">berogui</a></td>
    <td><a href="filemanager.php">Asterisk Configuration</a></td>
    <td><a href="index.php?action=reload">Reload Configuration</a></td>
    </tr>
   </table>
  </div>

  <h2>zeroconf-PBX-trunks</h2>
  <div>You have two possibilities for setting up a trunk, depending on your provider.<br>
  <br>If you use the trunk only for outgoing calls, you have to setup [trunk1iax-gw] context for IAX2 protocol,<br>
  [username] context for SIP protocol.<br><br>
  Datas are given by your provider, you need at least username and hostname or IP to connect to it, additional secret for SIP protocol.<br>
  For the last one, use the sip_general.conf file to enter your register credencials.<br><br>

  <b>In all cases you need to adapt extensions_globals.conf file to mark your provider as preferred gateway</b><br><br>

  If you want your trunk to also accept incoming calls, in IAX you have to set datas in context [trunkiax1]<br>
  which are IP or hostname of provider server and the authorized IPs.<br><br>

  For SIP, if you are registered using sip_general.conf, everything should be already OK.<br>
<pre>
extensions.conf
=============== 
[globals](+)
TRUNK1=trunkiax1-gw ;or username
TECH1=IAX2 ;or SIP

[incoming-trunk]
include => local
;
; sample for incoming SIP calls from provider
;
exten => username,1,NoOp(Incoming call from Provider)
 same => n,Dial(SIP/10&SIP/20,,t)

sip.conf
========
[username]; to replace with the username given by your provider
host=IP or hostname of your provider
secret=secret given by your provider

sip_general.conf
================
register => username:secret@provider_IP_or_hostname/username

iax.conf
========
[trunkiax1-gw]
username=given by your provider
host=IP or hostname of your provider

[trunkiax1]
host=IP or hostname of your provider
permit=provider IP
</pre>

  <br><b>SIP Status:</b><br>
  <table width=100%>
   <?php echo $sippeers ?>
  </table>
  <br><b>IAX Status:</b><br>
 <table width=100%>
   <?php echo $iaxpeers ?>
  </table>
 </div>
 </div>
 <div class='bottom'><img src="./include/images/bg_bottom.png"></div>
 </div>
 </body>
</html>
