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

//catch all banners
include("../includes/pagination.php");
$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `banners`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 15, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM banners") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM banners order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $banner_id = $row['id'];
   $banner_author = $row['avtor'];
   $banner_img = $row['banner_img'];
   $banner_type = $row['type'];
   $banners_info_acp[]  = array('banner_id'=>$banner_id,'banner_author'=>$banner_author,'banner_img'=>$banner_img,'banner_type'=>$banner_type);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allbanners'] =new ArrayIterator( $banners_info_acp); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_banners'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_banners'=>"<div class='alert alert-danger'>Няма банери!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end catch

//check is this page
$is_ownbanners_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_own_banners.php') !== false) {
	$is_ownbanners_page_acp = array('is_ownbanners_page'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_own_banners');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_ownbanners_page_acp); //принтираме всичко в страницата ($values+$values)