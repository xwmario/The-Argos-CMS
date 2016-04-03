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

$page_title = array('page_title'=>$lang_sys['lang_videos']); //title на страницата

//all functions
require 'includes/functions.php';

//check is this page
$is_video_page[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/videos.php') !== false) {
	$is_video_page = array('is_video_page'=>1);
}
//end 

//catch all cats
$getcats = mysqli_query($link,"SELECT * FROM videocat");
$getallcats[]="";
$allvcats ="";
while($row = mysqli_fetch_assoc($getcats)) {
	$allvcats.='<option value="'.$row['category'].'">'.$row['category'].'</option>'; 
}
$getallcats = array('allvcats' =>$allvcats);
@mysqli_free_result($getcats);
//end

//catch all pics
require("includes/pagination_news.php");

//select category and catch it
if(isset($_POST['submit_cat_ch'])) {
setcookie("lifted_cat", mysqli_real_escape_string($link,$_POST['cat_choose']),time()+3600);
echo "<meta http-equiv='refresh' content='0;url=".url()."/videos.php'>";
}
//end
$choosed_cat = $_COOKIE["lifted_cat"];

if(isset($_COOKIE["lifted_cat"])) {
$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM uploadvideos WHERE approved=1 AND cat='$choosed_cat'"), 0); // общия брой резултати
} else {
$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM uploadvideos WHERE approved=1"), 0); // общия брой резултати
}
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view'],
		'cat_choose' =>  mysqli_real_escape_string($link,$_GET['cat_choose']),
    ), 
    'per_page'  => 10, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

if(isset($_COOKIE["lifted_cat"])) {
$mysql_check = mysqli_query($link,"SELECT * FROM uploadvideos WHERE approved=1 AND cat='$choosed_cat'") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM uploadvideos WHERE approved=1 AND cat='$choosed_cat' order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
} else {
$mysql_check = mysqli_query($link,"SELECT * FROM uploadvideos WHERE approved=1") or die(mysqli_error($link));
$mysql = mysqli_query($link,"SELECT * FROM uploadvideos WHERE approved=1 order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
	
}
if(mysqli_num_rows($mysql_check)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $video_date = date('d.m.y :: h:i',$row['date']);
   $video_uploader = $row['uploader'];
   $video_link = $row['videolink'];
   $video_site = $row['site'];
   $video_title = $row['original_title'];
   if($video_site =="vbox") {
	   	$pieces1 = explode("play:", $video_link);
	   	$vid = $pieces1[1];
	    $vbox_api = file_get_contents("http://vbox7.com/etc/ext.do?key=$vid");
		$vbox_api = get_string_between($vbox_api, '&jpg_addr=', '&subs');
        $video_pic = 'http://'.$vbox_api;
		$video_link = "http://vbox7.com/emb/external.php?vid=$vid";
   } else {
	   $pieces2 = explode("watch?v=", $video_link);
	   $vid = $pieces2[1];
	   $video_pic = "http://img.youtube.com/vi/$vid/default.jpg";
   }
   $video_info[]  = array('video_date'=>$video_date,'video_uploader'=>$video_uploader,'video_link'=>$video_link,'video_pic'=>$video_pic,'video_title'=>$video_title,'video_site'=>$video_site);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values5['allvideos'] =new ArrayIterator( $video_info); 

$pagination_out = $pagination['output'];
$values6 = array('pagination_videos'=>$pagination_out);
} else {
	$values5 =  array('no_have_videos'=>"<br/><div class='alert alert-info'><i class='fa fa-exclamation-triangle'></i> ".$lang_sys['lang_no_videos']."</div>");
	$values6[] ="";
}
@mysqli_free_result($mysql_check);
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('videos');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values3 + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $sliders + $is_video_page + $values5 + $values6 + $getallcats); //принтираме всичко в страницата ($values+$values)