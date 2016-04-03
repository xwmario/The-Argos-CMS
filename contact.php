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

$page_title = array('page_title'=>$lang_sys['lang_contacts']); //title на страницата

//all functions
require 'includes/functions.php';

//contact form//
$contact[] ="";//globalize
if(isset($_POST['submit_contact'])) {
	$your_name = mysqli_real_escape_string($link,$_POST['your-name']);
	$your_question = mysqli_real_escape_string($link,$_POST['your-question']);
	$your_email = mysqli_real_escape_string($link,$_POST['your-email']);
	$your_text = mysqli_real_escape_string($link,htmlspecialchars($_POST['your-text']));
	$captcha_response= $_POST['g-recaptcha-response'];
	 
if(empty($your_name)) {
$contact = array( 
	'submit_contact' => $lang_sys['lang_no_name_found'],
	'submit_contact_alert' => "danger",
	'submit_contact_ico' => "exclamation-circle",
);	
} else if(empty($your_question)) {
	$contact = array(
	'submit_contact' => $lang_sys['lang_no_question_found'],
	'submit_contact_alert' => "danger",
	'submit_contact_ico' => "exclamation-circle",
	);
	
} else if(empty($your_text)) {
	$contact = array(
	'submit_contact' => $lang_sys['lang_no_text_found'],
	'submit_contact_alert' => "danger",
	'submit_contact_ico' => "exclamation-circle",	
);
} else if(captcha($captcha_response) == true)	{

$contact = array( 
	'submit_contact' => $lang_sys['lang_success_contact'],
	'submit_contact_alert' => "success",
	'submit_contact_ico' => "check",	
);
$time = time();
$go = mysqli_query($link,"INSERT INTO contacts (`date`, `ip`,`username`, `text`, `question`, `email`) VALUES('$time','$bb_user_ip','$your_name', '$your_text','$your_question','$your_email')") or die(mysqli_error($link));
@mysqli_free_result($go);
} else {
	$contact = array(
	'submit_contact' => $lang_sys['lang_wrong_captcha'],
	'submit_contact_alert' => "danger",
	'submit_contact_ico' => "exclamation-circle",	
);	
}
}
//end contact form//
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('contact');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values3 + $values4 + $lang_sys + $contact  + $banner88x31 + $banner468x60 + $sliders + $get_menuz + $get_menuz2 + $poll_print + $poll_send_vote); //принтираме всичко в страницата ($values+$values)