<?php
require_once 'config.php';

class ArticleHandler
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    // 獲取所有文章
    public function getAllArticles($status = 'published', $limit = null)
    {
        try {
            if ($status === 'all') {
                $sql = "SELECT * FROM articles ORDER BY created_at DESC";
                if ($limit) {
                    $sql .= " LIMIT " . intval($limit);
                }
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "SELECT * FROM articles WHERE status = ? ORDER BY created_at DESC";
                if ($limit) {
                    $sql .= " LIMIT " . intval($limit);
                }
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$status]);
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("獲取文章錯誤: " . $e->getMessage());
            return [];
        }
    }

    // 根據ID獲取文章
    public function getArticleById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = ?");
            $stmt->execute([$id]);
            $article = $stmt->fetch();

            if ($article) {
                // 增加瀏覽次數
                $this->incrementViews($id);
            }

            return $article;
        } catch (PDOException $e) {
            error_log("獲取文章錯誤: " . $e->getMessage());
            return null;
        }
    }

    // 根據slug獲取文章
    public function getArticleBySlug($slug)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE slug = ? AND status = 'published'");
            $stmt->execute([$slug]);
            $article = $stmt->fetch();

            if ($article) {
                // 增加瀏覽次數
                $this->incrementViews($article['id']);
            }

            return $article;
        } catch (PDOException $e) {
            error_log("獲取文章錯誤: " . $e->getMessage());
            return null;
        }
    }

    // 獲取相關文章
    public function getRelatedArticles($category, $currentId, $limit = 3)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM articles 
                WHERE category = ? AND id != ? AND status = 'published' 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$category, $currentId, $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("獲取相關文章錯誤: " . $e->getMessage());
            return [];
        }
    }

    // 獲取熱門文章
    public function getPopularArticles($limit = 5, $excludeId = null)
    {
        try {
            $sql = "SELECT * FROM articles WHERE status = 'published'";
            $params = [];

            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }

            $sql .= " ORDER BY views DESC, created_at DESC LIMIT ?";
            $params[] = $limit;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("獲取熱門文章錯誤: " . $e->getMessage());
            return [];
        }
    }

    // 創建文章
    public function createArticle($data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO articles (title, slug, content, excerpt, featured_image, category, author, tags, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['title'],
                $data['slug'],
                $data['content'],
                $data['excerpt'],
                $data['featured_image'],
                $data['category'],
                $data['author'],
                $data['tags'],
                $data['status']
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("創建文章錯誤: " . $e->getMessage());
            return false;
        }
    }

    // 更新文章
    public function updateArticle($id, $data)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE articles 
                SET title = ?, slug = ?, content = ?, excerpt = ?, featured_image = ?, 
                    category = ?, author = ?, tags = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");

            return $stmt->execute([
                $data['title'],
                $data['slug'],
                $data['content'],
                $data['excerpt'],
                $data['featured_image'],
                $data['category'],
                $data['author'],
                $data['tags'],
                $data['status'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("更新文章錯誤: " . $e->getMessage());
            return false;
        }
    }

    // 刪除文章
    public function deleteArticle($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("刪除文章錯誤: " . $e->getMessage());
            return false;
        }
    }

    // 增加瀏覽次數
    private function incrementViews($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("更新瀏覽次數錯誤: " . $e->getMessage());
        }
    }

    // 生成URL友好的slug
    public function generateSlug($title)
    {
        // 簡單的slug生成（實際使用時可能需要更複雜的處理）
        $slug = strtolower(str_replace(' ', '-', $title));
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        // 檢查是否已存在
        $counter = 1;
        $originalSlug = $slug;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // 檢查slug是否存在
    private function slugExists($slug)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM articles WHERE slug = ?");
            $stmt->execute([$slug]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // 獲取文章統計
    public function getArticleStats()
    {
        try {
            $stats = [];

            // 總文章數
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM articles");
            $stats['total'] = $stmt->fetch()['total'];

            // 已發布文章數
            $stmt = $this->pdo->query("SELECT COUNT(*) as published FROM articles WHERE status = 'published'");
            $stats['published'] = $stmt->fetch()['published'];

            // 草稿數
            $stmt = $this->pdo->query("SELECT COUNT(*) as draft FROM articles WHERE status = 'draft'");
            $stats['draft'] = $stmt->fetch()['draft'];

            // 總瀏覽數
            $stmt = $this->pdo->query("SELECT SUM(views) as total_views FROM articles");
            $stats['total_views'] = $stmt->fetch()['total_views'] ?? 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("獲取統計錯誤: " . $e->getMessage());
            return [];
        }
    }

    // 處理圖片上傳
    public function handleImageUpload($imageFile)
    {
        try {
            // 檢查文件是否上傳成功
            if ($imageFile['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('文件上傳失敗');
            }

            // 檢查文件大小 (5MB)
            if ($imageFile['size'] > 5 * 1024 * 1024) {
                throw new Exception('文件大小超過限制 (5MB)');
            }

            // 檢查文件類型
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $imageFile['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('不支援的文件類型');
            }

            // 生成唯一文件名
            $extension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $uploadPath = '../uploads/';

            // 確保上傳目錄存在
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $filepath = $uploadPath . $filename;

            // 移動文件
            if (!move_uploaded_file($imageFile['tmp_name'], $filepath)) {
                throw new Exception('文件移動失敗');
            }

            return $filename;
        } catch (Exception $e) {
            error_log("圖片上傳錯誤: " . $e->getMessage());
            return false;
        }
    }

    // 獲取所有分類
    public function getCategories()
    {
        try {
            $stmt = $this->pdo->query("SELECT DISTINCT category FROM articles WHERE status = 'published' AND category IS NOT NULL AND category != '' ORDER BY category");
            $categories = [];
            while ($row = $stmt->fetch()) {
                $categories[] = $row['category'];
            }
            return $categories;
        } catch (PDOException $e) {
            error_log("獲取分類錯誤: " . $e->getMessage());
            return [];
        }
    }

    // 獲取文章總數（支持搜索和分類篩選）
    public function getArticleCount($status = 'published', $search = '', $category = '')
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM articles WHERE status = ?";
            $params = [$status];

            if (!empty($search)) {
                $sql .= " AND (title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
                $searchTerm = '%' . $search . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($category)) {
                $sql .= " AND category = ?";
                $params[] = $category;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['count'];
        } catch (PDOException $e) {
            error_log("獲取文章數量錯誤: " . $e->getMessage());
            return 0;
        }
    }

    // 分頁獲取文章（支持搜索和分類篩選）
    public function getArticlesPaginated($status = 'published', $page = 1, $perPage = 10, $search = '', $category = '')
    {
        try {
            $offset = ($page - 1) * $perPage;
            $sql = "SELECT * FROM articles WHERE status = ?";
            $params = [$status];

            if (!empty($search)) {
                $sql .= " AND (title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
                $searchTerm = '%' . $search . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($category)) {
                $sql .= " AND category = ?";
                $params[] = $category;
            }

            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("分頁獲取文章錯誤: " . $e->getMessage());
            return [];
        }
    }
}

// 處理AJAX請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleHandler = new ArticleHandler();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create':
            // 處理圖片上傳
            $featuredImage = '';
            if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
                $featuredImage = $articleHandler->handleImageUpload($_FILES['new_image']);
                if ($featuredImage === false) {
                    echo json_encode(['success' => false, 'message' => '圖片上傳失敗']);
                    exit;
                }
            } elseif (!empty($_POST['featured_image'])) {
                // 如果是編輯模式且沒有新圖片，保留原有圖片
                $featuredImage = $_POST['featured_image'];
            }

            // 準備文章數據
            $articleData = $_POST;
            $articleData['featured_image'] = $featuredImage;

            $result = $articleHandler->createArticle($articleData);
            echo json_encode(['success' => $result !== false, 'id' => $result]);
            break;

        case 'update':
            $id = $_POST['id'] ?? 0;

            // 處理圖片上傳
            $featuredImage = $_POST['featured_image'] ?? ''; // 保留原有圖片
            if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
                $newImage = $articleHandler->handleImageUpload($_FILES['new_image']);
                if ($newImage === false) {
                    echo json_encode(['success' => false, 'message' => '圖片上傳失敗']);
                    exit;
                }

                // 刪除舊圖片（如果存在）
                if (!empty($featuredImage) && file_exists('../uploads/' . $featuredImage)) {
                    unlink('../uploads/' . $featuredImage);
                }

                $featuredImage = $newImage;
            }

            // 準備文章數據
            $articleData = $_POST;
            $articleData['featured_image'] = $featuredImage;

            $result = $articleHandler->updateArticle($id, $articleData);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? 0;

            // 獲取文章信息以刪除相關圖片
            $article = $articleHandler->getArticleById($id);
            if ($article && !empty($article['featured_image'])) {
                $imagePath = '../uploads/' . $article['featured_image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $result = $articleHandler->deleteArticle($id);
            echo json_encode(['success' => $result]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => '無效的操作']);
    }
    exit;
}
