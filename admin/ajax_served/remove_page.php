<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$go = mysqli_query($link,"SELECT * FROM pages WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($go);
@mysqli_free_result($go);
$pagename = $row['page_name'];


unlink("../../custom_page_content/$pagename.php");

foreach(glob('../../template/*', GLOB_ONLYDIR) as $dir) {
$style_name = str_replace('../../template/', '', $dir);
unlink("../../template/$style_name/$pagename.html");
}

unlink("../../$pagename.php");

$go = mysqli_query($link,"DELETE FROM pages WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);

echo json_encode(array('info' => "Страницата е успешно изтрита!", 'id' => $id));
?> 
