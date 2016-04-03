<?php
require __DIR__ . '/SourceQuery/bootstrap.php';

//SAMP:
// Query Server classes by the SA:MP team or someone else
class QueryServer
{
     // Private variables used for the query-ing.
     private $szServerIP;
     private $iPort;
     private $rSocketID;
     
     private $bStatus;
     // The __construct function gets called automatically
     // by PHP once the class gets initialized.
     function __construct( $szServerIP, $iPort )
     {
         $this->szServerIP = $this->VerifyAddress( $szServerIP );
         $this->iPort = $iPort;
         
         if (empty( $this->szServerIP ) || !is_numeric( $iPort )) {
             throw new QueryServerException( 'Either the ip-address or the port isn\'t filled in correctly.' );
         }
         
         $this->rSocketID = @fsockopen( 'udp://' . $this->szServerIP, $iPort, $iErrorNo, $szErrorStr, 5 );
         if (!$this->rSocketID) {
             throw new QueryServerException( 'Cannot connect to the server: ' . $szErrorStr );
         }
         
         socket_set_timeout( $this->rSocketID, 0, 100000 );
         $this->bStatus = true;
     }
     
     // The VerifyAddress function verifies the given hostname/
     // IP address and returns the actual IP Address.
     function VerifyAddress( $szServerIP )
     {
         if (ip2long( $szServerIP ) !== false && 
             long2ip( ip2long( $szServerIP ) ) == $szServerIP ) {
             return $szServerIP;
         }
         $szAddress = gethostbyname( $szServerIP );
         if ($szAddress == $szServerIP) {
             return "";
         }
         
         return $szAddress;
     }
     
     // The SendPacket function sends a packet to the server which
     // requests information, based on the type of packet send.
     function SendPacket( $cPacket )
     {
         $szPacket = 'SAMP';
         $aIpChunks = explode( '.', $this->szServerIP );
         
         foreach( $aIpChunks as $szChunk ) {
             $szPacket .= chr( $szChunk );
         }
         
         $szPacket .= chr( $this->iPort & 0xFF );
         $szPacket .= chr( $this->iPort >> 8 & 0xFF );
         $szPacket .= $cPacket;
         
        return fwrite( $this->rSocketID, $szPacket, strlen( $szPacket ) );
     }
    
     // The GetPacket() function returns a specific number of bytes
     // read from the socket. This uses a special way of getting stuff.
     function GetPacket( $iBytes )
     {
         $iResponse = fread( $this->rSocketID, $iBytes );
         if ($iResponse === false) {
             throw new QueryServerException( 'Connection to ' . $this->szServerIP . ' failed or has dropped.' );
         }
         
         $iLength = ord( $iResponse );
         if ($iLength > 0)
             return fread( $this->rSocketID, $iLength );
         
         return "";
     }
     
     // After we're done, the connection needs to be closed using
     // the Close() function. Otherwise stuff might go wrong.
     function Close( )
     {
         if ($this->rSocketID !== false) {
             fclose( $this->rSocketID );
         }
     }
     
     // A little function that's needed to properly convert the
     // four bytes we're recieving to integers to an actual PHP
     // integer. ord() can't handle value's higher then 255.
     function toInteger( $szData )
     {
         $iInteger = 0;
         
         $iInteger += ( ord( $szData[ 0 ] ) );
         $iInteger += ( ord( $szData[ 1 ] ) << 8 );
         $iInteger += ( ord( $szData[ 2 ] ) << 16 );
         $iInteger += ( ord( $szData[ 3 ] ) << 24 );
         
         if( $iInteger >= 4294967294 )
             $iInteger -= 4294967296;
         
         return $iInteger;
     }
     // The GetInfo() function returns basic information about the
     // server, like the hostname, number of players online etc.
     function GetInfo( )
     {
         if ($this->SendPacket('i') === false) {
             throw new QueryServerException( 'Connection to ' . $this->szServerIP . ' failed or has dropped.' );
         }
         
         $szFirstData = fread( $this->rSocketID, 4 );
         if (empty( $szFirstData ) || $szFirstData != 'SAMP') {
             throw new QueryServerException( 'The server at ' . $this->szServerIP . ' is not an SA-MP Server.' );
         }
         
         // Pop the first seven characters returned.
         fread( $this->rSocketID, 7 );
         
         return array (
             'Password'   =>   ord( fread( $this->rSocketID, 1 ) ),
             'Players'    =>   ord( fread( $this->rSocketID, 2 ) ),
             'MaxPlayers' =>   ord( fread( $this->rSocketID, 2 ) ),
             'Hostname'   =>   $this->GetPacket( 4 ),
             'Gamemode'   =>   $this->GetPacket( 4 ),
             'Map'        =>   $this->GetPacket( 4 )
         );
     }
     
