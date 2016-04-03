<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}
?>
<!DOCTYPE html>
<!-- created by dedihost.org -->
<html lang="en">
<head>
<title></title>
    <meta charset="UTF-8">
    <!-- Bootstrap -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../template/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	
	<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>

</head>
<body style="background:#ecf0f5">

<div class="container">
<br/>
<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Сървърите се обновяват веднъж на 5 минути</div>
<br/>
<?php 
$id = (int)$_GET['id'];	

$get = mysqli_query($link,"SELECT * FROM greyfish_servers WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$text = htmlspecialchars($row['text']);
$news_id = $row['newsid'];

echo '
<form method="post">
Hostname:<br/>
<input type="text" class="form-control" name="hostname" value="'.$row['hostname'].'" style="width:400px"/><br/>
IP:<br/>
<input type="text" class="form-control" name="ip" value="'.$row['ip'].'" style="width:400px"/><br/>
Port:<br/>
<input type="text" class="form-control" name="port" value="'.$row['port'].'" style="width:400px"/></br>
Map:<br/>
<input type="text" class="form-control" name="map" value="'.$row['map'].'" style="width:400px"/></br>
Game (възможни типове: cs,csgo,mc,ts,samp):<br/>
<input type="text" class="form-control" name="game" value="'.$row['type'].'" style="width:400px"/></br>
Вотове:<br/>
<input type="text" class="form-control" name="vote" value="'.$row['vote'].'" style="width:400px"/></br>
<input type="submit" name="submit" class="btn btn-md btn-success" value="Промени"/>
</form>
<br/>
';


if(isset($_POST['submit'])) {

	$hostname = $_POST['hostname'];
	$ip = $_POST['ip'];
	$port = $_POST['port'];
	$map = $_POST['map'];
	$type = $_POST['game'];
	$vote = $_POST['vote'];
	$go = mysqli_query($link,"UPDATE greyfish_servers SET hostname='$hostname',ip='$ip',port='$port',map='$map',type='$type',vote='$vote' WHERE id='$id'") or die(mysqli_error($link));
	@mysqli_free_result($go);
	echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен сървър</div>';
	
}

?>

</div>

</body>
</html>