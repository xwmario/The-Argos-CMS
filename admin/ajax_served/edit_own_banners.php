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

$get = mysqli_query($link,"SELECT * FROM banners WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$banner_img = $row['banner_img'];
$title = $row['link_title'];
$avtor = $row['avtor'];
$banner_type= $row['type'];

echo '
<form role="form" action="" method="post">


<div class="input-group">
<span class="input-group-addon"><i class="fa fa-image"></i></span>
<input type="text" name="banner_img" value="'.$banner_img.'" placeholder="Банер изображение(линк)" class="form-control" required>
</div>
<br/>
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-info"></i></span>
<input type="text" name="banner_title" value="'.$title.'" placeholder="Тайтъл таг" class="form-control" required>
</div>
<br/>
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-user"></i></span>
<input type="text" name="banner_author" value="'.$avtor.'" placeholder="Автор на банера" class="form-control" required>
</div>
<br/>
Настоящ тип на банера: <b>'.$banner_type.' (ако изберете стойност по-долу - ще редактирате типа)<br/>
<select name="type">
<option value="0">Избери</option>
		<option value="468x60">468x60</option>
		<option value="88x31">88x31</option>
		<option value="userbar">userbar</option>
		<option value="728x90">728x90</option>
		<option value="200x200">200x200</option>
		<option value="120x240">120x240</option>
</select><br/><br/>

<button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form>
<br/>
';
 

if(isset($_POST['submit'])) {
$banner = mysqli_real_escape_string($link,$_POST['banner_img']);
$avtor = mysqli_real_escape_string($link,$_POST['banner_author']);
$title = mysqli_real_escape_string($link,$_POST['banner_title']);
$type = mysqli_real_escape_string($link,$_POST['type']);
if($type != 0) {
	$type = $type;
} else {
	$type = $banner_type;
}
$go = mysqli_query($link,"UPDATE banners SET banner_img='$banner',link_title='$title',avtor='$avtor',type='$type' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен банер</div>';	
	
}

?>

</div>

</body>
</html>