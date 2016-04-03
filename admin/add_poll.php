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

//submit poll
$submit_poll_acp[]="";
if(isset($_POST['submit_poll'])) {
	$poll_question = mysqli_real_escape_string($link,$_POST['poll_question']);
	$poll_answers = mysqli_real_escape_string($link,$_POST['poll_answers']);
    $poll_answers2= explode('\r\n',$poll_answers);
	$poll_answers = array('answers'=>$poll_answers2);
    
	foreach($poll_answers['answers'] as $v) { 
		$format_poll .= "$v##0;"; 
	}
	$format_poll = rtrim($format_poll, ';');
	
	$go = mysqli_query($link,"INSERT INTO dpolls (poll_question,poll_answer,poll_votes) VALUES('$poll_question','$format_poll','0')") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$submit_poll_acp = array('poll_add'=>'Успешно!');
}
//end submit poll
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_poll');  //име на темплейт файла
echo $tpl->render($values_acp +$contact_pm_acp + $submit_poll_acp); //принтираме всичко в страницата ($values+$values)