<?php
    require 'config.php';
    error_reporting(0);
    session_start();
    if(isset($_SESSION['id'])){
        header("Location: index.php");
    }
    $email=$_POST['email'];
    $pass=md5($_POST['npd']);
    $cpass=md5($_POST['cnpd']);
    if(isset($_POST['sb'])){
        $sql = "SELECT email FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if($result->num_rows>0){
            echo "<script>alert('請至信箱查看驗證碼')</script>";
            mb_encode_mimeheader('UTF-8');
            $to = $_POST['email'];
            $subject=mb_encode_mimeheader("朝陽公司", 'UTF-8');
            $checknuber=number();
            $_SESSION['fchecknuber']=$checknuber;
            $body="<p>您忘記密碼的驗證數字為: ".$checknuber."</p>";
            $header = "MIME-Version: 1.0\r\n";
            $header.= "Content-type: text/html; charset=utf-8\r\n";
            $header.="From: ".mb_encode_mimeheader('朝陽公司', 'UTF-8');
            mail($to,$subject,$body,$header);
        }else{
            echo "<script>alert('沒有此帳號')</script>";
        }
    }
    if(isset($_POST['sb1'])){
        if($_POST['check']==$_SESSION['fchecknuber']){
            $cpd=true;
        }else{
            echo "<script>alert('驗證碼錯誤')</script>";
            $cpd=false;
        }
    }
    if(isset($_POST['submit']) && $pass==$cpass){
        $sql = "SELECT email FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if($result->num_rows>0){
            $sql = "UPDATE users SET `password`='$pass' WHERE email='$email'";
            $result = mysqli_query($conn, $sql);
            if($result){
                echo "<script>alert('修改成功')</script>";
                echo '<meta http-equiv="refresh" content="0; url=index.php">';
            }else{
                echo "<script>alert('出現錯誤')</script>";
            }
        }else{
            echo "<script>alert('沒有此帳號')</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script>
            $(document).ready(function(){
                    checkemail=function(){
                        if ($("#email").val().length >= 2) {
                            $.ajax({
                            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
                            type: 'GET',    // 傳遞的方法
                            data: {
                                email: $('#email').val()
                            },
                            error: function(xhr) {          // 設定錯誤訊息
                                alert('Ajax request 發生錯誤');
                            },
                            success: function(response) {   // 將回傳的訊息寫入網頁中
                                $('#msg_email').html(response);
                                $('#msg_email').fadeIn();
                            }
                        });
                        } else {
                            $('#msg_email').html('');
                        }
                    };
                    checkepassword=function(){
                        if ($("#password").val().length >= 2) {
                            $.ajax({
                            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
                            type: 'GET',    // 傳遞的方法
                            data: {
                                password: $('#password').val()
                            },
                            error: function(xhr) {          // 設定錯誤訊息
                                alert('Ajax request 發生錯誤');
                            },
                            success: function(response) {   // 將回傳的訊息寫入網頁中
                                $('#msg_password').html(response);
                                $('#msg_password').fadeIn();
                            }
                        });
                        } else {
                            $('#msg_password').html('');
                        }
                    };
                    checkecpassword=function(){
                        if ($("#cpassword").val().length >= 2) {
                            $.ajax({
                            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
                            type: 'GET',    // 傳遞的方法
                            data: {
                                cpassword: $('#cpassword').val(),
                                password: $('#password').val()
                            },
                            error: function(xhr) {          // 設定錯誤訊息
                                alert('Ajax request 發生錯誤');
                            },
                            success: function(response) {   // 將回傳的訊息寫入網頁中
                                $('#msg_cpassword').html(response);
                                $('#msg_cpassword').fadeIn();
                            }
                        });
                        } else {
                            $('#msg_cpassword').html('');
                        }
                    };
            });
        </script>
	    <title>找回密碼</title>
    </head>
<body>
<div class="container">
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">找回密碼</p>
			<div class="input-group">
				<input type="email" placeholder="Email" name="email" id="email" value="<?php echo $email; ?>" required onkeyup="checkemail()">
                <span class="text-danger" id="msg_email"></span>
            </div>
            <div class="input-group">
                <input type="text" placeholder="驗證碼" name="check" id="check" value="<?php echo $_POST['check']?>">
            </div>
            <div class="input-group">
                <button name="sb" class="sb">送出驗證信</button>
            </div>
            <div class="input-group">
                <button name="sb1" class="sb">驗證</button>
            </div>
            <div class="input-group">
                <input type="password" placeholder="新的密碼" name="npd" id="password" value="<?php echo $_POST['npd']; ?>" <?php echo $cpd ? "required" : "disabled" ?> onkeyup="checkepassword()">
                <span class="text-danger" id="msg_password"></span>
            </div>
            <div class="input-group">
                <input type="password" placeholder="確認新的密碼" name="cnpd" id="cpassword" value="<?php echo $_POST['cnpd']; ?>" <?php echo $cpd ? "required" : "disabled" ?> onkeyup="checkecpassword()">
                <span class="text-danger" id="msg_cpassword"></span>
            </div>
			<div class="input-group">
				<button name="submit" class="btn" id="msg_check">送出</button>
			</div>
            <p class="login-register-text"><a href="index.php">回首頁</a></p>
		</form>
	</div>
</body>
</html>