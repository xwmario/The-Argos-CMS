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

$get = mysqli_query($link,"SELECT * FROM sliders WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$is_link = $row['is_link'];
$slider_img = $row['slider_img'];
if(filter_var($slider_img, FILTER_VALIDATE_URL)) { 
    $slider_img= $row['slider_img'];
   } else {
	    $slider_img= '../../'.$row['slider_img'];
   }
$slider_text = $row['text'];
$slider_link = $row['slider_link'];
 

echo '
<form role="form" action="" method="post">

 Изображение:<br/>
   <img src="'.$slider_img.'" style="max-width:158px;height:128px"/><br/>
   
   <div class="form-group">
      <label for="name">Линк към изображението:</label>
      <input type="text" class="form-control"  value="'.$slider_img.'" style="max-width:500px" name="slider_img" required>
   </div>

   <div class="form-group">
      <label for="name">Текст</label>
      <input type="text" class="form-control" value="'.$slider_text.'" placeholder="Текст към слайдъра" name="slider_text" style="max-width:200px" required>
   </div>
   ';

   if($is_link == 1) {
      echo '
   		<label class="ios7-switch">
		<input name="enable_link" type="checkbox" checked>
		<span></span>
		Изключи/включи линка
		</label><br/>
		
		<div class="form-group">
        <label for="name">Линк</label>
        <input type="text" class="form-control"  value="'.$slider_link.'" placeholder="Линк към който да сочи" name="slider_link" style="max-width:200px" required>
        </div>';
   } else {
	     echo '
   		<label class="ios7-switch">
		<input name="enable_link" type="checkbox">
		<span></span>
		Изключи/включи линка
		</label><br/>
		
		<div class="form-group">
        <label for="name">Линк</label>
        <input type="text" class="form-control"  placeholder="Линк към който да сочи" name="slider_link" style="max-width:200px">
        </div>';
	   
   }
   
   echo '
   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form>
<br/>
';



if(isset($_POST['submit'])) {

$slider_link = mysqli_real_escape_string($link,$_POST['slider_link']);
$slider_img = mysqli_real_escape_string($link,$_POST['slider_img']);
$slider_text = mysqli_real_escape_string($link,$_POST['slider_text']);
$enable_link = $_POST['enable_link'];
switch($enable_link) {
	case 'on': {
		$enable_link = 1;
		break;
	}
	case '': {
		$enable_link = 0;
		break;
	}
}
	
$go = mysqli_query($link,"UPDATE sliders SET is_link='$enable_link',slider_link='$slider_link',slider_img='$slider_img',text='$slider_text' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен слайдър</div>';	
	
}


?>
</div>

</body>
</html>