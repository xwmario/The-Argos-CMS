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

$page_title = array('page_title'=>$lang_sys['lang_files']); //title на страницата

//all functions
require 'includes/functions.php';


//catch files
$values5[]=""; //pagination
$get_specific_files[] =""; //when all is ready (durji krainite failove, sled kato vsichko e izbrano game,type,cat)
$game_set[] ="";
if(isset($_GET['game'])) {
	$game_set = array('game_set'=>1,'game_type'=>$_GET['game']);
}

if(isset($_GET['type']) && isset($_GET['game'])) {
	$type = (int)$_GET['type'];
	$game = (int)$_GET['game'];
	$get = mysqli_query($link,"SELECT * FROM files WHERE type_not_real='$type' AND game_not_real='$game' GROUP by category") or die(mysqli_error($link));
	if(mysqli_num_rows($get) > 0) {
		
		$get_files .= "
		<form method='post'>
		<select name='choose_cat' class='form-control' style='max-width:300px;display:inline-block'>
		<option value=''>".$lang_sys['lang_choose']."</option>";
		while($row = mysqli_fetch_assoc($get)) {
		$category_get = $row['category'];
		if($_COOKIE['argos_en'] == 1) {
			switch($category_get) {
				case 'Админ команди': {
					$category_get2 = "Admin commands";
					break;
				}
				case 'Общо предназначение': {
					$category_get2 = "General Purpose";
					break;
				}
				case 'Статистически': {
					$category_get2 = "Statistical";
					break;
				}
				case 'Геймплей': {
					$category_get2 = "Gameplay";
					break;
				}
				case 'Събития': {
					$category_get2 = "Events";
					break;
				}
				case 'Управление на сървър': {
					$category_get2 = "Server Manage";
					break;
				}
				case 'Забавни': {
					$category_get2 = "Funny";
					break;
				}
				case 'Технически': {
					$category_get2 = "Technical";
					break;
				}
				case 'Всякакви': {
					$category_get2 = "Any";
					break;
				}
			}
			$get_files .= "<option value='$category_get'>$category_get2</option>";
		} else {
			$get_files .= "<option value='$category_get'>$category_get</option>";
		}
		}
		$get_files .= "
		</select>
		<input type='submit' value='".$lang_sys['lang_send']."' name='submit_cat' class='btn btn-md btn-success'/>
		</form>
		";
		if(isset($_POST['submit_cat'])) {
			$choosed_cat = mysqli_real_escape_string($link,$_POST['choose_cat']);
			$get_current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			header('Location: '.$get_current_url.'&cat='.$choosed_cat.'');
			exit();
		}
		
	} else {
		$get_files = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.$lang_sys['lang_no_files'].'</div>';
	}
	@mysqli_free_result($get);
	$game_set = array('game_set'=>1,'game_type'=>$_GET['game'],'choose_cat'=>1,'get_files'=>$get_files);
}
if(isset($_GET['type']) && isset($_GET['game']) && isset($_GET['cat'])) {

	$file_type = (int)$_GET['type'];
	$file_game = (int)$_GET['game'];
	$file_cat = mysqli_real_escape_string($link,$_GET['cat']);
	
	require("includes/pagination_files.php");
	$results    = mysqli_result(mysqli_query($link,"SELECT COUNT(id) FROM files WHERE type_not_real='$file_type' AND game_not_real='$file_game' AND category='$file_cat'"), 0); // общия брой резултати
	$pagination = pagination($results, array(
    'get_vars'  => array(
        'view'  => @$_GET['view']
    ), 
    'per_page'  => 10, // по колко резултата да се показват на страница
    'per_side'  => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name'  => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
	
	), $file_game, $file_cat, $file_type);
    
	$pagination_check = mysqli_query($link,"SELECT * FROM files WHERE type_not_real='$file_type' AND game_not_real='$file_game' AND category='$file_cat' order by id DESC"); //only for check
	$get = mysqli_query($link,"SELECT * FROM files WHERE type_not_real='$file_type' AND game_not_real='$file_game' AND category='$file_cat' order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
	if(mysqli_num_rows($pagination_check) > 0) {
		while($row = mysqli_fetch_assoc($get)) {
			$file_id = $row['id'];
			$file_link = $row['link'];
			$opisanie = $row['opisanie'];
			$file_author = $row['author'];
			$file_date = date('d.m.Y :: h:i',$row['date']);
			$down_counts = $row['down_counts'];
			$file_size = $row['size'];
			$file_img = $row['picture'];
			$file_name = $row['name'];
			
			$get_all_in_cat[] = array('file_id'=>$file_id,'file_name'=>$file_name,'file_img'=>$file_img,'file_size'=>$file_size,'file_author'=>$file_author,'file_date'=>$file_date,'file_opisanie'=>$opisanie,'file_link'=>$file_link,'file_down_counts'=>$down_counts);
		}
		$pagination_out = $pagination['output'];
        $values5 = array('file_pagination'=>$pagination_out);
		
		$get_specific_files['all_spec_files'] =new ArrayIterator($get_all_in_cat); 
	} else {
		$values5[] ="";
		$get_specific_files = array('no_files_here'=>'<br/><div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.$lang_sys['lang_no_files'].'</div>');
	}
	@mysqli_free_result($get);
	
	$game_set = array('game_set'=>1,'game_type'=>$_GET['game'],'choose_cat'=>1,'get_files'=>$get_files,'files_is_ready'=>1);
	
}
	
//end catch


//check is this page
$is_files_page[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/files.php') !== false) {
	$is_files_page = array('is_files_page'=>1);
}
//end
 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('files');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values3 + $values4 + $values5 + $lang_sys + $banner88x31 + $banner468x60 + $sliders + $game_set + $get_specific_files + $is_files_page); //принтираме всичко в страницата ($values+$values)