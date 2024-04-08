<?php
include 'config.php';// 連線資料庫 
error_reporting(0);
if (isset($_POST['username'])) {
    $rs = $_POST['username'];           //判斷帳號是否存在
    $sql="SELECT * FROM users WHERE username='$rs'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        $ret = "";
    }else {
        $ret = "此帳號已經有人使用";
    }
    echo $ret;
}
if (isset($_POST['name1'])) {
    $rs1 = $_POST['name1'];             //判斷是否為中文姓名
    if (preg_match("/[\x{4e00}-\x{9fa5}]$/u",$rs1)) {
        $ret1 = "";
    } else {
        $ret1 = "請輸入中文名子";
    }
    echo $ret1;
}
if (isset($_POST['email'])) {
    $rs2 = $_POST['email'];           //判斷信箱格式是否正確以及是否存在
    $sql="SELECT email FROM users WHERE email='$rs2'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
   if($row){
        $ret2="email已被使用";
    } 
    elseif (filter_var($rs2, FILTER_VALIDATE_EMAIL)) {
        $ret2 = "";
    }
    else {
        $ret2 = "請輸入正確email";
    }
    echo $ret2;
}
if (isset($_POST['password'])) {
    $rs3 = $_POST['password'];         //判斷密碼是否符合格式
    if (preg_match('/\W/',$rs3)) {
        $ret3 = "不能有特殊字元";
    } elseif(!preg_match('/[a-z]/',$rs3) || !preg_match('/[A-Z]/',$rs3) || !preg_match('/[0-9]/',$rs3)) {
        $ret3 = "需要有大小寫英文跟數字";
    }
    else{
        $ret3="";
    }
    echo $ret3;
}
if (isset($_POST['phone'])) {
    $rs5 = $_POST['phone'];         //判斷電話號碼是否為正確的
    if (preg_match('/(\d{2,3}-?|\(\d{2,3}\))\d{3,4}-?\d{4}|09\d{2}(\d{6}|-\d{3}-\d{3})/',$rs5)) {
        $ret5 = "";
    }
    else{
        $ret5="格式不正確";
    }
    echo $ret5;
}

?>