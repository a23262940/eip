<?php
require 'config.php';
session_start();
if (isset($_POST['username'])&& isset($_POST['name1']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['staut'])&& isset($_POST['sex']) && isset($_POST['birthday'])&&isset($_SESSION['id'])&&isset($_POST['tt'])){
    $username=$_POST['username'];
    $name1=$_POST['name1'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $staut=$_POST['staut'];
    $sex=$_POST['sex'];
    $birthday=$_POST['birthday'];
    $id=$_SESSION['id'];
    $tt=$_POST['tt'];
    $dt=$_POST['dt'];
    $sql="UPDATE users SET username='$username', name1='$name1' ,email='$email', phone='$phone' ,statu='$staut',sex='$sex',birthday='$birthday',tt='$tt',dt='$dt' WHERE id='$id';";
    $result=mysqli_query($conn,$sql);
    if($result){
        $username='';
        $name1='';
        $email='';
        $phone='';
        $staut='';
        $sex='';
        $birthday='';
        $tt="";
        $dt='';
    }else{
        echo "<script>alert('發生錯誤，請再重新嘗試')</script>";
    }
}else{
    header("Location: index.php");
}

?>