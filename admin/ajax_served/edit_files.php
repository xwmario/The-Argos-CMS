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

$get = mysqli_query($link,"SELECT * FROM files WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$file_name = $row['name'];
$file_author = $row['author'];
$file_size = $row['size'];
$opisanie = $row['opisanie'];
$file_link = $row['link'];

$img = $row['picture'];
	if(filter_var($img, FILTER_VALIDATE_URL)) {
		$img = $img;
	} else {
	    $img = "../../assets/img/no_image.jpg";	
	}

echo '
<form role="form" method="post">

   <div class="form-group">
      <label for="name">Име на файла</label>
      <input type="text" class="form-control" value="'.$file_name.'" style="max-width:200px" name="file_name" required>
   </div>
   
   <div class="form-group">
      <label for="name">Автор</label>
      <input type="text" class="form-control" value="'.$file_author.'" style="max-width:200px" name="file_author" required>
   </div>

    <div class="form-group">
      <label for="name">Линк към файла</label>
      <input type="text" class="form-control" value="'.$file_link.'" style="max-width:200px" name="file_link" required>
   </div>
      
	<div class="form-group">
      <label for="name">Размер файла</label>
      <input type="text" class="form-control" value="'.$file_size.'" style="max-width:200px" name="file_size" required>
   </div>
   
   Изображение:<br/>
   <img src="'.$img.'" style="max-width:158px;height:128px"/><br/>
   <div class="form-group">
      <label for="name">Линк към изображението</label>
      <input type="text" class="form-control"  value="'.$img.'" placeholder="Изображение" name="img" style="max-width:500px">
   </div>


  <div class="form-group">
      <label for="name">Описание</label>
      <textarea class="darcy" data-editor="php" type="text" name="opisanie" style="height:200px;width:100%">'.$opisanie.'</textarea>
   </div>
 

   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form><br/>
';


if(isset($_POST['submit'])) {
$opisanie = mysqli_real_escape_string($link,$_POST['opisanie']);
$file_name = mysqli_real_escape_string($link,$_POST['file_name']);
$file_author = mysqli_real_escape_string($link,$_POST['file_author']);
$file_img = mysqli_real_escape_string($link,$_POST['img']);
	if(filter_var($file_img, FILTER_VALIDATE_URL)) {
		$file_img = $file_img;
	} else {
	    $file_img = "assets/img/no_image.jpg";	
	}
$file_size = mysqli_real_escape_string($link,$_POST['file_size']);
$file_link = mysqli_real_escape_string($link,$_POST['file_link']);
	
$go = mysqli_query($link,"UPDATE files SET link='$file_link',size='$file_size',name='$file_name',picture='$file_img',author='$file_author',opisanie='$opisanie' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен файл!</div>';
	
}


?>
</div>

</body>
</html>