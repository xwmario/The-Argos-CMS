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

//catch all news
include("../includes/pagination.php");
 

$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `news`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 15, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM news") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM news order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $sender_id = $row['id'];
   $sender_username = $row['author'];
   $sender_date = date('d.m.y h:i:s',$row['date']);
   $sender_title = truncate_chars($row['title'],1,30,'...');
   $senders_info[]  = array('sender_username'=>$sender_username,'sender_title'=>$sender_title,'sender_date'=>$sender_date,'sender_id'=>$sender_id);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allnews'] =new ArrayIterator( $senders_info); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_news'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_news'=>"<div class='alert alert-danger'>Няма новини!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end catch

//check is this page
$is_news_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_news.php') !== false) {
	$is_news_page_acp = array('is_news_page'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_news');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_news_page_acp); //принтираме всичко в страницата ($values+$values)