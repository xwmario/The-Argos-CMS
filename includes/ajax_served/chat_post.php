<?php
if (empty($_SERVER['HTTP_REFERER'])){
echo "Die";
die();
}

require '../config.php'; //Нашия конфигурационен файл
$forum_path = "../../".$forum_path;
require '../phpbb_bridge.php'; //phpbb3 интеграцията


$date = time();
$convertd = date('d.m.y в H:i', $date);
$chattext=htmlspecialchars(mysqli_real_escape_string($link,trim($_POST["text1"])));
$user_ava=$_POST["ava"];

if (!strlen(trim($chattext)) || strlen($chattext) > 300) {
return;
}

$username=htmlspecialchars(mysqli_real_escape_string($link,trim($_POST["usernamec"])));
$go = mysqli_query($link,"INSERT INTO `chat` (name, text, date,avatar) VALUES('$username', '$chattext', '$convertd','$user_ava')") or die(mysqli_error($link));
@mysqli_free_result($go);
echo "Съобщението е изпратено!";
?>
