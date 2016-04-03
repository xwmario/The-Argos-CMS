<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 
$approved = (int)$_GET['approved'];
if($approved == "1") {
$go = mysqli_query($link,"UPDATE uploadvideos SET approved=1 WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
$message = "Клипа е успешно одобрен!";	
} else {
$go = mysqli_query($link,"UPDATE uploadvideos SET approved=0 WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
$go = mysqli_query($link,"REMOVE FROM uploadvideos WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
$message = "Клипа е успешно отказан!";
}
 
	

echo json_encode(array('info' => $message, 'id' => $id));
?> 
