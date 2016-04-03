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
	<link href="../js/darcy/darcy.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/darcy/darcy.js"></script>
    <style>
	select {color:black;}.btn{width:100%}
	</style>
	
	
</head>
<body style="background:#ecf0f5">

<div class="container">
<br/>
<?php 
$id = (int)$_GET['id'];	

$get = mysqli_query($link,"SELECT * FROM comments WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$text = htmlspecialchars($row['text']);

echo '
<form role="form" action="" method="post">
      <label for="name">Текст на коментара</label>
      <textarea class="darcy form-control" data-editor="php" type="text" name="text" style="height:200px;width:100%">'.$text.'</textarea><br/>

   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form>
<br/>
';

if(isset($_POST['submit'])) {
$text = htmlspecialchars_decode(mysqli_real_escape_string($link,$_POST['text']));
$go = mysqli_query($link,"UPDATE comments SET text='$text' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен коментар</div>';
}
?>
</div>

</body>
</html>