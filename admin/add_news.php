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

//news add
$news_add_acp[]="";
if(isset($_POST['submit_news'])) {
	
	$news_add_acp=array('news_add'=>'Успешно!');
	
	$comments_enable = $_POST['news_comment_enable'];
	switch($comments_enable) {
		case '':{
			$comments_enable = 0;
			break;
		}
		case 'on': {
			$comments_enable = 1;
			break;
		}
	}
	
	$news_title = mysqli_real_escape_string($link,$_POST['news_name']);
	$news_poster = $_POST['admin_poster'];
	$seourl =  parse_cyr_en_url($news_title.'_'.date('d_m_Y',time()));
	$news_date = time();
	$news_text = mysqli_real_escape_string($link,$_POST['text']);
	$img = mysqli_real_escape_string($link,$_POST['img_link']);

	if(filter_var($img, FILTER_VALIDATE_URL)) {
		$img = $img;
	} else {
	    $img = "assets/img/news_img.png";	
	}
	$go = mysqli_query($link,"INSERT INTO news (author,title,seourl,text,date,comments_enabled,comments,img) VALUES('$news_poster','$news_title','$seourl','$news_text','$news_date','$comments_enable','0','$img')") or die(mysqli_error($link));
    @mysqli_free_result($go);
}
//end news add
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_news');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $news_add_acp); //принтираме всичко в страницата ($values+$values)