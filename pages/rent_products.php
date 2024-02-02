<?php
include_once './db_connect.php';
$perPage = 5;
$sqlproductInfo = "SELECT * FROM product_info";
$resultproductInfo = $conn->query($sqlproductInfo);
$rowsproductInfo = $resultproductInfo->fetch_all(MYSQLI_ASSOC);

if(isset($_GET["class"])){
  $class=$_GET["class"];
  $sqlCount = "SELECT COUNT(*) AS count FROM rent WHERE class_id= '$class' AND valid=1";

  $resultCount = $conn->query($sqlCount);
  $row = $resultCount->fetch_assoc();
  $productTotalCount = $row['count'];
}elseif (isset($_GET["search"])) {
  $search = $_GET["search"];
  $sqlCount = "SELECT COUNT(*) as count FROM rent WHERE name LIKE '%$search%' AND valid=1";
  $resultCount = $conn->query($sqlCount);
  $row = $resultCount->fetch_assoc();
  $productTotalCount = $row['count'];
}
else {
  $sqlAll = "SELECT COUNT(*) as count FROM rent WHERE valid=1";
  $resultAll = $conn->query($sqlAll);
  $row = $resultAll->fetch_assoc();
  $productTotalCount = $row['count'];
}

$pageCount = ceil($productTotalCount / $perPage);

if (isset($_GET["order"])) {
  $order = $_GET["order"];

  if ($order == 1) {
    $orderCount = "ORDER BY id ASC";
  } elseif ($order == 2) {
    $orderCount = "ORDER BY id DESC";
  } elseif ($order == 3) {
    $orderCount = "ORDER BY price ASC";
  } elseif ($order == 4) {
    $orderCount = "ORDER BY price DESC";
  } elseif ($order == 5) {
    $orderCount = "ORDER BY created_at DESC";
  }
}

if (isset($_GET["search"])) {
  $search = $_GET["search"];
  $p = isset($_GET["p"]) ? $_GET["p"] : 1;
  $startIndex = ($p - 1) * $perPage;
  $sql = "SELECT * FROM rent WHERE name LIKE '%$search%' AND valid=1 LIMIT $startIndex, $perPage";
} elseif (isset($_GET["p"])) {
  $p = $_GET["p"];
  $startIndex = ($p - 1) * $perPage;
  $sql = "SELECT * FROM rent WHERE valid=1 $orderCount LIMIT $startIndex, $perPage";
} elseif(isset($_GET["class"])){
  $class=$_GET["class"];
  $p = isset($_GET["p"]) ? $_GET["p"] : 1;
  $startIndex = ($p - 1) * $perPage;
  $sql = "SELECT * FROM rent WHERE class_id= '$class' AND valid=1 LIMIT $startIndex, $perPage";
}else {
  $p = 1;
  $order = 1;
  $orderCount = "ORDER BY id ASC";
  $sql = "SELECT * FROM rent WHERE valid=1 $orderCount LIMIT $perPage";
}

$result = $conn->query($sql);

