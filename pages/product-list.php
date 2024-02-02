<?php
session_start();
require_once("../db_connect.php");

// 想要一頁呈現幾筆
$perPage = 8;

// 全部的商品
$sqlAll = "SELECT * FROM product WHERE valid=1";
$resultAll = $conn->query($sqlAll);
$rowsAll = $resultAll->fetch_all(MYSQLI_ASSOC);
$rowAllCount = $resultAll->num_rows;
// 全部商品總數量 / 一頁有幾筆
$pageCount = ceil($rowAllCount / $perPage);


// 排序 order 
if (isset($_GET["order"])) {
    $order = $_GET["order"];

    if ($order == 1) {
        $orderString = "ORDER BY id ASC";
    } elseif ($order == 2) {
        $orderString = "ORDER BY id DESC";
    } elseif ($order == 3) {
        $orderString = "ORDER BY price ASC";
    } elseif ($order == 4) {
        $orderString = "ORDER BY price DESC";
    }
} else {
    $orderString = "ORDER BY id ASC";
}


// 分頁判斷
if (isset($_GET["class"]) && isset($_GET["p"])) {
    // 有選了類別，而且有按分頁
    $class = $_GET["class"];
    $p = $_GET["p"];
    $startIndex = ($p - 1) * $perPage;

    // 為了能在點選類別時，可以呈現該類別的 總數量 / 一頁有幾筆
    $sqlClass = "SELECT * FROM product WHERE class = '$class' AND valid=1";
    $resultClass = $conn->query($sqlClass);
    $rowsClassCount = $resultClass->num_rows;
    $pageClassCount = ceil($rowsClassCount / $perPage);

    // 結果
    $whereClause = "WHERE class = '$class' AND valid=1";
    $pageClause = "LIMIT $startIndex, $perPage";
} elseif (isset($_GET["p"])) {
    // 沒有選類別，在「全部」的狀態下，按下分頁
    $p = $_GET["p"];
    $startIndex = ($p - 1) * $perPage;

    // 結果
    $whereClause = "WHERE valid=1";
    $pageClause = "LIMIT $startIndex, $perPage";
} elseif (isset($_GET["class"])) {
    // 已經選了類別，但還沒有選分頁
    $p = 1;
    $class = $_GET["class"];
    $startIndex = ($p - 1) * $perPage;

    // 為了能在點選類別時，可以呈現該類別的 總數量 / 一頁有幾筆
    $sqlClass = "SELECT * FROM product WHERE class = '$class' AND valid=1";
    $resultClass = $conn->query($sqlClass);
    $rowsClassCount = $resultClass->num_rows;
    $pageClassCount = ceil($rowsClassCount / $perPage);

    // 結果
    $whereClause = "WHERE class = '$class' AND valid=1";
    $pageClause = "LIMIT $startIndex, $perPage";
} else {
    // 沒有選類別，也沒有選分頁
    $p = 1;
    $startIndex = ($p - 1) * $perPage;

    $whereClause = "WHERE valid=1";
    $pageClause = "LIMIT $startIndex, $perPage";
}

$sql = "SELECT * FROM product $whereClause $orderString $pageClause";

$result = $conn->query($sql);

$rows = $result->fetch_all(MYSQLI_ASSOC);

$rowscount = $result->num_rows;


// 為了撈出類別欄位
$sqlInfoClass = "SELECT class FROM product_info";
$resultInfoClass = $conn->query($sqlInfoClass);
$rowsInfoClass = $resultInfoClass->fetch_all(MYSQLI_ASSOC);



?>

<!doctype html>
<html lang="en">

<head>
    <title>商品管理</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php
    include("../assets/css/css_yu.php");
    ?>
</head>

