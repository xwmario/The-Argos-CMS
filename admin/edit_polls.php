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


//catch all polls
include("../includes/pagination.php");

$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `dpolls`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 15, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM dpolls") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM dpolls order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $poll_id = $row['id'];
   $poll_question = $row['poll_question'];
   $poll_votes = $row['poll_votes'];
   $polls_info[]  = array('poll_id'=>$poll_id,'poll_question'=>$poll_question,'poll_votes'=>$poll_votes);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allpolls'] =new ArrayIterator( $polls_info); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_polls'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_polls'=>"<div class='alert alert-danger'>Няма анкети!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end catch


//check is this page
$is_poll_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_polls.php') !== false) {
	$is_poll_page_acp = array('is_poll_page'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_polls');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_poll_page_acp); //принтираме всичко в страницата ($values+$values)