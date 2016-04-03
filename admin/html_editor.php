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

//check is this page
$is_html_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/html_editor.php') !== false) {
	$is_html_page_acp = array('is_html_page'=>1);
}
//end

//select files for htmlarea
$thelist[]="";
if ($handle = opendir('../template/'.get_from_db_config('style').'')) {
while (false !== ($entry = readdir($handle))) {
if ($entry != "." && $entry != ".." && strtolower(substr($entry, strrpos($entry, '.') + 1)) == 'html')
{ 
$htmlfiles .="<option data-html='$entry' value='../template/".get_from_db_config('style')."/$entry'>$entry</option>\n";
$thelist = array('thelist'=> $htmlfiles); 
}  
}
closedir($handle);
}
//end select files for htmlarea
 
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_htmleditor');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $is_html_page_acp + $thelist); //принтираме всичко в страницата ($values+$values)