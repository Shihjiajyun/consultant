<?php
// 資料庫配置
define('DB_HOST', '43.207.210.147');
define('DB_USER', 'myuser');
define('DB_PASS', '123456789');
define('DB_NAME', 'consultant_db');

// 建立資料庫連接
function getDBConnection()
{
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("資料庫連接失敗: " . $e->getMessage());
    }
}

// 開始session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 網站基本配置
define('SITE_URL', 'http://localhost');
define('UPLOAD_PATH', '../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// 確保上傳資料夾存在
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
