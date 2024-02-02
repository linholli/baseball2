<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>已上傳商品列表</title>
</head>
<body>
    <h1>已上傳商品列表</h1>

    <form action="view_products.php" method="post">
        <label>選擇顏色篩選:</label><br>
        <input type="checkbox" name="filterColor[]" value="全部"> 全部
        <input type="checkbox" name="filterColor[]" value="黃"> 黃
        <input type="checkbox" name="filterColor[]" value="綠"> 綠
        <input type="checkbox" name="filterColor[]" value="藍"> 藍
        <input type="checkbox" name="filterColor[]" value="黑"> 黑
        <input type="checkbox" name="filterColor[]" value="白"> 白
        <input type="checkbox" name="filterColor[]" value="紅"> 紅
        <input type="checkbox" name="filterColor[]" value="橘"> 橘
        <br>
        <input type="submit" value="篩選">
    </form>

    <?php
    // 連接到資料庫的代碼（使用 mysqli 或 PDO）
    include 'db_connect.php';

    // 預設顏色濾鏡為空（顯示所有商品）
    $colorFilter = [];

    // 檢查是否提交了顏色篩選表單
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filterColor"])) {
        $colorFilter = $_POST["filterColor"];

        // 如果选择了“全部”，则将颜色过滤器置为空数组（显示所有商品）
        if (in_array("全部", $colorFilter)) {
            $colorFilter = [];
        }
    }

    // 查询已上传的商品信息
    $query = "SELECT * FROM `product` WHERE `valid` = 1";

    // 如果有選擇的顏色，添加到查詢條件中
    if (!empty($colorFilter)) {
        $colorConditions = [];

        foreach ($colorFilter as $selectedColor) {
            // 使用 IN 運算符處理單一顏色的情況
            $colorConditions[] = "FIND_IN_SET('$selectedColor', `colors`) OR `colors` = '$selectedColor'";
        }

        $colorFilterClause = implode(' AND ', $colorConditions);

        $query .= " AND ($colorFilterClause)";
    }

    // 使用 mysqli 或 PDO 执行上述查询
    $result = mysqli_query($connection, $query);

    // 处理查询结果
    if ($result) {
        echo "<ul>";

        // 使用循环遍历查询结果，显示商品信息
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo "<strong>商品名稱：</strong>{$row['name']}<br>";
            echo "<strong>價格：</strong>{$row['price']}<br>";
            echo "<strong>顏色：</strong>{$row['colors']}<br>";
            echo "<strong>尺寸：</strong>{$row['sizes']}<br>";

            // 檢查是否存在 class、other 和 brand 這些欄位
            if (isset($row['class_id'])) {
                echo "<strong>分類：</strong>{$row['class_id']}<br>";
            }

            if (isset($row['other_id'])) {
                echo "<strong>其他：</strong>{$row['other_id']}<br>";
            }

            if (isset($row['brand_id'])) {
                echo "<strong>品牌：</strong>{$row['brand_id']}<br>";
            }

            // 檢查是否存在 description 這個欄位
            if (isset($row['description'])) {
                echo "<strong>描述：</strong>{$row['description']}<br>";
            }

            echo "<img src='{$row['image_url']}' alt='{$row['name']}' style='max-width: 200px; max-height: 200px;'><br>";
            echo "</li>";
        }

        echo "</ul>";
    } else {
        echo "查询失败：" . mysqli_error($connection);
    }

    // 关闭数据库连接
    mysqli_close($connection);
    ?>
</body>
</html>
