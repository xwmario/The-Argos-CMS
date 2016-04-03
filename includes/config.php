<?php
$link = mysqli_connect("localhost","root","pass","db") or die("Error " . mysqli_error($link)); //кънекция към db (mysqli)
mysqli_set_charset($link, "utf8");

//###PHPBB
$forum_path = "forum/"; //в коя папка е форума

//Greyfish
$greyfish_tw = ""; //twiter link
$greyfish_fb = ""; //fb link
$greyfish_go = ""; //g+ link
$greyfish_update = 300; //на колко секунди да ъпдейтва, default 5 минути (не променяй!)
