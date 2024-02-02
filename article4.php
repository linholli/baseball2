<?php
require_once("../baseball/db_connect.php");
$perPage = 25;


if (isset($_GET["search"])  ) {
    $search = $_GET["search"];
    $sqlCount = "SELECT COUNT(*) as count FROM article WHERE title LIKE '%$search%' AND valid=1 ";
    $resultCount = $conn->query($sqlCount);
    $row = $resultCount->fetch_assoc();
    $articleTotalCount = $row['count'];


    if (isset($_GET["cate"])) {
        $cate = $_GET["cate"];
        $sqlCount .= " AND type_id = $cate";
    }

   
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



$sqlType = "SELECT * FROM type";
$resultType = $conn->query($sqlType);
$rowsType = $resultType->fetch_all(MYSQLI_ASSOC);




if (isset($_GET["order"]) ) {
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
}


if (isset($_GET["search"])  ) {
    $search = $_GET["search"];

    $p = isset($_GET["p"]) ? $_GET["p"] : 1;
    $startIndex = ($p - 1) * $perPage;



    $sql = "SELECT * FROM article WHERE title LIKE '%$search%' AND valid=1
     LIMIT $startIndex, $perPage";



    
    if (isset($_GET["cate"])) {
        $cate = $_GET["cate"];
        $sql .= " AND type_id = $cate";
    }

 




} elseif (isset($_GET["p"])) {
    $p = $_GET["p"];
    $startIndex = ($p - 1) * $perPage;

    $sql = "SELECT * FROM article WHERE valid=1 $orderString
      LIMIT $startIndex, $perPage";


    
    if (isset($_GET["cate"])) {
        $cate = $_GET["cate"];
        $sql .= " AND type_id = $cate";
    }




} elseif (isset($_GET["cate"]) ) {
        $cate = $_GET["cate"];
        // $sql = "SELECT product.*, category.name AS category_name
        //   FROM product
        //   JOIN category ON product.category_id = category.id
        //   WHERE product.category_id = $cate
        //   ORDER BY product.id";
        $sql = "SELECT article.*, type.name AS type_name  FROM article  
        JOIN type ON article.type_id = type.id 
        WHERE article.type_id = $cate 
        ORDER BY article.id"; 
        
        if (isset($_GET["search"])) {
            $search = $_GET["search"];
            $sql .= " AND title LIKE '%$search%'";
        }


}else {
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
  $cartCount=count($_SESSION["cart"]);
}



if (isset($_GET["search"]) || isset($_GET["cate"]) ) {
    $articleCount = $result->num_rows;
} else {
    $articleCount = $articleTotalCount;
}





?>


<!doctype html>
<html lang="en">

<head>
    <title>文章列表頁</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    

    <?php include("../baseball/assets/css/css_lin.php") ?>
</head>

<body>
    <div class="container">
        <h1 class="articletitle "><i class="fa-solid fa-baseball-bat-ball"></i>文章列表頁<i class="fa-solid fa-baseball"></i></h1>


        <div class="py-2">
        <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link 
          <?php if (!isset($_GET["cate"])) echo "active"; ?>
          " aria-current="page" href="article4.php">全部</a>
        </li>
        <?php foreach ($rowsType as $type) : ?>
          <li class="nav-item">
            <a class="nav-link <?php
                                if (isset($_GET["cate"]) && $_GET["cate"] == $type["id"]) echo "active";
                                ?>" aria-current="page" href="article4.php?cate=<?= $type["id"] ?>"><?= $type["name"] ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>


        <div class="py-2">
            <div class="row g-3">
            <?php if (isset($_GET["search"])) : ?>
                    <div class="col-auto">
                        <a name="" id="" class="btn btn-primary" href="article4.php" role="button"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                    </div>
                <?php endif; ?>

                <div class="col">
                    <form action="">
                        <div class="input-group mb-3">
                        
                            <input type="search" class="form-control" placeholder="輸入關鍵字搜尋文章" aria-label="Recipient's username" aria-describedby="button-addon2" name="search" 
                            <?php
                        if (isset($_GET["search"])) :
                          $searchValue = $_GET["search"];
                                ?> 
                                value="<?= $searchValue ?>" <?php endif; ?>>

                            <!-- 可保留搜尋條件 放在使用者頁面  -->
                            <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa-solid fa-magnifying-glass fa-fw"></i></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="d-flex justify-content-between pb-2 align-items-center">
            <div>
                共 <?= $articleCount ?> 篇
                
            </div>
            <div>
                <a name="" id="" class="btn btn-warning" href="insert_article.php" role="button"><i class="fa-solid fa-square-plus"></i> 新增文章</a>

            </div>
        </div>
        <?php if (!isset($_GET["search"]) && !isset($_GET["cate"]) ) : ?>
            <div class="py-2 justify-content-end d-flex align-items-center">
                <div class="me-2">排序</div>
                <div class="btn-group">
                    <a class="btn btn-primary
                <?php if ($order == 1) echo "active" ?>
                " href="article4.php?order=1&p=<?= $p ?>"><i class="fa-solid fa-arrow-down-1-9 fa-fw"></i> 編號小到大</a>
                    <a class="btn btn-primary
                <?php if ($order == 2) echo "active" ?>
                " href="article4.php?order=2&p=<?= $p ?>"><i class="fa-solid fa-arrow-down-9-1 fa-fw"></i> 編號大到小</a>
                    <a class="btn btn-primary
                <?php if ($order == 3) echo "active" ?>
                " href="article4.php?order=3&p=<?= $p ?>"><i class="fa-solid fa-calendar-days fa-fw"></i> 舊到新</a>
                    <a class="btn btn-primary
                <?php if ($order == 4) echo "active" ?>
                " href="article4.php?order=4&p=<?= $p ?>"><i class="fa-solid fa-calendar-days fa-fw"></i> 新到舊</a>
                </div>

            </div>
        <?php endif; ?>
        <table class="table table-bordered">
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
                </tr>
            </thead>
            <tbody>
                <?php
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($rows as $article) :
                ?>
                    <tr>
                        <td class="articlebox"><?= $article["id"] ?></td>
                        <td class="articlebox"><?= $article["title"] ?></td>
                        <td class="articlebox"><?= $article["description"] ?></td>

                        <td class="articlebox"><img class="imgcover" src="../baseball/assets/img/article_img/<?= $article["photo"] ?>" alt=""></td>

                        <td class="articlebox"><?= $article["created_at"] ?></td>
                        <td class="articlebox"><?= $article["type"] ?></td>
                        <td class="articlebox"><a class="btn btn-primary" href="view_article.php?id=<?= $article["id"] ?>" role="button"><i class="fa-solid fa-eye"></i></a>
                        </td>
                        <td class="articlebox"><a class="btn btn-primary" href="edit_article.php?id=<?= $article["id"] ?>" role="button"><i class="fa-solid fa-pen-to-square"></i></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if (!isset($_GET["search"]) ): ?>
                <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                    <a class="page-link" href="article4.php?order=<?= $order ?>&p=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <?php else : ?>
                <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                <li class="page-item <?php if ($i == $p) echo "active"; ?>">
                    <a class="page-link" href="article4.php?order=<?= isset($_GET["order"]) ? $_GET["order"] : 1 ?>&p=<?= $i ?>&search=<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <?php endif ?>
            </ul>
        </nav>


       
    </div>

    <?php include("../baseball/assets/js/js_lin.php") ?>
</body>

</html>