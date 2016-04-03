<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$go = mysqli_query($link,"DELETE FROM news WHERE id='$id'") or die(mysqli_error($link));

@mysqli_free_result($go);

echo json_encode(array('info' => "Новината е успешно изтрита!", 'id' => $id));
?> 
