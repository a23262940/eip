<?php
extract($_REQUEST);
include('connect.php');
$A = mysqli_query($db, "SELECT * FROM art WHERE id='$del'");
$B = mysqli_fetch_array($A);

?>
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
    <!--TOP Navbar -->
    <?php include '../php/topnavbar.php'; ?>
    <!--整體-->
    <div class="container-fluid">
        <div class="row">
            <!--left side bar 側邊導航-->
            <div class="col-lg-2">
                <?php include '../php/leftsidenavbar.php'; ?>
            </div>

            <!--所有委任-整體頁面-->
            <main class="col-lg-10 px-md-5">
                <!--所有會議-->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <!-- 標頭 -->
                    <h1 class="h2">標題:<?php echo $B['title'] ?></h1>
                </div>
                <!--委任-->
                <div class="p-3 bottom">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">發布者:</label>
                        <div class="col-sm-9">
                            <?php echo $B['name'] ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">會議日期:</label>
                        <div class="col-sm-9">
                            <?php echo $B['time'] ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">相關部門:</label>
                        <div class="col-sm-9">
                            <?php echo $B['depart'] ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">身分:</label>
                        <div class="col-sm-9">
                            <?php echo $B["statu"] ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">代號:</label>
                        <div class="col-sm-9">
                            <?php echo $B["qid"] ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">文章內容:</label>
                        <div class="col-sm-9">
                            <textarea rows="20" cols="60" readonly><?php echo $B['connect'] ?></textarea><br />
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-sm-3 control-label p-2">留言:</label>
                        <div class="col-sm-9">
                            <textarea type="text" name="coment" class="form-control" placeholder="自行輸入..." style="height: 100px" required></textarea>
                        </div>
                    </div> -->
                    <div class="d-flex justify-content-end">
                        <a type="button" class="btn btn-secondary" href="../quest/quest_main.php">返回</a>
                    </div>
                </div>

            </main>

        </div>
    </div>

    <!-- footer copyright-->
    <?php include '../php/footer.php'; ?>

</body>

</html>