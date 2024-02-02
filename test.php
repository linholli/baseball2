<?php
include_once './db_connect.php';
$perPage = 5;

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sqlCount = "SELECT COUNT(*) as count FROM rent WHERE name LIKE '%$search%' AND valid=1";
    $resultCount = $conn->query($sqlCount);
    $row = $resultCount->fetch_assoc();
    $productTotalCount = $row['count'];
} else {
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
} else {
    $p = 1;
    $order = 1;
    $orderCount = "ORDER BY id ASC";
    $sql = "SELECT * FROM rent WHERE valid=1 $orderCount LIMIT $perPage";
}

$result = $conn->query($sql);

if (isset($_GET["search"])) {
    $productCount = $result->num_rows;
} else {
    $productCount = $productTotalCount;
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include './rent-css.php' ?>
</head>

<body>
    <?php include './js.php' ?>
    <!-- Modal -->
    <div class="container">
        <h1>租借商品列表</h1>
        <form action="rent_products.php">
            <div class="input-group  mb-3">
                <?php if (isset($_GET["search"])): ?>
                <div class="col-auto">
                    <a name="" id="" class="btn btn-primary" href="rent_products.php" role="button"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                </div>
                <?php endif; ?>
                <input type="hidden" name="order" value="<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>">
                <input type="hidden" name="p" value="<?= isset($_GET["p"]) ? $_GET["p"] : 1 ?>">
                <input type="search" class="form-control" placeholder="請輸入要搜尋的商品關鍵字" aria-describedby="button-addon2" name="search" value="<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>">
                <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>

        <div class="d-flex justify-content-between">
            <h5>共 <?= $productTotalCount ?> 個商品</h5>
        </div>
        <?php if (!isset($_GET["search"])): ?>
        <div class="d-flex justify-content-between">
            <div class="input-group mb-3">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                    <a class="dropdown-item" href="rent_products.php?order=1&p=<?= $p ?>">編碼由小到大</a>
                    <a class="dropdown-item" href="rent_products.php?order=2&p=<?= $p ?>">編碼由大到小</a>
                    <a class="dropdown-item" href="rent_products.php?order=3&p=<?= $p ?>">價格由低到高</a>
                    <a class="dropdown-item" href="rent_products.php?order=4&p=<?= $p ?>">價格由高到低</a>
                    <a class="dropdown-item" href="rent_products.php?order=5&p=<?= $p ?>">最新商品</a>
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
        <table class="table  table-bordered table-hover">
            <thead>
                <tr class="text-center">
                    <th>編號</th>
                    <th>商品名稱</th>
                    <th>商品價格</th>
                    <th>商品照片</th>
                    <th>商品敘述</th>
                    <th>商品類型</th>
                    <th>商品規格</th>
                    <th>商品品牌</th>
                    <th>商品現有尺寸</th>
                    <th>商品現有顏色</th>
                    <th>上架日期</th>
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
                    <td><?= $rent["id"] ?></td>
                    <td><?= $rent["name"] ?></td>
                    <td><?= $rent["price"] ?></td>
                    <td style="width:100px; height:50px"><img class="img-fluid img-thumbnail" src="./<?= $rent["image_url"] ?>" alt="<?= $rent["name"] ?>"></td>
                    <td><?= $rent["description"] ?></td>
                    <td><?= $rent["class_id"] ?></td>
                    <td><?= $rent["other_id"] ?></td>
                    <td><?= $rent["brand_id"] ?></td>
                    <td><?= $rent["sizes"] ?></td>
                    <td><?= $rent["colors"] ?></td>
                    <td><?= $rent["created_at"] ?></td>
                    <td><?= $rent["mode"] ?></td>
                    <td class="text-nowrap">
                        <a class="mx-2" role="button" href=""><i class="fa-solid fa-pen-to-square" style="color: #FFD43B;"></i></a>
                        <button type="" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-rentid="<?= $rent["id"] ?>">
                            <i class="fa-solid fa-trash" style="color: #f23607;"></i>
                        </button>
                    </td>
                </tr>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <a href="Deleterent_product.php?id=<?=$rent["id"]?>" role="button" class="btn btn-primary">確認</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if (!isset($_GET["search"])): ?>
                <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                    <a class="page-link" href="rent_products.php?order=<?= $order ?>&p=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <?php else : ?>
                <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                    <a class="page-link" href="rent_products.php?order=<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>&p=<?= $i ?>&search=<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <?php endif ?>
            </ul>
        </nav>
        <?php else : ?>
        沒有該商品
        <?php endif; ?>
    </div>
</body>

</html>