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

//files upload
$files_up_acp[] ="";

$file_type = $_POST['dropdown_type'];
switch($file_type) {
	case '1': {
		$file_type_real = "Плъгин";
		break;
	}
	case '2': {
		$file_type_real = "Карта";
		break;
	}
	case '3': {
		$file_type_real = "Скин";
		break;
	}
	case '4': {
		$file_type_real = "Програма";
		break;
	}
}
$file_game = $_POST['dropdown_game'];
switch($file_game) {
	case '1': {
		$file_game_real = "CS 1.6";
		break;
	}
	case '2': {
		$file_game_real = "CS:GO";
		break;
	}
	case '3': {
		$file_game_real = "SAMP";
		break;
	}
	case '4': {
		$file_game_real = "Minecraft";
		break;
	}
}
if($file_type != 0 && $file_game != 0) {

$ready_template[]="";//globalize

if(($file_game == 1 || $file_game ==2 || $file_game ==3 || $file_game ==4) && $file_type == 1) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="" selected="selected"> - Избери - </option>
		<option value="Админ команди">Админ команди</option>
		<option value="Общо предназначение">Общо предназначение</option>
		<option value="Статистически">Статистически</option>
		<option value="Геймплей">Геймплей</option>
		<option value="Събития">Събития</option>
		<option value="Управление на сървър">Управление на сървър</option>
		<option value="Забавни">Забавни</option>
		<option value="Технически">Технически</option>
		<option value="Всякакви">Всякакви</option>
		</select><br/><br/>
		';
}

if(($file_game == 1 || $file_game ==2) && $file_type == 2) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="" selected="selected"> - Избери - </option>
		<option value="aim">aim</option>
		<option value="awp">awp</option>
		<option value="bb">bb</option>
		<option value="cs">cs</option>
		<option value="de">de</option>
		<option value="deathrun">deathrun</option>
		<option value="fy">fy</option>
		<option value="gg">gg</option>
		<option value="hns">hns</option>
		<option value="jb">jb</option>
		<option value="jump">jump</option>
		<option value="zm">zm</option>
		</select><br/><br/>';
}
	
if($file_game == 1 && $file_type == 3) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="" selected="selected"> - Избери - </option>
		<option value="ak47">ak47</option>
		<option value="awp">awp</option>
		<option value="deagle">deagle</option>
		<option value="g3sg1">g3sg-1</option>
		<option value="glock18">glock18</option>
		<option value="knives">knives</option>
		<option value="m3">m3</option>
		<option value="mac10">mac10</option>
		<option value="mp5">mp5</option>
		<option value="p9">p9</option>
		<option value="sg550">sg550</option>
		<option value="tmp">tmp</option>
		<option value="usp">usp</option>
		<option value="shield">shield</option>
		<option value="aug">aug</option>
		<option value="c4">c4</option>
		<option value="dualbarettas">dual barettas</option>
		<option value="fiveseven">fiveseven</option>
		<option value="galil">galil</option>
		<option value="grenades">grenades</option>
		<option value="m249">m249</option>
		<option value="m4a1">m4a1</option>
		<option value="xm1014">xm1014</option>
		<option value="p228">p228</option>
		<option value="scout">scout</option>
		<option value="sg552">sg552</option>
		<option value="ump">ump</option>
		<option value="arctic">arctic (T)</option>
		<option value="guerilla">guerilla (T)</option>
		<option value="l33t">l33t (T)</option>
		<option value="terror">terror (T)</option>
		<option value="gign">gign (CT)</option>
		<option value="gsg9">gsg9 (CT)</option>
		<option value="sas">sas (CT)</option>
		<option value="urban">urban (CT)</option>
		</select><br/><br/>';
}	

if(($file_game == 1 || $file_game ==2) && $file_type == 4) {
		$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="" selected="selected"> - Избери - </option>
		<option value="skin">за скинове</option>
		<option value="maps">за карти</option>
		<option value="bots">ботове</option>
		<option value="videos">за клипове</option>
		<option value="backgrounds">за бекграунди</option>
		<option value="rcon">за rcon</option>
		<option value="exploits">exploit дефендъри</option>
		<option value="models">за модели</option>
		</select><br/><br/>';
}

if($file_game == 2 && $file_type == 3) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="" selected="selected"> - Избери - </option>
		<option value="cz75">cz75</option>
		<option value="deagle">deagle</option>
		<option value="dualbarettas">dual barretas</option>
		<option value="fiveseven">five seven</option>
		<option value="glock18">glock18</option>
		<option value="p2000">p2000</option>
		<option value="p250">p250</option>
		<option value="tec9">tec9</option>
		<option value="usp-s">usp-s</option>
		<option value="ak47">ak47</option>
		<option value="aug">aug</option>
		<option value="awp">awp</option>
		<option value="famas">famas</option>
		<option value="g3sg1">g3sg-1</option>
		<option value="galil">galil</option>
		<option value="m4a4">m4a4</option>
		<option value="m4a1-s">m4a1-s</option>
		<option value="scar20">scar20</option>
		<option value="sg553">sg553</option>
		<option value="ssg08">ssg08</option>
		<option value="mac10">mac10</option>
		<option value="mp7">mp7</option>
		<option value="mp9">mp9</option>
		<option value="cz75">cz75</option>
		<option value="ppbizon">pp-bizon</option>
		<option value="p90">cz75</option>
		<option value="ump45">ump-45</option>
		<option value="mag7">mag7</option>
		<option value="nova">nova</option>
		<option value="sawedoff">sawedoff</option>
		<option value="xm1014">xm1014</option>
		<option value="m249">m249</option>
		<option value="negev">negev</option>
		<option value="knives">knives</option>
		
		</select><br/><br/>';
}	

