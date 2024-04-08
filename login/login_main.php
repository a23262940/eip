<?php 
  session_start();
  if (!isset($_SESSION['id'])) {
    header("Location: ../main/index.php");
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!--html head-->
  <?php include '../php/html_head.php'; ?>
  <script>
    $(document).ready(function() {
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
  <!--TOP Navbar -->
  <?php include '../php/topnavbar.php'; ?>
  <!--整體-->
  <div class="container-fluid">
    <div class="row">
      <!--left side bar 側邊導航-->
      <div class="col-lg-2">
        <?php include '../php/leftsidenavbar.php'; ?>
      </div>

      <!--個人資料-整體頁面-->
      <main class="col-lg-10 px-md-5">
        <!--直接導入-->
        <iframe src="user profile1.php" frameborder="1" width="100%" height="1000px" class="p-1"></iframe>
      </main>

    </div>
  </div>


  <!-- footer copyright-->
  <?php include '../php/footer.php'; ?>
</body>

</html>