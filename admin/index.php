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


//daily stats
$mysql=mysqli_query($link,"SELECT *,COUNT(id) as visitors, DATE_FORMAT( date, '%b-%d' ) as dat from stats GROUP by dat order by `date` DESC LIMIT 5") or die(mysqli_error($link)); 

while($rows=mysqli_fetch_assoc($mysql)){
	$stats_date = $rows['dat'];
	$stats_visitors = $rows['visitors'];
	$daily_stats[] = array( 'stats_date'=>$stats_date,'stats_visitors'=>$stats_visitors,);
}
@mysqli_free_result($mysql);
$values2_acp['daily_stats'] =new ArrayIterator( $daily_stats ); 
//end



//get latest members//
$mysql = mysqli_query($link,"SELECT username,user_id,user_regdate,user_avatar,user_avatar_type FROM `$bb_db`.".$bb_prefix."_users WHERE  group_id!=6 AND group_id!=1 AND user_type!=1 Order by user_id DESC LIMIT 8") or die(mysqli_error($link));
while($row = mysqli_fetch_assoc($mysql)) {
$id = $row['user_id'];
$regdate = date('d.m.y',$row['user_regdate']);	
$new_username = $row['username'];
$user_avatar_type = $row['user_avatar_type'];
$user_avatar_real = $row['user_avatar'];

switch($user_avatar_type) {
	case '': {
		$user_avatar = "../assets/img/no_avatar.png";
		break;
	}
	case 'avatar.driver.upload': {
		$user_avatar = "../".preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path)."/download/file.php?avatar=".$user_avatar_real."";
		break;
	}
	case 'avatar.driver.remote': {
		$mysql2 = mysqli_query($link,"SELECT user_avatar FROM `$bb_db`.".$bb_prefix."_users WHERE user_id=".$id."") or die(mysqli_error($link));
		$fetchrow = mysqli_fetch_assoc($mysql2);
		@mysqli_free_result($mysql2);
		$avatar_link = $fetchrow['user_avatar'];
		$user_avatar = $avatar_link;
		break;
	}
	case 'avatar.driver.local': {
		$mysql2 = mysqli_query($link,"SELECT user_avatar FROM `$bb_db`.".$bb_prefix."_users WHERE user_id=".$id."") or die(mysqli_error($link));
		$fetchrow = mysqli_fetch_assoc($mysql2);
		@mysqli_free_result($mysql2);
		$avatar_mail = $fetchrow['user_avatar'];
		$user_avatar = $fetchrow['user_avatar'];
		$user_avatar = "../".preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path)."/images/avatars/gallery/$user_avatar_real";
		break;
	}
	case 'avatar.driver.gravatar': {
		$mysql2 = mysqli_query($link,"SELECT user_avatar FROM `$bb_db`.".$bb_prefix."_users WHERE user_id=".$id."") or die(mysqli_error($link));
		$fetchrow = mysqli_fetch_assoc($mysql2);
		@mysqli_free_result($mysql2);
		$avatar_mail = $fetchrow['user_avatar'];
		$user_avatar = get_gravatar($avatar_mail);
		break;
	}	
}

$newest_members[]  = array('new_userid'=>$id,'new_user_ava'=>$user_avatar,'new_username'=>$new_username,'new_user_regdate'=>$regdate,);
	
}
@mysqli_free_result($mysql);
$values3_acp['newest_members'] =new ArrayIterator( $newest_members ); 
//end



//check is this page
$is_index_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/index.php') !== false || strpos($_SERVER["REQUEST_URI"], "/admin/") !== false ) {
	$is_index_page_acp = array('is_index_page_acp'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('index');  //име на темплейт файла
echo $tpl->render($values_acp + $values2_acp + $values3_acp + $contact_pm_acp + $is_index_page_acp); //принтираме всичко в страницата ($values+$values)