if($file_game == 3 && $file_type == 2) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="multiplayer">multiplayer</option>
		<option value="others">others</option>
		<option value="racetrack">racetrack</option>
		<option value="stunt">stunt</option>
		</select><br/><br/>';
		
		
}

if($file_game == 3 && $file_type == 3) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="cellphones">cellphones (телефони)</option>
		<option value="clothing">clothing (облекло)</option>
		<option value="glasses">glasses (очила)</option>
		<option value="jackets">jackets (якета)</option>
		<option value="others">others (други)</option>
		</select><br/><br/>';
}
 
if($file_game == 3 && $file_type == 4) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="всякакви">Всякакви</option>
		</select><br/><br/>';	
}


if($file_game == 4 && $file_type == 2) {
	$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="adventure">adventure</option>
		<option value="parkour">parkour</option>
		<option value="horror">horror</option>
		<option value="survival">survival</option>
		<option value="creation">creation</option>
		<option value="pvp">pvp</option>
		<option value="puzzle">puzzle</option>
		<option value="game">game</option>
		<option value="others">others (други)</option>
		</select><br/><br/>';	
}
 
if($file_game == 4 && $file_type == 3) {
		$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="всякакви">Всякакви</option>
		</select><br/><br/>';	
}

if($file_game == 4 && $file_type == 4) {
		$ready_template = '
	    Категория:<br/>
	    <select name="category">
		<option value="editors">едитори</option>
		<option value="mapping">mapping</option>
		<option value="viewers">viewers</option>
		<option value="mods">mods</option>
		<option value="3d-exporters">3d exporters</option>
		<option value="others">others (други)</option>
		</select><br/><br/>';	
}
	
$files_up_acp = array('file_type_not_real'=>$file_type, 'file_game_not_real'=>$file_game,'file_type'=>$file_type_real,'file_game'=>$file_game_real,'ready_template'=>$ready_template);

} else {
	$files_up_acp = array('form_cant_submit'=>'Попълнете горните полета!');
}

$submit_file_acp[]="";
//submit file
if(isset($_POST['submit_file'])){
	if($_POST['file_game'] != 0 && $_POST['file_type'] != 0 && !empty($_POST['file_name']) &&  !empty($_POST['file_link']) && !empty($_POST['file_author'])) {
	
	$file_type_not_real = $_POST['file_type'];
	$file_type = $_POST['file_type'];
	switch($file_type) {
	case '1': {
		$file_type = "Плъгин";
		break;
	}
	case '2': {
		$file_type = "Карта";
		break;
	}
	case '3': {
		$file_type = "Скин";
		break;
	}
	case '4': {
		$file_type = "Програма";
		break;
	}
   }
   
    $file_game_not_real= $_POST['file_game'];
	$file_game= $_POST['file_game'];
	switch($file_game) {
	case '1': {
		$file_game = "CS 1.6";
		break;
	}
	case '2': {
		$file_game = "CS:GO";
		break;
	}
	case '3': {
		$file_game = "SAMP";
		break;
	}
	case '4': {
		$file_game = "Minecraft";
		break;
	}
}
	$file_name = mysqli_real_escape_string($link,$_POST['file_name']);
	$file_link = mysqli_real_escape_string($link,$_POST['file_link']);
	$file_author = mysqli_real_escape_string($link,$_POST['file_author']);
	$file_img = mysqli_real_escape_string($link,$_POST['file_img']);
	if(empty($file_img) || !filter_var($fle_img, FILTER_VALIDATE_URL)) {
		$file_img = "assets/img/no_image.jpg";
	}
	$file_description = mysqli_real_escape_string($link,$_POST['opisanie']);
	$file_size = mysqli_real_escape_string($link,$_POST['file_size']);
	$file_upload_date = time();
	$file_cat = mysqli_real_escape_string($link,$_POST['category']);
 
	$go = mysqli_query($link,"INSERT INTO files (picture,author,down_counts,date,size,type,game,type_not_real,game_not_real,category,opisanie,link,name) VALUES('$file_img','$file_author','0','$file_upload_date','$file_size','$file_type','$file_game','$file_type_not_real','$file_game_not_real','$file_cat', '$file_description','$file_link','$file_name')") or die(mysqli_error($link));
	@mysqli_free_result($go);
	$submit_file_acp=array('file_add'=>'<div class="alert alert-success"><i class="fa fa-check"></i> Успешно!</div>');
	} else {
	 $submit_file_acp=array('file_add'=>'<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Има непопълнени полета!</div>');
	}
}
//end
//end files uplod
 
//check is this page
$is_filesadd_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/add_file.php') !== false) {
	$is_filesadd_page_acp = array('is_filesadd_page'=>1);
}
//end

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_file');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $files_up_acp + $submit_file_acp + $is_filesadd_page_acp); //принтираме всичко в страницата ($values+$values)