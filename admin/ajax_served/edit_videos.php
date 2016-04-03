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
<?php 
$id = (int)$_GET['id'];

$get = mysqli_query($link,"SELECT * FROM uploadvideos WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$vlink = $row['videolink'];
$vsite = $row['site'];
if($vsite == "vbox") {
	$pieces1 = explode("play:", $vlink);
	$vid = $pieces1[1];
	$vlink = "http://vbox7.com/emb/external.php?vid=$vid";
} else {
	$pieces2 = explode("watch?v=", $vlink);
	$vid = $pieces2[1];
	$vlink = "https://www.youtube.com/embed/$vid";
}
$uploader = $row['uploader'];
$curcat = $row['cat'];

$getallcats2 = mysqli_query($link,"SELECT * FROM videocat");
$getallcats = ""; //globalize
while($row2= mysqli_fetch_assoc($getallcats2)) {
	$getallcats .= '<option value="'.$row2['category'].'">'.$row2['category'].'</option>';
}
@mysqli_free_result($getallcats);
echo '
<iframe width="560" height="315" src="'.$vlink.'" frameborder="0" allowfullscreen></iframe>
<form role="form" action="" method="post">

   <div class="form-group">
      <label for="name">Качил</label>
      <input type="text" class="form-control"  value="'.$uploader.'" style="max-width:200px" name="uploader" required>
   </div>


   Текуща категория: <b>'.$curcat.'</b><br/>
   Смяна на категорията:<br/>
   <select name="cat">
   '.$getallcats.'
   </select>
   <br/><br/>

   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form><br/>';

if(isset($_POST['submit'])) {
	
	
$cat = mysqli_real_escape_string($link,$_POST['cat']);
$uploader = mysqli_real_escape_string($link,$_POST['uploader']);

$go = mysqli_query($link,"UPDATE uploadvideos SET cat='$cat', uploader='$uploader' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен клип!</div>';
	
}


?>
</div>

</body>
</html>