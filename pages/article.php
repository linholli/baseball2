<?php
require_once("./db_connect.php");
$perPage = 10;


// 為了撈出類別欄位
$sqlType = "SELECT * FROM type";
$resultType = $conn->query($sqlType);
$rowsType = $resultType->fetch_all(MYSQLI_ASSOC);





if (isset($_GET["cate"])) {
  $cate = $_GET["cate"];
  $sqlCount = "SELECT COUNT(*) AS count FROM article WHERE article.type_id = $cate AND valid=1";

  $resultCount = $conn->query($sqlCount);
  $row = $resultCount->fetch_assoc();
  $articleTotalCount = $row['count'];
} elseif (isset($_GET["search"])) {
  $search = $_GET["search"];
  $sqlCount = "SELECT COUNT(*) as count FROM article WHERE title LIKE '%$search%' AND valid=1 ";
  $resultCount = $conn->query($sqlCount);
  $row = $resultCount->fetch_assoc();
  $articleTotalCount = $row['count'];
} else {
  $sqlAll = "SELECT COUNT(*) as count FROM article WHERE valid=1 ";
  $resultAll = $conn->query($sqlAll);
  $row = $resultAll->fetch_assoc();
  $articleTotalCount = $row['count'];
}

$pageCount = ceil($articleTotalCount / $perPage);






// $sqlAll="SELECT * FROM article WHERE valid=1";
// $resultAll = $conn->query($sqlAll);
// $articleTotalCount = $resultAll->num_rows;

// $pageCount = ceil($articleTotalCount / $perPage);



// $sqlType = "SELECT * FROM type";
// $resultType = $conn->query($sqlType);
// $rowsType = $resultType->fetch_all(MYSQLI_ASSOC);




if (isset($_GET["order"])) {
  $order = $_GET["order"];

  if ($order == 1) {
    $orderString = "ORDER BY id ASC";
  } elseif ($order == 2) {
    $orderString = "ORDER BY id DESC";
  } elseif ($order == 3) {
    $orderString = "ORDER BY created_at ASC";
  } elseif ($order == 4) {
    $orderString = "ORDER BY created_at DESC";
  }
} else {
  $orderString = "ORDER BY id ASC";
}


//篩選加排序加分頁
if (isset($_GET["cate"]) && isset($_GET["p"]) && isset($_GET["order"])) {
  $order = $_GET["order"];
  $cate = $_GET["cate"];
  $p = $_GET["p"];
  $startIndex = ($p - 1) * $perPage;
  $sql = "SELECT * FROM article WHERE article.type_id= '$cate' AND valid=1  $orderString LIMIT $startIndex, $perPage  ";
}
//篩選加分頁
elseif (isset($_GET["cate"]) && isset($_GET["p"])) {

  $cate = $_GET["cate"];
  $p = $_GET["p"];
  $startIndex = ($p - 1) * $perPage;

  $sql = "SELECT * FROM article WHERE article.type_id= '$cate' AND valid=1 LIMIT $startIndex, $perPage";
} elseif (isset($_GET["search"])) {
  $search = $_GET["search"];

  $p = isset($_GET["p"]) ? $_GET["p"] : 1;
  $startIndex = ($p - 1) * $perPage;



  $sql = "SELECT * FROM article WHERE title LIKE '%$search%' AND valid=1
     LIMIT $startIndex, $perPage";




  // if (isset($_GET["cate"])) {
  //     $cate = $_GET["cate"];
  //     $sql .= " AND type_id = $cate ";
  // }

  // $sql .= " LIMIT $startIndex, $perPage";




} elseif (isset($_GET["p"])) {
  $p = $_GET["p"];
  $startIndex = ($p - 1) * $perPage;

  $sql = "SELECT * FROM article WHERE valid=1 $orderString
      LIMIT $startIndex, $perPage";



  // if (isset($_GET["cate"])) {
  //     $cate = $_GET["cate"];
  //     $sql .= " AND type_id = $cate ";
  // }
  // $sql .= " LIMIT $startIndex, $perPage";



} elseif (isset($_GET["cate"])) {
  $cate = $_GET["cate"];

  $p = isset($_GET["p"]) ? $_GET["p"] : 1;
  $startIndex = ($p - 1) * $perPage;
  // $sql = "SELECT product.*, category.name AS category_name
  //   FROM product
  //   JOIN category ON product.category_id = category.id
  //   WHERE product.category_id = $cate
  //   ORDER BY product.id";


  $sql = "SELECT article.*, type.name AS type_name  FROM article  
        JOIN type ON article.type_id = type.id
        WHERE article.type_id = $cate 
        AND article.valid = 1 
           ORDER BY article.id LIMIT $startIndex, $perPage";
  // $sql = "SELECT * FROM article WHERE article.type_id= '$cate' AND valid=1 LIMIT $startIndex, $perPage";

  // if (isset($_GET["search"])) {
  //     $search = $_GET["search"];
  //     $sql .= " AND title LIKE '%$search%'";
  // }

  // $sql .= " AND article.valid = 1 LIMIT $startIndex, $perPage";

  // $sql .= " ORDER BY article.id ";





} else {
  $p = 1;
  $order = 1;
  $orderString = "ORDER BY id ASC";
  $sql = "SELECT * FROM article WHERE valid=1 $orderString LIMIT $perPage";
}

