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

$page_title = array('page_title'=>$lang_sys['lang_gallery']); //title на страницата

//all functions
require 'includes/functions.php'; //funcs


//check is this page
$is_gallery_page[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/gallery.php') !== false) {
	$is_gallery_page = array('is_gallery_page'=>1);
}
//end 

//catch all pics
require("includes/pagination_news.php");
$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `gallery`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 10, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM gallery") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM gallery order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $pic_date = date('d.m.y :: h:i',$row['date']);
   $pic_uploader = $row['uploader'];
   $pic_link = $row['pic_link'];
   $gallery_info[]  = array('pic_date'=>$pic_date,'pic_uploader'=>$pic_uploader,'pic_link'=>$pic_link);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values5['allpictures'] =new ArrayIterator( $gallery_info); 

$pagination_out = $pagination['output'];
$values6 = array('pagination_gallery'=>$pagination_out);
} else {
	$values5 =  array('no_have_pics'=>"<br/><div class='alert alert-info'><i class='fa fa-exclamation-triangle'></i> ".$lang_sys['lang_no_pics']."</div>");
	$values6[] ="";
}
@mysqli_free_result($mysql_check);
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('gallery');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values3 + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $sliders + $is_gallery_page + $values5 + $values6); //принтираме всичко в страницата ($values+$values)