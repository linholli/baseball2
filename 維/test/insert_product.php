<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>後台商品插入</title>
    <script>
        function validateForm() {
            var classSelect = document.getElementById("class");
            var otherSelect = document.getElementById("other");
            var brandSelect = document.getElementById("brand");

            // 檢查是否為默認值
            if (classSelect.value === "請選擇分類" || otherSelect.value === "請選擇規格" || brandSelect.value === "請選擇品牌") {
                alert("請選擇分類、規格和品牌。");
                return false; // 阻止表單提交
            }

            return true; // 允許表單提交
        }

        function updateOtherOptions() {
            var classSelect = document.getElementById("class");
            var otherSelect = document.getElementById("other");
            var selectedClass = classSelect.value;

            // 清空 other 選項
            otherSelect.innerHTML = '';

            // 根據選擇的 class 動態添加 other 選項
            if (selectedClass === "球棒") {
                addOption(otherSelect, "鋁棒", "鋁棒");
                addOption(otherSelect, "木棒", "木棒");
                      }
             else if (selectedClass === "帽子") {
                addOption(otherSelect, "球帽", "球帽");
                addOption(otherSelect, "打擊頭盔", "打擊頭盔");
            } 
            else if (selectedClass === "球衣") {
                addOption(otherSelect, "長袖", "長袖");
                addOption(otherSelect, "短袖", "短袖");
            }
            else if (selectedClass === "手套") {
                addOption(otherSelect, "左手", "左手");
                addOption(otherSelect, "右手", "右手");
                addOption(otherSelect, "打擊手套", "打擊手套");
            } 
            else if (selectedClass === "球褲") {
                addOption(otherSelect, "長褲", "長褲");
                addOption(otherSelect, "短褲", "短褲");
                addOption(otherSelect, "七分褲", "七分褲");
            }
            else if (selectedClass === "襪子") {
                addOption(otherSelect, "長襪", "長襪");
                addOption(otherSelect, "短襪", "短襪");
            }
            else if (selectedClass === "球鞋") {
                addOption(otherSelect, "跑鞋", "跑鞋");
                addOption(otherSelect, "釘鞋", "釘鞋");
            }
            else if (selectedClass === "裝備") {
                addOption(otherSelect, "裝備袋", "裝備袋");
                addOption(otherSelect, "球袋", "球袋");
                addOption(otherSelect, "鞋袋", "鞋袋");
            }
            else if (selectedClass === "護具") {
                addOption(otherSelect, "打擊護具", "打擊護具");
                addOption(otherSelect, "捕手護具", "捕手護具");
            }
            else if (selectedClass === "球") {
                addOption(otherSelect, "硬式", "硬式");
                addOption(otherSelect, "軟式", "軟式");
            }
             // ... （原來的條件分支）

            // 添加其他分類的選項
            // ...

            // 這裡可以根據其他分類添加 other 選項
        }

        function addOption(selectElement, value, text) {
            var option = document.createElement("option");
            option.value = value;
            option.text = text;
            selectElement.add(option);
        }
    </script>
