<?php
require_once 'config.php';

class Auth
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    // 管理員登入
    public function adminLogin($username, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_logged_in'] = true;
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("登入錯誤: " . $e->getMessage());
            return false;
        }
    }

    // 檢查是否已登入
    public function isAdminLoggedIn()
    {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    // 登出
    public function logout()
    {
        session_unset();
        session_destroy();
    }

    // 獲取當前管理員資訊
    public function getCurrentAdmin()
    {
        if ($this->isAdminLoggedIn()) {
            return [
                'id' => $_SESSION['admin_id'],
                'username' => $_SESSION['admin_username']
            ];
        }
        return null;
    }

    // 要求管理員登入
    public function requireAdminLogin()
    {
        if (!$this->isAdminLoggedIn()) {
            header('Location: admin_login.php');
            exit;
        }
    }
}

// 處理登入請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    header('Content-Type: application/json');

    $auth = new Auth();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->adminLogin($username, $password)) {
        echo json_encode(['success' => true, 'message' => '登入成功']);
    } else {
        // 不要設置 401 狀態碼，這會導致 fetch 進入 catch
        echo json_encode(['success' => false, 'message' => '帳號或密碼錯誤']);
    }
    exit;
}

// 處理登出請求
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth = new Auth();
    $auth->logout();
    header('Location: admin_login.php?message=' . urlencode('已登出'));
    exit;
}
