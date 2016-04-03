<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$go = mysqli_query($link,"SELECT * FROM videocat WHERE id='$id'");
$row = mysqli_fetch_assoc($go);
@mysqli_free_result($go);
$video_cat = $row['category'];

$go = mysqli_query($link,"DELETE FROM uploadvideos WHERE cat='$video_cat'") or die(mysqli_error($link));
@mysqli_free_result($go);
$go = mysqli_query($link,"DELETE FROM videocat WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);

echo json_encode(array('info' => "Категорията и клиповете в нея са успешно изтрити!", 'id' => $id));
?> 
