<?php
include 'config.php';
session_start();
//echo $_SESSION['username1']." ".$_SESSION['email1']."  ".$_SESSION['password1']." ".$_SESSION['name1']." ".$_SESSION['staut']." ".$_SESSION['birthday']." ".$_SESSION['sex']." ".$_SESSION['phone'];
if(isset($_GET['aa']) && password_verify($_SESSION['emailrc'],$_GET['aa'])){
    if($_SESSION['staut']=="管理者"){
        $id="m0";
    }else if($_SESSION['staut']=="經理"){
        $id="m1";
    }else if($_SESSION['staut']=="員工"){
        $id="m2";
    }else{
        $id="m3";
    }
    $username=$_SESSION['username1'];
	$email=$_SESSION['email1'];
	$password=$_SESSION['password1'];
	$name1=$_SESSION['name1'];
	$staut=$_SESSION['staut'];
	$brithday=$_SESSION['birthday'];	
	$sex=$_SESSION['sex'];
	$phone=$_SESSION['phone'];
    $dt = $_SESSION['dt'];
    $sql = "SELECT email FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (!$result->num_rows > 0) {
        $result = mysqli_query($conn,"SELECT COUNT(username) FROM users");
        $count = mysqli_fetch_row($result);
        if(strlen($count[0])==1){
            $id .= "0000".$count[0];
        }else if(strlen($count[0])==2){
            $id .= "000".$count[0];
        }else if(strlen($count[0])==3){
            $id .= "00".$count[0];
        }else if(strlen($count[0])==4){
            $id .= "0".$count[0];
        }else{
            $id .= $count[0];
        }
        //生成雲端資料夾 的路徑
        $path="../cloud/".$id;
        //生成雲端資料夾
        @mkdir($path, 0777, true);
        
        $sql = "INSERT INTO users (username, email, password,name1,statu,birthday,sex,phone,id,dt)
                VALUES ('$username', '$email', '$password','$name1','$staut','$brithday','$sex','$phone','$id','$dt')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "註冊成功!請登入謝謝。";
            echo '<meta http-equiv="refresh" content="0; url=index.php">';
        } else {
            echo "糟糕!出現不知名錯誤。";
    }
}
}else{
    echo "失敗";
}
?>