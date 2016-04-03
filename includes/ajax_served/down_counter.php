<?php
if (empty($_SERVER['HTTP_REFERER'])){
echo "Die";
die();
}

$id = (int)$_GET['file_id'];
require '../config.php'; //Нашия конфигурационен файл

$go = mysqli_query($link,"UPDATE files SET down_counts=down_counts+1 WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);