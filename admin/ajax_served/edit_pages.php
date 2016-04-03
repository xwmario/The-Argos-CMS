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

   
$get = mysqli_query($link,"SELECT * FROM pages WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$page_name_old = $row['page_name'];
$page_title = $row['page_title'];
$page_content = file_get_contents_utf8('../../custom_page_content/'.$page_name_old.'.php');
 
echo '
<form role="form" action="" method="post" class="submitform">

   <input type="hidden" name="page_name_old" value="'.$page_name_old.'"/>
   <input type="hidden" name="page_id" value="'.$id.'"/>
   
   <div class="form-group">
      <label for="name">Име на страницата</label>
      <input type="text" class="form-control page_name"  value="'.$page_name_old.'" style="max-width:200px" name="page_name" required>
   </div>

   <div class="form-group">
      <label for="name">Тайтъл</label>
      <input type="text" class="form-control page_title" value="'.$page_title.'" placeholder="Въведете заглавие" name="page_title" style="max-width:200px" required>
   </div>
 
 <div class="form-group">
      <label for="name">Новина</label>
      <textarea class="darcy" data-editor="php" type="text" name="page_content" style="height:300px;width:100%">'.$page_content.'</textarea>
   </div>

   <button type="submit" name="submit" class="btn btn-success submiter">Редактирай</button>
</form><br/>';

 
?>
</div>
<div id="res1"></div>

<script>
$( ".submiter" ).click(function() {
var form = $('.submitform');
$.ajax({
                    type: "POST", //метод
					dataType: "json",
                    url: "edit_pages2_ajax.php", //от къде ще взимаме резултатите
					data:  form.serialize(),
                    success: function(mqy) {
					   $("#res1").show();
                       $("#res1").html(mqy['message']);
					   $('.darcy').val('');
					   $('.page_name').val('');
					   $('.page_title').val('');
					   $('.darcy').val(mqy['content']);
					   $('.page_name').val(mqy['page_name']);
					   $('.page_title').val(mqy['page_title']);
					   
					   setTimeout(function(){
					   if ($('#res1').length > 0) {
					   $('#res1').hide();
					   }
					   }, 2000)
                    } 
    });
return false;
});
</script>

</body>
</html>