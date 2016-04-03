<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb_bridge.php");
if(!$bb_is_admin) {
	die();
}
?>
<!DOCTYPE html>
<!-- created by dedihost.org -->
<html lang="en">
<head>
<title></title>
    <meta charset="UTF-8">
    <!-- Bootstrap -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../template/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	
	<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
	
</head>
<body style="background:#ecf0f5">
<div class="container">
<br/>
<div class="alert alert-warning" style="max-width:600px;margin: 0 auto"><i class="fa fa-exclamation-triangle"></i> Когато редактирате анкетата, вие ще рестартирате нейните гласувания!</div>
<br/>

<?php 
$id = (int)$_GET['id'];	

$get = mysqli_query($link,"SELECT * FROM dpolls WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$poll_question = $row['poll_question'];
$poll_answers = $row['poll_answer'];
 
$pieces = explode(";", $poll_answers); //otdelqme vuzmojnite otgovori
$pollansw = array("votes"=>$pieces); //vkarvame votovete v array (vuzmojnite otgovori)

foreach($pollansw['votes'] as $v ) {
$counter++;
$pollansw_redit= explode("##",$v);
$poll_print .= $pollansw_redit[0].PHP_EOL;
}
$poll_print = rtrim($poll_print, PHP_EOL);

echo '
<form role="form" action="" method="post">

<div class="input-group">
<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
<input type="text" name="poll_question" value="'.$poll_question.'" placeholder="Въпрос на анкетата" class="form-control" required>
</div>
<br/>


<textarea class="form-control" rows="5" name="poll_answer">'.$poll_print.'</textarea>

<button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form>
<br/>

';


if(isset($_POST['submit'])) {
$poll_question = mysqli_real_escape_string($link,$_POST['poll_question']);
$poll_answers = mysqli_real_escape_string($link,$_POST['poll_answer']);
$poll_answers2= explode('\r\n',$poll_answers);
$poll_answers = array('answers'=>$poll_answers2);
foreach($poll_answers['answers'] as $v) { 
$format_poll .= "$v##0;"; 
}
$format_poll = rtrim($format_poll, ';');

$go = mysqli_query($link,"UPDATE dpolls SET poll_question='$poll_question',poll_answer='$format_poll',poll_votes=0 WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
$go = mysqli_query($link,"DELETE FROM dpolls_votes WHERE poll_id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променена анкета</div>';	
	
}

?>

</div>

</body>
</html>