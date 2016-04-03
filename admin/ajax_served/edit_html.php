<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}
 

$content_html = stripcslashes($_POST['content']);
$file_name = $_POST['html_file'];	
file_put_contents('../'.$file_name, $content_html);
echo '<br/><div class="alert alert-success">Успешно</div>';
?>