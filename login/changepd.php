<?php
session_start();
error_reporting(0);
require_once "config.php"; 
if(isset($_SESSION['id']) && md5(1)===$_COOKIE['check1']){  //如果已登錄以及信箱驗證已通過
    $pd=md5($_POST['password']);
    $cpd=md5($_POST['cpassword']);
    $id=$_SESSION['id'];
    if(isset($_POST['btn1'])&&isset($_SESSION['id']) && $pd==$cpd){
        $sql="UPDATE users set `password`='$pd' where id='$id'";  //更改密碼
        $result=mysqli_query($conn,$sql);
        if($result){
            session_destroy();
            echo "<script>alert('修改完成請重新登錄')</script>";
            echo '<script>if(window.top !== window.self){window.top.location.href = window.self.location.href;}</script>';
            echo '<meta http-equiv="refresh" content="0; url=index.php">';
        }else{
            echo "<script>alert('發生了問題請重新嘗試')</script>";
        }
    }else if($pd!=$cpd){
        echo "<script>alert('密碼不相等')</script>";
    }
}else{
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
         $(document).ready(function(){
            checkepassword=function(){
                $("#password").on("keyup",function(){  //判斷密碼欄位有沒有特殊字元
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
                            $("#btn1").attr("disabled",false);
                        }else{
                            $("#btn1").attr("disabled",true);
                        }
                    });
                    }
                    });
                };
        checkecpassword=function(){
            $("#cpassword").on("keyup",function(){  //判斷密碼有沒有相符
                if($("#cpassword").val()!=$('#password').val()){
                    $("#cpassword").addClass('has-error');
                    $('#msg_cpassword').html('密碼不符');
                    $("#submit").attr("disabled",true);
                }else{
                    $("#cpassword").removeClass('has-error');
                    $('#msg_cpassword').html('');
                    $("#submit").attr("disabled",false);
                }
            });
            };
         });
    </script>
</head>
<body>
<ul class="text-left border-bottom mb-1">
    <form action="" method="POST">
        <li>新的密碼: <input type="password" name="password" placeholder="新的密碼" id="password" required onkeyup="checkepassword()"></li><br>
        <span class="text-danger" id="msg_password"></span><br>
        <li>確認新的密碼: <input type="password" name="cpassword" placeholder="確認新的密碼" id="cpassword" required onkeyup="checkecpassword()"></li><br>
        <span class="text-danger" id="msg_cpassword"></span><br>
        <button type="submit" class="btn btn-primary" id="btn1" name="btn1">確定</button>
    </form>
    <br>
    <a class="btn btn-primary" href="index.php" role="button">回首頁</a>
</ul>
</body>
</html>