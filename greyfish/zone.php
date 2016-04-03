<?php
if (empty($_SERVER['HTTP_REFERER'])){die();}
include("inc/game_q.php");
use xPaw\SourceQuery\SourceQuery;
$Query = new SourceQuery( );

function url() 
{
	// output: /myproject/index.php
	$currentPath = $_SERVER['PHP_SELF']; 
    $pathInfo = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$hostName = $_SERVER['HTTP_HOST']; 
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
	
	// return: http://localhost/myproject/
	return $protocol.$hostName.$pathInfo."";
}
?>
<link href="<?php echo url();?>/style_zone.css" rel="stylesheet" type="text/css" media="screen, projection" />
<script src="<?php echo url();?>/js/fancybox/jquery.fancybox.pack.js"></script>
<script src="<?php echo url();?>/js/jcarousel.js"></script>

<?php 
include("../includes/config.php");
$getzone = mysqli_query($link,"SELECT * FROM greyfish_servers ORDER by type DESC");
if(mysqli_num_rows($getzone) >0) {?>

<div style="max-width:280px;max-height:170px;margin:15px auto">

<ul class="bxslider">
<?php

define('CHARS', 0); 
define('WORDS', 1); 

function truncate_chars($string, $method = 1, $length = 25, $pattern = '...') 
{ 
    $truncate = $string; 

    if (!is_numeric($length)) 
    { 
        $length = 25; 
    } 

    if (strlen($string) <= $length) 
    { 
        return $truncate; 
    } 

    switch ($method) 
    { 
        case CHARS: 
            $truncate = substr($string, 0, $length) . $pattern; 
               break; 
        case WORDS: 
            if (strstr($string, ' ') == false) 
            { 
                $truncate = truncate_chars($string, CHARS, $length, $pattern); 
            } 
            else 
            { 
                $count = 0; 
                $truncated = ''; 

                $word = explode(' ', $string); 

                foreach ($word AS $single) 
                { 
                    if ($count >= $length) 
                    { 
                        // Do nothing... 
                        continue; 
                    } 

                    if (($count + strlen($single)) <= $length) 
                    { 
                        $truncated .= $single . ' '; 
                        $count = $count + strlen($single); 
                    } 
                    else if (($count + strlen($single)) >= $length) 
                    { 
                        break; 
                    } 
                } 
                $truncate = rtrim($truncated) . $pattern; 
            } 
            break; 
    } 
    return $truncate; 
}  
/*край на съкращаване*/

$getzone = mysqli_query($link,"SELECT * FROM greyfish_servers ORDER by type DESC");
while($row = mysqli_fetch_assoc($getzone)) {
	$type = $row['type'];
	$map = $row['map'];
	$map_min =  truncate_chars($row['map'],1,10,'...');
	$servid = $row['id'];
	$players = $row['players'];
	$maxplayers = $row['maxplayers'];
	$hostname = $row['hostname'];
	$hostname_min = truncate_chars($row['hostname'],1,25,'...');
	$ip = $row['ip'];
	$port = $row['port'];
	@$progressbar=floor(($players / $maxplayers) * 100); //progress bar
	
	$steam = ""; //globalize
	if($type == "cs" || $type=="csgo") {
	$steam = "<a href='steam://connect/$ip:$port' title='steam'><img src='".url()."/icons/steam/steam.gif' alt='steam' style='display:inline-block'/></a>";
	} 
	$gametracker ="<a href='https://www.gametracker.com/server_info/$ip:$port/' target='_blank' title='gametracker'><img style='display:inline-block' src='".url()."/icons/gt/gt.gif' alt='gt'/></a>";
	
	
	$status = $row['status'];
	switch($status) {
		case 1: {
			$statuscolor = "green";
			break;
		}
		case 0: {
			$statuscolor = "red";
			break;
		}
	}
	
	if (file_exists(dirname(__FILE__).'/maps/'.$type.'/'.$map.'.jpg')) {
		 $mapimg = ''.url().'/maps/'.$type.'/'.$map.'.jpg';
	} else {
		$mapimg = ''.url().'/maps/map_no_response.jpg';
	}
	$last_update = $row['last_update'];
	 
	 switch($type) {
		case 'cs': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try
			{
			$Query->Connect( ''.$ip.'',$port, 1, SourceQuery::GOLDSOURCE );
			$update_q_cs = $Query->GetInfo();
			$ServerErr = true;
			}
			catch( Exception $e )
			{
			$ServerErr = false;
			}
			finally
			{
			$Query->Disconnect( );
			}
	
			$host_cron = $update_q_cs['HostName'];
			if ($ServerErr == false) {
			//offline
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			} else {
			//online
			$map_cron = $update_q_cs['Map'];
			$p_cron = $update_q_cs['Players'];
			$maxp_cron = $update_q_cs['MaxPlayers'];
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron',map='$map_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			}
			}
			///////////////////END CRON///////////////////////

			$game = '<img style="display:inline-block" src="'.url().'/icons/cs/cs.png" alt="CS 1.6"/>';
			break;
		}
		case 'csgo': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try
			{
			$Query->Connect( ''.$ip.'',$port, 1, SourceQuery::SOURCE );
			$update_q_cs = $Query->GetInfo();
			$ServerErr = true;
			}
			catch( Exception $e )
			{
			$ServerErr = false;
			}
			finally
			{
			$Query->Disconnect( );
			}
	
			$host_cron = $update_q_cs['HostName'];
			if ($ServerErr == false) {
			//offline
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			} else {
			//online
			$map_cron = $update_q_cs['Map'];
			$p_cron = $update_q_cs['Players'];
			$maxp_cron = $update_q_cs['MaxPlayers'];
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron',map='$map_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			}
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="'.url().'/icons/csgo/csgo.png" alt="CS:GO"/>';
			break;
		}
		case 'samp': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try {
			$rQuery = new QueryServer( $ip, $port );
    
			$aInformation  = $rQuery->GetInfo( );
			$aServerRules  = $rQuery->GetRules( );
			$aTotalPlayers = $rQuery->GetDetailedPlayers( );
    
			$rQuery->Close( );
			$serverState = true;
			}
			catch (QueryServerException $pError) {
			$serverState = false;
			}
			if ($serverState == true) {
			$host_cron = mb_convert_encoding( $aInformation['Hostname'], "utf-8", "windows-1251");
			$map_cron = $aInformation['Map'];
            $p_cron = $aInformation['Players'];
			$maxp_cron = $aInformation['MaxPlayers'];
			$query_q_samp = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron',map='$map_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_samp);
			} else {
			$query_q_samp = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_samp);
			}
			
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="'.url().'/icons/samp/samp.png" alt="San Andreas Multi-Player"/>';
			break;
		}
		case 'ts': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			$ip_data = @json_decode(file_get_contents("https://api.planetteamspeak.com/serverstatus/$ip:$port"));
			@$host_cron = $ip_data->result->name;
			
			if(!$host_cron) {
			//offline
			$query_q_ts3 = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_ts3);
			} else {
			//online
			@$p_cron =  $ip_data->result->users;
			@$maxp_cron =  $ip_data->result->slots;
			@$host_cron = $ip_data->result->name;
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			}
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="'.url().'/icons/ts/ts.png" alt="TeamSpeak 3"/>';
			break;
		}
		case 'mc': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try {
			$Query = new MinecraftQuery( );
			$Query->Connect( $ip,$port );
		   
			$mc_data =  $Query->GetInfo( );
			$host_cron = mb_convert_encoding($mc_data['HostName'], "utf-8", "windows-1251");
			$map = $mc_data['Map'];
		    $p_cron = $mc_data['Players'];
		    $maxp_cron = $mc_data['MaxPlayers'];
			$query_q_mc = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_mc);
		     
			} catch( MinecraftQueryException $e ) {
			 $query_q_mc = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			 @mysqli_free_result($query_q_mc);
			}
			
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="'.url().'/icons/mc/mc.png" alt="Minecraft"/>';
			break;
		}
	}
	
