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


//edit menus
$mysql = mysqli_query($link,"SELECT * FROM menus order by id DESC") or die(mysqli_error($link));
if(mysqli_num_rows($mysql)>0) {
while ($row=mysqli_fetch_assoc($mysql)) { 
   $menu_title = $row['title'];
   $menu_id = $row['id'];
   $menus_info[]  = array('menu_title'=>$menu_title,'menu_id'=>$menu_id);
   
}
@mysqli_free_result($mysql);
$values3_acp['allmenus'] =new ArrayIterator( $menus_info); 

} else {
	$values3_acp =  array('no_have_menus'=>"<div class='alert alert-danger'>Няма менюта!</div>");
}
//end edit menus

//check is this page
$is_menus_page_acp[] ="";
if (strpos($_SERVER["REQUEST_URI"], '/edit_menus.php') !== false) {
	$is_menus_page_acp = array('is_menus_page'=>1);
}
//end

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_menus');  //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $is_menus_page_acp); //принтираме всичко в страницата ($values+$values)