<?php
require "../config.php";

$ip=$_SERVER['REMOTE_ADDR']; 

if($_POST['id'])
{
$id=$_POST['id'];
$id = str_replace('sup', '', $id);
$id = mysqli_real_escape_string($link,$id);
//Verify IP address in Voting_IP table
$ip_sql=mysqli_query($link,"select ip_add from voting_ip_comments where mes_id_fk='$id' and ip_add='$ip'");
$count=mysqli_num_rows($ip_sql);

if($count==0)
{
// Update Vote.
$sql = "update comments set vote=vote+1 where id='$id'";
mysqli_query($link, $sql);
// Insert IP address and Message Id in Voting_IP table.
$sql_in = "insert into voting_ip_comments (mes_id_fk,ip_add) values ('$id','$ip')";
mysqli_query( $link,$sql_in);
echo "<script>alert('Благодаря, че гласува!');</script>";
}
else
{
echo "<script>alert('Ти вече си гласувал!');</script>";
}

$result=mysqli_query($link,"select vote from  comments where id='$id'");
$row=mysqli_fetch_array($result);
$up_value=$row['vote'];
echo $up_value;

}
?>
