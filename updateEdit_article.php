<?php
require_once("./db_connect.php");

if(!isset($_POST["title"])){
    die("請循正常管道進入");
}

$title=$_POST["title"];
//$photo=$_POST["photo"];
$description=$_POST["description"];
$created_at=$_POST["created_at"];
$id=$_POST["id"];

if ($_FILES["photo"]["error"] == 0) {
    $filename=time();
    $fileExt=pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
    $filename=$filename.".".$fileExt;
    // echo $filename;
    // exit;
  
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], "../assets/img/article_img/" . $filename)) {
      // $filename = $_FILES["pic"]["name"];
      //$now = date('Y-m-d H:i:s');
      
  
    //   if ($conn->query($sql)) {
    //     echo "新增資料完成";
    //   } else {
    //     echo "新增資料錯誤: " . $conn->error;
    //   }
  
  
      echo "upload success!";
    } else {
      echo "upload failed!";
    }
  }




$sql="UPDATE article SET title='$title' ,photo='$filename', description='$description',created_at='$created_at' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
   } else {
    echo "更新資料錯誤: " . $conn->error;
   }

$conn->close();

 //header("location: edit_article.php?id=$id");
 header("location: article.php");


//  編輯文章
