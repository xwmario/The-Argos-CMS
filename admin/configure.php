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

//we set some default values
$get = mysqli_query($link,"SELECT * FROM config") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$chat_enable = $row['chat_enable'];
if($chat_enable == 0 || empty($chat_enable)){ 
	$chat_enable = "";
} else {
	$chat_enable = "checked";
}

$gallery_enable = $row['gallery_enable'];
if($gallery_enable == 0 || empty($gallery_enable)){ 
	$gallery_enable = "";
} else {
	$gallery_enable = "checked";
}

$img_upload_enable = $row['img_upload_enable'];
if($img_upload_enable == 0 || empty($img_upload_enable)){ 
	$img_upload_enable = "";
} else {
	$img_upload_enable = "checked";
}

$file_upload_enable = $row['file_upload_enable'];
if($file_upload_enable == 0 || empty($file_upload_enable)){ 
	$file_upload_enable = "";
} else {
	$file_upload_enable = "checked";
}

$poll_enable = $row['poll_enable'];
if($poll_enable == 0 || empty($poll_enable)){ 
	$poll_enable = "";
} else {
	$poll_enable = "checked";
}

$stats_enable = $row['footer_stats_enable'];
if($stats_enable == 0 || empty($stats_enable)){ 
	$stats_enable = "";
} else {
	$stats_enable = "checked";
}

$socials_enable = $row['socials_enable'];
if($socials_enable == 0 || empty($socials_enable)){ 
	$socials_enable = "";
} else {
	$socials_enable = "checked";
}

$servers_enable = $row['servers_enable'];
if($servers_enable == 0 || empty($servers_enable)){ 
	$servers_enable = "";
} else {
	$servers_enable = "checked";
}

$cookie_policy_enable = $row['cookie_policy'];
if($cookie_policy_enable == 0 || empty($cookie_policy_enable)){ 
	$cookie_policy_enable = "";
} else {
	$cookie_policy_enable = "checked";
}

$video_enable = $row['video_enable'];
if($video_enable == 0 || empty($video_enable)){ 
	$video_enable = "";
} else {
	$video_enable = "checked";
}

$rating_enable = $row['rating_enable'];
if($rating_enable == 0 || empty($rating_enable)){ 
	$rating_enable = "";
} else {
	$rating_enable = "checked";
}

$site_name = $row['site_name'];
$logo_text_small = $row['logo_text_small'];
$logo_text_big = $row['logo_text_big'];
$favicon = $row['favicon'];
$admin_email = $row['admin_email'];
$fb_link = $row['fb_link'];
$tw_link = $row['tw_link'];
$goo_link = $row['goo_link'];
$language = $row['default_language'];
$head_box_text = $row['head_box_text'];
$last_news_link = $row['last_news_link'];
$last_news_name = $row['last_news_name'];
$google_analytics = $row['google_analytics'];
$google_site_verify = $row['google_site_verify'];
$google_recaptcha_secret = $row['recaptcha_secret'];
$google_recaptcha_sitekey = $row['recaptcha_sitekey'];
$current_style = $row['style'];
if($language == "bg") { $language_bg = "selected"; }
if($language == "en") { $language_en = "selected"; }
if($language == "ru") { $language_ru = "selected"; }

//styles changer
foreach(glob('../template/*', GLOB_ONLYDIR) as $dir) {
    $style_name = str_replace('../template/', '', $dir);
	if($current_style == $style_name) {
		$all_styles .= '<option value="'.$style_name.'" selected>'.$style_name.'</option>'; 
	} else {
		$all_styles .= '<option value="'.$style_name.'">'.$style_name.'</option>'; 
	}
}
//end styles changer
 
$defvars = array('chat_enable'=>$chat_enable,'gallery_enable'=>$gallery_enable,'video_enable'=>$video_enable,'img_upload_enable'=>$img_upload_enable,'file_upload_enable'=>$file_upload_enable,'poll_enable'=>$poll_enable,'stats_enable'=>$stats_enable,'socials_enable'=>$socials_enable,'servers_enable'=>$servers_enable,'rating_enable'=>$rating_enable
,'site_name'=>$site_name, 'logo_text_small'=>$logo_text_small,'logo_text_big'=>$logo_text_big
,'favicon'=>$favicon, 'admin_email'=>$admin_email,'fb_link'=>$fb_link,'tw_link'=>$tw_link,'goo_link'=>$goo_link,'cookie_policy_enable'=>$cookie_policy_enable,'default_lang_bg'=>$language_bg,'default_lang_en'=>$language_en,'default_lang_ru'=>$language_ru,
'head_box_text'=>$head_box_text,'last_news_name'=>$last_news_name,'last_news_link'=>$last_news_link, 'google_analytics'=>$google_analytics, 'google_site_verify'=>$google_site_verify, 'google_recaptcha_secret' =>$google_recaptcha_secret,'google_recaptcha_sitekey'=>$google_recaptcha_sitekey,'styles_name'=>$all_styles);
//end

