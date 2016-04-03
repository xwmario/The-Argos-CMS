<?php
//language system
if(isset($_COOKIE['argos_en']) && $_COOKIE['argos_en'] == 1){
   require "lang/en/en.php"; 
} else
if(isset($_COOKIE['argos_bg']) && $_COOKIE['argos_bg'] == 1){
   require "lang/bg/bg.php"; 
} else if(isset($_COOKIE['argos_ru']) && $_COOKIE['argos_ru'] == 1){
	require "lang/ru/ru.php"; 
} else {
	$get = mysqli_query($link,"SELECT default_language FROM config");
	$row = mysqli_fetch_assoc($get);
	@mysqli_free_result($get);
	$default_lang = $row['default_language'];
	require "lang/$default_lang/$default_lang.php"; //начален език
}
 