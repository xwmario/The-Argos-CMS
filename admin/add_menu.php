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
$menuz_acp[] ="";
if(isset($_POST['submit_menu'])) {
	$menu_name = mysqli_real_escape_string($link,$_POST['menu_name']);
	$menu_text = stripcslashes( mysqli_real_escape_string($link,$_POST['menu_text']));
	$menu_pos = mysqli_real_escape_string($link,$_POST['position_menu']);
	 
	$go = mysqli_query($link,"INSERT INTO menus (`title`,`the_content`,`position`) VALUES('$menu_name','$menu_text','$menu_pos')") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$menuz_acp = array('menu_add'=>'Успешно!');
}
//end add menu
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_menu');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $menuz_acp); //принтираме всичко в страницата ($values+$values)