if(isset($_GET["search"])|| isset($_GET["class"])) {
  $productCount = $result->num_rows;
} else {
  $productCount = $productTotalCount;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <!-- 網頁favcon -->
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    棒球好玩家
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
  <!-- font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="../assets/css/ader.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <!-- 回首頁連結 -->
      <a class="navbar-brand m-0" href="">
        <!-- LOGO -->
        <img src=" ../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">棒球好玩家</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">會員管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white " href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">訂單管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white active" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">商品管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">租借管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">類別管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">優惠券管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">課程管理</span>
          </a>
        </li>
        <li class="nav-item">
          <!-- 超連結 -->
          <a class="nav-link text-white" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">文章管理</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <!-- 麵包屑 -->
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Template</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Template</h6>
        </nav>
      </div>
    </nav>
    <div class="container">
    <?php include './js.php' ?>
    <!-- Modal -->
    <div class="container">
      <h1>租借商品列表</h1>
      <form action="rent_products.php">
        <div class="input-group  mb-3">
          <?php if (isset($_GET["search"])): ?>
            <div class="col-auto ">
              <a name="" id="" class="btn btn-primary my-3" href="rent_products.php" role="button"><i
                  class="fa-solid fa-arrow-left fa-fw"></i></a>
            </div>
          <?php endif; ?>
          <input type="hidden" name="order" value="<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>">
          <input type="hidden" name="p" value="<?= isset($_GET["p"]) ? $_GET["p"] : 1 ?>">
          <input type="search" class="form-control my-3" placeholder="請輸入要搜尋的商品關鍵字" aria-describedby="button-addon2"
            name="search" value="<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>">
          <button class="btn btn-primary my-3" type="submit" id="button-addon2"><i
              class="fa-solid fa-magnifying-glass"></i></button>
        </div>
      </form>

      <div class="d-flex justify-content-between">
        <h5>共
          <?= $productTotalCount ?> 個商品
        </h5>
      </div>
      <?php if (!isset($_GET["search"]) && !isset($_GET["class"])): ?>
        <div class="d-flex justify-content-between">
          <div class="input-group mb-3">
          
            <button class="btn btn-outline-secondary me-auto dropdown-toggle" type="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <?php if ($order == 1) {
                echo "編碼由小到大";
              } elseif ($order == 2) {
                echo "編碼由大到小";
              } elseif ($order == 3) {
                echo "價格由低到高";
              } elseif ($order == 4) {
                echo "價格由高到低";
              } elseif ($order == 5) {
                echo "最新商品";
              } ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="rent_products.php?order=1&p=<?= $p ?>">編碼由小到大</a></li>
              <li> <a class="dropdown-item" href="rent_products.php?order=2&p=<?= $p ?>">編碼由大到小</a></li>
              <li> <a class="dropdown-item" href="rent_products.php?order=3&p=<?= $p ?>">價格由低到高</a></li>
              <li><a class="dropdown-item" href="rent_products.php?order=4&p=<?= $p ?>">價格由高到低</a></li>
              <li><a class="dropdown-item" href="rent_products.php?order=5&p=<?= $p ?>">最新商品</a></li>
            </ul>
          </div>
          <div class="button-sun">
            <a class="btn btn-warning text-nowrap" href="add-rent_product.php" role="button">
              <i class="fa-regular fa-square-plus m-2"></i>新增商品
            </a>
          </div>
        </div>
      <?php endif; ?>
      <?php if ($productCount > 0): ?>
        <div class="py-2">
          
          <ul class="nav nav-tabs">
          <h4 class=pt-1>分類:</h4>
            <li class="nav-item">
              <a class="nav-link



          <?php if (!isset($_GET["class"]))
            echo "active"; ?>
          " aria-current="page"  href="rent_products.php">全部</a>
            </li>
            <?php 
            // $rows = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($rowsproductInfo as $productinfo): ?>
              <li class="nav-item">
                <a  class="nav-link
                <?php
                if (isset($_GET["class"]) && $_GET["class"] == $productinfo["class"])
                  echo "active";
                ?>" aria-current="page" href="rent_products.php?class=<?= $productinfo["class"] ?>&order=<?php $other=isset($_GET["order"]) ? $_GET["order"] :1; echo $order?>&P=<?php $p=isset($_GET["p"]) ? $_GET["p"] :1;echo $p ?>">
                  <?= $productinfo["class"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <table class="table  table-bordered table-hover">
          <thead>
            <tr class="text-center">
              <th>編號</th>
              <th>商品名稱</th>
              <th>租借價格/天</th>
              <th>商品照片</th>
            
              <th>商品類型</th>
              <!-- <th>商品規格</th> -->
              <th>商品品牌</th>
              <!-- <th>商品現有尺寸</th> -->
              <!-- <th>商品現有顏色</th> -->
              <th>更新日期</th>
              <th>狀態</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($rows as $rent):
              ?>
              <tr class="text-center">
                <td>
                  <?= $rent["id"] ?>
                </td>
                <td>
                  <?= $rent["name"] ?>
                </td>
                <td>
                  <?= $rent["price"] ?>
                </td>
                <td style="width:100px; height:50px"><img class="img-fluid img-thumbnail" src="../<?= $rent["image_url"] ?>"
                    alt="<?= $rent["name"] ?>"></td>
                <td>
                  <?= $rent["class_id"] ?>
                </td>

                <td>
                  <?= $rent["brand_id"] ?>
                </td>
                <td>
                  <?= $rent["created_at"] ?>
                </td>
                <td>
                  <?= $rent["mode"] ?>
                </td>
                <td class="text-nowrap">
                  <a class="mx-2" role="button" href="rent_product-edit.php?id=<?= $rent["id"] ?>"><i
                      class="fa-solid fa-pen-to-square" style="color: #FFD43B;"></i></a>
                      <a id="myLink" href="rent_products?id=<?=$rent["id"]?>"></a>
                  <button id="delbutton"type="" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $rent["id"] ?>">
                    <i class="fa-solid fa-trash" style="color: #f23607;"></i>
                  </button>
                </td>
              </tr>
              <div class="modal fade" id="exampleModal<?= $rent["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">刪除商品</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      確認刪除該租借商品?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                      <a href="Deleterent_product.php?id=<?= $rent["id"] ?>" role="button" class="btn btn-primary">確認</a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </tbody>
        </table>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php if (isset($_GET["search"])): ?>
              <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li class="page-item <?php if ($i == $p)
                  echo "active"; ?>">
                  <a class="page-link"
                    href="rent_products.php?search=<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>&order=<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>&p=<?= $i ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>
              

             <?php elseif(isset($_GET["class"])):?>
             <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li class="page-item <?php if ($i == $p)
                  echo "active"; ?>">
                  <a class="page-link" 
                  href="rent_products.php?class=<?= isset($_GET["class"]) ? $_GET["class"] : '' ?>&order=<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>&p=<?= $i ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>
            <?php else: ?>
              <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li class="page-item <?php if ($i == $p)
                  echo "active"; ?>">
                  <a class="page-link" href="rent_products.php?order=<?= $order ?>&p=<?= $i ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>
            <?php endif ?>
          </ul>
        </nav>
      <?php else: ?>
        <div class="col-auto ">
              <a name="" id="" class="btn btn-primary my-3" href="rent_products.php" role="button"><i
                  class="fa-solid fa-arrow-left fa-fw"></i></a>
        </div>
        沒有該商品
      <?php endif; ?>
    </div>
    </div>
    <!-- End Navbar -->
  </main>
  <!-- 右下設定 -->
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">更改UI</h5>
          <p>請選擇喜愛的配色</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-icons">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <?php include './js.php' ?>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
      var win = navigator.platform.indexOf('Win') > -1;
      if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
          damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
      }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>