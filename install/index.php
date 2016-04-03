<!DOCTYPE html>
<!-- created by dedihost.org -->
<html lang="en">
<head>
<meta charset="utf-8">    
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<title>Argos Installator</title>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>

<body style="background:#34495E">
<br/>
<div class="container" style="background:#dedede;max-width:500px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px">
<br/>
<div class="alert alert-info" style="max-width:500px"><i class="fa fa-info"></i> Welcome to Argos Installator</div>
<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Please create new db and fill the form below!</div>

<form method="post" style="max-width:500px">
<input type="text" name="host" placeholder="host (example: localhost)" class="form-control" /><br/>
<input type="text" name="user" placeholder="root user" class="form-control" /><br/>
<input type="password" name="pass" placeholder="root pass" class="form-control" /><br/>
<input type="text" name="db" placeholder="database" class="form-control" /><br/>
<br/>
<input type="text" name="forum_path" placeholder="phpbb folder, like forum/ (end trailing slash is necessary)" class="form-control" /><br/>
<hr/>
Greyfish data:<br/>
<input type="text" name="fb_link" placeholder="facebook link (is not necessary)" class="form-control" /><br/>
<input type="text" name="tw_link" placeholder="twitter link (is not necessary)" class="form-control" /><br/>
<input type="text" name="goo_link" placeholder="google+ link (is not necessary)" class="form-control" /><br/>
<input type="text" name="greyfish_upd" placeholder="How much seconds to updating the servers ? (def:300)" class="form-control" /><br/>
<hr/>
<input type="submit" name="submit" class="btn btn-md btn-success" value="Install Argos"/>
</form>
<br/>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Please, delete install folder after seeing the 'green' box below!</div>

<?php
error_reporting(0);
if(isset($_POST['submit'])) {
	$host = $_POST['host'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$db = $_POST['db'];
	$phpbb_ver = $_POST['phpbb_vers'];
	$forum_path = $_POST['forum_path'];
	$fb_link = $_POST['fb_link'];
	$tw_link = $_POST['tw_link'];
	$goo_link = $_POST['goo_link'];
	$greyfish_upd = (int)$_POST['greyfish_upd'] == '' ? 300 : $_POST['greyfish_upd'];
	//////////////////////////
	
	if(!empty($host) && !empty($user) && !empty($pass) && !empty($db) &&  !empty($forum_path) && !empty($greyfish_upd)){

if( $link = mysqli_connect($host,$user,$pass,$db)) {
mysqli_set_charset($link, "utf8");


$filename = '../documentation/sql.sql';
$templine = '';
$lines = file($filename);
foreach ($lines as $line)
{
if (substr($line, 0, 2) == '--' || $line == '')
    continue;

$templine .= $line;
if (substr(trim($line), -1, 1) == ';')
{
    mysqli_query($link,$templine);
    $templine = '';
}
}


$filename = "../includes/config.php";
$output = '<?php
$link = mysqli_connect("'.$host.'","'.$user.'","'.$pass.'","'.$db.'") or die("Error " . mysqli_error($link)); //кънекция към db (mysqli)
mysqli_set_charset($link, "utf8");

//###PHPBB
$forum_path = "'.$forum_path.'"; //в коя папка е форума

//Greyfish
$greyfish_tw = "'.$tw_link.'"; //twiter link
$greyfish_fb = "'.$fb_link.'"; //fb link
$greyfish_go = "'.$goo_link.'"; //g+ link
$greyfish_update = '.$greyfish_upd.'; //на колко секунди да ъпдейтва, default 5 минути (не променяй!)
';
$filehandle = fopen($filename, 'w');
fwrite($filehandle, $output);
fclose($filehandle);

echo "<br/><div class='alert alert-success'><i class='fa fa-check'></i> Success! Now you can delete install folder and visit your Argos!</div>";
} else {
	echo "<br/><div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i> DB connection failed</div>";
}
	} else {
		echo "<br/><div class='alert alert-danger'><i class='fa fa-exclamation-circle'></i> Missing input data</div>";
	}
}
?>
<hr/>
Made by val4o0o0 @ <a href="http://dedihost.org">dedihost.org</a><br/><br/>
</div>
</body>
</html>