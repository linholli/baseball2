<?php

require_once("./db_connect.php");

if(!isset($_POST["title"])){
    echo "請循正常管道進入";
    exit;
}

// $type=$_POST["type"];
//$type_id=$_POST["type_id"];
$title=$_POST["title"];
//$photo=$_POST["photo"];
$description=$_POST["description"];
$selectype=isset($_POST["type"]) ? $_POST["type"] :[];
$selectype_id=isset($_POST["type_id"]) ? $_POST["type_id"] :[];
$type=implode("," ,$selectype);
$type_id=implode("," ,$selectype_id);

if(empty($title)  ||  empty($description) || empty($type)){
    echo "請填入必要的欄位";
    exit;
}


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

$now=date('Y-m-d H:i:s');

$sql="INSERT INTO article (title, photo, description, created_at, type, type_id, valid)
VALUES ('$title', '$filename', '$description','$now','$type','$type_id', 1)"; 

// echo $sql;
// exit;

if($conn->query($sql)){
    echo "新增文章完成";
    // $last_id = $conn->insert_id;
    // echo ", id 為 $last_id";
}else{
    echo " 新增文章錯誤: " . $conn->error; // conn.error
}

$conn->close();

header("location: article.php");