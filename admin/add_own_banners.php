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

//add own banners
$banner_add_acp[] ="";
if(isset($_POST['submit_banner'])) {
	$banner_type = $_POST['banner_type'];
	 
	$banner_img = mysqli_real_escape_string($link,$_POST['banner_img']);
	$banner_link = 'http://'.$_SERVER['SERVER_NAME'] ;
	$banner_link_title = mysqli_real_escape_string($link,$_POST['banner_link_title']);
	$banner_author=mysqli_real_escape_string($link,$_POST['banner_author']);
	$go = mysqli_query($link,"INSERT INTO banners (type,site_link,banner_img,link_title,avtor) VALUES('$banner_type','$banner_link','$banner_img','$banner_link_title','$banner_author')") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$banner_add_acp = array('banner_add'=>'Успешно');
	
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_own_banners');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $banner_add_acp); //принтираме всичко в страницата ($values+$values)