     // The GetRules() function returns the rules which are set
     // on the server, e.g. the gravity, version etcetera.
     function GetRules( )
     {
         if ($this->SendPacket('r') === false) {
             throw new QueryServerException( 'Connection to ' . $this->szServerIP . ' failed or has dropped.' );
         }
         
         // Pop the first 11 bytes from the response;
         fread( $this->rSocketID, 11 );
         
         $iRuleCount = ord( fread( $this->rSocketID, 2 ) );
         $aReturnArray = array( );
         
         for( $i = 0; $i < $iRuleCount; $i ++ ) {
             $szRuleName = $this->GetPacket( 1 );
             $aReturnArray[ $szRuleName ] = $this->GetPacket( 1 );
         }
         
         return $aReturnArray;
     }
     
     // The GetPlayers() function is pretty much simelar to the
     // detailed function, but faster and contains less information.
     function GetPlayers( )
     {
         if ($this->SendPacket('c') === false) {
             throw new QueryServerException( 'Connection to ' . $this->szServerIP . ' failed or has dropped.' );
         }
         
         // Again, pop the first eleven bytes send;
         fread( $this->rSocketID, 11 );
         
         $iPlayerCount = ord( fread( $this->rSocketID, 2 ) );
         $aReturnArray = array( );
         
         for( $i = 0; $i < $iPlayerCount; $i ++ )
         {
             $aReturnArray[ ] = array (
                 'Nickname' => $this->GetPacket( 1 ),
                 'Score'    => $this->toInteger( fread( $this->rSocketID, 4 ) )
             );
         }
         
         return $aReturnArray;
     }
     
     // The GetDetailedPlayers() function returns the player list,
     // but in a detailed form inclusing the score and the ping.
     function GetDetailedPlayers( )
     {
         if ($this->SendPacket('d') === false) {
             throw new QueryServerException( 'Connection to ' . $this->szServerIP . ' failed or has dropped.' );
         }
         
         // Skip the first 11 bytes of the response;
         fread( $this->rSocketID, 11 );
         
         $iPlayerCount = ord( fread( $this->rSocketID, 2 ) );
         $aReturnArray = array( );
         
         for( $i = 0; $i < $iPlayerCount; $i ++ ) {
             $aReturnArray[ ] = array(
                 'PlayerID'   =>  ord( fread( $this->rSocketID, 1 ) ),
                 'Nickname'   =>  $this->GetPacket( 1 ),
                 'Score'      =>  $this->toInteger( fread( $this->rSocketID, 4 ) ),
                 'Ping'       =>  ord( fread( $this->rSocketID, 4 ) )
             );
         }
         
         return $aReturnArray;
     }
}
/*********************************************
  *
  * The QueryServerException is used to throw errors when querying
  * a specific server. That way we force the user to use proper
  * error-handling, and preferably even a try-/catch statement.
  *
  **********************************************/
class QueryServerException extends Exception
{
     // The actual error message is stored in this variable.
     private $szMessage;
     
     // Again, the __construct function gets called as soon
     // as the exception is being thrown, in here we copy the message.
     function __construct( $szMessage )
     {
         $this->szMessage = $szMessage;
     }
     
     // In order to read the exception being thrown, we have
     // a .NET-like toString() function, which returns the message.
     function toString( )
     {
         return $this->szMessage;
     }
}

///MINECRAFT
 
class MinecraftQueryException extends \Exception
{
	// Exception thrown by MinecraftQuery class
}

class MinecraftQuery
{
	 
	/*
	 * Class written by xPaw
	 *
	 * Website: http://xpaw.me
	 * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
	 */

	const STATISTIC = 0x00;
	const HANDSHAKE = 0x09;

	private $Socket;
	private $Players;
	private $Info;

