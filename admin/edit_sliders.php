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


//edit sliders
include("../includes/pagination.php");

$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `sliders`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 15, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM sliders") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM sliders order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $slider_id = $row['id'];
   $slider_text= $row['text'];
   $slider_img= $row['slider_img'];
   if(filter_var($slider_img, FILTER_VALIDATE_URL)) { 
    $slider_img= $row['slider_img'];
   } else {
	    $slider_img= '../'.$row['slider_img'];
   }
   $sliders_info_acp[]  = array('slider_id'=>$slider_id,'slider_text'=>$slider_text,'slider_img'=>$slider_img);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allsliders'] =new ArrayIterator( $sliders_info_acp); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_sliders'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_sliders'=>"<div class='alert alert-danger'>Няма слайдери!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end

//check is this page
$is_sliders_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_sliders.php') !== false) {
	$is_sliders_page_acp = array('is_sliders_page'=>1);
}
//end

 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_sliders');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_sliders_page_acp); //принтираме всичко в страницата ($values+$values)