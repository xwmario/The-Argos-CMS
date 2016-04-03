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

//banners
$banner_add_acp[] ="";
if(isset($_POST['submit_banner'])) {
	$type = $_POST['type'];
	$aktivnost = $_POST['aktivnost'];
	switch($aktivnost) {
		case '30': {
			$aktivnost = '2629743';
			break;
		}
		case '7': {
			$aktivnost = '604800';
			break;
		}
		case '0':{
			$aktivnost = '9999999999999999';
			break;
		}
	}
	$img_link = mysqli_real_escape_string($link,$_POST['img_link']);
	$img_banner = mysqli_real_escape_string($link,$_POST['img_banner']);
	$title_b = mysqli_real_escape_string($link,$_POST['link_title']);
	$dobaven_na = time();
	$go = mysqli_query($link,"INSERT INTO advertise (type,site_link,banner_img,expire,link_title,dobaven_na) VALUES('$type','$img_link','$img_banner','$aktivnost','$title_b','$dobaven_na')") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$banner_add_acp = array('banner_add'=>'Успешно');
	
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_banners');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $banner_add_acp); //принтираме всичко в страницата ($values+$values)