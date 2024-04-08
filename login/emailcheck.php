<?php
session_start();
error_reporting(0);
?>

<!DOCTYPE html>
<html>

<head>
  <?php include '../php/html_head.php' ?>
  <link rel="stylesheet" href="css/style.css">
</head>
<!-- First body -->
<div class="container">
  <div class="row">
    <div class="container">
      <div class="row justify-content-center align-items-center">
        <div class="col-8 col-md-6">
          <div class="text">
            <h1>沒收到信件嗎?</h1>
          </div>
          <input type="hidden" id="email" value="<?php echo $_SESSION['email1']; ?>">
          <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3" id="reemail" name="reemail">重新寄送</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
</body>
<script>
  $("#reemail").on("click", function() {
    $("#reemail").attr("disabled", true);
    setTimeout(function() {
      $("#reemail").attr("disabled", false);
    }, 5000);
    var email2 = $('#email').val();
    if (email2 != '') {
      alert("請至信箱查詢驗證碼");
      $.ajax({
        type: "POST",
        url: "outemail.php",
        data: {
          email2
        },
        error: function(xhr) {
          alert('Ajax request 發生錯誤');
        },
        success: function(response) {}
      });
    }
  });
</script>

</html>