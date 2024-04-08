<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!--html head-->
  <?php include '../php/html_head.php' ?>
  <link rel="stylesheet" href="css/style.css">
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
        <div class="container">
          整體規劃:</br>
          1.現在進度搓合能使用</br>
          -1:架設成功 任何設備有網路都能直接連線</br>
          -2:每個人創立帳號能夠成功 且登入 分立不同能看到的權限</br>
          2.規劃現有資源以及未來主題目標</br>
          -1:主要以公司輔助為主...多餘的功能添加?</br>

          -2:李育承 > 列表 能延攬出那些作用能提供甚麼服務</br>
          > 建立新列表 發展成文章發布類型 多人都能觀看並且反應留言</br>
          -3:吳品翰 > 登入 暫時整理完成權限分立後朝管理後台發展</br>
          例如:能夠調整他人權限,對於李育承列表的更動(刪除 警告 封鎖)</br>
          -4:黃威浩 +高右殷 > 檔案管理系統 吳品翰信件和李育承列表/文章結合 能夠超連結到特定位置 觀看/下載</br>
          -5:陳立軒 > 頁面規劃 基於上述所有人的想法 規劃出頁面提供使用</br>
          **需提前所有人越早弄好越好 結合問題 無法成功展現功能等等問題</br>
        </div>
      </main>

    </div>
  </div>
  <!--塞空間 避免右側沒東西 導致footer內縮
  <canvas height="500"></canvas>-->
  <!-- footer co  pyright-->
  <?php include '../php/footer.php'; ?>
</body>

</html>