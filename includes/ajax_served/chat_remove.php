<?php
if (empty($_SERVER['HTTP_REFERER'])){
echo "Die";
die();
}
require '../config.php'; //Нашия конфигурационен файл
$forum_path = "../../".$forum_path;
require '../phpbb_bridge.php'; //phpbb3 интеграцията

if($bb_is_admin) {
$id = (int)$_GET['id'];	
$mysql = mysqli_query($link,"DELETE FROM chat WHERE id='$id' LIMIT 1") or die(mysqli_error($link));
@mysqli_free_result($mysql);
echo "Успешно изтрито съобщение #$id";
}
?>