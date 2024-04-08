<?php
session_start();
extract($_REQUEST);
include('connect.php');
if (isset($_POST['y'])) {
    $name = $_SESSION['name1'];
    $time = date('Y-m-d H:i:s');
    $comment = $_POST['comment'];
    $qid = $del;
    $C = "insert into board (name,time,comment,qid) values('$name','$time','$comment','$qid')";
    $A = mysqli_query($db, $C);
}
?>
<script>
setInterval(function () { $(".refresh").load(location.href + " .refresh"); }, 5000);
</script>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!--html head-->
    <?php include '../php/html_head.php' ?>
    <!-- DataTable 連結 -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.css"> -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.js"></script>
    <!--Database連結-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</head>

<body>
    <?php
    $D = mysqli_query($db, "SELECT * FROM board WHERE qid='$del'");
    ?>
    <!--TOP Navbar -->
    <?php include '../php/topnavbar.php'; ?>
    <!--整體-->
    <div class="container-fluid">
        <div class="row">
            <!--left side bar 側邊導航-->
            <div class="col-lg-2">
                <?php include '../php/leftsidenavbar.php'; ?>
            </div>

            <main class="col-lg-10 px-md-5">
                <!--所有委任-搜尋區塊-->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">討論區</h1>
                </div>

                <!--委任-->
                <div class="modal-body">
                    <form action="" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">留言:</label>
                            <div class="col-sm-6">
                                <textarea type="text" name="comment" class="form-control" placeholder="自行輸入..." style="height: 100px" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="y" class="btn btn-primary">確定</button>
                                <a type="button" class="btn btn-secondary" href="../quest/quest_main.php">返回</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="p-3 bottom">
                    <div class="form-group row">
					<div class="refresh">
                        <?php while($E = mysqli_fetch_array($D)){
                            echo $E['name'] . " " . $E['time'] . " " . ":" . " " . $E['comment'] . "<br>";
                        }?>
						</div>
                    </div>

                </div>
            </main>
            <!--委任-->


        </div>
    </div>
</body>