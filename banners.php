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

$page_title = array('page_title'=>$lang_sys['lang_banners']); //title на страницата

//all functions
require 'includes/functions.php';

//get banners
////////////////////////468x60//////////////////////
$own_banners .= "<div class='alert alert-warning'>468x60</div>";
$get = mysqli_query($link,"SELECT * FROM `banners` WHERE type='468x60' ORDER by id")or die(mysqli_error($link)); 
while($row = mysqli_fetch_assoc($get)) { 

$id = $row['id'];
$banner_link = $row['site_link'];
$banner_img = $row['banner_img'];
$banner_title = $row['link_title'];
$banner_author = $row['avtor'];

$own_banners .="
<div style='text-align:center'>
<img src='$banner_img' width='468' height='60' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>".$lang_sys['lang_author'].": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

////////////////////////88x31//////////////////////
$own_banners .= "<div class='alert alert-warning'>88x31</div>";
$get = mysqli_query($link,"SELECT * FROM `banners` WHERE type='88x31' ORDER by id")or die(mysqli_error($link)); 
while($row = mysqli_fetch_assoc($get)) { 

$id = $row['id'];
$banner_link = $row['site_link'];
$banner_img = $row['banner_img'];
$banner_title = $row['link_title'];
$banner_author = $row['avtor'];

$own_banners .="
<div style='text-align:center'>
<img src='$banner_img' width='88' height='31' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>".$lang_sys['lang_author'].": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

////////////////////////200x200//////////////////////
$own_banners .= "<div class='alert alert-warning'>200x200</div>";
$get = mysqli_query($link,"SELECT * FROM `banners` WHERE type='200x200' ORDER by id")or die(mysqli_error($link)); 
while($row = mysqli_fetch_assoc($get)) { 

$id = $row['id'];
$banner_link = $row['site_link'];
$banner_img = $row['banner_img'];
$banner_title = $row['link_title'];
$banner_author = $row['avtor'];

$own_banners .="
<div style='text-align:center'>
<img src='$banner_img' width='200' height='200' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>".$lang_sys['lang_author'].": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

////////////////////////userbars//////////////////////
$own_banners .= "<div class='alert alert-warning'>userbars</div>";
$get = mysqli_query($link,"SELECT * FROM `banners` WHERE type='userbar' ORDER by id")or die(mysqli_error($link)); 
while($row = mysqli_fetch_assoc($get)) { 

$id = $row['id'];
$banner_link = $row['site_link'];
$banner_img = $row['banner_img'];
$banner_title = $row['link_title'];
$banner_author = $row['avtor'];

$own_banners .="
<div style='text-align:center'>
<img src='$banner_img' width='350' height='20' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>".$lang_sys['lang_author'].": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

////////////////////////728x90//////////////////////
$own_banners .= "<div class='alert alert-warning'>728x90</div>";
$get = mysqli_query($link,"SELECT * FROM `banners` WHERE type='728x90' ORDER by id") or die(mysqli_error($link)); 
while($row = mysqli_fetch_assoc($get)) { 

$id = $row['id'];
$banner_link = $row['site_link'];
$banner_img = $row['banner_img'];
$banner_title = $row['link_title'];
$banner_author = $row['avtor'];

$own_banners .="
<div style='text-align:center'>
<img src='$banner_img' width='728' height='90' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>".$lang_sys['lang_author'].": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

////////////////////////120x240//////////////////////
$own_banners .= "<div class='alert alert-warning'>120x240</div>";
$get = mysqli_query($link,"SELECT * FROM `banners` WHERE type='120x240' ORDER by id") or die(mysqli_error($link)); 
while($row = mysqli_fetch_assoc($get)) { 

$id = $row['id'];
$banner_link = $row['site_link'];
$banner_img = $row['banner_img'];
$banner_title = $row['link_title'];
$banner_author = $row['avtor'];

$own_banners .="
<div style='text-align:center'>
<img src='$banner_img' width='120' height='240' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>".$lang_sys['lang_author'].": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

$banners_own[] = "";
$get = mysqli_query($link,"SELECT * FROM banners");
if(mysqli_num_rows($get) < 1) {
$banners_own = array('all_own_banners'=>"<br/><div class='alert alert-info'><i class='fa fa-info-circle'></i> ".$lang_sys['lang_no_banners']."</div>");
} else {
$banners_own = array('all_own_banners'=>$own_banners);
}
@mysqli_free_result($get);
//end get banners

 
/////////PRINT/////// 
$tpl = $mustache->loadTemplate('banners');  //име на темплейт файла
echo $tpl->render($page_title + $values + $values3 + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $sliders +$banners_own); //принтираме всичко в страницата ($values+$values)