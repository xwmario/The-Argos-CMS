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

//catch all emails
include("../includes/pagination.php");

$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `contacts`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 15, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM contacts") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM contacts order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $sender_id = $row['id'];
   $sender_username = $row['username'];
   $sender_date = date('d.m.y h:i:s',$row['date']);
   $sender_question = truncate_chars($row['question'],1,30,'...');
   $sender_respond = $row['respond'] ? 'да' : 'не';
   $sender_respond2 = $row['respond'];
   $sender_badge = $sender_respond2 ? 'green':'red';
   $senders_info[]  = array('sender_username'=>$sender_username,'sender_question'=>$sender_question,'sender_date'=>$sender_date,'sender_respond'=>$sender_respond,'sender_badge'=>$sender_badge,'sender_id'=>$sender_id);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allpms'] =new ArrayIterator( $senders_info); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_pm'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_pms'=>"<div class='alert alert-danger'>Няма съобщения!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end catch


//check is this page
$is_mails_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/view_emails.php') !== false) {
	$is_mails_page_acp = array('is_mails_page'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_view_mails');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_mails_page_acp); //принтираме всичко в страницата ($values+$values)