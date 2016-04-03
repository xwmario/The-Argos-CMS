<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}
require('../../includes/functions.php');

$page_content = stripcslashes($_POST['page_content']);
$page_title = htmlspecialchars(trim(mysqli_real_escape_string($link,$_POST['page_title'])));
$page_name = htmlspecialchars(trim(mysqli_real_escape_string($link,$_POST['page_name'])));
$page_name_old=$_POST['page_name_old'];
$id = (int)$_POST['page_id'];


$go = mysqli_query($link,"UPDATE pages SET page_name='$page_name',page_title='$page_title' WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);	

rename("../../custom_page_content/$page_name_old.php", "../../custom_page_content/$page_name.php");

foreach(glob('../../template/*', GLOB_ONLYDIR) as $dir) {
$style_name = str_replace('../../template/', '', $dir);
rename("../../template/$style_name/$page_name_old.html", "../../template/$style_name/$page_name.html");
}

rename("../../$page_name_old.php", "../../$page_name.php");

write_utf8_file("../../custom_page_content/$page_name.php",$page_content);

 
echo json_encode(array('message' => '<br/><div style="max-width:700px;margin: 0 auto" class="alert alert-success"><i class="fa fa-check"></i> Успешно променена страница</div>','content' => $page_content,'page_title'=>$page_title,'page_name'=>$page_name)); //broikata


?>