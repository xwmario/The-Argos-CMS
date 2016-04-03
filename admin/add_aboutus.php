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

//aboutus
$get = mysqli_query($link,"SELECT * FROM aboutus");
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$aboutus = htmlspecialchars_decode($row['aboutus']);
$aboutus_acp = array('aboutus'=>$aboutus);

$aboutus_submit_acp[] ="";
if(isset($_POST['submit_aboutus'])) {
	$aboutus_post = mysqli_real_escape_string($link,$_POST['aboutus']);
	$go = mysqli_query($link,"UPDATE aboutus SET aboutus='$aboutus_post'") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$aboutus_submit_acp = array('submit_aboutus'=>'Успешно!');
}
//end aboutus
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_aboutus');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $aboutus_submit_acp + $aboutus_acp); //принтираме всичко в страницата ($values+$values)