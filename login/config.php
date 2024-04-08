<?php 
error_reporting(0);
$server = "localhost";
$user = "root";
$pass = "123qwe";
$database = "company";

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
    die("<script>alert('Connection Failed.')</script>");
}

function number($len=3){
	$numbers = range (10,99);
//shuffle 将数组顺序随即打乱
	shuffle ($numbers);
//取值起始位置随机
	$start = mt_rand(1,10);
//取从指定定位置开始的若干数
	$result = array_slice($numbers,$start,$len);
	$random = "";
	for ($i=0;$i<$len;$i++){
		$random = $random.$result[$i];
	}
	return $random;
}
?>
