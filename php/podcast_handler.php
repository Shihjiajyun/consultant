<?php
require_once 'config.php';

class PodcastHandler
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    // 獲取所有 Podcast
    public function getAllPodcasts($status = 'published', $limit = null)
    {
        try {
            $sql = "SELECT * FROM podcasts";
            $params = [];

            if ($status !== 'all') {
                $sql .= " WHERE status = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY release_date DESC, episode_number DESC";

            if ($limit) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("獲取 Podcast 列表失敗: " . $e->getMessage());
            return [];
        }
    }

    // 根據ID獲取單個 Podcast
    public function getPodcastById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM podcasts WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("獲取 Podcast 失敗: " . $e->getMessage());
            return false;
        }
    }

    // 創建 Podcast
    public function createPodcast($data)
    {
        try {
            $sql = "INSERT INTO podcasts (episode_number, title, description, duration, release_date, status, audio_url, podcast_link, cover_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['episode_number'],
                $data['title'],
                $data['description'],
                $data['duration'],
                $data['release_date'],
                $data['status'],
                $data['audio_url'] ?? '',
                $data['podcast_link'] ?? '',
                $data['cover_image'] ?? ''
            ]);

            if ($result) {
                $this->updatePodcastStats();
                return $this->pdo->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("創建 Podcast 失敗: " . $e->getMessage());
            return false;
        }
    }

    // 更新 Podcast
    public function updatePodcast($id, $data)
    {
        try {
            $sql = "UPDATE podcasts SET 
                    episode_number = ?, 
                    title = ?, 
                    description = ?, 
                    duration = ?, 
                    release_date = ?, 
                    status = ?, 
                    audio_url = ?, 
                    podcast_link = ?,
                    cover_image = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['episode_number'],
                $data['title'],
                $data['description'],
                $data['duration'],
                $data['release_date'],
                $data['status'],
                $data['audio_url'] ?? '',
                $data['podcast_link'] ?? '',
                $data['cover_image'] ?? '',
                $id
            ]);

            if ($result) {
                $this->updatePodcastStats();
            }
            return $result;
        } catch (PDOException $e) {
            error_log("更新 Podcast 失敗: " . $e->getMessage());
            return false;
        }
    }

    // 刪除 Podcast
    public function deletePodcast($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM podcasts WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                $this->updatePodcastStats();
            }
            return $result;
        } catch (PDOException $e) {
            error_log("刪除 Podcast 失敗: " . $e->getMessage());
            return false;
        }
    }

    // 增加播放次數
    public function incrementPlayCount($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE podcasts SET play_count = play_count + 1 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("更新播放次數失敗: " . $e->getMessage());
            return false;
        }
    }

    // 增加瀏覽次數
    public function incrementViewCount($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE podcasts SET view_count = view_count + 1 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("更新瀏覽次數失敗: " . $e->getMessage());
            return false;
        }
    }

    // 獲取 Podcast 統計資料
    public function getPodcastStats()
    {
        try {
            $stats = [];

            // 總集數
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM podcasts");
            $stats['total'] = $stmt->fetchColumn();

            // 已發布集數
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as published FROM podcasts WHERE status = 'published'");
            $stmt->execute();
            $stats['published'] = $stmt->fetchColumn();

            // 草稿集數
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as draft FROM podcasts WHERE status = 'draft'");
            $stmt->execute();
            $stats['draft'] = $stmt->fetchColumn();

            // 總播放次數
            $stmt = $this->pdo->query("SELECT SUM(play_count) as total_plays FROM podcasts");
            $stats['total_plays'] = $stmt->fetchColumn() ?: 0;

            // 總瀏覽次數
            $stmt = $this->pdo->query("SELECT SUM(view_count) as total_views FROM podcasts");
            $stats['total_views'] = $stmt->fetchColumn() ?: 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("獲取 Podcast 統計失敗: " . $e->getMessage());
            return [
                'total' => 0,
                'published' => 0,
                'draft' => 0,
                'total_plays' => 0,
                'total_views' => 0
            ];
        }
    }

    // 更新統計資料
    private function updatePodcastStats()
    {
        try {
            $stats = $this->getPodcastStats();

            // 更新或插入統計資料
            $sql = "INSERT INTO podcast_stats (id, total_episodes, total_listens, total_views) 
                    VALUES (1, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    total_episodes = VALUES(total_episodes),
                    total_listens = VALUES(total_listens),
                    total_views = VALUES(total_views)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $stats['total'],
                $stats['total_plays'],
                $stats['total_views']
            ]);
        } catch (PDOException $e) {
            error_log("更新統計資料失敗: " . $e->getMessage());
        }
    }

    // 獲取平台連結
    public function getPlatforms()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM podcast_platforms WHERE is_active = 1 ORDER BY sort_order");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("獲取平台列表失敗: " . $e->getMessage());
            return [];
        }
    }

    // 更新平台連結
    public function updatePlatform($id, $url)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE podcast_platforms SET platform_url = ? WHERE id = ?");
            return $stmt->execute([$url, $id]);
        } catch (PDOException $e) {
            error_log("更新平台連結失敗: " . $e->getMessage());
            return false;
        }
    }
}

// 處理 AJAX 請求
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $podcastHandler = new PodcastHandler();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create':
            $result = $podcastHandler->createPodcast($_POST);
            echo json_encode(['success' => $result !== false, 'id' => $result]);
            break;

        case 'update':
            $result = $podcastHandler->updatePodcast($_POST['id'], $_POST);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            $result = $podcastHandler->deletePodcast($_POST['id']);
            echo json_encode(['success' => $result]);
            break;

        case 'increment_plays':
            $result = $podcastHandler->incrementPlayCount($_POST['id']);
            echo json_encode(['success' => $result]);
            break;

        case 'increment_views':
            $result = $podcastHandler->incrementViewCount($_POST['id']);
            echo json_encode(['success' => $result]);
            break;

        case 'update_platform':
            $result = $podcastHandler->updatePlatform($_POST['platform_id'], $_POST['url']);
            echo json_encode(['success' => $result]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => '無效的操作']);
            break;
    }
    exit;
}

// 處理 GET 請求
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');

    $podcastHandler = new PodcastHandler();
    $action = $_GET['action'];

    switch ($action) {
        case 'get':
            if (isset($_GET['id'])) {
                $podcast = $podcastHandler->getPodcastById($_GET['id']);
                echo json_encode(['success' => $podcast !== false, 'data' => $podcast]);
            } else {
                echo json_encode(['success' => false, 'message' => '缺少 Podcast ID']);
            }
            break;

        case 'list':
            $podcasts = $podcastHandler->getAllPodcasts();
            echo json_encode(['success' => true, 'data' => $podcasts]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => '無效的操作']);
            break;
    }
    exit;
}
