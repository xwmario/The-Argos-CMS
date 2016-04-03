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

	
</head>
<body style="background:#ecf0f5">

<div class="container">
<br/>
<div class="alert alert-info">Писмата пристигат на емейла, който е зададен в раздел 'Конфигурация'</div>
<?php 
$id = (int)$_GET['id'];	

//get admin mail to send
$get_origin = mysqli_query($link,"SELECT admin_email,site_name FROM config") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get_origin);
@mysqli_free_result($get_origin);
$admin_email = $row['admin_email'];
$site_name = $row['site_name'];
//end

$get_data = mysqli_query($link,"SELECT * FROM contacts WHERE id='$id'") or die(mysqli_error($link));
$row = mysqli_fetch_assoc($get_data);
@mysqli_free_result($get_data);
$question = $row['question'];
$username = $row['username'];
$his_email = $row['email'];
$his_text = $row['text'];
echo '
<form method="post">

<div class="input-group">
<span class="input-group-addon"><i class="fa fa-question-circle"></i></span>
<input type="text" name="his-question" value="'.$question.'" class="form-control" readonly="readonly">
</div>
<br/>
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-user"></i></span>
<input type="text" name="his_name" value="'.$username.'" class="form-control" readonly="readonly">
</div>
<br/>
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
<input type="email" name="his_mail" value="'.$his_email.'" class="form-control" readonly="readonly">
</div>
<br/>
Неговия текст:<br/>
<textarea class="form-control" rows="3" name="his_text" disabled>'.$his_text.'</textarea><br/>

Вашето име:<br/>
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-user"></i></span>
<input type="text" name="our_name" placeholder="Вашето име" class="form-control" required>
</div>
<br/>

Вашия текст:<br/>
<textarea class="form-control" rows="3" name="our_text" required></textarea><br/>
<br/>
<input type="submit" class="btn btn-md btn-success" name="submit_contact" value="Изпрати"/>

</form>';
?>

<?php
if(isset($_POST['submit_contact'])) {

$go = mysqli_query($link,"UPDATE contacts SET respond=1 WHERE id='$id'") or die(mysqli_error($link));
@mysqli_free_result($go);
echo "<br/><div class='alert alert-success'><i class='fa fa-check'></i> Съобщението е изпратено успешно, моля чакайте отговор на вашия емейл!</div>";

$our_text = $_POST['our_text'];
$our_name = $_POST['our_name'];
$his_mail =$_POST['his_mail'];

$headers = ""; // застраховай се да няма и други глупости в променливата
$headers .= "From: $admin_email"."\r\n";
$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
$our_text = "$our_text <br/><br/><hr/>Поздрави, $our_name.";
mail($his_mail, 'Suobshtenie ot ekipa na '.$site_name.'', $our_text, $headers);

}
?>

</div>

</body>
</html>