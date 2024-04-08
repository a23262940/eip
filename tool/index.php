<?php
session_start();
include "../login/config.php";
if (isset($_SESSION['id']) && $_SESSION['statu']=="管理者") {
    $result = mysqli_query($conn, "SELECT * FROM users");
} else {
    echo "<script>alert('您不是管理員。')</script>";
    echo '<meta http-equiv="refresh" content="0; url=../main/index.php">';
}
?>
<!DOCTYPE html>
<html>

<head>
    <!--html head-->
    <?php include "../php/html_head.php" ?>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <title>朝陽公司管理員系統</title>
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
            <!--整體頁面-->
            <main class="col-lg-10 px-md-5">
                <!--區塊-->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <!--路徑顯示-->
                    <h1 class="h2">
                        <div id="breadcrumb">管理者後台</div>
                    </h1>
                </div>
                <table class="table" id="user">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">帳號</th>
                            <th scope="col">uid</th>
                            <th scope="col">姓名</th>
                            <th scope="col">信箱</th>
                            <th scope="col">職位</th>
                            <th scope="col">生日</th>
                            <th scope="col">性別</th>
                            <th scope="col">電話</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr name="tr">';
                            echo '<th scope="row"><input id="iid" class="form-control-plaintext" name="iid" disabled value=' . $i . '></th>';
                            echo '<td><input id="getuid" class="form-control-plaintext" name="getuid" disabled value=' . $row['username'] . '></td>';
                            echo '<td><input id="getid" class="form-control-plaintext" name="getid" disabled value=' . $row['id'] . '></td>';
                            echo '<td><input id="getname1" class="form-control-plaintext" name="getname1" disabled value=' . $row['name1'] . '></td>';
                            echo '<td><input id="getemail" class="form-control-plaintext" name="getemail" disabled value=' . $row['email'] . "></td>";
                            echo '<td><input id="getstatu" class="form-control-plaintext" name="getstatu" disabled value=' . $row['statu'] . "></td>";
                            echo '<td><input id="getbd" class="form-control-plaintext" name="getbd" disabled value=' . $row['birthday'] . "></td>";
                            echo '<td><input id="getsex" class="form-control-plaintext" name="getsex" disabled value=' . $row['sex'] . "></td>";
                            echo '<td><input id="getphone" class="form-control-plaintext" name="getphone" disabled value=' . $row['phone'] . "></td>";
                            echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modfidata" onclick="changemodel(' . $i . ')">修改資料</button><td>';
                            echo '<td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#user-delete-Modal" onclick="showdelete(' . $i . ')">刪除</button><td>';
                            echo "</tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </main>
            <!-- Modal -->

        </div>
    </div>
    <!--建立委託 模态框（Modal)-->
    <div class="modal fade" id="modfidata" role="dialog" tabindex="-1" aria-labelledby="modfidataLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                    </svg>
                    <h5 class="modal-title">更改會員資料</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">id:</label>
                            <div class="col-sm-9">
                                <input type="text" name="id" id="id" value="" readonly />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">帳號:</label>
                            <div class="col-sm-9">
                                <input type="text" name="uid" id="uid" value="" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">姓名:</label>
                            <div class="col-sm-9">
                                <input type="text" name="name1" id="name1" value="" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">信箱:</label>
                            <div class="col-sm-9">
                                <input type="text" name="eamil" id="email" value="" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">職位:</label>
                            <div class="col-sm-9">
                                <?php
                                $statu = ["請選擇身分", "管理者", "經理", "員工"];
                                echo '<select name="staut" id="staut" class="form-select">';
                                for ($i = 0; $i < count($statu); $i++) {
                                    echo '<option value="' . $statu[$i] . '"';
                                    echo ($row['statu'] == $statu[$i]) ? ' selected ' : '';
                                    echo '>' . $statu[$i];
                                }
                                echo '</select>';
                                ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">生日:</label>
                            <div class="col-sm-9">
                                <input type="date" name="bd" id="bd" value="" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">性別:</label>
                            <div class="col-sm-9">
                                <?php
                                $sex1 = ["請選擇性別", "男性", "女性", "不顯示"];
                                $sex2 = ["", "M", "F", "O"];
                                echo '<select name="sex" id="sex" class="form-select" required>';
                                for ($i = 0; $i < count($sex1); $i++) {
                                    echo '<option value="' . $sex2[$i] . '"';
                                    echo ($row['sex'] == $sex2[$i]) ? ' selected ' : '';
                                    echo '>' . $sex1[$i];
                                }
                                echo '</select>';
                                ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label p-2">電話:</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" id="phone" value="" /><br>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                            <button type="button" class="btn btn-primary" id="userupdata">修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--刪除modal-->
    <div class="modal fade" id="user-delete-Modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                    </svg>
                    <h5 class="modal-title">確定刪除?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal" method=post>
                        <div class="form-group">
                            <label class="control-label p-2">刪除代碼為:<?php $a = number();
                                                                    echo $a; ?>(6位隨機亂數)</label>
                            <input type="text" id="cc" class="form-control" placeholder="請輸入對應的刪除代碼..." required>
                            <input type="hidden" id="cs" name="cs" value="">
                        </div>

                        <div class="modal-footer">
                            <div class="form-group">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger" id="delete" name="delete">確定</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- footer copyright-->
    <?php include '../php/footer.php'; ?>
