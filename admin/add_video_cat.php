<?php
require '../Mustache/Autoloader.php'; //нужни неща за Mustache
require '../includes/config.php'; //Нашия конфигурационен файл
$forum_path = "../".$forum_path;
require '../includes/phpbb_bridge.php'; //phpbb3 интеграцията
Mustache_Autoloader::register(); //Регистрираме всичко от Autoloader-a
$options =  array('extension' => '.html');

$mustache = new Mustache_Engine(array( //декларираме обект
    'template_class_prefix' => '__argos_', //Префикс на кеша
    'cache' => '../cache', //папка за кеша
	'loader' => new Mustache_Loader_FilesystemLoader('template',$options), //папка за темплейт файловете
));

require '../includes/functions.php'; //funcs
require 'includes/admin_functions.php';

//add menu
$add_video_cat_acp[] ="";
if(isset($_POST['submit_vcat'])) {
	$catname = mysqli_real_escape_string($link,$_POST['video_cat_add']);
    $go0 = mysqli_query($link,"SELECT * FROM videocat WHERE category='$catname'") or die(mysqli_error($link));
	if(mysqli_num_rows($go0) > 0) {
	$add_video_cat_acp = array('video_cat_add'=>'<div class="alert alert-danger"> Вече има такава категория!</div>');
	} else {
	$go1 = mysqli_query($link,"INSERT INTO videocat (`category`) VALUES('$catname')") or die(mysqli_error($link));
	@mysqli_free_result($go1);
	$add_video_cat_acp = array('video_cat_add'=>'<div class="alert alert-success"><i class="fa fa-check"></i> Успешно!</div>');
	}
	@mysqli_free_result($go0);
	 
}
//end add menu

 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_video_cat');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $add_video_cat_acp); //принтираме всичко в страницата ($values+$values)