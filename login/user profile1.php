<?php
session_start();
error_reporting(0);
require_once 'config.php';
if (!isset($_SESSION['id'])) {
  header("Location: ../main/index.php");
} else {
  $id = $_SESSION['id'];
  $sql = "SELECT * FROM users WHERE id='$id';";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  mysqli_free_result($result);
  $_SESSION['username'] = $row['username'];
  $_SESSION['name1'] = $row['name1'];
  $_SESSION['email'] = $row['email'];
  $_SESSION['statu'] = $row['statu'];
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!--html head-->
  <?php include '../php/html_head.php'; ?>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script>
    $(document).ready(function() {
      checkRegAcc = function() {
		$("#username").on("keyup",function(){
		if($(this).val() != ""){
			$.ajax({
            url: 'mag_admin_check.php',  // 判斷欄位是否存在的程式
            type: 'POST',    // 傳遞的方法
            data: {
                username: $('#username').val()
            },
            error: function(xhr) {          // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
            },
           	success: function(response) {   // 將回傳的訊息寫入網頁中
                $('#msg_username').html(response);
                $('#msg_username').fadeIn();
           	}
			}).done(function(data){
				if(data==''){
					$("#cbtn").attr("disabled",false);
          $("#username").removeClass("is-invalid");
				}else{
					$("#cbtn").attr("disabled",true);
          $("#username").addClass("is-invalid");
				}
			});
			}
        });
	};
      checkname = function() {
        $("#name1").on("keyup", function() {
          if ($(this).val() != "") {
            $.ajax({
              url: 'mag_admin_check.php', // 判斷欄位是否存在的程式
              type: 'POST', // 傳遞的方法
              data: {
                name1: $('#name1').val()
              },
              error: function(xhr) { // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
              },
              success: function(response) { // 將回傳的訊息寫入網頁中
                $('#msg_name1').html(response);
                $('#msg_name1').fadeIn();

              }
            }).done(function(data) {
              if (data == '') {
                $("#cbtn").attr("disabled", false);
                $("#name1").removeClass("is-invalid");
              } else {
                $("#cbtn").attr("disabled", true);
                $("#name1").addClass("is-invalid");
              }
            });
          }
        });
      };
      checkemail = function() {
        $("#email").on("keyup", function() {
          if ($(this).val() != "") {
            $.ajax({
              url: 'mag_admin_check.php', // 判斷欄位是否存在的程式
              type: 'POST', // 傳遞的方法
              data: {
                email: $('#email').val()
              },
              error: function(xhr) { // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
              },
              success: function(response) { // 將回傳的訊息寫入網頁中
                $('#msg_email').html(response);
                $('#msg_email').fadeIn();
              }
            }).done(function(data) {
              if (data == '') {
                $("#cbtn").attr("disabled", false);
                $("#email").removeClass("is-invalid");
              } else {
                $("#cbtn").attr("disabled", true);
                $("#email").addClass("is-invalid");
              }
            });
          }
        });
      };
      checkephone = function() {
        $("#phone").on("keyup", function() {
          if ($(this).val() != "") {
            $.ajax({
              url: 'mag_admin_check.php', // 判斷欄位是否存在的程式
              type: 'POST', // 傳遞的方法
              data: {
                phone: $('#phone').val()
              },
              error: function(xhr) { // 設定錯誤訊息
                alert('Ajax request 發生錯誤');
              },
              success: function(response) { // 將回傳的訊息寫入網頁中
                $('#msg_phone').html(response);
                $('#msg_phone').fadeIn();
              }
            }).done(function(data) {
              if (data == '') {
                $("#cbtn").attr("disabled", false);
                $("#phone").removeClass("is-invalid");
              } else {
                $("#cbtn").attr("disabled", true);
                $("#phone").addClass("is-invalid");
              }
            });
          }
        });
      };
    });

    function checknm() {
      var nm = $('#check1').val();
      if (nm == '') {
        alert('驗證碼不能是空白');
      } else {
        $.ajax({
          type: "POST",
          url: "outemail.php",
          data: {
            nm
          },
          error: function(xhr) {
            alert('Ajax request 發生錯誤');
          },
          success: function(response) {}
        }).done(function(data) {
          if (data == "true") {
            window.location.replace("changepd.php");
          } else {
            alert('驗證碼錯誤');
          }
        });
      }
    }
  </script>
</head>

<body>

  <!--個人資料-整體頁面-->
  <main class="px-md-5">
    <!--個人資料-上層區塊-->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">個人資料</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
          <!--<input class="form-control" placeholder="搜尋他人頁面..." required>
          <button type="button" class="btn btn-sm btn-outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
          </button>-->
        </div>
        <div class="btn-group me-2">
          <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userinformation-Modal">編輯資料</button>
          <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changepd-Modal">修改密碼</a>
          <a class="btn btn-danger" id="logout" href="" role="button">登出</a>
        </div>
      </div>
    </div>

    <!--個人資料-所有資訊列出-->
    <div class="container-fluid justify-content-left border-bottom p-3 mb-5">
      <ul class="text-left mb-1">
        <li>帳號: <?php echo $row['username'] ?></li><br>
        <li>名稱: <?php echo $row['name1'] ?></li><br>
        <li>信箱: <?php echo $row['email'] ?></li><br>
        <li>電話: <?php echo $row['phone'] ?></li><br>
        <li>身分組: <?php echo $row['statu'] ?></li><br>
        <li>所屬部門: <?php echo $row['dt'] ?></li><br>
        <li>性別: <?php $sex1 = ["請選擇性別", "男性", "女性", "不顯示"];
                $sex2 = ["", "M", "F", "O"];
                for ($i = 0; $i < count($sex1); $i++) {
                  if ($sex2[$i] == $row['sex'])
                    echo $sex1[$i];
                } ?></li><br>
        <li>生日: <?php echo $row['birthday'] ?></li><br>
        <li>個人簡介: <?php echo $row['tt'] ?></li><br>
      </ul>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!--修改個人資料內容 模态框（Modal)-->
  <div class="modal fade" id="userinformation-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
          </svg>
          <h5 class="modal-title">編輯個人資料</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">帳號:</label>
              <div class="col-sm-9">
                <input type="account" class="form-control" id="username" placeholder="帳號" value="<?php echo $row['username']; ?>" onkeyup="checkRegAcc()" required>
                <span class="text-danger" id="msg_username"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="name1" class="col-sm-3 control-label p-2">名稱:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="名稱" name="name1" id="name1" value="<?php echo $row['name1']; ?>" onkeyup="checkname()" required>
                <span class="text-danger" id="msg_name1"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-sm-3 control-label p-2">信箱:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="123456@xxx.com" id="email" value="<?php echo $row['email']; ?>" onkeyup="checkemail()" disabled>
                <span class="text-danger" id="msg_email"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="phone" class="col-sm-3 control-label p-2">電話:</label>
              <div class="col-sm-9">
                <input type="tel" class="form-control" placeholder="電話號碼" id="phone" value="<?php echo $row['phone']; ?>" onkeyup="checkephone()" required>
                <span class="text-danger" id="msg_phone"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="staut" class="col-sm-3 control-label p-2">身分組:</label>
              <div class="col-sm-9 p-2">
                <?php
                $statu = ["請選擇身分", "經理", "員工", "管理者"];
                echo '<select name="staut" id="staut" class="form-select" disabled>';
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
              <label for="staut" class="col-sm-3 control-label p-2">所屬部門:</label>
              <div class="col-sm-9 p-2">
                <?php
                $dt = ["請選擇部門", "開發部", "會計部", "行銷部"];
                echo '<select name="dt" id="dt" class="form-select">';
                for ($i = 0; $i < count($dt); $i++) {
                  echo '<option value="' . $dt[$i] . '"';
                  echo ($row['dt'] == $dt[$i]) ? ' selected ' : '';
                  echo '>' . $dt[$i];
                }
                echo '</select>';
                ?>
              </div>
            </div>

            <div class="form-group row">
              <label for="sex" class="col-sm-3 control-label p-2">性別:</label>
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
              <label for="birthday" class="col-sm-3 control-label p-2">生日:</label>
              <div class="col-sm-9">
                <input type="date" class="form-control" id="birthday" placeholder="生日" value="<?php echo $row['birthday']; ?>" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">個人簡介:</label>
              <div class="col-sm-9">
                <textarea type="text" class="form-control" id="tt" name="tt" placeholder="自行輸入..." style="height: 100px" required><?php echo $row['tt']; ?></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <div class="form-group">
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary" id="cbtn" name="cbtn" onclick="updata()">確定</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!--更改密碼 模态框（Modal)-->
  <div class="modal fade" id="changepd-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
            <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
            <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z" />
          </svg>
          <h5 class="modal-title">更改密碼</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!--  <form class="form-horizontal">-->

          <div class="form-group row p-2">
            <label class="col-lg-3 control-label p-2">寄送驗證碼:</label>
            <div class="btn-group col-lg-9">
              <input type="email" class="form-control" placeholder="123456@xxx.com" name="email" id="email" value="<?php echo $row['email']; ?>" onkeyup="checkemail()" disabled>
              <button type="button" class="btn btn-sm btn-outline-secondary w-50" name="button-out" id="button-out">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                  <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                </svg>
                <span>寄出</span>
              </button>
            </div>
          </div>

          <div class="form-group row p-2 border-bottom">
            <label class="col-lg-3 control-label p-2">驗證碼:</label>
            <div class="col-lg-9">
              <input type="account" class="form-control" id="check1" placeholder="驗證碼" required>
            </div>
          </div>

          <!--  <div class="form-group row p-2">
            <label class="col-lg-3 control-label p-2">原始密碼:</label>
            <div class="col-lg-9">
              <input type="account" class="form-control"  placeholder="自行輸入..." required>
            </div>
          </div>

          <div class="form-group row p-2">
            <label class="col-lg-3 control-label p-2">新密碼:</label>
            <div class="col-lg-9">
              <input type="account" class="form-control"  placeholder="自行輸入..." required>
            </div>
          </div>

          <div class="form-group row p-2">
            <label class="col-lg-3 control-label p-2">再次確認:</label>
            <div class="col-lg-9">
              <input type="account" class="form-control"  placeholder="自行輸入..." required>
            </div>
          </div>
                -->
          <div class="modal-footer">
            <div class="form-group">
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="c1btn" name="c1btn" onclick="checknm()">確定</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
              </div>
            </div>
          </div>

          <!--  </form> -->
        </div>

      </div>
    </div>
  </div>
  <script>
    function updata() {
      var username = $('#username').val();
      var name1 = $('#name1').val();
      var email = $('#email').val();
      var phone = $('#phone').val();
      var staut = $('#staut').val();
      var sex = $('#sex').val();
      var birthday = $('#birthday').val();
      var tt = $("#tt").val();
      var dt = $('#dt').val();
      if (name1 == '') {
        alert('名子不能為空白');
      } else if (email == '') {
        alert('信箱不能為空');
      } else if (phone == '') {
        alert('電話不能為空');
      } else if (staut == '請選擇身分') {
        alert('請選擇職稱');
      } else if (dt == '請選擇部門') {
        alert('請選擇部門');
      }else if (sex == '') {
        alert('請選擇性別');
      } else if (birthday == '') {
        alert('請選擇生日');
      } else {
        $.ajax({
          type: "POST",
          url: "updata.php",
          data: {
            username,
            name1,
            email,
            phone,
            staut,
            sex,
            birthday,
            tt,
            dt
          },
          error: function(xhr) {
            alert('Ajax request 發生錯誤');
          },
          success: function(response) {
            alert('修改完成');
          }
        }).done(function() {
          location.reload(true);
        });
        $('#userinformation-Modal').modal('hide');
      }
    }

    $("#button-out").on("click", function() {
      $("#button-out").attr("disabled", true);
      setTimeout(function() {
        $("#button-out").attr("disabled", false);
      }, 30000);
      var email = $('#email').val();
      if (email != '') {
        alert("請至信箱查詢驗證碼");
        $.ajax({
          type: "POST",
          url: "outemail.php",
          data: {
            email
          },
          error: function(xhr) {
            alert('Ajax request 發生錯誤');
          },
          success: function(response) {}
        });
      }
    });
    $("#logout").on("click",function(){
      if(window.top !== window.self){ //若自身窗口不等于顶层窗口
        window.top.location.href = window.self.location.href; //顶层窗口跳转到自身窗口
        $.ajax({
        type: "POST",
        url: "logout.php",
        data:{},
        success: function(response){
          alert("登出成功");
        }
      });
      }
    });
  </script>

</body>

</html>