$mapimg = "<img style='width:280px;height:160px;' src='$mapimg' alt='$map'/>";
echo "
<li class='mqy'>
<span class='zoverlay' style='display:none;position:absolute;'>
$game
<a href='$greyfish_tw' title='Сървърите в Twitter'><img style='display:inline-block' src='".url()."/icons/socials/tw.png' alt='Twitter'/></a>
<a href='$greyfish_go' title='Сървърите в Google+'><img style='display:inline-block' src='".url()."/icons/socials/goo.png' alt='Google+'/></a>
<a href='$greyfish_fb' title='Сървърите във Facebook'><img style='display:inline-block' src='".url()."/icons/socials/fb.png' alt='Facebook'/></a>
<i class='fancybox2 uncategorizei' data-fancybox-type='iframe' data-href='".url()."/showplayers.php?ip=$ip&amp;port=$port&amp;game=$type' title='".$hostname_min." :: PLAYERS:' data-type='iframe'><img style='display:inline-block' src='".url()."/icons/users/users.png' alt='Users'/></i>
$gametracker $steam
</span>
$mapimg
<p class=\"caption\" style=\"border-left:4px solid ".$statuscolor."\">
<span style=\"float:left\">IP: <span  onclick='prompt(\"Server: ".$hostname_min.":\",\"".$ip.":".$port."\"); return false;'>".$ip.":".$port."</span><br/>".$hostname_min."</span>
<span style=\"float:right\">Map: ".$map_min."<br/>Players: <i class='fancybox2 uncategorizei' data-fancybox-type='iframe' data-href='".url()."/showplayers.php?ip=$ip&amp;port=$port&amp;game=$type' title='".$hostname_min." :: PLAYERS:' data-type='iframe'>".$players."/".$maxplayers."</i></span>
</p>

<div style=\"clear:both\"></div>
<div class=\"statusbar back\">
<div class=\"statusbar filled\" style=\"width: ".$progressbar."%\"></div>
</div>
					
</li>";
} 
} else {
	echo "<br/><div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Няма сървъри</div>";
}
@mysqli_free_result($getzone);
?>
</ul>
</div>

<script>
$(".fancybox2").fancybox({
		maxWidth	: 850,
		maxHeight	: 600,
		fitToView	: false,
		width		: '30%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
});
	
	$('.bxslider').bxSlider({
  adaptiveHeight: true,
  mode: 'fade',
   onSliderLoad: function(){
	$(".zoverlay").hide();
    $(".zoverlay").animate({
    left: "+=60",
    height: "toggle"
  }, 1000, function() {
  });
  }

});
</script>