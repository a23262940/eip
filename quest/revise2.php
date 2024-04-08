<?php
extract($_REQUEST);
include('connect.php');
$A = mysqli_query($db, "SELECT * FROM art WHERE id='$del'");
$B = mysqli_fetch_array($A);
if (isset($_POST['ch'])) {
    $name = $_POST['name'];
    $time = $_POST['time'];
    $title = $_POST['title'];
    $connect = $_POST['connect'];
    mysqli_query($db, "update art set name='$name',time='$time',title='$title',connect='$connect'  where id='$del'");
    header("Location:quest_main.php");
}
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
                <!--所有委任-搜尋區塊-->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">編輯文章</h1>
                </div>

                <!--委任-->
                <div class="modal-body">
                    <form action="" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">發布者:</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" value="<?php echo $B['name'] ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">身分:</label>
                            <div class="col-sm-9">
                                <input type="text" name="statu" class="form-control" value="<?php echo $B["statu"] ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">會議日期:</label>
                            <div class="col-sm-9">
                                <input type="date" name="time" class="form-control" value="<?php echo $B['time'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">相關部門:</label>
                            <div class="col-sm-9">
                                <input type="text" name="depart" class="form-control" value="<?php echo $B['depart'] ?>" readonly>
                            </div>
                        </div>

                        </br>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">標題:</label>
                            <div class="col-sm-9">
                                <input type="text" name="title" class="form-control" value="<?php echo $B['title'] ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">文章內容:</label>
                            <div class="col-sm-9">
                                <!--<input type="text" name="connect" class="form-control" style="height: 300px" value="<?php //echo $B['connect'] ?>" required></input> -->
                                <textarea name="connect" rows="10" cols="50"><?php echo $B['connect']?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" name="ch" class="btn btn-primary">確定</button>
                                    <a type="button" class="btn btn-secondary" href="../quest/quest_main.php">取消</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </main>

        </div>
    </div>

    <!-- footer copyright-->
    <?php include '../php/footer.php'; ?>

</body>

</html>