//submit site changes
$site_changes[] ="";
if(isset($_POST['submit_changes'])) {
	
switch($_POST['chat_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET chat_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET chat_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}
	
switch($_POST['gallery_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET gallery_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET gallery_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}

switch($_POST['img_upload_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET img_upload_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET img_upload_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}

switch($_POST['upload_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET file_upload_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET file_upload_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['poll_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET poll_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET poll_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['stats_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET footer_stats_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET footer_stats_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['socials_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET socials_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET socials_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['servers_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET servers_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET servers_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['cookie_policy_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET cookie_policy=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET cookie_policy=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['video_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET video_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET video_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

switch($_POST['rating_enable']) {
	case '':{
		$go = mysqli_query($link,"UPDATE config SET rating_enable=0") or die(mysqli_error($link));
		@mysqli_free_result($go);
		break;
	}
	case 'on':{ 
		$go = mysqli_query($link,"UPDATE config SET rating_enable=1") or die(mysqli_error($link));
		@mysqli_free_result($go);
	    break;
	}
}	

//other text
$site_name = mysqli_real_escape_string($link,$_POST['site_name']);
$go = mysqli_query($link,"UPDATE config SET site_name='$site_name'") or die(mysqli_error($link));
@mysqli_free_result($go);

$small_text_header = mysqli_real_escape_string($link,$_POST['small_text_header']);
$go = mysqli_query($link,"UPDATE config SET logo_text_small='$small_text_header'") or die(mysqli_error($link));
@mysqli_free_result($go);

$big_text_header = mysqli_real_escape_string($link,$_POST['big_text_header']);
$go = mysqli_query($link,"UPDATE config SET logo_text_big='$big_text_header'") or die(mysqli_error($link));
@mysqli_free_result($go);

$favicon = mysqli_real_escape_string($link,$_POST['favicon']);
$go = mysqli_query($link,"UPDATE config SET favicon='$favicon'") or die(mysqli_error($link));
@mysqli_free_result($go);

$admin_email = mysqli_real_escape_string($link,$_POST['admin_mail']);
$go = mysqli_query($link,"UPDATE config SET admin_email='$admin_email'") or die(mysqli_error($link));
@mysqli_free_result($go);

$fb_link = mysqli_real_escape_string($link,$_POST['fb_link']);
$go = mysqli_query($link,"UPDATE config SET fb_link='$fb_link'") or die(mysqli_error($link));
@mysqli_free_result($go);

$tw_link = mysqli_real_escape_string($link,$_POST['tw_link']);
$go = mysqli_query($link,"UPDATE config SET tw_link='$tw_link'") or die(mysqli_error($link));
@mysqli_free_result($go);

$goo_link = mysqli_real_escape_string($link,$_POST['goo_link']);
$go = mysqli_query($link,"UPDATE config SET goo_link='$goo_link'") or die(mysqli_error($link));
@mysqli_free_result($go);

$default_lang = mysqli_real_escape_string($link,$_POST['language_now']);
$go = mysqli_query($link,"UPDATE config SET default_language='$default_lang'") or die(mysqli_error($link));
@mysqli_free_result($go);

$last_news_link = mysqli_real_escape_string($link,$_POST['last_news_link']);
$go = mysqli_query($link,"UPDATE config SET last_news_link='$last_news_link'") or die(mysqli_error($link));
@mysqli_free_result($go);

$last_news_name = mysqli_real_escape_string($link,$_POST['last_news_name']);
$go = mysqli_query($link,"UPDATE config SET last_news_name='$last_news_name'") or die(mysqli_error($link));
@mysqli_free_result($go);

$head_box_text = mysqli_real_escape_string($link,$_POST['head_box_text']);
$go = mysqli_query($link,"UPDATE config SET head_box_text='$head_box_text'") or die(mysqli_error($link));
@mysqli_free_result($go);

$google_analytics = mysqli_real_escape_string($link,$_POST['google_analytics']);
$go = mysqli_query($link,"UPDATE config SET google_analytics='$google_analytics'") or die(mysqli_error($link));
@mysqli_free_result($go);

$google_site_verify = mysqli_real_escape_string($link,$_POST['google_site_verify']);
$go = mysqli_query($link,"UPDATE config SET google_site_verify='$google_site_verify'") or die(mysqli_error($link));
@mysqli_free_result($go);

$google_recaptcha_secret = mysqli_real_escape_string($link,$_POST['google_recaptcha_secret']);
$go = mysqli_query($link,"UPDATE config SET recaptcha_secret='$google_recaptcha_secret'") or die(mysqli_error($link));
@mysqli_free_result($go);

$google_recaptcha_sitekey = mysqli_real_escape_string($link,$_POST['google_recaptcha_sitekey']);
$go = mysqli_query($link,"UPDATE config SET recaptcha_sitekey='$google_recaptcha_sitekey'") or die(mysqli_error($link));
@mysqli_free_result($go);

$default_style= mysqli_real_escape_string($link,$_POST['style_now']);
$go = mysqli_query($link,"UPDATE config SET style='$default_style'") or die(mysqli_error($link));
@mysqli_free_result($go);
//end

$site_changes = array('site_changes'=>'Успешно');

}
//end

//del cache
$cache_delete[] ="";
if(isset($_POST['submit_cache'])) {
	unlink_recursive('../cache','php');
    $cache_delete = array('cache_delete'=>"Успешно!");
}
//end

//delete chat
$delete_chat[]="";
if(isset($_POST['submit_chat_del'])) {
	$go = mysqli_query($link,"TRUNCATE TABLE chat") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$delete_chat = array('chat_delete'=>"Успешно!");
}
//end delete chat

 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_configuration');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $cache_delete + $defvars + $site_changes + $delete_chat); //принтираме всичко в страницата ($values+$values)