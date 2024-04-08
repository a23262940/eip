<?php 
require_once "config.php";
error_reporting(0);
session_start();
if(isset($_POST['email'])){
    echo "<script>alert('請至信箱查看驗證碼')</script>";
    mb_encode_mimeheader('UTF-8');
    $to = $_POST['email'];
    $subject=mb_encode_mimeheader("朝陽公司", 'UTF-8');
    $_SESSION['chpdnu']=number();
    $body="<p>您修改密碼的驗證數字為: ".$_SESSION['chpdnu']."</p>";
    $header = "MIME-Version: 1.0\r\n";
    $header.= "Content-type: text/html; charset=utf-8\r\n";
    $header.="From: ".mb_encode_mimeheader('朝陽公司', 'UTF-8');
    mail($to,$subject,$body,$header);
}
if(isset($_POST['nm'])){
    if($_SESSION['chpdnu'] == $_POST['nm']){
        $rett="true";
        setcookie("check1",md5(1),time()+3600);
    }else{
        $rett="false";
        setcookie("check1","",time()-3600);
    }
    echo $rett;
}
if(isset($_POST['email1'])){
	$email=$_POST['email1'];
	$sql = "SELECT email FROM users WHERE email='$email'";
	$result = mysqli_query($conn, $sql);
	if(!$result->num_rows > 0){
		mb_encode_mimeheader('UTF-8');
		$to = $_POST['email1'];
		$subject=mb_encode_mimeheader("朝陽公司", 'UTF-8');
		$_SESSION['rchecknuber']=number();
		$body="<p>您的註冊驗證數字為: ".$_SESSION['rchecknuber']."</p>";
		$header = "MIME-Version: 1.0\r\n";
		$header.= "Content-type: text/html; charset=utf-8\r\n";
		$header.="From: ".mb_encode_mimeheader('朝陽公司', 'UTF-8');
		mail($to,$subject,$body,$header);
	}else{
		echo "<script>alert('糟糕!信箱已經存在')</script>";
	}
}
if(isset($_POST['email2'])){
    $_SESSION['username1'] = htmlspecialchars($_POST['username'],ENT_QUOTES);
	$_SESSION['email1'] = $_POST['email2'];
	$_SESSION['password1'] = md5($_POST['password']);
	$_SESSION['name1']=$_POST['name1'];
	$_SESSION['staut']=$_POST['staut'];
	$_SESSION['birthday']=$_POST['brithday'];
	$_SESSION['sex']=$_POST['sex'];
	$_SESSION['phone']=$_POST['phone'];
    $_SESSION['dt']=$_POST['dt'];
    mb_encode_mimeheader('UTF-8');
    $to = $_POST['email2'];
    $subject=mb_encode_mimeheader("朝陽公司", 'UTF-8');
    $_SESSION['emailrc']=number();
    $_SESSION['emailrc1']=password_hash($_SESSION['emailrc'],PASSWORD_DEFAULT);
    $body="<p>請直接點選超連結以請用您的帳號: http://localhost/code/login/insertregister.php?aa=".$_SESSION['emailrc1']."</p>";
    $header = "MIME-Version: 1.0\r\n";
    $header.= "Content-type: text/html; charset=utf-8\r\n";
    $header.="From: ".mb_encode_mimeheader('朝陽公司', 'UTF-8');
    mail($to,$subject,$body,$header);
}
?>