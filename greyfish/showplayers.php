<?php
if (empty($_SERVER['HTTP_REFERER'])){die();}
include("inc/game_q.php");
use xPaw\SourceQuery\SourceQuery;
$Query = new SourceQuery( );

$ip = htmlspecialchars($_GET['ip']);
$port = (int)$_GET['port'];
$game = htmlspecialchars($_GET['game']);

switch($game) {
	case 'cs': {
		    try
			{
			$Query->Connect( ''.$ip.'',$port, 1, SourceQuery::GOLDSOURCE );
			$Players = $Query->GetPlayers( );
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
 
		if($ServerErr != false) {
		if( !empty( $Players ) ) {
		foreach ($Players as $Player){
		echo "<div style='outline:1px solid black;width:300px;'>";
		echo "<b>Name:</b> ".htmlspecialchars($Player['Name'])."<br>";
		echo "<b>Score:</b> ".$Player['Frags']."<br>";
		echo "<b>Time connected:</b> ".$Player['TimeF']."</div><br/>";
		}
		} else {
			echo "Няма играчи в този сървър!";
		}
		} else {
			echo "Няма играчи в този сървър или той е спрян!";
		}
		break;
	}
	
	case 'csgo': {
		    try
			{
			$Query->Connect( ''.$ip.'',$port, 1, SourceQuery::SOURCE );
			$Players = $Query->GetPlayers( );
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
			
		if($ServerErr != false) {
		if( !empty( $Players ) ) {
		foreach ($Players as $Player){
		echo "<div style='outline:1px solid black;width:300px;'>";
		echo "<b>Name:</b> ".htmlspecialchars($Player['Name'])."<br>";
		echo "<b>Score:</b> ".$Player['Frags']."<br>";
		echo "<b>Time connected:</b> ".$Player['TimeF']."</div><br/>";
		}
		} else {
			echo "Няма играчи в този сървър!";
		}
		} else {
			echo "Няма играчи в този сървър или той е спрян!";
		}
		break;
	}
	case 'samp': {
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

                if ($aInformation['Players'] > 0) {
                   
                        foreach( $aTotalPlayers as $aPlayer ) {
                        echo "<div style='outline:1px solid black;width:300px;'>";
                        echo "<b>Name:</b> ".$aPlayer['Nickname']."<br>";
                        echo "<b>Score:</b> ".$aPlayer['Score']."<br>";
                        echo "<b>Ping:</b> ".$aPlayer['Ping']."</div><br/>";
                        }
                } else {
					echo "Няма играчи в този сървър!";
				}
				
}else {
  echo 'Няма играчи в този сървър или той е спрян!'; 
}

		break;
	}
	case 'mc': {
		try {
          $Query = new MinecraftQuery( );
          $Query->Connect( $ip,$port );
		   
          $mc_data =  $Query->GetInfo( );
		  
		  if($mc_data['Players'] > 0) {
		  foreach ($Query->GetPlayers( ) as $player){
		  echo "<div style='outline:1px solid black;width:300px;padding:15px;'>";
		  echo "<b>Name:</b> ".$player."<br>";
		  echo "</div><br/>";
		  }
		  } else {
			  echo "Няма играчи в този сървър!";
		  }
		  
		} catch( MinecraftQueryException $e ) {
			echo 'Няма играчи в този сървър или той е спрян!';  
		}
		break;
	}
	case 'ts': {
		echo "Не се поддържа от системата ни!";
		break;
	}
	
}

?>