// $sql = "SELECT article.*, type.name AS type_name  FROM article 
// JOIN type ON article.type_id = type.id
// $whereClause
// ORDER BY article.id";

$result = $conn->query($sql);



$cartCount = 0;
if (isset($_SESSION["cart"])) {
  $cartCount = count($_SESSION["cart"]);
}



if (isset($_GET["search"]) || isset($_GET["cate"])) {
  $articleCount = $result->num_rows;
} else {
  $articleCount = $articleTotalCount;
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
    文章列表頁
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
  <!-- <link href="../assets/css/ader.css" rel="stylesheet" /> -->
  <link href="./ader.css" rel="stylesheet" />
  <?php include("../assets/css/css_lin.php") ?>
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
          <a class="nav-link text-white" href="article.php">
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">後臺管理</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">文章管理</li>
          </ol>
          <h6 class="font-weight-bolder mb-0 playfair">文章列表Article</h6>
        </nav>
      </div>
    </nav>
    <div class="container">
      <h1 class="articletitle "><i class="fa-solid fa-baseball-bat-ball"></i>文章列表頁<i class="fa-solid fa-baseball"></i></h1>


      <div class="py-2">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link 
          <?php if (!isset($_GET["cate"])) echo "active"; ?>
          " aria-current="page" href="article.php">全部</a>
          </li>

          <?php foreach ($rowsType as $type) : ?>
            <li class="nav-item">
              <a class="nav-link 


           


            <?php
            if (isset($_GET["cate"]) && $_GET["cate"] == $type["id"]) echo "active";
            ?>" aria-current="page" href="article.php?cate=<?= $type["id"] ?>&order=<?php $other = isset($_GET["order"]) ? $_GET["order"] : 1;
               echo $order ?>&p=<?php $p = isset($_GET["p"]) ? $_GET["p"] : 1;
                    echo $p ?>
              
              ">

                <?= $type["name"] ?>

              </a>


            </li>
          <?php endforeach; ?>




        </ul>
      </div>


      <div class="py-2">
        <div class="row g-3">
          <?php if (isset($_GET["search"])) : ?>
            <div class="col-auto">
              <a name="" id="" class="btn btn-primary" href="article.php" role="button"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
            </div>
          <?php endif; ?>

          <div class="col">
            <form action="">
              <div class="input-group mb-3">

                <input type="search" class="form-control" placeholder="輸入關鍵字搜尋文章" aria-label="Recipient's username" aria-describedby="button-addon2" name="search" 
                <?php
                      if (isset($_GET["search"])) :
                        $searchValue = $_GET["search"];
                      ?> value="<?= $searchValue ?>" <?php endif; ?>>

                <!-- 可保留搜尋條件 放在使用者頁面  -->
                <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa-solid fa-magnifying-glass fa-fw"></i></button>
              </div>
            </form>
          </div>
        </div>

      </div>
      <div class="d-flex justify-content-between pb-2 align-items-center">
        <div>


          <?php if (isset($_GET["search"])) : ?>
            共 <?= $articleTotalCount ?> 篇文章 , 第 <?= $p ?> 頁 / 共 <?= $pageCount ?> 頁

            <!-- 點選各分類後的 -->
          <?php elseif (!isset($_GET["cate"])) : ?>
            共 <?= $articleCount ?> 篇文章 , 第 <?= $p ?> 頁 / 共 <?= $pageCount ?> 頁
          <?php elseif (isset($_GET["cate"])) : ?>
            共 <?= $articleTotalCount ?> 篇文章 , 第 <?= $p ?> 頁 / 共 <?= $pageCount ?> 頁

          <?php endif; ?>

        </div>
        <div>
          <a name="" id="" class="btn btn-primary" href="insert_article.php" role="button"><i class="fa-solid fa-square-plus"></i> 新增文章</a>

        </div>
      </div>

      <?php if (isset($_GET["cate"])) : ?>

        <div class="py-2 justify-content-end d-flex align-items-center">
          <div class="me-2">排序</div>
          <div class="btn-group">
            <a class="btn btn-primary
                  <?php if ($order == 1) echo "active" ?>
                  " href="article.php?cate=<?= $cate ?>&order=1&p=<?= $p ?>
                  "><i class="fa-solid fa-arrow-down-1-9 fa-fw"></i> 編號小到大</a>
                            <a class="btn btn-primary
                  <?php if ($order == 2) echo "active" ?>
                  " href="article.php?cate=<?= $cate ?>&order=2&p=<?= $p ?>
                  "><i class="fa-solid fa-arrow-down-9-1 fa-fw"></i> 編號大到小</a>
                            <a class="btn btn-primary
                  <?php if ($order == 3) echo "active" ?>
                  " href="article.php?cate=<?= $cate ?>&order=3&p=<?= $p ?>"><i class="fa-solid fa-calendar-days fa-fw"></i> 舊到新</a>
                            <a class="btn btn-primary
                  <?php if ($order == 4) echo "active" ?>
                  " href="article.php?cate=<?= $cate ?>&order=4&p=<?= $p ?>"><i class="fa-solid fa-calendar-days fa-fw"></i> 新到舊</a>
          </div>

        </div>







      <?php elseif (!isset($_GET["search"])) : ?>

        <div class="py-2 justify-content-end d-flex align-items-center">
          <div class="me-2">排序</div>
          <div class="btn-group">
            <a class="btn btn-primary
                <?php if ($order == 1) echo "active" ?>
                " href="article.php?order=1&p=<?= $p ?>
                "><i class="fa-solid fa-arrow-down-1-9 fa-fw"></i> 編號小到大</a>
            <a class="btn btn-primary
                <?php if ($order == 2) echo "active" ?>
                " href="article.php?order=2&p=<?= $p ?>
                "><i class="fa-solid fa-arrow-down-9-1 fa-fw"></i> 編號大到小</a>
            <a class="btn btn-primary
                <?php if ($order == 3) echo "active" ?>
                " href="article.php?order=3&p=<?= $p ?>"><i class="fa-solid fa-calendar-days fa-fw"></i> 舊到新</a>
            <a class="btn btn-primary
                <?php if ($order == 4) echo "active" ?>
                " href="article.php?order=4&p=<?= $p ?>"><i class="fa-solid fa-calendar-days fa-fw"></i> 新到舊</a>
          </div>

        </div>





      <?php endif; ?>
      <table class="table table-bordered table-striped ">
        <thead>
          <tr>
            <th class="box1 box">文章編號</th>
            <th class="box2 box">文章標題</th>
            <th class="box3 box">文章內容</th>
            <th class="box4 box">照片</th>
            <th class="box5 box">發布時間</th>
            <th class="box6 box">類型</th>
            <th class="box">顯示</th>
            <th class="box">編輯</th>
            <th class="box">刪除</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $rows = $result->fetch_all(MYSQLI_ASSOC);

          foreach ($rows as $article) :
          ?>
            <tr>
              <td class="articlebox " style="white-space: normal; text-align: center;"><?= $article["id"] ?></td>
              <td class="articlebox " style="white-space: normal; "><?= $article["title"] ?></td>
              <td class="articlebox " style="white-space: normal; text-align: center;"><?= $article["description"] ?></td>

              <td class="articlebox "><img class="imgcover" src="../assets/img/article_img/<?= $article["photo"] ?>" alt=""></td>

              <td class="articlebox "><?= $article["created_at"] ?></td>
              <td class="articlebox " style="white-space: normal; text-align: center;"><?= $article["type"] ?></td>
              <td class="articlebox " style="white-space: normal; text-align: center;"><a class="btn btn-primary" href="view_article.php?id=<?= $article["id"] ?>" role="button"><i class="fa-solid fa-eye fa-fw"></i></a>
              </td>
              <td class="articlebox " style="white-space: normal; text-align: center;"><a class="btn btn-warning" href="edit_article.php?id=<?= $article["id"] ?>" role="button"><i class="fa-solid fa-pen-to-square fa-fw"></i></td>
              <td class="articlebox" style="white-space: normal; text-align: center;"><button data-bs-toggle="modal" data-bs-target="#confrimModal<?= $article["id"] ?>" class="btn btn-danger " type="button">
                  <i class="fa-solid fa-trash fa-fw"></i>
                </button></td>
            </tr>
            <!-- 跳出刪除確認 -->
            <div class="modal fade" id="confrimModal<?= $article["id"] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">刪除文章</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    確認刪除?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a type="button" class="btn btn-danger" href="articlesoftdelete.php?id=<?= $article["id"] ?>">確認
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </tbody>
      </table>

      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php if (isset($_GET["search"])) : ?>
            <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
              <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                <a class="page-link" href="article.php?search=<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>&order=<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>&p=<?= $i ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>
          <?php elseif (isset($_GET["cate"])) : ?>
            <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
              <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                <a class="page-link" href="article.php?cate=<?= isset($_GET["cate"]) ? $_GET["cate"] : '' ?>&order=<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>&p=<?= $i ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>
          <?php else : ?>
            <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
              <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                <a class="page-link" href="article.php?order=<?= $order ?>&p=<?= $i ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>
          <?php endif ?>
        </ul>
      </nav>



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
  <?php include("./js.php") ?>

  <?php include("../assets/js/js_lin.php") ?>
</body>

</html>