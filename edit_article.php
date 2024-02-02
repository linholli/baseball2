<?php


if (!isset($_GET["id"])) {
    $id = 0;
} else {
    $id = $_GET["id"];
}
require_once("../baseball/db_connect.php");

$sql = "SELECT * FROM article WHERE id=$id AND valid=1";
$result = $conn->query($sql);

$rowCount = $result->num_rows;

if ($rowCount != 0) {
    $row = $result->fetch_assoc();
}


?>


<!doctype html>
<html lang="en">

<head>
    <title>編輯文章頁</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../baseball/assets/css/css_lin.php") ?>
    <script src="/baseball/ckeditor/ckeditor5-build-classic-41.0.0/ckeditor5-build-classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
</head>

<body>



    <div class="container">
        <h1>文章修改頁</h1>
        <?php if ($rowCount == 0) : ?>
            文章不存在
        <?php else :

        ?>
            <div class="py-2">
                <a class="btn btn-primary" href="article.php?id=<?= $row["id"] ?>" role="button"><i class="fa-solid fa-chevron-left"></i> 回文章列表</a>
            </div>

            <form action="updateEdit_article.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row["id"] ?>">
                <table class="table table-bordered">

                    <tr>
                        <th>文章標題</th>
                        <td><input type="text" class="form-control" value="<?= $row["title"] ?>" name="title"></td>
                    </tr>
                    <tr>
                        <th>文章圖片</th>
                        <td>
                            <div class="mb-1 mt-2">目前圖片</div>
                            <img class="" src="../baseball/assets/img/article_img/<?= $row["photo"] ?>" alt=""  width="250" height="auto">
                            <!-- <label for="formFileMultiple" class="form-label"></label> -->

                            <!-- <input class="form-control" type="file" id="formFileMultiple" name="photo" multiple > -->
                            

                            <div class="mb-1 mt-2">修改後的圖片</div>
                            <form action="/somewhere/to/upload" enctype="multipart/form-data">
                            <img class="" id="preview_progressbarTW_img" src="../baseball/assets/img/article_img/uploadimg2.png" width="250" height="auto"/>
                            <input class="form-control" type="file" id="formFileMultiple" name="photo" multiple 
                            onchange="readURL(this)" targetID="preview_progressbarTW_img" accept="image/gif, image/jpeg, image/png"/>    

                            </form>

                            
                        </td>
                    </tr>
                    <tr>
                        <th>文章內容</th>
                        <td><textarea id="editor" name="description" class="form-control" aria-label="With textarea">
                        <?= $row["description"] ?>
                        </textarea></td>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {
                                    console.log(editor);
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        </script>
                    </tr>
                    <tr>
                        <th>建立時間</th>
                        <td><input type="datetime" class="form-control" value="<?= $row["created_at"] ?>" name="created_at"></td>
                    </tr>

                </table>
                <div class="">
                    <button type="submit" class="btn btn-primary">
                        儲存
                    </button>
                    <a href="article.php" class="btn btn-primary" role="button">取消</a>
                    <!-- <button type="button" class="btn btn-primary">
                        取消
                    </button> -->



                    <button data-bs-toggle="modal" data-bs-target="#confrimModal" class="btn btn-danger float-end" type="button">
                        刪除
                    </button>





                </div>
            </form>



        <?php endif; ?>
        <!-- 跳出刪除確認 -->
        <div class="modal fade" id="confrimModal" tabindex="-1" aria-hidden="true">
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
                        <a type="button" class="btn btn-danger" href="articlesoftdelete.php?id=<?= $row["id"] ?>">確認
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../baseball/assets/js/js_lin.php") ?>
    
</body>

</html>