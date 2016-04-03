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

require('../../includes/functions.php');

$get = mysqli_query($link,"SELECT * FROM news WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$avtor = $row['author'];
$newsid = $row['id'];
$zaglavie = $row['title'];
$text = $row['text'];
$img = $row['img'];
	if(filter_var($img, FILTER_VALIDATE_URL)) {
		$img = $img;
	} else {
	    $img = "../../assets/img/news_img.png";	
	}
$comments_enable = $row['comments_enabled'];
switch($comments_enable){
	case '1': {
		$comments_enable = "checked";
		break;
	}
	case '0': {
		$comments_enable = "";
		break;
	}
}

echo '
<form role="form" action="" method="post">

   <div class="form-group">
      <label for="name">Автор</label>
      <input type="text" class="form-control"  value="'.$avtor.'" style="max-width:200px" name="author" required>
   </div>

   <div class="form-group">
      <label for="name">Заглавие</label>
      <input type="text" class="form-control" value="'.$zaglavie.'" placeholder="Въведете заглавие" name="novina" style="max-width:200px" required>
   </div>

   Изображение:<br/>
   <img src="'.$img.'" style="max-width:158px;height:128px"/><br/>
   <div class="form-group">
      <label for="name">Линк към изображението</label>
      <input type="text" class="form-control" value="'.$img.'" placeholder="Изображение" name="img" style="max-width:500px" required>
   </div>


 <div class="form-group">
      <label for="name">Новина</label>
      <textarea class="darcy" data-editor="php" type="text" name="text" style="height:200px;width:100%">'.$text.'</textarea>
 </div>

 <label class="ios7-switch">
		<input name="news_comment_enable" type="checkbox" '.$comments_enable.'>
		<span></span>
		Изключи/включи коментарите.
 </label><br/>



   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form><br/>';

if(isset($_POST['submit'])) {
	
	@$comments_enable = $_POST['news_comment_enable'];
	switch($comments_enable) {
		case '':{
			$comments_enable = 0;
			break;
		}
		case 'on': {
			$comments_enable = 1;
			break;
		}
	}
	
$text = mysqli_real_escape_string($link,$_POST['text']);
$author = htmlspecialchars(trim(mysqli_real_escape_string($link,$_POST['author'])));
$novina= mysqli_real_escape_string($link,$_POST['novina']);
$seourl =  parse_cyr_en_url($novina.'_'.date('d_m_Y',time()));
$img = htmlspecialchars(trim(mysqli_real_escape_string($link,$_POST['img'])));
	if(filter_var($img, FILTER_VALIDATE_URL)) {
		$img = $img;
	} else {
	    $img = "assets/img/news_img.png";	
	}
	
$go = mysqli_query($link,"UPDATE news SET author='$author',title='$novina',seourl='$seourl',text='$text',comments_enabled='$comments_enable',img='$img' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променена новина</div>';
	
}


?>
</div>

</body>
</html>