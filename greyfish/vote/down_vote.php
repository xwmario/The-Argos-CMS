<?php
error_reporting(0);
include("../../includes/config.php");
 
$ip=$_SERVER['REMOTE_ADDR']; 

if($_POST['id'])
{
$id=$_POST['id'];
$id = str_replace('sdown', '', $id);
$id = mysqli_real_escape_string($link,$id);

$ip_sql=mysqli_query($link,"select ip_add from voting_ip where mes_id_fk='$id' and ip_add='$ip'");
$count=mysqli_num_rows($ip_sql);

if($count==0)
{
$sql = "update greyfish_servers set vote=vote-1  where id='$id'";
mysqli_query($link, $sql);

$sql_in = "insert into voting_ip (mes_id_fk,ip_add) values ('$id','$ip')";
mysqli_query($link,$sql_in);
echo "<script>alert('Благодаря, че гласува!');</script>";


}
else
{
echo "<script>alert('Ти вече си гласувал!');</script>";
}

$result=mysqli_query($link,"select vote from greyfish_servers where id='$id'");
$row=mysqli_fetch_array($result);
$down_value=$row['vote'];
echo $down_value;

}
?>
