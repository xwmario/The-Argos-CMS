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
$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `uploadvideos`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 15, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM uploadvideos") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM uploadvideos order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $video_id = $row['id'];
   $video_link = $row['videolink'];
   $video_approved = $row['approved'];
   if($video_approved == "0") {
	   $video_approved = "<a href='ajax_served/video_approved.php?id=$video_id&approved=1' class='approved_video' style='color:green'><i class='fa fa-check'></i></a>";
   } else {
	   $video_approved = "<a href='ajax_served/video_approved.php?id=$video_id&approved=0' class='approved_video' style='color:red'><i class='fa fa-times'></i></a>";
   }
   $video_title = $row['original_title'];
   $videos_info[]  = array('video_id'=>$video_id,'video_link'=>$video_link,'video_approved'=>$video_approved,'video_title'=>$video_title);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allvideos'] =new ArrayIterator( $videos_info); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_videos'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_videos'=>"<div class='alert alert-danger'>Няма клипове!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end catch
 
//check is this page
$is_video_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_videos.php') !== false) {
	$is_video_page_acp = array('is_video_page_acp'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_videos');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_video_page_acp); //принтираме всичко в страницата ($values+$values)