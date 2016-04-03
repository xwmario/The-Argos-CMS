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

$get = mysqli_query($link,"SELECT * FROM advertise WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$banner_img = $row['banner_img'];
$banner_link = $row['site_link'];
$title = $row['link_title'];


echo '
<form role="form" action="" method="post">

<div class="input-group">
<span class="input-group-addon"><i class="fa fa-link"></i></span>
<input type="text" name="banner_link" value="'.$banner_link.'" placeholder="Банер линк" class="form-control" required>
</div>
<br/>
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

<button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form>
<br/>
';

if(isset($_POST['submit'])) {
$banner = mysqli_real_escape_string($link,$_POST['banner_img']);
$b_link = mysqli_real_escape_string($link,$_POST['banner_link']);
$title = mysqli_real_escape_string($link,$_POST['banner_title']);
$go = mysqli_query($link,"UPDATE advertise SET banner_img='$banner',site_link='$b_link',link_title='$title' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променен банер</div>';	
	
}

?>

</div>

</body>
</html>