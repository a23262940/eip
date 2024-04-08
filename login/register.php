<?php 
include 'config.php';
error_reporting(0);
session_start();
ini_set("session.cookie_httponly", 1);
if (isset($_SESSION['id'])) {
    header("Location: index.php");
}
If($_SERVER['REQUEST_METHOD']=="POST"){
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
        $msg = "OK";
    else
        $msg = "Error";
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-Nj1D6pu2WnJojj+67GiU9ZFNwbl7bUWX5Kj5MS22C8bGjllemM9pvQyvj14zJb58" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script>
        $(document).ready(function(){
    	checkRegAcc = function() {
		$("#username").on("keyup",function(){
		if($(this).val() != ""){
			$.ajax({
            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
            type: 'POST',    // 傳遞的方法
            data: {
                username: $('#username').val()
            },
            error: function(xhr) {          // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
            },
           	success: function(response) {   // 將回傳的訊息寫入網頁中
                $('#msg_username').html(response);
                $('#msg_username').fadeIn();
           	}
			}).done(function(data){
				if(data==''){
					$("#submit").attr("disabled",false);
				}else{
					$("#submit").attr("disabled",true);
				}
			});
			}
        });
	};
	checkname=function(){
		$("#name1").on("keyup",function(){
		if($(this).val() != ""){
			$.ajax({
            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
            type: 'POST',    // 傳遞的方法
            data: {
                name1: $('#name1').val()
            },
            error: function(xhr) {          // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
            },
            success: function(response) {   // 將回傳的訊息寫入網頁中
                $('#msg_name1').html(response);
                $('#msg_name1').fadeIn();
            }
        }).done(function(data){
				if(data==''){
					$("#submit").attr("disabled",false);
				}else{
					$("#submit").attr("disabled",true);
				}
			});
		}
		});
	};
	checkemail=function(){
		$("#email1").on("keyup",function(){
		if($(this).val() != ""){
			$.ajax({
            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
            type: 'POST',    // 傳遞的方法
            data: {
                email: $('#email1').val()
            },
            error: function(xhr) {          // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
            },
            success: function(response) {   // 將回傳的訊息寫入網頁中
                $('#msg_email').html(response);
                $('#msg_email').fadeIn();
            }
        	}).done(function(data){
				if(data==''){
					$("#submit").attr("disabled",false);
				}else{
					$("#submit").attr("disabled",true);
				}
			});
		}
		});
	};
	checkepassword=function(){
		$("#password").on("keyup",function(){
		if($(this).val() != ""){
			$.ajax({
            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
            type: 'POST',    // 傳遞的方法
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
        	}).done(function(data){
				if(data==''){
					$("#submit").attr("disabled",false);
				}else{
					$("#submit").attr("disabled",true);
				}
			});
		}
		});
	};
	checkecpassword=function(){
		$("#cpassword").on("keyup",function(){
			if($("#cpassword").val()!=$('#password').val()){
				$('#password').parent().addClass('has-error');
				$("#cpassword").parent().addClass('has-error');
				$('#msg_cpassword').html('密碼不符');
				$("#submit").attr("disabled",true);
			}else{
				$('#password').parent().removeClass('has-error');
				$("#cpassword").parent().removeClass('has-error');
				$('#msg_cpassword').html('');
				$("#submit").attr("disabled",false);
			}
		});
	};
	checkephone=function(){
		$("#phone").on("keyup",function(){
		if($(this).val() != ""){
			$.ajax({
            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
            type: 'POST',    // 傳遞的方法
            data: {
                phone: $('#phone').val()
            },
            error: function(xhr) {          // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
            },
            success: function(response) {   // 將回傳的訊息寫入網頁中
                $('#msg_phone').html(response);
                $('#msg_phone').fadeIn();
            }
        	}).done(function(data){
				if(data==''){
					$("#submit").attr("disabled",false);
				}else{
					$("#submit").attr("disabled",true);
				}
			});
		}
		});
	};
	checkstatu=function(){
		$("#staut").on("click",function(){
			if($(this).val() != ""){
				$("#submit").attr("disabled",false);
			}else{
					$("#submit").attr("disabled",true);
			}
		});
	};
});
        </script>
	<title>註冊</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email" name="subscribeform">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">註冊</p>
			<div class="input-group">
				<input type="text" placeholder="帳號" name="username" id="username" value="<?php echo $_POST['username']; ?>" required onkeyup="checkRegAcc()">
				<span class="text-danger" id="msg_username"></span>
			</div>
			<div class="input-group">
				<input type="text" placeholder="中文名子" name="name1" id="name1" value="<?php echo $_POST['name1'] ?>" required onkeyup="checkname()">
				<span class="text-danger" id="msg_name1"></span>
			</div>
			<div class="input-group">
				<input type="email" placeholder="信箱" name="email" id="email1" value="<?php echo $_POST['email']; ?>" required onkeyup="checkemail()">
				<span class="text-danger" id="msg_email"></span>
			</div>
			<div class="input-group">
				<input type="password" placeholder="密碼" name="password" id="password" value="<?php echo $_POST['password']; ?>" required onkeyup="checkepassword()">
				<span class="text-danger" id="msg_password"></span>
			</div>
            <div class="input-group">
				<input type="password" placeholder=" 確認密碼" name="cpassword" id="cpassword" value="<?php echo $_POST['cpassword']; ?>" required onkeyup="checkecpassword()">
				<span class="text-danger" id="msg_cpassword"></span>
			</div>
			<div class="input-group">
				<input type="tel" placeholder="電話" name="phone" id="phone" value="<?php echo $_POST['phone']; ?>" required  onkeyup="checkephone()">
				<span class="text-danger" id="msg_phone"></span>
			</div>

			<div class="input-group">
				<?php 
					$statu=["請選擇身分","員工","經理"];
					echo '<select name="staut" id="staut" class="select" required>';
					for($i=0; $i<count($statu);$i++){
						echo '<option value="'.$statu[$i].'"';
						echo ($_POST['staut']==$statu[$i]) ? ' selected ' : '';
						echo '>'.$statu[$i];
					}
					echo '</select>';
				?>
			</div>

			<div class="input-group">
				<?php 
					$dt=["請選擇部門","開發部", "會計部", "行銷部"];
					echo '<select name="dt" id="dt" class="select" required>';
					for($i=0; $i<count($dt);$i++){
						echo '<option value="'.$dt[$i].'"';
						echo ($_POST['staut']==$dt[$i]) ? ' selected ' : '';
						echo '>'.$dt[$i];
					}
					echo '</select>';
				?>
			</div>

			<div class="input-group">
				<?php 
					$sex1=["請選擇性別","男性","女性","其他"];
					$sex2=["","M","F","O"];
					echo '<select name="sex" id="sex" class="select" required>';
					for($i=0; $i<count($sex1);$i++){
						echo '<option value="'.$sex2[$i].'"';
						echo ($_POST['sex']==$sex2[$i]) ? ' selected ' : '';
						echo '>'.$sex1[$i];
					}
					echo '</select>';
				?>
			</div>
			<label>請輸入生日:</label>
			<div class="input-group">
				<input type="date" name="brithday" id="birthday" value="<?php echo $_POST['brithday']; ?>" required>
			</div>
			<div class="g-recaptcha" data-sitekey="6Lfn6RMjAAAAAGjhG1YbaVQLhukn7fA1XTu4A233"></div>
			<br>
			<div class="input-group">
				<button name="submit" class="btn" id='submit'>註冊	</button>
			</div>
			<p class="login-register-text">已經有帳號了? <a href="index.php">從這邊登入</a></p>
		</form>
	</div>
	<script type="text/javascript">
    </script>
	<script>
	$(document).ready(function(){
			$("form[name=subscribeform]").submit(function(ev){
				if(grecaptcha.getResponse() != ""){
				 	cc = true;
					return true;
				}
				alert("請驗證不是機器人");
				var cc = false;
				return false;
			});
		});
    $("#submit").on("click",function(){
        $("#submit").attr("disabled",true);
        setTimeout(function(){
          $("#submit").attr("disabled",false);
        },5000);
		var username =$('#username').val();
		var name1 =$('#name1').val();
		var password =$('#password').val();
		var cpassword =$('#cpassword').val();
		var phone =$('#phone').val();
		var staut =$('#staut').val();
		var brithday =$('#birthday').val();
		var sex =$('#sex').val();
        var email2 = $('#email1').val();
		var dt = $('#dt').val();
        if(email2!=''  && name1!='' && password==cpassword && phone!="" && staut!="請選擇身分" && sex!="請選擇性別" && brithday!="" && username!="" && dt!='請選擇部門' && grecaptcha.getResponse() != ""){
            alert("請至信箱啟動帳號");
            $.ajax({
              type:"POST",
              url:"outemail.php",
              data:{
				username,
				name1,
				password,
				phone,
				staut,
				brithday,
				sex,
                email2,
				dt
              },
              error: function(xhr){
                alert('Ajax request 發生錯誤');
              },
              success: function(response){
				  location.href='emailcheck.php'
              }
          });
        }else if(username==""){
			alert('帳號不能是空的');
		}else if(name1==""){
			alert('名子不能是空的');
		}else if(email2==""){
			alert('信箱不能是空的');
		}else if(password!=cpassword){
			alert('密碼不相符');
		}else if(phone==""){
			alert('電話不能是空的');
		}else if(staut=="請選擇身分"){
			alert('身分不能是空的');
		}else if(dt=="請選擇部門"){
			alert('部門不能為空的');
		}else if(sex=="請選擇性別"){
			alert('性別不能是空的');
		}else if(brithday==""){
			alert('生日不能是空的');
		}else{
			alert('請驗證不是機器人')
		}
      });
    </script>
</body>
</html>