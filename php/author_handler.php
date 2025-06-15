<?php
require_once 'config.php';

class AuthorHandler
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    // 獲取所有作者
    public function getAllAuthors($activeOnly = true)
    {
        try {
            $sql = "SELECT * FROM authors";
            $params = [];

            if ($activeOnly) {
                $sql .= " WHERE is_active = ?";
                $params[] = 1;
            }

            $sql .= " ORDER BY name";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("獲取作者列表失敗: " . $e->getMessage());
            return [];
        }
    }

    // 根據ID獲取單個作者
    public function getAuthorById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM authors WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("獲取作者失敗: " . $e->getMessage());
            return false;
        }
    }

    // 創建作者
    public function createAuthor($data)
    {
        try {
            $sql = "INSERT INTO authors (name, email, bio, avatar, title, company, website, facebook, linkedin, twitter, specialties, experience_years, achievements, education, certifications, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['name'],
                $data['email'] ?? '',
                $data['bio'] ?? '',
                $data['avatar'] ?? '',
                $data['title'] ?? '',
                $data['company'] ?? '',
                $data['website'] ?? '',
                $data['facebook'] ?? '',
                $data['linkedin'] ?? '',
                $data['twitter'] ?? '',
                $data['specialties'] ?? '',
                $data['experience_years'] ?? 0,
                $data['achievements'] ?? '',
                $data['education'] ?? '',
                $data['certifications'] ?? '',
                isset($data['is_active']) ? $data['is_active'] : 1
            ]);

            if ($result) {
                return $this->pdo->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("創建作者失敗: " . $e->getMessage());
            return false;
        }
    }

    // 更新作者
    public function updateAuthor($id, $data)
    {
        try {
            $sql = "UPDATE authors SET 
                    name = ?, 
                    email = ?, 
                    bio = ?, 
                    avatar = ?, 
                    title = ?, 
                    company = ?, 
                    website = ?, 
                    facebook = ?, 
                    linkedin = ?, 
                    twitter = ?, 
                    specialties = ?, 
                    experience_years = ?, 
                    achievements = ?, 
                    education = ?, 
                    certifications = ?, 
                    is_active = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['name'],
                $data['email'] ?? '',
                $data['bio'] ?? '',
                $data['avatar'] ?? '',
                $data['title'] ?? '',
                $data['company'] ?? '',
                $data['website'] ?? '',
                $data['facebook'] ?? '',
                $data['linkedin'] ?? '',
                $data['twitter'] ?? '',
                $data['specialties'] ?? '',
                $data['experience_years'] ?? 0,
                $data['achievements'] ?? '',
                $data['education'] ?? '',
                $data['certifications'] ?? '',
                isset($data['is_active']) ? $data['is_active'] : 1,
                $id
            ]);

            return $result;
        } catch (PDOException $e) {
            error_log("更新作者失敗: " . $e->getMessage());
            return false;
        }
    }

    // 刪除作者（軟刪除，設為不活躍）
    public function deleteAuthor($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE authors SET is_active = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("刪除作者失敗: " . $e->getMessage());
            return false;
        }
    }

    // 根據名稱獲取作者
    public function getAuthorByName($name)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM authors WHERE name = ? AND is_active = 1");
            $stmt->execute([$name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("根據名稱獲取作者失敗: " . $e->getMessage());
            return false;
        }
    }

    // 獲取作者統計
    public function getAuthorStats()
    {
        try {
            $stats = [];

            // 總作者數
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM authors WHERE is_active = 1");
            $stats['total'] = $stmt->fetchColumn();

            // 有文章的作者數
            $stmt = $this->pdo->query("SELECT COUNT(DISTINCT author_id) as active FROM articles WHERE author_id IS NOT NULL");
            $stats['active'] = $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            error_log("獲取作者統計失敗: " . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0
            ];
        }
    }
}

// 處理 AJAX 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $authorHandler = new AuthorHandler();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create':
            $result = $authorHandler->createAuthor($_POST);
            echo json_encode(['success' => $result !== false, 'id' => $result]);
            break;

        case 'update':
            $result = $authorHandler->updateAuthor($_POST['id'], $_POST);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            $result = $authorHandler->deleteAuthor($_POST['id']);
            echo json_encode(['success' => $result]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => '無效的操作']);
            break;
    }
}

// 處理 GET 請求
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');

    $authorHandler = new AuthorHandler();
    $action = $_GET['action'];

    switch ($action) {
        case 'get':
            if (isset($_GET['id'])) {
                $author = $authorHandler->getAuthorById($_GET['id']);
                echo json_encode(['success' => $author !== false, 'data' => $author]);
            } else {
                echo json_encode(['success' => false, 'message' => '缺少作者ID']);
            }
            break;

        case 'list':
            $authors = $authorHandler->getAllAuthors();
            echo json_encode(['success' => true, 'data' => $authors]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => '無效的操作']);
            break;
    }
}
