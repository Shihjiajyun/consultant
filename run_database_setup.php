<?php
require_once 'php/config.php';

try {
    $pdo = getDBConnection();

    // 讀取 SQL 文件
    $sql = file_get_contents('podcast_table.sql');

    // 分割多個 SQL 語句
    $statements = explode(';', $sql);

    echo "<h2>執行資料庫設置...</h2>";

    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "<p style='color: green;'>✓ 執行成功: " . substr($statement, 0, 50) . "...</p>";
            } catch (PDOException $e) {
                echo "<p style='color: red;'>✗ 執行失敗: " . $e->getMessage() . "</p>";
                echo "<p style='color: orange;'>SQL: " . substr($statement, 0, 100) . "...</p>";
            }
        }
    }

    echo "<h3 style='color: green;'>資料庫設置完成！</h3>";
    echo "<p><a href='admin_dashboard.php'>返回管理後台</a></p>";
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>資料庫連接失敗: " . $e->getMessage() . "</h3>";
}
