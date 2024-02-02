<?php
require_once("./db_connect.php");

if(!isset($_POST["title"])){
    die("請循正常管道進入");
}

$title=$_POST["title"];
//$photo=$_POST["photo"];
$description=$_POST["description"];
$created_at=$_POST["created_at"];
//$type=$_POST["type"];
$selectype=isset($_POST["type"]) ? $_POST["type"] :[];
$selectype_id=isset($_POST["type_id"]) ? $_POST["type_id"] :[];
$id=$_POST["id"];


$type=implode("," ,$selectype);
$type_id=implode("," ,$selectype_id);

if ($_FILES["photo"]["error"] == 0) {
    $filename=time();
    $fileExt=pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
    $filename=$filename.".".$fileExt;
    // echo $filename;
    // exit;
  
    move_uploaded_file($_FILES["photo"]["tmp_name"], "../assets/img/article_img/" . $filename);
    $now = date('Y-m-d H:i:s');
    $sql="UPDATE article SET title='$title' ,photo='$filename', description='$description',type='$type',type_id='$type_id',created_at='$created_at' WHERE id=$id";
      // $filename = $_FILES["pic"]["name"];
      //$now = date('Y-m-d H:i:s');
      
  
    //   if ($conn->query($sql)) {
    //     echo "新增資料完成";
    //   } else {
    //     echo "新增資料錯誤: " . $conn->error;
    //   }
  
  
    //   echo "upload success!";
    // } else {
    //   echo "upload failed!";
    // }
  }else{
    $now = date('Y-m-d H:i:s');
    $sql="UPDATE article SET title='$title' , description='$description' ,type='$type',type_id='$type_id',created_at='$created_at' WHERE id=$id";
  }




// $sql="UPDATE article SET title='$title' ,photo='$filename', description='$description',type='$type',created_at='$created_at' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
   } else {
    echo "更新資料錯誤: " . $conn->error;
   }

$conn->close();

 //header("location: edit_article.php?id=$id");
 header("location: article.php");


//  編輯文章