	public function Connect( $Ip, $Port = 25565, $Timeout = 3 )
	{
		if( !is_int( $Timeout ) || $Timeout < 0 )
		{
			throw new \InvalidArgumentException( 'Timeout must be an integer.' );
		}

		$this->Socket = @FSockOpen( 'udp://' . $Ip, (int)$Port, $ErrNo, $ErrStr, $Timeout );

		if( $ErrNo || $this->Socket === false )
		{
			throw new MinecraftQueryException( 'Could not create socket: ' . $ErrStr );
		}

		Stream_Set_Timeout( $this->Socket, $Timeout );
		Stream_Set_Blocking( $this->Socket, true );

		try
		{
			$Challenge = $this->GetChallenge( );

			$this->GetStatus( $Challenge );
		}
		// We catch this because we want to close the socket, not very elegant
		catch( MinecraftQueryException $e )
		{
			FClose( $this->Socket );

			throw new MinecraftQueryException( $e->getMessage( ) );
		}

		FClose( $this->Socket );
	}

	public function GetInfo( )
	{
		return isset( $this->Info ) ? $this->Info : false;
	}

	public function GetPlayers( )
	{
		return isset( $this->Players ) ? $this->Players : false;
	}

	private function GetChallenge( )
	{
		$Data = $this->WriteData( self :: HANDSHAKE );

		if( $Data === false )
		{
			throw new MinecraftQueryException( 'Failed to receive challenge.' );
		}

		return Pack( 'N', $Data );
	}

	private function GetStatus( $Challenge )
	{
		$Data = $this->WriteData( self :: STATISTIC, $Challenge . Pack( 'c*', 0x00, 0x00, 0x00, 0x00 ) );

		if( !$Data )
		{
			throw new MinecraftQueryException( 'Failed to receive status.' );
		}

		$Last = '';
		$Info = Array( );

		$Data    = SubStr( $Data, 11 ); // splitnum + 2 int
		$Data    = Explode( "\x00\x00\x01player_\x00\x00", $Data );

		if( Count( $Data ) !== 2 )
		{
			throw new MinecraftQueryException( 'Failed to parse server\'s response.' );
		}

		$Players = SubStr( $Data[ 1 ], 0, -2 );
		$Data    = Explode( "\x00", $Data[ 0 ] );

		// Array with known keys in order to validate the result
		// It can happen that server sends custom strings containing bad things (who can know!)
		$Keys = Array(
			'hostname'   => 'HostName',
			'gametype'   => 'GameType',
			'version'    => 'Version',
			'plugins'    => 'Plugins',
			'map'        => 'Map',
			'numplayers' => 'Players',
			'maxplayers' => 'MaxPlayers',
			'hostport'   => 'HostPort',
			'hostip'     => 'HostIp',
			'game_id'    => 'GameName'
		);

		foreach( $Data as $Key => $Value )
		{
			if( ~$Key & 1 )
			{
				if( !Array_Key_Exists( $Value, $Keys ) )
				{
					$Last = false;
					continue;
				}

				$Last = $Keys[ $Value ];
				$Info[ $Last ] = '';
			}
			else if( $Last != false )
			{
				$Info[ $Last ] = $Value;
			}
		}

		// Ints
		$Info[ 'Players' ]    = IntVal( $Info[ 'Players' ] );
		$Info[ 'MaxPlayers' ] = IntVal( $Info[ 'MaxPlayers' ] );
		$Info[ 'HostPort' ]   = IntVal( $Info[ 'HostPort' ] );

		// Parse "plugins", if any
		if( $Info[ 'Plugins' ] )
		{
			$Data = Explode( ": ", $Info[ 'Plugins' ], 2 );

			$Info[ 'RawPlugins' ] = $Info[ 'Plugins' ];
			$Info[ 'Software' ]   = $Data[ 0 ];

			if( Count( $Data ) == 2 )
			{
				$Info[ 'Plugins' ] = Explode( "; ", $Data[ 1 ] );
			}
		}
		else
		{
			$Info[ 'Software' ] = 'Vanilla';
		}

		$this->Info = $Info;

		if( $Players )
		{
			$this->Players = Explode( "\x00", $Players );
		}
	}

	private function WriteData( $Command, $Append = "" )
	{
		$Command = Pack( 'c*', 0xFE, 0xFD, $Command, 0x01, 0x02, 0x03, 0x04 ) . $Append;
		$Length  = StrLen( $Command );

		if( $Length !== FWrite( $this->Socket, $Command, $Length ) )
		{
			throw new MinecraftQueryException( "Failed to write on socket." );
		}

		$Data = FRead( $this->Socket, 4096 );

		if( $Data === false )
		{
			throw new MinecraftQueryException( "Failed to read from socket." );
		}

		if( StrLen( $Data ) < 5 || $Data[ 0 ] != $Command[ 2 ] )
		{
			return false;
		}

		return SubStr( $Data, 5 );
	}
}

?>