</head>
<body>
    <h1>後台商品插入</h1>
    <form action="insert_product.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
   <label for="productName">商品名稱:</label>
        <input type="text" id="productName" name="productName" required><br>

        <label for="price">商品價格:</label>
        <input type="text" id="price" name="price" required><br>

        <label>選擇顏色:</label><br>
        <input type="checkbox" name="colors[]" value="黃"> 黃
        <input type="checkbox" name="colors[]" value="綠"> 綠
        <input type="checkbox" name="colors[]" value="藍"> 藍
        <input type="checkbox" name="colors[]" value="黑"> 黑
        <input type="checkbox" name="colors[]" value="白"> 白
        <input type="checkbox" name="colors[]" value="紅"> 紅
        <input type="checkbox" name="colors[]" value="橘"> 橘<br>

        <label>選擇尺寸:</label><br>
        <input type="checkbox" name="sizes[]" value="XS"> XS
        <input type="checkbox" name="sizes[]" value="S"> S
        <input type="checkbox" name="sizes[]" value="M"> M
        <input type="checkbox" name="sizes[]" value="L"> L
        <input type="checkbox" name="sizes[]" value="XL"> XL<br>

        <label for="description">商品描述:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="image">上傳圖片:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br>

        <label for="class">選擇分類:</label>
        <select id="class" name="class" onchange="updateOtherOptions()" required>
        <option value ="請選擇分類" selected>請選擇分類</option>    
        <option value="球棒">球棒</option>
            <option value="球">球</option>
            <option value="帽子">帽子</option>
            <option value="球衣">球衣</option>
            <option value="手套">手套</option>
            <option value="球褲">球褲</option>
            <option value="襪子">襪子</option>
            <option value="球鞋">球鞋</option>
            <option value="裝備">裝備</option>
            <option value="護具">護具</option>
            <!-- 添加其他分類項目 -->
        </select><br>

        <label for="other">選擇規格:</label>
        
        <select id="other" name="other" required>
            <!-- 這裡的選項會在 JavaScript 中動態添加 -->
        </select><br>

        <label for="brand">選擇品牌:</label>
        <select id="brand" name="brand" required>
        <option value="請選擇品牌" selected>請選擇品牌</option>
            <option value="MIZUNO">MIZUNO</option>
            <option value="久保田Slugger">久保田Slugger</option>
            <option value="ZETT">ZETT</option>
            <option value="SSK">SSK</option>
            <option value="LouisvilleSlugger">LouisvilleSlugger</option>
            <option value="EASTON">EASTON</option>
            <option value="BRETT">BRETT</option>
            <option value="Wilson">Wilson</option>
            <option value="UNDERARMOUR">UNDERARMOUR</option>
            <option value="NIKE">NIKE</option>
            <option value="adidas">adidas</option>
            <option value="創信MLB">創信MLB</option>
            <!-- 添加其他品牌項目 -->
        </select><br>

        <!-- 其他表單元素 -->

        <input type="submit" value="插入商品">
    </form>
</body>
</html>

<?php
// 連接到資料庫的代碼（使用 mysqli 或 PDO）
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取商品信息
    $productName = $_POST["productName"];
    $price = $_POST["price"];
    $colorsSelected = isset($_POST["colors"]) ? $_POST["colors"] : [];
    $sizesSelected = isset($_POST["sizes"]) ? $_POST["sizes"] : [];
    $description = $_POST["description"];
    $classText = isset($_POST["class"]) ? $_POST["class"] : '';
    $otherText = isset($_POST["other"]) ? $_POST["other"] : '';
    $brandText = isset($_POST["brand"]) ? $_POST["brand"] : '';

    // 將顏色和尺寸組合為逗號分隔的字符串
    $colors = implode(',', $colorsSelected);
    $sizes = implode(',', $sizesSelected);

    // 處理上傳的圖片
    $imagePath = "uploads/" . basename($_FILES["image"]["name"]);
    $uploadedFilePath = $_FILES["image"]["tmp_name"];

    // 確保目錄存在，如果不存在則創建
    $uploadDirectory = "uploads/";
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // 複製上傳的文件到指定目錄
    if (move_uploaded_file($uploadedFilePath, $uploadDirectory . basename($_FILES["image"]["name"]))) {
        echo "文件已成功上傳。";
    } else {
        echo "上傳文件失敗。";
    }

    // 插入商品信息到資料庫
    $query = "INSERT INTO `product` (`name`, `price`, `colors`, `sizes`, `class_id`, `other_id`, `brand_id`, `description`, `image_url`, `created_at`, `valid`) 
              VALUES ('$productName', $price, '$colors', '$sizes', '$classText', '$otherText', '$brandText', '$description', '$imagePath', NOW(), 1)";

    // 執行查詢
    $result = mysqli_query($connection, $query);

    // 檢查是否有錯誤
    if (!$result) {
        die("插入失敗：" . mysqli_error($connection));
    }

    // 關閉資料庫連接
    mysqli_close($connection);

    echo "商品插入成功！";

    // 等待5秒后跳轉到 view_products.php
    echo '<script>';
    echo 'setTimeout(function() {';
    echo '    window.location.href = "view_products.php";';
    echo '}, 5000);';
    echo '</script>';
} else {
    echo "無效的請求。";
}
?>
