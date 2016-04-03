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

$page_title = array('page_title'=>$lang_sys['lang_home']); //title на страницата

//all functions
require 'includes/functions.php';



//fetch news
$values7[]=""; //globalize for comments submit
if(!isset($_GET['id'])) {
require("includes/pagination_news.php");
$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(`id`) FROM `news`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars'  => array(
        'cat'   => (int)@$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 10, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link,"SELECT * FROM news");
$mysql = mysqli_query($link,"SELECT * FROM news order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
if(mysqli_num_rows($mysql_check)>0) {
	
while ($row=mysqli_fetch_assoc($mysql)) { 
   $news_id = $row['id'];
   $news_username = $row['author'];
   $news_date = date('d.m.y в h:i',$row['date']);
   $news_title = truncate_chars($row['title'],1,30,'...');
   $news_comments = $row['comments'];
   $news_text = $row['text'];
   $news_votes = $row['vote'];
   if(is_html($news_text)) {
   $news_text = htmlspecialchars_decode($row['text']);
   } else {
	 $news_text = truncate_chars(htmlspecialchars_decode(strip_word_html($row['text'])),1,400,'...');  
   }
   $news_img = $row['img'];
   $news_seourl = $row['seourl'];
   $news_info[]  = array('news_username'=>$news_username,'news_title'=>$news_title,'news_date'=>$news_date,'news_votes'=>$news_votes,'news_id'=>$news_id,'news_comments'=>$news_comments,'news_text'=>$news_text,'news_img'=>$news_img,'news_seourl'=>$news_seourl);
   
}
@mysqli_free_result($results);
@mysqli_free_result($mysql);
$values5['allnews'] =new ArrayIterator( $news_info); 

$pagination_out = $pagination['output'];
$values6 = array('pagination_news'=>$pagination_out);
} else {
	$values5 =  array('no_have_news'=>"<div class='box'><div class='boxhead_L'><span class='boxhead_titles'><i class='fa fa-comment'></i> ".$lang_sys['lang_no_news']."</span></div><br/><div class='alert alert-info'><i class='fa fa-exclamation-triangle'></i> ".$lang_sys['lang_no_news']."</div></div>");
	$values6[] ="";
}
@mysqli_free_result($mysql_check);
} else { //specific preview (comments and other things)
	$url =  $_SERVER['REQUEST_URI'];
	$pieces = explode("topic_", $url);
	$newsearch = addslashes(trim(htmlspecialchars(mysqli_real_escape_string($link,$pieces[1]))));
	$get = mysqli_query($link,"SELECT * FROM news WHERE seourl='$newsearch'") or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($get);
	
	//18.01.2016 (check for unauthorized urls)
	if(mysqli_num_rows($get) < 1) {
		header('Location:index.php');
		exit();
	}
	//end
	@mysqli_free_result($get);
	
	$news_id = $row['id'];
	$news_username = $row['author'];
	$news_date = date('d.m.y в h:i',$row['date']);
	$news_title = $row['title'];
	$news_comments = $row['comments'];
	$news_text = htmlspecialchars_decode($row['text']);
	$news_img = $row['img'];
	$comments_enable = $row['comments_enabled'];
	$news_votes = $row['vote'];

	$get_comm = mysqli_query($link,"SELECT * FROM comments WHERE newsid='$news_id' order by id ASC") or die(mysqli_error($link));
	if(mysqli_num_rows($get_comm)>0) {
	while($row = mysqli_fetch_assoc($get_comm)) {
	$comment_id = $row['id'];
	$comment_votes = $row['vote'];
	$comment_author = $row['author'];
	$comment_text = $row['text'];
	$comment_text=str_replace(":)", "<img src=\"assets/img/emoticons/smile.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(sad)", "<img src=\"assets/img/emoticons/sad.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace(":D", "<img src=\"assets/img/emoticons/laught.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace(";)", "<img src=\"assets/img/emoticons/wink.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(coffee)", "<img src=\"assets/img/emoticons/coffee.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(welcome)", "<img src=\"assets/img/emoticons/hi.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(mocking)", "<img src=\"assets/img/emoticons/mocking.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(beer)", "<img src=\"assets/img/emoticons/beer.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(kiss)", "<img src=\"assets/img/emoticons/kiss.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(cry)", "<img src=\"assets/img/emoticons/cry.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace(":P", "<img src=\"assets/img/emoticons/tongue.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(confused)", "<img src=\"assets/img/emoticons/confused.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(dislike)", "<img src=\"assets/img/emoticons/dislike.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(heart)", "<img src=\"assets/img/emoticons/heart.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(poop)", "<img src=\"assets/img/emoticons/poop.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(skull)", "<img src=\"assets/img/emoticons/skull.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(sun)", "<img src=\"assets/img/emoticons/sun.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(blowing-heart)", "<img src=\"assets/img/emoticons/blowing-heart.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(exclamation)", "<img src=\"assets/img/emoticons/exclamation.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(heart-eyes)", "<img src=\"assets/img/emoticons/heart-eyes.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(pacman)", "<img src=\"assets/img/emoticons/pacman.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(sunglasses)", "<img src=\"assets/img/emoticons/sunglasses.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(warning)", "<img src=\"assets/img/emoticons/warning.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(curly_lips)", "<img src=\"assets/img/emoticons/curly_lips.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(hamburger)", "<img src=\"assets/img/emoticons/ham.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(lips)", "<img src=\"assets/img/emoticons/lips.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(piggy)", "<img src=\"assets/img/emoticons/piggy.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(santa)", "<img src=\"assets/img/emoticons/santa.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(thumb-up)", "<img src=\"assets/img/emoticons/thumb-up.png\"  border='0' alt='' />", $comment_text);
	$comment_text=str_replace("(umbrella)", "<img src=\"assets/img/emoticons/umbrella.png\"  border='0' alt='' />", $comment_text);
	$comment_date = date('d.m.y в h:i',$row['date']);
	$comment_ava = $row['avatar'];
	$comment_nick_color = $row['nick_colour'];
	$comment_userid = $row['user_id'];
	
	$newsinfo[]=array('comment_id'=>$comment_id,'comment_votes'=>$comment_votes,'comment_author'=>$comment_author,'comment_text'=>$comment_text,'comment_date'=>$comment_date,'comment_ava'=>$comment_ava,'comment_nick_color'=>$comment_nick_color,'comment_userid'=>$comment_userid);
	}
	$values5['allcomments'] =new ArrayIterator( $newsinfo); 
	} else {
	$values5= array('no_comments_here'=>'<div class="alert alert-warning"><i class="fa fa-volume-up"></i> '.$lang_sys['lang_no_comments'].'</div>');
	}
	@mysqli_free_result($get_comm);
	
	$values6=array('news_votes'=>$news_votes,'news_id'=>$news_id,'news_exists'=>1,'comments_enable'=>$comments_enable,'news_author'=>$news_username,'news_title'=>$news_title,'news_date'=>$news_date,'news_comments'=>$news_comments,'news_text'=>$news_text,'news_img'=>$news_img,);
	
	//submit comments
	if(isset($_POST['submit_comm'])) {
		$com_username = mysqli_real_escape_string($link,$_POST['com_username']);
		$com_ava = $_POST['com_user_ava'];
		$com_user_color = mysqli_real_escape_string($link,$_POST['com_user_color']);
		$com_text = mysqli_real_escape_string($link,htmlspecialchars($_POST['com_text']));
		if(empty($com_text)) {
		$values7[] = "";
		} else {
		$com_text = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@','URL disabled for chat!',$com_text);
		$com_date = time();
		$go = mysqli_query($link,"INSERT INTO comments (author,text,date,avatar,nick_colour,user_id,newsid) VALUES('$com_username','$com_text','$com_date','$com_ava','$com_user_color','$bb_user_id', '$news_id')") or die(mysqli_error($link));
		@mysqli_free_result($go);
		$go = mysqli_query($link,"UPDATE news SET comments=comments+1 WHERE id='$news_id'") or die(mysqli_error($link));
		@mysqli_free_result($go);
         
		$values7 = array('submit_com_suc'=>''.$lang_sys['lang_success'].'! <meta http-equiv="refresh" content="1">');
		}
	}
	//end submit comments//
}
//end news
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('index');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values2 + $values3 + $values4 + $lang_sys + $values5 + $values6 + $values7 + $banner88x31 + $banner468x60 + $sliders + $get_menuz + $get_menuz2 + $poll_print + $poll_send_vote); //принтираме всичко в страницата ($values+$values)