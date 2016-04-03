<?php

//admin check
if(!$bb_is_admin) {
	header("Location: ../".removeLastSlash($forum_path)."/ucp.php?mode=login");
}
//end admin check

//broika pm
function get_pm_acp() {
global $link, $bb_db, $bb_prefix;
$mysql = mysqli_query($link,"SELECT COUNT(id) as broika_pm FROM contacts WHERE respond=0 order by id DESC LIMIT 5") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($mysql);
@mysqli_free_result($mysql);
return $row['broika_pm'];
}
$new_pms = get_pm_acp();
//end broika pm

//broika dokladi
function get_reports_acp() {
global $link, $bb_db, $bb_prefix;
$mysql = mysqli_query($link,"SELECT COUNT(report_id) as reports FROM `$bb_db`.".$bb_prefix."_reports WHERE report_closed=0") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($mysql);
@mysqli_free_result($mysql);
return $row['reports'];
}
$new_reports = get_reports_acp();
//end broika dokladi

//dokladi link
$dokladi_link = '../'.removeLastSlash($forum_path).'/mcp.php?i=mcp_reports&amp;mode=reports';
//end

$values_acp = array( 
 'username'=>$bb_username,
 'baseurl'=>'http://'.$_SERVER['SERVER_NAME'].'',
 'is_logged'=>$bb_is_anonymous ? false : true,
 'is_admin' => $bb_is_admin ? true : false,
 'user_avatar' => $user_avatar,
 'user_id' => $bb_user_id,
 'forum_path' => '../'.removeLastSlash($forum_path),
 'user_logout' => '../'.removeLastSlash($forum_path).'/ucp.php?mode=logout&sid='.$bb_session_id.'',
 'total_new_pm'=>$new_pms,
 'total_new_reports'=>$new_reports,
 'reports_link'=>$dokladi_link,
 'online_users' => $online_sessions,
 'online_users_anonymous' => $online_sessions_anony,
 'total_users'=>$total_users,
 'total_topics'=>$total_topics,
 'total_forums'=>$total_forums,
 'total_posts'=>$total_posts,
 'total_topics_views'=>$total_topics_views,
 'current_time'=>date('d.m.Y :: h:i:s'),
 'current_year'=>date("Y"),
);

 

//pms
function get_emails_from_users() {
global $link;
$mysql = mysqli_query($link,"SELECT * FROM contacts WHERE respond=0 order by id DESC LIMIT 5") or die(mysqli_error($link));
if(mysqli_num_rows($mysql) > 0) {
while($row = mysqli_fetch_assoc($mysql)){
	$contact_date = date("d.m.y h:i:s", $row['date']);
	$contact_quest = truncate_chars($row['question'],1,15,'...');
	$contact_text = truncate_chars($row['text'],1,15,'...');
	$contact_info[]  = array('contact_date'=>$contact_date,'contact_quest'=>$contact_quest,'contact_text'=>$contact_text);
}
@mysqli_free_result($mysql);
return new ArrayIterator( $contact_info ); 
}
}
$contact_pm_acp['contact_pms'] = get_emails_from_users();
if(empty($contact_pm_acp['contact_pms'])) {
$contact_pm_acpp[] ="";
}
//end pms


//gravatar fetch
function get_gravatar( $email, $s = 150, $d = 'mm', $r = 'pg', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}