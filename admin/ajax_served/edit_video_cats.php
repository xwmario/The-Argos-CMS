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

</head>
<body style="background:#ecf0f5">

<div class="container">
<br/>
<?php 
$id = (int)$_GET['id'];

$get = mysqli_query($link,"SELECT * FROM videocat WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$cat = $row['category'];


echo '
<div class="alert alert-info">При смяна на името на категория, всички клипове в нея - ще се пренесат автоматично в новата категория.</div>
<form role="form" action="" method="post">

   <div class="form-group">
      <label for="name">Име на категорията</label>
      <input type="text" class="form-control" value="'.$cat.'" placeholder="Въведете заглавие" name="vcat" style="max-width:200px" required>
   </div>

 
   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form><br/>';

if(isset($_POST['submit'])) {
$vcat = mysqli_real_escape_string($link,$_POST['vcat']);
$go = mysqli_query($link,"UPDATE uploadvideos SET cat='$vcat' WHERE cat='$cat'") or die(mysqli_error($link));
@mysqli_free_result($go);
$go = mysqli_query($link,"UPDATE videocat SET category='$vcat' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променена категория!</div>';	
	
}


?>
</div>

</body>
</html>