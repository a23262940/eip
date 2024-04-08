<?php error_reporting(0); ?>
<!--TOP Navbar -->
<nav class="navbar navbar-expand-lg navbar-light p-3 navbar-expand-lg" style="background-color: #e3f2fd;">
    <div class="container">
        <!-- Brand here-->
        <a href="../main/index.php" class="navbar-brand">
            <img src="../pic/success.png" width="60" height="60" alt=""> 朝陽公司
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="../login/login_main.php" class="nav-link">功能區塊</a>
                </li>
                <li class="nav-item">
                    <a href="../main/help.php" class="nav-link">關於我們</a>
                </li>
                <?php if(isset($_SESSION['id'])){ ?>
                <li class="nav-item">
                    <a type="button" href="../login/index.php" class="btn btn-light"><?php echo "哈摟~".$_SESSION['name1'] ?></a>
                </li>
                <?php }else{ ?>
                <li class="nav-item">
                    <a type="button" class="btn btn-light" href="../login/index.php">登入</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>