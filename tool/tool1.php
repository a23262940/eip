<?php
include "../login/config.php";
if(isset($_POST['cs'])){
    $cs = $_POST['cs'];
    $sql = "DELETE FROM users where id='$cs'";
    $result=mysqli_query($conn,$sql);
    echo "<script>alert('刪除成功')</script>";
}
if(isset($_POST['uid'])&& isset($_POST['name1']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['statu'])&& isset($_POST['sex']) && isset($_POST['bd'])&& isset($_POST['id'])){
    $username = $_POST['uid'];
    $id = $_POST['id'];
    $name1=$_POST['name1'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $staut=$_POST['statu'];
    $sex=$_POST['sex'];
    $birthday=$_POST['bd'];
    $sql1="UPDATE users SET username='$username', name1='$name1' ,email='$email', phone='$phone' ,statu='$staut',sex='$sex',birthday='$birthday' WHERE id='$id';";
    $result=mysqli_query($conn,$sql1);
    echo "<script>alert('修改成功')</script>";
}
?>