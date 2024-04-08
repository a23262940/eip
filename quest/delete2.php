<?php

extract($_REQUEST);
include('connect.php');
$qid = 'q'. $del;
$file = "../cloud/questcloud/" . $qid;
mysqli_query($db, "delete from art where id='$del'"); //刪除sub裡id欄位的資料
rmrf($file);
header("Location:quest_main.php");
function rmrf($dir)
{
    $filess = $dir;
    $filess = str_replace('../cloud/','',$filess);
    $conn = new PDO('mysql:host=localhost; dbname=company', 'root', '123qwe');
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file)
            rmrf("$dir/$file");
        rmdir($dir);
        $query = $conn->query("delete from upload where dir='$filess'");
    } else {
        unlink($dir);
        $query = $conn->query("delete from upload where dir='$filess'");
    }
}
?>