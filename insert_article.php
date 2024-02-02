<?php

if(!isset($_GET["id"])){
    $id=0;
}else{
    $id=$_GET["id"];
}
require_once("../baseball/db_connect.php");

$sql="SELECT * FROM article WHERE id=$id AND valid=1";
$result=$conn->query($sql);

$rowCount = $result->num_rows;

?>

<!doctype html>
<html lang="en">

<head>
    <title>新增文章頁</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../baseball/assets/css/css_lin.php") ?>


    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/[version.number]/[distribution]/ckeditor.js"></script> -->
    <script src="/baseball/ckeditor/ckeditor5-build-classic-41.0.0/ckeditor5-build-classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <!-- <script src="/baseball//ckeditor//ckeditor4-releases-master/ckeditor.js"></script> -->
    
    
    
</head>

<body>

    <div class="container">
        <div class="py-2">
            <a class="btn btn-primary" 
                href="article.php"
                role="button"
            ><i class="fa-solid fa-chevron-left"></i>回文章列表</a>
        </div>

        <h1>新增文章</h1>
        <form action="doaddarticle.php" method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <label for="class">選取文章類別</label>
                <select class="form-select" name="type" id="">
                    <option value="">請選擇類型</option>
                    <option value="打擊">打擊</option>
                    <option value="投球">投球</option>
                    <option value="守備">守備</option>
                    <option value="體能">體能</option>
                    <option value="知識">知識</option>
                </select>
            </div>

            <div class="mb-2">
                <label for="formFileMultiple" class="form-label">選取圖片</label><br>
                <!-- <input class="form-control" type="file" id="formFileMultiple" name="photo" multiple> -->
                <!-- <form action="/somewhere/to/upload" enctype="multipart/form-data"> -->
                            <img class="" id="preview_progressbarTW_img" src="../baseball/assets/img/article_img/uploadimg2.png" width="250" height="auto"/>
                            <input class="form-control" type="file" id="formFileMultiple" name="photo" multiple 
                            onchange="readURL(this)" targetID="preview_progressbarTW_img" accept="image/gif, image/jpeg, image/png"/>    

                <!-- </form> -->
                
            </div>

            <div class="mb-2">
                <label for="" class="form-label">文章標題</label>
                <input type="text" class="form-control" name="title" required max="" min="">
                <!-- 用required 可以用開發者工具刪掉required就能送出資料 所以才要用後端去做驗證-->

            </div>
         

            <div class="mb-2">
                <label for="" class="form-label">文章內容</label>
                <textarea id="editor" name="description" class="form-control" aria-label="With textarea">

                </textarea>

                

                <script>
                    ClassicEditor
                        .create(document.querySelector('#editor') )
                        .then(editor => {
                            console.log(editor);
                        })
                        .catch(error => {
                            console.error(error);
                        });
                </script>
            </div>






            <div class="mb-2">

            </div>


            <button type="submit" class="btn btn-primary">送出</button>
            
        </form>
    </div>

    <?php include("../baseball/assets/js/js_lin.php") ?>
   
</body>

</html>




















