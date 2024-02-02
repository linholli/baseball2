<?php

require_once("./db_connect.php");

$sql = "SELECT article. *, type.name AS type_name FROM article
JOIN type ON article.type_id = type.id
 ORDER BY article.id";

$result=$conn->query($sql);

$rows=$result->fetch_all(MYSQLI_ASSOC);

?>
<pre>
    <?php
    print_r($rows);
    ?>
</pre>
<?php

$conn->close();

// 外建資料表