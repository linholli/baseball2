<?php

if(!isset($_GET["id"])){
    $id=0;
}else{
    $id=$_GET["id"];
}


 require_once("../baseball/db_connect.php");


// $sqlAll = "SELECT * FROM article WHERE valid=1";
// $resultAll = $conn->query($sqlAll);
// $articleTotalCount = $resultAll->num_rows;



$sql = "SELECT * FROM article WHERE id=$id and valid=1";
$result = $conn->query($sql);

$rowCount=$result->num_rows;

if($rowCount!=0){
    $rows=$result->fetch_assoc();
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>文章顯示頁</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />


        <?php include("../baseball/assets/css/css_lin.php") ?>
        
    </head>

    <body>
        <!-- 跳出刪除確認 -->
    <div class="modal fade" id="confrimModal" tabindex="-1"  aria-hidden="true">
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
                    <a type="button" class="btn btn-danger" href="articlesoftdelete.php?id=<?=$rows["id"]?>">確認
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="py-2">
            <a class="btn btn-primary" 
                href="article.php"
                role="button"
            ><i class="fa-solid fa-chevron-left"></i>回文章列表</a>
        </div>


       
            
        <?php if ($rowCount == 0) : ?>
            文章不存在
        <?php else :
            
            ?>
            <div><h1><?=$rows["title"]?></h1></div>
            <div><img class="" src="../baseball/assets/img/article_img/<?= $rows["photo"] ?>" alt=""></div>
            <div><?=$rows["description"]?></div>

            

       


            
        <div class="py-2">
            <a class="btn btn-primary" 
                href="edit_article.php?id=<?=$rows["id"]?>"
                role="button"
            >編輯</a>

            <button 
                    
                 data-bs-toggle="modal" data-bs-target="#confrimModal"
                 class="btn btn-danger float-end"
                >
                刪除
            </button>
        </div>
        <?php endif; ?>
    </div>


        <?php include("../baseball/assets/js/js_lin.php") ?>
        
    </body>
</html>
