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

$page_title = array('page_title'=>$lang_sys['lang_upload_video']); //title на страницата

//all functions
require 'includes/functions.php';


//get all video cats
$getcats = mysqli_query($link,"SELECT category FROM videocat");
$getvcats[]="";
if(mysqli_num_rows($getcats)>0) {
	while($row=mysqli_fetch_assoc($getcats)) {
		$cats .= '<option value="'.$row['category'].'">'.$row['category'].'</option>';
		$getvcats = array('allvcats'=>$cats);
	}
}
//end


//submit video
$submit_video[]="";
if(isset($_POST['submit_video'])) {
	
	 
	$video_site = mysqli_real_escape_string($link,$_POST['video_site']);
	$video_url = mysqli_real_escape_string($link,$_POST['videoid']);
	$video_title = mysqli_real_escape_string($link,$_POST['video_title']);
	$video_cat = mysqli_real_escape_string($link,$_POST['video_category']);
	$date_video = time();
 
    if($video_cat == "0") {
		$submit_video = array('submit_video'=>'<br/><div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> '.$lang_sys['lang_not_choosed_cat'].'</div>');
	} else if($video_site == "0") {
		$submit_video = array('submit_video'=>'<br/><div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> '.$lang_sys['lang_not_choosed_site'].'</div>');
	} else {
	$check = mysqli_query($link,"SELECT videolink FROM `uploadvideos` WHERE `videolink`='$video_url'") or die(mysqli_error($link));
	if(mysqli_num_rows($check) > 0){
		$submit_video = array('submit_video'=>'<br/><div class="alert alert-info"><i class="fa fa-exclamation-triangle"></i> '.$lang_sys['lang_already_has_video'].'</div>');
	} else {
		$submit_video = array('submit_video'=>'<br/><div class="alert alert-success"><i class="fa fa-check"></i> '.$lang_sys['lang_success_submit_video'].'</div>');
		$go = mysqli_query($link,"INSERT INTO uploadvideos (`uploader`,`videolink`,`date`,`cat`,`site`, `approved`,`original_title`) VALUES('$bb_username','$video_url','$date_video','$video_cat','$video_site','0','$video_title')") or die(mysqli_error($link));
		@mysqli_free_result($go);
	}
	@mysqli_free_result($check);
	}
	
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('upload_video');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values3 + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $sliders + $getvcats +$submit_video); //принтираме всичко в страницата ($values+$values)