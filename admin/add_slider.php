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

//add slider
$slider_add_acp[]="";
if(isset($_POST['submit_slider'])) {
$slider_img = mysqli_real_escape_string($link,$_POST['slider_link_img']);	
$slider_link_enable = $_POST['slider_link_enable'];
switch($slider_link_enable) {
	case 'on': {
		$slider_link_enable = 1;
		break;
	}
	case '': {
		$slider_link_enable = 0;
		break;
	}
}
$slider_link = mysqli_real_escape_string($link,$_POST['slider_link']);	
$slider_text = mysqli_real_escape_string($link,$_POST['slider_text']);

if($slider_link_enable == 1) {
	$go = mysqli_query($link,"INSERT INTO sliders (`slider_img`, `is_link`,`slider_link`,`text`) VALUES('$slider_img','1','$slider_link','$slider_text')") or die(mysqli_error($link));
	@mysqli_free_result($go);
} else {
	$go = mysqli_query($link,"INSERT INTO sliders (`slider_img`, `is_link`,`slider_link`,`text`) VALUES('$slider_img','0','$slider_link','$slider_text')") or die(mysqli_error($link));
	@mysqli_free_result($go);	
}
	$slider_add_acp = array('slider_add'=>'Успешно!');
}
//end slider
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_slider');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $slider_add_acp); //принтираме всичко в страницата ($values+$values)