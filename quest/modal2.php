<?php
session_start();
//$_SESSION['id'] = "2";
include("connect.php");
if (isset($_POST['btn1'])) {
    $name = $_POST['name'];
    $time = $_POST['time'];
    $depart_arr = array();
        $depart_arr = $_POST['depart'];
        $depart = implode('、', $depart_arr);
    $statu = $_POST['statu'];
    $title = $_POST['title'];
    $connect = $_POST['connect'];
    $uid = $_POST['uid'];
    $A = "insert into art (name,time,depart,statu,title,connect,uid) values('$name','$time','$depart','$statu','$title','$connect','$uid')";
    if ($retval = mysqli_query($db, $A)) {
        if ($query = mysqli_query($db, "select MAX(id) from art ") or die(mysqli_error($db))) {
            $result = mysqli_fetch_array($query);
            $qid = "q" . $result[0];
            $date = date('Y-m-d H:i:s');
            $folderdir = "questcloud/". $qid;
            mysqli_query($db, "update  art set qid = '$qid' where id = '" . $result[0] . "'");
            //生成雲端資料夾 的路徑
            $path = "../cloud/questcloud/" . $qid;
            //生成雲端資料夾
            @mkdir($path, 0777, true);
            $conn = new PDO('mysql:host=localhost; dbname=company', 'root', '123qwe');
            $query = $conn->query("INSERT INTO upload (name,date,dir,type) VALUES ('$qid','$date','$folderdir','folder')");
        }
        echo "<script>alert('success')</script>";
    }
    header("Location:quest_main.php");
}
