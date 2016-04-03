<?php
require("config.php");
$forum_path = "../".$forum_path;
require 'phpbb_bridge.php'; //phpbb3 интеграцията
$ds          = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = '../uploads/images';   //2
 

 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3             
    if(getimagesize($tempFile)) {
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
     
    $targetFile = $targetPath. time().'_'.$_FILES['file']['name'];  //5
    $file_to_down =  time().'_'.$_FILES['file']['name'];
    move_uploaded_file($tempFile,$targetFile); //6
	 
    echo 'uploads/images/'.$file_to_down; 
	$date = time();
	$go = mysqli_query($link,"INSERT INTO gallery (date,pic_link,uploader) VALUES('$date','$file_to_down','$bb_username')");
	@mysqli_free_result($go);
	}
}
?>     