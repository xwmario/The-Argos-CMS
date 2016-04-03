<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$get = mysqli_query($link,"SELECT * FROM comments WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$news_id = $row['newsid'];

$go = mysqli_query($link,"UPDATE news SET comments=comments-1 WHERE id='$news_id'") or die(mysqli_error($link));
@mysqli_free_result($go);
$go = mysqli_query($link,"DELETE FROM comments WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
	
echo json_encode(array('info' => "Коментара е успешно изтрит!", 'id' => $id));
?> 
