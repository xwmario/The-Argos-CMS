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

//jquery_js
$get = mysqli_query($link,"SELECT * FROM jquery_js");
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$jquery_js = htmlspecialchars_decode($row['jquery_js']);
$jquery_js_acp = array('jquery_js'=>$jquery_js);

$jquery_js_submit_acp[] ="";
if(isset($_POST['submit_jquery_js'])) {
	$jquery_js_post = mysqli_real_escape_string($link,stripcslashes($_POST['jquery_js']));
	$go = mysqli_query($link,"UPDATE jquery_js SET jquery_js='".$jquery_js_post."'") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$jquery_js_submit_acp = array('submit_jquery_js'=>'Успешно!');
}
//end jquery_js
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_jquery_js');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $jquery_js_submit_acp + $jquery_js_acp); //принтираме всичко в страницата ($values+$values)