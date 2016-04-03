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
<link href="<?php echo url();?>/style_list.css" rel="stylesheet" type="text/css" media="screen, projection" />
<script src="<?php echo url();?>/js/fancybox/jquery.fancybox.pack.js"></script>
<script src="<?php echo url();?>/js/jquery.tablesorter.min.js"></script>

<?php
include("../includes/config.php");
$getlist = mysqli_query($link,"SELECT * FROM greyfish_servers ORDER by type DESC");
if(mysqli_num_rows($getlist) >0) {
?>
<section id="flip-scroll">
<table cellpadding="0" cellspacing="0" border="0" id="table" class="tinytable cf">
<thead>
<tr>
<th style="width:10%">#</th>
<th style="width:50%">Име</th>
<th style="width:15%">IP</th>
<th style="width:5%">Карта</th>
<th style="width:10%">Играчи</th>
<th style="width:10%">Вот</th>
</tr>
</thead>
<tbody>
               
<?php
include("../includes/config.php");
function truncate_charsasd($text, $limit, $ellipsis = '...') {
    if( strlen($text) > $limit ) {
        $endpos = strpos(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $text), ' ', $limit);
        if($endpos !== FALSE)
        $text = trim(substr($text, 0, $endpos)) . $ellipsis;
    }
    return $text;
}

$greyfish_Get = mysqli_query($link,"SELECT * FROM greyfish_servers ORDER by type DESC");
while($row = mysqli_fetch_assoc($greyfish_Get)) {
	$hostname = $row['hostname'];
	$players = $row['players'];
	$maxplayers = $row['maxplayers'];
	$ip = $row['ip'];
	$port = $row['port'];
	$type = $row['type'];
	$last_update = $row['last_update'];
	$map = $row['map'];
	$mapimg = "";//globalize
	if (file_exists(dirname(__FILE__).'/maps/'.$type.'/'.$map.'.jpg')) {
		 $mapimg = url().'/maps/'.$type.'/'.$map.'.jpg';
	} else {
		$mapimg = url().'/maps/map_no_response.jpg';
	}
	
	$status = $row['status'];
	$vote = $row['vote'];
	$servid = $row['id'];
	$game = "";//globalize
	$statusimg = ""; //globalize
	$steam = ""; //globalize
	if($type == "cs" || $type=="csgo") {
	$steam = "<a href='steam://connect/$ip:$port' title='steam'><img src='".url()."/icons/steam/steam.gif' alt='steam'/></a>";
	} 
	$gametracker ="<a href='https://www.gametracker.com/server_info/$ip:$port/' target='_blank' title='gametracker'><img src='".url()."/icons/gt/gt.gif' alt='gt'/></a>";
	switch($status) {
		case '1': {
			$statusimg ='<img src="'.url().'/icons/status/online.png" title="This server is online" alt="online"/>';
			break;
		}
		case '0': {
			$statusimg ='<img src="'.url().'/icons/status/offline.png" title="This server is offline" alt="offline"/>';
			break;
		}
	}
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

			$game = '<img src="'.url().'/icons/cs/cs.png" alt="CS 1.6"/>';
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
			
			$game = '<img src="'.url().'/icons/csgo/csgo.png" alt="CS:GO"/>';
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
			
			$game = '<img src="'.url().'/icons/samp/samp.png" alt="San Andreas Multi-Player"/>';
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
			
			$game = '<img src="'.url().'/icons/ts/ts.png" alt="TeamSpeak 3"/>';
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
			
			$game = '<img src="'.url().'/icons/mc/mc.png" alt="Minecraft"/>';
			break;
		}
	}

 echo "
 <tr>
 <td>$game $statusimg</td>
 <td><span title='$hostname'>".truncate_charsasd($hostname,32,'...')."</span></td>
 <td><span onclick='prompt(\"IP адреса на сървъра $hostname е:\",\"$ip:$port\"); return false;' style='cursor:pointer'>$ip:$port</span> $gametracker $steam</td>
 <td><a class='tip2'><span><img src='$mapimg' alt='$map'/></span>$map</a></td>
 <td class='slots'><i class='fancybox uncategorizei' data-fancybox-type='iframe' data-href='".url()."/showplayers.php?ip=$ip&amp;port=$port&amp;game=$type' title='".truncate_charsasd($hostname,32,'...')." :: PLAYERS:' data-type='iframe'>$players/$maxplayers</i></td>
 <td><span class='upme vote-btn' data-vote='upvote'  data-my='$servid'></span> <span id='bid-$servid'>$vote</span> <span class='downme vote-btn' data-vote='downvote'  data-my='$servid'></span></td>
 </tr>";
	
}
@mysqli_free_result($greyfish_Get);
?>           
</tbody>
</table>
</section>


<?php
//total servers
$gettotal = mysqli_query($link,"SELECT COUNT(*) as numservers FROM greyfish_servers");
$row1 = mysqli_fetch_assoc($gettotal);

//total players
$gettotal2 = mysqli_query($link,"SELECT SUM(players) as numplayers FROM greyfish_servers");
$row2 = mysqli_fetch_assoc($gettotal2);


//total max slots
$gettotal3 = mysqli_query($link,"SELECT SUM(maxplayers) as slots FROM greyfish_servers");
$row3 = mysqli_fetch_assoc($gettotal3);

@$per_cent = floor(($row2['numplayers']/$row3['slots'])*100);
$bg = ""; //globalize

if($per_cent < 0 || $per_cent > 35) {
	$bg = "#ac0";
} 
if($per_cent > 50) {
	$bg = "#fb5";
} 
if($per_cent > 80) {
	$bg = "#f67";
}

echo '
<div class="progressbar" style="width: 100%"><div  style="background-color:'.$bg.';width:'.$per_cent.'%;max-width:100%" class="progressbar-inner"></div></div>

<div class="downstats">Имаме '.$row1['numservers'].' сървъра, '.$row2['numplayers'].' играча и '.$row3['slots'].' слота!</div>';

//free fucking memory
@mysqli_free_result($gettotal);
@mysqli_free_result($gettotal2);
@mysqli_free_result($gettotal3);

} else {
	echo "<br/><div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Няма сървъри</div>";
}
@mysqli_free_result($getlist);
?>

<script>
$("#table").tablesorter( {sortList: [[0,0], [1,0]]} );

	  $(".fancybox").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	
$(".vote-btn").click(function() 
{

var id = $(this).attr("data-my");
var name = $(this).attr("data-vote");
var dataString = 'id='+ id ;
var parent = $('#bid-'+id);


if(name=='upvote')
{

$(this).fadeIn(200).html('');
$.ajax({
   type: "POST",
   url: "<?php echo url();?>/vote/up_vote.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
   parent.html(html);
  
  }  });
  
}
else
{

$(this).fadeIn(200).html('');
$.ajax({
   type: "POST",
   url: "<?php echo url();?>/vote/down_vote.php",
   data: dataString,
   cache: false,
   success: function(html)
   {
       parent.html(html);
  }
 });
}
return false;
});
</script>