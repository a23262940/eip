<?php 
include 'config.php';

session_start();
error_reporting(0);

if (isset($_SESSION['id'])) {
    header("Location: login_main.php");
}
If($_SERVER['REQUEST_METHOD']=="POST"){
    $email = $_POST['email'];
	$password = md5($_POST['password']);
	$sql = "SELECT email,`password`,id,`login` FROM users WHERE email='$email'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
    $ch=curl_init();
    $secretKey="6Lfn6RMjAAAAABjrIoDUVB2swNCu1uIj5fSFA7Uc";
    $captcha=$_POST['g-recaptcha-response'];
    curl_setopt_array($ch,[
        CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'secret'=>$secretKey,
            'response'=>$captcha,
            'remoteip'=>$_SERVER['REMOTE_ADDR']
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $out = curl_exec($ch);
    curl_close($ch);
    $json=json_decode($out);
    if($json->success===true)
		if ($result->num_rows > 0 && $row['password']==$password) {
			$_SESSION['id'] = $row['id'];
			$_SESSION['kkey'] = number();
			setcookie("user",password_hash($_SESSION['kkey'],PASSWORD_DEFAULT),time()+3600*24);
			mysqli_query($conn,"UPDATE users set `login`='0' where id='".$row['id']."'");
			header("Location: login_main.php");
		}else if($row['login']<=3){
			$count = $row['login']+1;
			$sql1 = "UPDATE users set `login`='".$count."' where id='".$row['id']."'";
			mysqli_query($conn,$sql1);
			$msg="帳號或密碼錯誤";
		}else if($row['login']>=4){
			$msg="帳號已被鎖定，請至信箱解鎖";
			mb_encode_mimeheader('UTF-8');
			$to = $_POST['email'];
			$_SESSION['succemail']=$_POST['email'];
			$subject=mb_encode_mimeheader("朝陽公司", 'UTF-8');
			$email = password_hash($_POST['email'],PASSWORD_DEFAULT);
			$body="<p>您的帳號已被封鎖，請按下此連結解鎖:http://localhost/code/login/succ.php?user=$email</p>";
			$header = "MIME-Version: 1.0\r\n";
			$header.= "Content-type: text/html; charset=utf-8\r\n";
			$header.="From: ".mb_encode_mimeheader('朝陽公司', 'UTF-8');
			mail($to,$subject,$body,$header);
		}else{
			$msg="帳號或密碼錯誤";
		}
    else
        $msg = "Error";
}/*
if (isset($_POST['submit'])){
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$sql = "SELECT email,`password`,`login`,id FROM users WHERE email='$email'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($result->num_rows > 0 && $row['password']==$password && $row['login']<3) {
		mysqli_query($conn,"UPDATE users SET `login`= 0 WHERE email='$email'");
		$_SESSION['id'] = $row['id'];
		$_SESSION['kkey'] = number();
		setcookie("user",password_hash($_SESSION['kkey'],PASSWORD_DEFAULT),time()+3600*24);
		header("Location: login_main.php");
	}else if($result->num_rows > 0 && $row['password']==$password && $row['login']>=3 && isset($_POST['notroabe'])){
		mysqli_query($conn,"UPDATE users SET `login`= 0 WHERE email='$email'");
		$_SESSION['id'] = $row['id'];
		$_POST['notroabe']="";
		$_SESSION['kkey'] = number();
		setcookie("user",password_hash($_SESSION['kkey'],PASSWORD_DEFAULT),time()+3600*24);
		header("Location: login_main.php");
	}else if($result->num_rows > 0  && $row['login']>=3 && empty($_POST['notroabe'])){
		echo "<script>alert('請勾選我不是機器人。')</script>";
	}
	else {
		$count=$row['login']+1;
		mysqli_query($conn,"UPDATE users SET `login`= $count WHERE email='$email'");
		echo "<script>alert('糟糕!信箱或密碼錯誤。')</script>";
	}
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>朝陽公司</title>
	<script type="text/javascript">
            $(document).ready(function(){
                $("form[name=subscribeform]").submit(function(ev){
                    if(grecaptcha.getResponse() != ""){
                        return true;
                    }
                    alert("請驗證不是機器人");
                    return false;
                });
            });
    </script>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email" name="subscribeform">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">登入</p>
			<div class="input-group">
				<input type="email" placeholder="信箱" id="em" name="email" value="<?php echo $_POST['email']; ?>" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="密碼" name="password" value="<?php echo $_POST['password']; ?>" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn" id="btn">登入</button><br>
			</div>
			<div class="g-recaptcha" data-sitekey="6Lfn6RMjAAAAAGjhG1YbaVQLhukn7fA1XTu4A233"></div>
			<p style="color: red;"><?php echo $msg; ?></p>
			<p class="login-register-text">忘記密碼? <a href="forgot.php">點這邊找回</a></p>
			<p class="login-register-text">還沒有帳號? <a href="register.php">點這邊註冊</a></p>
			<p class="login-register-text">回首頁? <a href="../main/index.php">回首頁</a></p>
		</form>
	</div>
</body>
</html>