<body class="g-sidenav-show  bg-gray-200">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="" target="_blank">
                <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold text-white">棒球好玩家</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">

        <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white active bg-gradient-primary" href="product-list.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">商品管理</span>
                    </a>
                </li>
        </div>

    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">管理後台</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">商品管理</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">商品列表</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <label class="form-label">輸入搜尋...</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New message</span> from Laur
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    13 minutes ago
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New album</span> by Travis Scott
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    1 day
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>credit-card</title>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                            <g transform="translate(1716.000000, 291.000000)">
                                                                <g transform="translate(453.000000, 454.000000)">
                                                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    Payment successfully completed
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    2 days
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <!-- 商品管理清單頁面 -->
        <div class="container">
            <div class="row">
                <h2>商品列表</h2>

                <!-- 類別選擇欄位 -->
                <div class="row">
                    <div class="my-1">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link
                    <?php if (!isset($_GET["class"])) echo "active"; ?>
                    " aria-current="page" href="product-list.php">全部</a>
                            </li>

                            <?php foreach ($rowsInfoClass as $infoClass) : ?>
                                <li class="nav-item">
                                    <a class="nav-link nav-color-yu 
                        <?php if (isset($_GET["class"]) && $_GET["class"] == $infoClass["class"]) echo "active"; ?>" aria-current="page" href="product-list.php?class=<?= $infoClass["class"] ?>">
                                        <?= $infoClass["class"] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>




                <div class="row justify-content-between">

                    <div class="col-6 d-flex flex-column justify-content-between">

                        <div class="row d-flex flex-row">
                            <div class="col-auto">
                                <!-- 價格篩選的部分 -->
                                <div class="row my-2 justify-content-center align-items-center">
                                    <div class="col-sm-auto">
                                        <input type="number" class="max-min-input" name="min" min="0" value="">
                                    </div>
                                    <div class="col-sm-auto">
                                        ~
                                    </div>
                                    <div class="col-sm-auto">
                                        <input type="number" class="max-min-input" name="max" min="0" value="">
                                    </div>
                                    <div class="col-sm-auto d-flex align-items-center m-2 ">
                                        <button type="submit" class="max-min-btn btn-primary">
                                            查詢
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                搜尋欄位
                            </div>
                        </div>

                        <!-- 共有幾件商品 -->
                        <div class="page-span">
                            <!-- 全部的 -->
                            <?php if ($rowscount > 0 && !isset($_GET["class"])) : ?>
                                共 <?= $rowAllCount ?> 件商品 &nbsp; , &nbsp;第 <?= "<span>" . $p . "</span>" ?> 頁 / 共 <?= $pageCount ?> 頁

                                <!-- 點選各分類後的 -->
                            <?php elseif ($rowscount > 0 && isset($_GET["class"])) : ?>
                                共 <?= $rowsClassCount ?> 件商品 &nbsp; , &nbsp;第 <?= "<span>" . $p . "</span>" ?> 頁 / 共 <?= $pageClassCount ?> 頁

                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 排序的部分 -->
                    <div class="col-lg-6 col-auto d-flex justify-content-end align-items-end">
                        <div class="d-flex flex-nowrap my-1">
                            <!-- 有點選類別，然後點排序的狀態 -->
                            <?php if (isset($_GET["class"])) : ?>
                                <a class="btn-sort btn-primary <?php if ($order == 1) echo "active"; ?>
                        " href="product-list.php?class=<?= $class ?>&p=<?= $p ?>&order=1">
                                    編號 <i class="fa-solid fa-arrow-up-wide-short"></i>
                                </a>
                                <a class="btn-sort btn-primary<?php if ($order == 2) echo "active"; ?>
                        " href="product-list.php?class=<?= $class ?>&p=<?= $p ?>&order=2">
                                    編號 <i class="fa-solid fa-arrow-down-wide-short"></i>
                                </a>
                                <a class="btn-sort btn-primary<?php if ($order == 3) echo "active"; ?>
                        " href="product-list.php?class=<?= $class ?>&p=<?= $p ?>&order=3">
                                    價格 <i class="fa-solid fa-arrow-up-wide-short"></i>
                                </a>
                                <a class="btn-sort btn-primary<?php if ($order == 4) echo "active"; ?>
                        " href="product-list.php?class=<?= $class ?>&p=<?= $p ?>&order=4">
                                    價格 <i class="fa-solid fa-arrow-down-wide-short"></i>
                                </a>

                                <!-- 沒有點選類別(位於全部)，然後點排序的狀態 -->
                            <?php else : ?>
                                <a class="btn-sort btn-primary m-1 <?php if ($order == 1) echo "active"; ?>
                        " href="product-list.php?p=<?= $p ?>&order=1">
                                    編號 <i class="fa-solid fa-arrow-up-wide-short"></i>
                                </a>
                                <a class="btn-sort btn-primary m-1 <?php if ($order == 2) echo "active"; ?>
                        " href="product-list.php?p=<?= $p ?>&order=2">
                                    編號 <i class="fa-solid fa-arrow-down-wide-short"></i>
                                </a>
                                <a class="btn-sort btn-primary m-1 <?php if ($order == 3) echo "active"; ?>
                        " href="product-list.php?p=<?= $p ?>&order=3">
                                    價格 <i class="fa-solid fa-arrow-up-wide-short"></i>
                                </a>
                                <a class="btn-sort btn-primary m-1 <?php if ($order == 4) echo "active"; ?>
                        " href="product-list.php?p=<?= $p ?>&order=4">
                                    價格 <i class="fa-solid fa-arrow-down-wide-short"></i>
                                </a>
                            <?php endif; ?>
                        </div>


                        <!-- 新增商品的按鈕 -->
                        <div class="text-end ms-5 me-1 my-3">
                            <a class="btn-bs5 btn-bs5-circle btn-primary" href="product-add.php" role="button">
                                <i class="fa-solid fa-file-circle-plus fa-fw fa-2xl"></i>
                            </a>
                        </div>
                    </div>

                </div>





                <!-- 商品清單表格 -->
                <?php if ($rowscount > 0) : ?>
                    <table id="list-table" class="table table-bordered table-striped g-1 mx-auto mb-3 col align-items-center">
                        <thead>
                            <tr class="text-center">
                                <th class="table-auto-yu">編號</th>
                                <th>圖片</th>
                                <th>商品名稱</th>
                                <th>品牌</th>
                                <th>類別 / 子類別</th>
                                <th>顏色 / 尺寸</th>
                                <th>價格</th>
                                <th>狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach ($rows as $product) : ?>
                                <tr>
                                    <td class="table-auto-yu"><?= $product["id"] ?></td>
                                    <td>
                                        <div class="ratio ratio-1x1 table-img-min">
                                            <img class="object-fit-cover" src="../assets/img/product_img/<?= $product["image_url"] ?>" alt="<?= $product["name"] ?>">
                                        </div>
                                    </td>
                                    <td class="text-start"><?= $product["name"] ?></td>
                                    <td><?= $product["brand"] ?></td>
                                    <td class="table-auto-yu"><?= $product["class"] ?> / <?= $product["other"] ?></td>
                                    <td class="text-start">顏色: <?= $product["color"] ?><br>
                                        尺寸: <?= $product["size"] ?></td>
                                    <td class="table-auto-yu">$ <?= $product["price"] ?></td>
                                    <td>放判斷</td>
                                    <td>
                                        <div class="flex-nowrap d-flex justify-content-center">
                                            <a class="btn-sm-edit btn-warning m-1" href="product-edit.php?id=<?= $product["id"] ?>">
                                                <i class="fa-solid fa-wand-magic-sparkles fa-fw"></i>
                                            </a>

                                            <!-- 刪除按鈕 : 按下會跳出是否要刪除的 Modal -->
                                            <button class="btn-sm-delete m-1 btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $product["id"] ?>">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>

                                            <!-- Modal 內容 -->
                                            <div class="modal fade" id="exampleModal<?= $product["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">刪除提醒</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-danger">
                                                            確認要刪除商品嗎?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                            <a href="doSoftDelete.php?id=<?= $product["id"] ?>">
                                                                <button type="button" class="btn btn-primary">刪除</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- 分頁按鈕 -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <!-- 如果有點 order 按鈕，得到order值的情況 -->
                            <?php if (isset($_GET["order"])) : ?>
                                <!-- 在點 order 狀態下，點選「類別」的分頁 -->
                                <?php if (isset($_GET["class"])) : ?>
                                    <?php for ($i = 1; $i <= $pageClassCount; $i++) : ?>
                                        <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                                            <a class="page-link" href="product-list.php?class=<?= $_GET["class"] ?>&p=<?= $i ?>&order=<?= $order ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor ?>
                                    <!-- 在點 order 狀態下，點選「全部」的分頁 -->
                                <?php else : ?>
                                    <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                                        <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                                            <a class="page-link" href="product-list.php?p=<?= $i ?>&order=<?= $order ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor ?>

                                <?php endif; ?>

                                <!-- 如果沒有點 order 按鈕 (一般狀態) 的情況-->
                            <?php else : ?>
                                <!-- 在一般狀態下，點選「類別」的分頁 -->
                                <?php if (isset($_GET["class"])) : ?>
                                    <?php for ($i = 1; $i <= $pageClassCount; $i++) : ?>
                                        <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                                            <a class="page-link" href="product-list.php?class=<?= $_GET["class"] ?>&p=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor ?>
                                    <!-- 在一般狀態下，點選「全部」的分頁 -->
                                <?php else : ?>
                                    <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                                        <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                                            <a class="page-link" href="product-list.php?p=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor ?>
                                <?php endif; ?>
                            <?php endif; ?>


                        </ul>
                    </nav>

                    <!-- 如果都沒有商品的話會顯示 :  -->
                <?php else : ?>
                    <h5 class="text-secondary mt-1 mb-5">~ 尚無商品 ~</h5>
                <?php endif; ?>





                <!-- 頁尾 -->
                <footer class="footer py-4  ">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-6 mb-lg-0 mb-4">
                                <div class="copyright text-center text-sm text-muted text-lg-start">
                                    © <script>
                                        document.write(new Date().getFullYear())
                                    </script>,
                                    棒球好玩家
                                </div>
                            </div>

                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </main>

    <!-- 其他功能列 -->
    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="material-icons py-2">settings</i>
        </a>
        <div class="card shadow-lg">
            <div class="card-header pb-0 pt-3">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Material UI Configurator</h5>
                    <p>See our dashboard options.</p>
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
                <a class="btn btn-outline-dark w-100" href="">View documentation</a>
                <div class="w-100 text-center">
                    <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
                    <h6 class="mt-3">Thank you for sharing!</h6>
                    <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                        <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                        <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
                    </a>
                </div>
            </div>
        </div>
    </div>


    <?php
    include("../assets/js/js_yu.php");
    ?>
</body>

</html>