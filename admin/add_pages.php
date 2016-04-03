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


//pages add
$page_add_acp[]="";
if(isset($_POST['submit_page'])) {

	$page_title = mysqli_real_escape_string($link,$_POST['page_title']);
	$page_name = mysqli_real_escape_string($link,$_POST['page_name']);
	if($page_name != "aboutus" && $page_name != "banners" && $page_name != "contact" && $page_name != "files" && $page_name != "gallery" && $page_name != "index" && $page_name != "servers" && $page_name != "upload_img") {
	$page_content = stripcslashes($_POST['page_content']);
	$page_type = mysqli_real_escape_string($link,$_POST['page_type']);
	
   write_utf8_file("../custom_page_content/$page_name.php",$page_content);
   //end

	if($page_type=="wmenu") {
		copy('new_pages_defaults/wmenu.php', '../'.$page_name.'.php');
		foreach(glob('../template/*', GLOB_ONLYDIR) as $dir) {
		$style_name = str_replace('../template/', '', $dir);
		copy('new_pages_defaults/'.$style_name.'/wmenu.html', '../template/'.$style_name.'/'.$page_name.'.html');
		}
	} else {
		copy('new_pages_defaults/menu.php', '../'.$page_name.'.php');
		foreach(glob('../template/*', GLOB_ONLYDIR) as $dir) {
		$style_name = str_replace('../template/', '', $dir);
		copy('new_pages_defaults/'.$style_name.'/menu.html', '../template/'.$style_name.'/'.$page_name.'.html');
		}
	}
	$go = mysqli_query($link,"INSERT INTO pages (page_name,page_title,menu_type) VALUES('$page_name','$page_title','$page_type')") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$page_add_acp=array('pages_add'=>'<div class="alert alert-success"><i class="fa fa-check"></i>Успешно!</div>');
	} else {
	$page_add_acp=array('pages_add'=>'<div class="alert alert-danger"> Има вече такава страница!</div>');	
	}
  
}
//end page add
 

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_pages');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $page_add_acp); //принтираме всичко в страницата ($values+$values)