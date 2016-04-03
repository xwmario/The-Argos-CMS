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


//catch all comments
include("../includes/pagination.php");
 

$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `comments`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 10, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM comments") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM comments order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}")  ;
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $sender_id = $row['id'];
   $sender_username = $row['author'];
   $news_id = $row['newsid'];
   
   //news info
   $get = mysqli_query($link,"SELECT title,seourl FROM news WHERE id='$news_id'") or die(mysqli_error($link));
   $row2 = mysqli_fetch_assoc($get);
   @mysqli_free_result($get);
   $news_title = $row2['title'];
   $seourl =$row2['seourl'];
   //end
   
   $sender_date = date('d.m.y h:i:s',$row['date']);
   $sender_text = $row['text'];
   $sender_text=str_replace(":)", "<img src=\"http://i.imgur.com/kM0PdWU.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(sad)", "<img src=\"http://i.imgur.com/KVnuEHL.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace(":D", "<img src=\"http://i.imgur.com/t2RAAD9.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace(";)", "<img src=\"http://i.imgur.com/LEYnCi4.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(coffee)", "<img src=\"http://i.imgur.com/n34xOuy.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(welcome)", "<img src=\"http://i.imgur.com/tnadycC.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(palav)", "<img src=\"http://i.imgur.com/OjdJK0B.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(beer)", "<img src=\"http://i.imgur.com/pQtOgpA.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(novsum)", "<img src=\"http://i.imgur.com/6v8D8LH.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(kiss)", "<img src=\"http://i.imgur.com/eIBPRfY.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(cry)", "<img src=\"http://i.imgur.com/BaTMEhT.gif\"   border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(gathering)", "<img src=\"http://i.imgur.com/nscGiyH.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(razz)", "<img src=\"http://i.imgur.com/mUKxhiJ.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(cool)", "<img src=\"http://i.imgur.com/Avhzv6O.gif\"   border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(fuck)", "<img src=\"http://i.imgur.com/4ak5jY8.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(curssing)", "<img src=\"http://i.imgur.com/0YuswtC.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(oops)", "<img src=\"http://i.imgur.com/xtTjece.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(frown)", "<img src=\"http://i.imgur.com/QstPKpR.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(omg)", "<img src=\"http://i.imgur.com/xUtVwW3.gif\"  border='0' alt='' />", $sender_text);
   $sender_text=str_replace("(bg)", "<img src=\"http://i.imgur.com/I11A4iw.png\"  border='0' alt='' />", $sender_text);

   $sender_color = $row['nick_colour'];
   $senders_info[]  = array('news_title'=>$news_title,'seourl'=>'topic_'.$seourl,'sender_color'=>$sender_color,'sender_username'=>$sender_username,'sender_text'=>$sender_text,'sender_date'=>$sender_date,'sender_id'=>$sender_id);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values3_acp['allcomments'] =new ArrayIterator( $senders_info); 

$pagination_out = $pagination['output'];
$values4_acp = array('pagination_comments'=>$pagination_out);
} else {
	$values3_acp =  array('no_have_comments'=>"<div class='alert alert-danger'>Няма коментари!</div>");
	$values4_acp[] ="";
}
@mysqli_free_result($mysql_check);
//end catch
 
//check is this page
$is_comments_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_comments.php') !== false) {
	$is_comments_page_acp = array('is_comments_page'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_comments');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_comments_page_acp); //принтираме всичко в страницата ($values+$values)