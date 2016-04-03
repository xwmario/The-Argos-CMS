<?php
if (empty($_SERVER['HTTP_REFERER'])){
echo "Die";
die();
}
require '../config.php'; //Нашия конфигурационен файл
$forum_path = "../../".$forum_path;
require '../phpbb_bridge.php'; //phpbb3 интеграцията

//checker//
$get = mysqli_query($link,"SELECT count(id) as roller from chat") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
if($row['roller']>20) {
$deleter =($row['roller']-20);
mysqli_query($link,"DELETE FROM chat ORDER BY id ASC LIMIT $deleter") or die(mysqli_error($link));
}
//end

$catch = mysqli_query($link,"SELECT * FROM chat INNER JOIN `$bb_db`.".$bb_prefix."_users ON name=username ORDER by id ASC LIMIT 40")  or die(mysqli_error($link));
if(mysqli_num_rows($catch) <1) {
	echo json_encode(array('chat'=>"Няма съобщения..."));
} else {
$data_chat = array();
while($row = mysqli_fetch_array($catch)) {
$chat_id = $row['id'];
$id = $row['user_id'];
$color = $row['user_colour'];
$userc=$row['name'];
$textc=$row['text'];
$textc=str_replace(":)", "<img src=\"assets/img/emoticons/smile.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(sad)", "<img src=\"assets/img/emoticons/sad.png\"  border='0' alt='' />", $textc);
$textc=str_replace(":D", "<img src=\"assets/img/emoticons/laught.png\"  border='0' alt='' />", $textc);
$textc=str_replace(";)", "<img src=\"assets/img/emoticons/wink.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(coffee)", "<img src=\"assets/img/emoticons/coffee.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(welcome)", "<img src=\"assets/img/emoticons/hi.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(mocking)", "<img src=\"assets/img/emoticons/mocking.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(beer)", "<img src=\"assets/img/emoticons/beer.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(kiss)", "<img src=\"assets/img/emoticons/kiss.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(cry)", "<img src=\"assets/img/emoticons/cry.png\"  border='0' alt='' />", $textc);
$textc=str_replace(":P", "<img src=\"assets/img/emoticons/tongue.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(confused)", "<img src=\"assets/img/emoticons/confused.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(dislike)", "<img src=\"assets/img/emoticons/dislike.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(heart)", "<img src=\"assets/img/emoticons/heart.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(poop)", "<img src=\"assets/img/emoticons/poop.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(skull)", "<img src=\"assets/img/emoticons/skull.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(sun)", "<img src=\"assets/img/emoticons/sun.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(blowing-heart)", "<img src=\"assets/img/emoticons/blowing-heart.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(exclamation)", "<img src=\"assets/img/emoticons/exclamation.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(heart-eyes)", "<img src=\"assets/img/emoticons/heart-eyes.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(pacman)", "<img src=\"assets/img/emoticons/pacman.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(sunglasses)", "<img src=\"assets/img/emoticons/sunglasses.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(warning)", "<img src=\"assets/img/emoticons/warning.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(curly_lips)", "<img src=\"assets/img/emoticons/curly_lips.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(hamburger)", "<img src=\"assets/img/emoticons/ham.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(lips)", "<img src=\"assets/img/emoticons/lips.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(piggy)", "<img src=\"assets/img/emoticons/piggy.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(santa)", "<img src=\"assets/img/emoticons/santa.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(thumb-up)", "<img src=\"assets/img/emoticons/thumb-up.png\"  border='0' alt='' />", $textc);
$textc=str_replace("(umbrella)", "<img src=\"assets/img/emoticons/umbrella.png\"  border='0' alt='' />", $textc);

$datec=$row['date'];

$user_avatar = $row['avatar'];

if($bb_is_admin) {
$admin_delete = "[<span class='remove_msg' data-my='$chat_id' title='Изтрий'><i style='color:red;cursor:pointer' class='fa fa-times'></i></span>]";
}

$data_chat[] = '<div class="post_chat" id="message-'.$chat_id.'"><div class="chat_ava">'.$user_avatar.'</div> <a href="'.preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path).'/memberlist.php?mode=viewprofile&u='.$id.'" style="color:#'.$color.' !important" target="_blank">'.$userc.'</a> на '.$datec.': <span style="color:orange">'.$textc.'</span> '.$admin_delete.'</div>';
}
//print json
echo json_encode(array("chat"=>$data_chat,"chat_id"=>"$chat_id"));

}
@mysqli_free_result($catch);
?>