</body>
<script>
    function changemodel(aa) {
        var tb = document.getElementById("user");
        $("#modfidata").modal("hide");
        var index = 1;
        while (true) {
            if (index == aa) {
                $("#modfidata").modal("show");
                var uid = tb.rows[aa].cells[1].children[0].value;
                var id = tb.rows[aa].cells[2].children[0].value;
                var name1 = tb.rows[aa].cells[3].children[0].value;
                var email = tb.rows[aa].cells[4].children[0].value;
                var statu = tb.rows[aa].cells[5].children[0].value;
                var bd = tb.rows[aa].cells[6].children[0].value;
                var sex = tb.rows[aa].cells[7].children[0].value;
                var phone = tb.rows[aa].cells[8].children[0].value;
                $('#uid').val(uid);
                $('#id').val(id);
                $('#name1').val(name1);
                $('#email').val(email);
                $('#staut').val(statu);
                $('#bd').val(bd);
                $('#sex').val(sex);
                $('#phone').val(phone);
                break;
            } else {
                index++
            }
        }
    }
    $("#userupdata").on("click", function() {
        var uid = $('#uid').val();
        var id = $('#id').val();
        var name1 = $('#name1').val();
        var email = $('#email').val();
        var statu = $('#staut').val();
        var bd = $('#bd').val();
        var sex = $('#sex').val();
        var phone = $('#phone').val();
        if (uid != "" && id != "" && email != '' && name1 != "" && statu != "請選擇身分" && bd != "" && sex != "請選擇性別" && phone != "") {
            $.ajax({
                type: "POST",
                url: "tool1.php",
                data: {
                    uid,
                    id,
                    email,
                    name1,
                    statu,
                    bd,
                    sex,
                    phone
                },
                error: function(xhr) {
                    alert('Ajax request 發生錯誤');
                },
                success: function(response) {}
            }).done(function() {
                alert('修改成功');
                location.reload(true);
            });
        } else if (uid == "") {
            alert("帳號不能是空的");
        } else if (id == "") {
            alert("id不能是空的");
        } else if (name1 == "") {
            alert("姓名不能是空的");
        } else if (email == "") {
            alert("email不能是空的");
        } else if (statu == "") {
            alert("職位不能是空的");
        } else if (bd == "") {
            alert("生日不能是空的");
        } else if (sex == "") {
            alert("性別不能是空的");
        } else if (phone == "") {
            alert("電話不能是空的");
        } else {
            alert("出現不知名錯誤");
        }
    });

    function showdelete(bb) {
        var index1 = 1;
        var tb = document.getElementById("user");
        $("#user-delete-Modal").modal("hide");
        while (true) {
            if (bb == index1) {
                $("#user-delete-Modal").modal("show");
                var uid = tb.rows[bb].cells[2].children[0].value;
                $('#cs').val(uid);
                break;
            } else {
                index1++;
            }
        }
    }
    $("#delete").on("click", function() {
        var ch = $('#cc').val();
        var cs = $('#cs').val();
        if (ch == <?php echo $a ?>) {
            $.ajax({
                type: "POST",
                url: "tool1.php",
                data: {
                    cs
                },
                error: function(xhr) {
                    alert('Ajax request 發生錯誤');
                },
                success: function(response) {}
            }).done(function() {
                alert('刪除成功');
                location.reload(true);
            });
        } else {
            alert("驗證碼錯誤");
        }
        $('#user-delete-Modal').modal('hide');
    });
</script>

</html>