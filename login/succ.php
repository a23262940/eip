<?php
include 'config.php';
session_start();
if(isset($_GET['user']) && password_verify($_SESSION['succemail'],$_GET['user'])){
    mysqli_query($conn,"UPDATE users set `login`='0' where email='".$_SESSION['succemail']."'");
    echo "<script>alert('已成功解鎖')</script>";
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
}
?>