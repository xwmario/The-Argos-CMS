<?php
//check install dir
if(file_exists('install')) {
	header('location: install/');
	exit();
}
//end check install dir

require 'Mustache/Autoloader.php'; //нужни неща за Mustache
require 'includes/config.php'; //Нашия конфигурационен файл
require 'includes/language_sys.php'; //lang
require 'includes/phpbb_bridge.php'; //phpbb3 интеграцията

Mustache_Autoloader::register(); //Регистрираме всичко от Autoloader-a

$options =  array('extension' => '.html');

//get style name
$get_style = mysqli_query($link,"SELECT style FROM config") or die(mysqli_error($link));
$row_style = mysqli_fetch_assoc($get_style);
@mysqli_free_result($get_style);
$current_style = $row_style['style'];

$mustache = new Mustache_Engine(array( //декларираме обект
    'template_class_prefix' => '__argos_', //Префикс на кеша
    'cache' => dirname(__FILE__).'/cache', //папка за кеша
	'loader' => new Mustache_Loader_FilesystemLoader("template/$current_style", $options), //папка за темплейт файловете
));

//page name check
$pagename =  basename($_SERVER['PHP_SELF']);
$pagename = explode('.php',$pagename);
$pagename = $pagename[0];
$page_name_get = mysqli_query($link,"SELECT * FROM pages WHERE page_name='$pagename'") or die(mysqli_error($link));
$row2 = mysqli_fetch_assoc($page_name_get);
@mysqli_free_result($page_name_get);
//

$page_title = array('page_title'=>$row2['page_title']); //title на страницата

require 'includes/functions.php'; //funcs

//custom page content
 $mustache->addHelper('legacy', array(
    'php' => function() {
		global $pagename;
        ob_start();
        include 'custom_page_content/'.$pagename.'.php';
        return ob_get_clean();
    },
)); 
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate($pagename);  //име на темплейт файла
@mysqli_free_result($page_name_get);
echo $tpl->render($page_title + $values + $values3 + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $sliders ); //принтираме всичко в страницата ($values+$values)