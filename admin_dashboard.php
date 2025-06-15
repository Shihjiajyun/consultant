<?php
require_once 'php/config.php';
require_once 'php/auth.php';
require_once 'php/article_handler.php';
require_once 'php/podcast_handler.php';
require_once 'php/author_handler.php';

$auth = new Auth();
$auth->requireAdminLogin();

$articleHandler = new ArticleHandler();
$podcastHandler = new PodcastHandler();
$authorHandler = new AuthorHandler();
$articles = $articleHandler->getAllArticles('all');
$podcasts = $podcastHandler->getAllPodcasts('all');
$authors = $authorHandler->getAllAuthors(true);
$stats = $articleHandler->getArticleStats();
$podcastStats = $podcastHandler->getPodcastStats();
$authorStats = $authorHandler->getAuthorStats();
$currentAdmin = $auth->getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員後台 - 共好計畫研究室</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- 側邊欄 -->
            <div class="col-md-3 sidebar text-white p-3">
                <div class="text-center mb-4">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4>管理後台</h4>
                    <small class="opacity-75">歡迎，<?php echo htmlspecialchars($currentAdmin['username']); ?></small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link text-white active" href="#" onclick="showSection('dashboard')">
                        <i class="fas fa-tachometer-alt me-2"></i>儀表板
                    </a>
                    <a class="nav-link text-white" href="#" onclick="showSection('articles')">
                        <i class="fas fa-newspaper me-2"></i>文章管理
                    </a>
                    <a class="nav-link text-white" href="#" onclick="showSection('podcasts')">
                        <i class="fas fa-podcast me-2"></i>Podcast管理
                    </a>
                    <a class="nav-link text-white" href="#" onclick="showSection('authors')">
                        <i class="fas fa-users me-2"></i>作者管理
                    </a>
                    <a class="nav-link text-white" href="article_editor.php">
                        <i class="fas fa-plus-circle me-2"></i>新增文章
                    </a>
                    <a class="nav-link text-white" href="index.php" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>查看網站
                    </a>
                    <hr class="my-3 opacity-25">
                    <a class="nav-link text-white" href="php/auth.php?action=logout" onclick="return confirmLogout()">
                        <i class="fas fa-sign-out-alt me-2"></i>安全登出
                    </a>
                </nav>
            </div>

            <!-- 主要內容 -->
            <div class="col-md-9 main-content">
                <!-- 歡迎橫幅 -->
                <div class="welcome-banner">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">歡迎回來！</h2>
                            <p class="mb-0 opacity-90">
                                今天是 <?php echo date('Y年m月d日'); ?>，
                                您有 <?php echo $stats['draft'] ?? 0; ?> 篇草稿待處理
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="article_editor.php" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>新增文章
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 統計卡片 -->
                <div class="row mb-4">
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="card stats-card">
                            <i class="fas fa-file-alt stats-icon"></i>
                            <div class="card-body">
                                <span class="stats-number"><?php echo $stats['total'] ?? 0; ?></span>
                                <div class="stats-label">總文章數</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="card stats-card">
                            <i class="fas fa-globe stats-icon"></i>
                            <div class="card-body">
                                <span class="stats-number"><?php echo $stats['published'] ?? 0; ?></span>
                                <div class="stats-label">已發布文章</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="card stats-card">
                            <i class="fas fa-podcast stats-icon"></i>
                            <div class="card-body">
                                <span class="stats-number"><?php echo $podcastStats['total'] ?? 0; ?></span>
                                <div class="stats-label">總集數</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="card stats-card">
                            <i class="fas fa-play stats-icon"></i>
                            <div class="card-body">
                                <span
                                    class="stats-number"><?php echo number_format($podcastStats['total_plays'] ?? 0); ?></span>
                                <div class="stats-label">總播放數</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="card stats-card">
                            <i class="fas fa-eye stats-icon"></i>
                            <div class="card-body">
                                <span
                                    class="stats-number"><?php echo number_format($stats['total_views'] ?? 0); ?></span>
                                <div class="stats-label">文章瀏覽</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                        <div class="card stats-card">
                            <i class="fas fa-users stats-icon"></i>
                            <div class="card-body">
                                <span class="stats-number"><?php echo $authorStats['total'] ?? 0; ?></span>
                                <div class="stats-label">作者數量</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 儀表板區塊 -->
                <div id="dashboard-section" class="content-section">
                    <div class="articles-table">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>快速總覽</h5>
                                    <small class="text-muted">系統概況一覽</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>最新文章</h6>
                                    <?php
                                    $recentArticles = array_slice($articles, 0, 3);
                                    foreach ($recentArticles as $article): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <span
                                                class="flex-grow-1"><?php echo htmlspecialchars($article['title']); ?></span>
                                            <small
                                                class="text-muted"><?php echo date('m/d', strtotime($article['created_at'])); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-6">
                                    <h6>最新 Podcast</h6>
                                    <?php
                                    $recentPodcasts = array_slice($podcasts, 0, 3);
                                    foreach ($recentPodcasts as $podcast): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-podcast text-success me-2"></i>
                                            <span class="flex-grow-1">EP.<?php echo $podcast['episode_number']; ?>
                                                <?php echo htmlspecialchars($podcast['title']); ?></span>
                                            <small
                                                class="text-muted"><?php echo date('m/d', strtotime($podcast['release_date'])); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 文章管理區塊 -->
                <div id="articles-section" class="content-section" style="display: none;">
                    <div class="articles-table">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>文章管理</h5>
                                    <small class="text-muted">管理您的所有文章內容</small>
                                </div>
                                <div class="col-auto">
                                    <a href="article_editor.php" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>新增文章
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($articles)): ?>
                            <div class="card-body text-center py-5">
                                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">尚無文章</h5>
                                <p class="text-muted">開始建立您的第一篇文章吧！</p>
                                <a href="article_editor.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>新增文章
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-file-text me-1"></i>標題</th>
                                            <th><i class="fas fa-folder me-1"></i>分類</th>
                                            <th><i class="fas fa-toggle-on me-1"></i>狀態</th>
                                            <th><i class="fas fa-eye me-1"></i>瀏覽數</th>
                                            <th><i class="fas fa-calendar me-1"></i>建立時間</th>
                                            <th><i class="fas fa-cogs me-1"></i>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($articles as $article): ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($article['title']); ?>
                                                    </div>
                                                    <small
                                                        class="text-muted"><?php echo htmlspecialchars(mb_substr($article['excerpt'] ?? '', 0, 50)); ?>...</small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $categoryColors = [
                                                        '提案技巧' => 'bg-primary',
                                                        '政策解析' => 'bg-success',
                                                        '成功案例' => 'bg-info',
                                                        '常見問題' => 'bg-warning',
                                                        '實務經驗' => 'bg-secondary'
                                                    ];
                                                    $colorClass = $categoryColors[$article['category']] ?? 'bg-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $colorClass; ?>">
                                                        <?php echo htmlspecialchars($article['category']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge <?php echo $article['status'] === 'published' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                        <i
                                                            class="fas <?php echo $article['status'] === 'published' ? 'fa-globe' : 'fa-edit'; ?> me-1"></i>
                                                        <?php echo $article['status'] === 'published' ? '已發布' : '草稿'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold"><?php echo number_format($article['views']); ?></span>
                                                </td>
                                                <td>
                                                    <small><?php echo date('Y-m-d H:i', strtotime($article['created_at'])); ?></small>
                                                </td>
                                                <td>
                                                    <div class="action-buttons d-flex">
                                                        <?php if ($article['status'] === 'published'): ?>
                                                            <a href="article.php?id=<?php echo $article['id']; ?>"
                                                                class="btn btn-sm btn-outline-info btn-action me-1" target="_blank"
                                                                title="查看文章">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="article_editor.php?id=<?php echo $article['id']; ?>"
                                                            class="btn btn-sm btn-outline-primary btn-action me-1" title="編輯文章">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button
                                                            onclick="deleteArticle(<?php echo $article['id']; ?>, '<?php echo addslashes($article['title']); ?>')"
                                                            class="btn btn-sm btn-outline-danger btn-action" title="刪除文章">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Podcast管理區塊 -->
                <div id="podcasts-section" class="content-section" style="display: none;">
                    <div class="articles-table">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0"><i class="fas fa-podcast me-2"></i>Podcast管理</h5>
                                    <small class="text-muted">管理您的所有 Podcast 集數</small>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary" onclick="showPodcastEditor()">
                                        <i class="fas fa-plus me-1"></i>新增集數
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($podcasts)): ?>
                            <div class="card-body text-center py-5">
                                <i class="fas fa-podcast fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">尚無 Podcast</h5>
                                <p class="text-muted">開始建立您的第一個 Podcast 集數！</p>
                                <button class="btn btn-primary" onclick="showPodcastEditor()">
                                    <i class="fas fa-plus me-1"></i>新增集數
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>集數</th>
                                            <th>標題</th>
                                            <th>時長</th>
                                            <th>狀態</th>
                                            <th>播放數</th>
                                            <th>發布日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($podcasts as $podcast): ?>
                                            <tr>
                                                <td>
                                                    <span
                                                        class="badge bg-primary fs-6">EP.<?php echo $podcast['episode_number']; ?></span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($podcast['title']); ?>
                                                    </div>
                                                    <small
                                                        class="text-muted"><?php echo htmlspecialchars(mb_substr($podcast['description'] ?? '', 0, 50)); ?>...</small>
                                                </td>
                                                <td><?php echo htmlspecialchars($podcast['duration']); ?></td>
                                                <td>
                                                    <span
                                                        class="badge <?php echo $podcast['status'] === 'published' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                        <?php echo $podcast['status'] === 'published' ? '已發布' : '草稿'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo number_format($podcast['play_count']); ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($podcast['release_date'])); ?></td>
                                                <td>
                                                    <div class="action-buttons d-flex">
                                                        <?php if (!empty($podcast['podcast_link'])): ?>
                                                            <a href="<?php echo htmlspecialchars($podcast['podcast_link']); ?>"
                                                                class="btn btn-sm btn-outline-info btn-action me-1" target="_blank"
                                                                title="聽 Podcast"
                                                                onclick="incrementPodcastPlay(<?php echo $podcast['id']; ?>)">
                                                                <i class="fas fa-play"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <button onclick="editPodcast(<?php echo $podcast['id']; ?>)"
                                                            class="btn btn-sm btn-outline-primary btn-action me-1" title="編輯">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button
                                                            onclick="deletePodcast(<?php echo $podcast['id']; ?>, '<?php echo addslashes($podcast['title']); ?>')"
                                                            class="btn btn-sm btn-outline-danger btn-action" title="刪除">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 作者管理區塊 -->
                <div id="authors-section" class="content-section" style="display: none;">
                    <div class="articles-table">
                        <div class="card-header bg-white border-0 p-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>作者管理</h5>
                                    <small class="text-muted">管理作者資料和簡介</small>
                                </div>
                                <div class="col-auto">
                                    <a href="author_editor.php" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>新增作者
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($authors)): ?>
                            <div class="card-body text-center py-5">
                                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">尚無作者資料</h5>
                                <p class="text-muted">開始建立您的第一位作者資料！</p>
                                <a href="author_editor.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>新增作者
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($authors as $author): ?>
                                        <div class="col-md-6 col-xl-4 mb-4">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body text-center">
                                                    <img src="<?php echo !empty($author['avatar']) ? htmlspecialchars($author['avatar']) : './img/people.jpg'; ?>"
                                                        class="rounded-circle mb-3" width="80" height="80"
                                                        alt="<?php echo htmlspecialchars($author['name']); ?>">
                                                    <h5><?php echo htmlspecialchars($author['name']); ?></h5>
                                                    <p class="text-muted">
                                                        <?php echo htmlspecialchars($author['title'] ?? '作者'); ?></p>
                                                    <p class="small">
                                                        <?php echo htmlspecialchars(mb_substr($author['bio'] ?? '', 0, 60)); ?><?php echo mb_strlen($author['bio'] ?? '') > 60 ? '...' : ''; ?>
                                                    </p>
                                                    <?php if (!empty($author['specialties'])): ?>
                                                        <div class="mb-2">
                                                            <small
                                                                class="badge bg-info"><?php echo htmlspecialchars($author['specialties']); ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="author_editor.php?id=<?php echo $author['id']; ?>"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i> 編輯
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-info"
                                                            onclick="viewAuthor(<?php echo $author['id']; ?>, '<?php echo addslashes($author['name']); ?>')">
                                                            <i class="fas fa-eye"></i> 查看
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteAuthor(<?php echo $author['id']; ?>, '<?php echo addslashes($author['name']); ?>')">
                                                            <i class="fas fa-trash"></i> 刪除
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // 切換顯示區塊
            function showSection(sectionName) {
                // 隱藏所有區塊
                document.querySelectorAll('.content-section').forEach(section => {
                    section.style.display = 'none';
                });

                // 顯示指定區塊
                document.getElementById(sectionName + '-section').style.display = 'block';

                // 更新側邊欄選中狀態
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                event.target.classList.add('active');
            }

            // Podcast 管理功能
            function showPodcastEditor(id = null) {
                // 這裡可以實現 Podcast 編輯器彈窗
                Swal.fire({
                    title: id ? '編輯 Podcast' : '新增 Podcast',
                    html: `
                        <form id="podcastForm">
                            <div class="mb-3 text-start">
                                <label class="form-label">集數編號</label>
                                <input type="number" class="form-control" name="episode_number" value="${podcast.episode_number}" placeholder="輸入數字即可，前方 EP. 會自動加上" required>
                                <div class="form-text">例如：輸入 1，顯示為 EP.1</div>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">標題</label>
                                <input type="text" class="form-control" name="title" value="${podcast.title}" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">描述</label>
                                <textarea class="form-control" name="description" rows="3">${podcast.description || ''}</textarea>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">時長</label>
                                <input type="text" class="form-control" name="duration" value="${podcast.duration}" placeholder="請包含單位，例如：25分鐘、1小時30分鐘" required>
                                <div class="form-text">請輸入完整時長，包含單位</div>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Podcast 連結</label>
                                <input type="url" class="form-control" name="podcast_link" value="${podcast.podcast_link || ''}" placeholder="https://...">
                                <div class="form-text">主要收聽平台的連結</div>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">發布日期</label>
                                <input type="date" class="form-control" name="release_date" value="${podcast.release_date}" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">狀態</label>
                                <select class="form-control" name="status">
                                    <option value="draft" ${podcast.status === 'draft' ? 'selected' : ''}>草稿</option>
                                    <option value="published" ${podcast.status === 'published' ? 'selected' : ''}>已發布</option>
                                </select>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: id ? '更新' : '創建',
                    cancelButtonText: '取消',
                    width: '600px',
                    preConfirm: () => {
                        const form = document.getElementById('podcastForm');
                        const formData = new FormData(form);
                        formData.append('action', id ? 'update' : 'create');
                        if (id) formData.append('id', id);

                        return fetch('php/podcast_handler.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.success) {
                                    throw new Error(data.message || '操作失敗');
                                }
                                return data;
                            });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: '操作成功',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }

            function editPodcast(id) {
                // 獲取 Podcast 資料並填入表單
                fetch(`php/podcast_handler.php?action=get&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            const podcast = data.data;
                            showPodcastEditorWithData(id, podcast);
                        } else {
                            Swal.fire('錯誤', '無法獲取 Podcast 資料', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('錯誤', '網路連線錯誤', 'error');
                    });
            }

            function showPodcastEditorWithData(id, podcast) {
                Swal.fire({
                    title: '編輯 Podcast',
                    html: `
                    <form id="podcastForm">
                        <div class="mb-3 text-start">
                            <label class="form-label">集數編號(輸入數字即可，前方 EP. 會自動加上)</label>
                            <input type="number" class="form-control" name="episode_number" value="${podcast.episode_number}" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">標題</label>
                            <input type="text" class="form-control" name="title" value="${podcast.title}" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">描述</label>
                            <textarea class="form-control" name="description" rows="3">${podcast.description || ''}</textarea>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">時長(需要輸入完整資訊，包含單位，如：35分鐘)</label>
                            <input type="text" class="form-control" name="duration" value="${podcast.duration}" placeholder="請包含單位，例如：25分鐘、1小時30分鐘" required>
                            <div class="form-text">請輸入完整時長，包含單位</div>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Podcast 連結</label>
                            <input type="url" class="form-control" name="podcast_link" value="${podcast.podcast_link || ''}" placeholder="https://...">
                            <div class="form-text">主要收聽平台的連結</div>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">發布日期</label>
                            <input type="date" class="form-control" name="release_date" value="${podcast.release_date}" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">狀態</label>
                            <select class="form-control" name="status">
                                <option value="draft" ${podcast.status === 'draft' ? 'selected' : ''}>草稿</option>
                                <option value="published" ${podcast.status === 'published' ? 'selected' : ''}>已發布</option>
                            </select>
                        </div>
                    </form>
                `,
                    showCancelButton: true,
                    confirmButtonText: '更新',
                    cancelButtonText: '取消',
                    width: '600px',
                    preConfirm: () => {
                        const form = document.getElementById('podcastForm');
                        const formData = new FormData(form);
                        formData.append('action', 'update');
                        formData.append('id', id);

                        return fetch('php/podcast_handler.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.success) {
                                    throw new Error(data.message || '操作失敗');
                                }
                                return data;
                            });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: '更新成功',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }

            function deletePodcast(id, title) {
                Swal.fire({
                    title: '確定要刪除 Podcast 嗎？',
                    text: `集數：${title}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '確定刪除',
                    cancelButtonText: '取消'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('action', 'delete');
                        formData.append('id', id);

                        fetch('php/podcast_handler.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '刪除成功',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('錯誤', data.message || '刪除失敗', 'error');
                                }
                            });
                    }
                });
            }

            // 作者管理功能
            function showAuthorEditor(id = null) {
                Swal.fire({
                    title: id ? '編輯作者' : '新增作者',
                    text: '作者編輯功能開發中...',
                    icon: 'info'
                });
            }

            function editAuthor(id) {
                showAuthorEditor(id);
            }

            function viewAuthor(id, name) {
                // 獲取作者詳細資料並顯示
                fetch(`php/author_handler.php?action=get&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            const author = data.data;
                            showAuthorDetails(author);
                        } else {
                            // 如果無法從資料庫獲取，顯示基本資訊
                            const basicInfo = {
                                name: name,
                                title: id === 1 ? '資深顧問' : '政策分析專員',
                                bio: id === 1 ? '專精政府補助提案輔導，協助眾多企業成功獲得補助資源。' : '專業的政策分析專家，深度了解各類政府補助計畫。',
                                company: '共好計畫研究室',
                                email: id === 1 ? 'consultant@example.com' : 'analyst@example.com'
                            };
                            showAuthorDetails(basicInfo);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // 顯示基本資訊作為備用
                        const basicInfo = {
                            name: name,
                            title: id === 1 ? '資深顧問' : '政策分析專員',
                            bio: id === 1 ? '專精政府補助提案輔導，協助眾多企業成功獲得補助資源。' : '專業的政策分析專家，深度了解各類政府補助計畫。',
                            company: '共好計畫研究室',
                            email: id === 1 ? 'consultant@example.com' : 'analyst@example.com'
                        };
                        showAuthorDetails(basicInfo);
                    });
            }

            function showAuthorDetails(author) {
                Swal.fire({
                    title: `${author.name} - 作者資料`,
                    html: `
                        <div class="text-start">
                            <div class="row">
                                <div class="col-12 text-center mb-3">
                                    <img src="./img/people.jpg" class="rounded-circle" width="100" height="100" alt="${author.name}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>姓名：</strong>${author.name || '未設定'}
                            </div>
                            <div class="mb-3">
                                <strong>職稱：</strong>${author.title || '未設定'}
                            </div>
                            <div class="mb-3">
                                <strong>公司：</strong>${author.company || '未設定'}
                            </div>
                            <div class="mb-3">
                                <strong>Email：</strong>${author.email || '未設定'}
                            </div>
                            <div class="mb-3">
                                <strong>個人簡介：</strong><br>
                                <div class="mt-2 p-2 bg-light rounded">${author.bio || '未設定'}</div>
                            </div>
                            ${author.specialties ? `
                            <div class="mb-3">
                                <strong>專業領域：</strong>${author.specialties}
                            </div>
                            ` : ''}
                            ${author.experience_years ? `
                            <div class="mb-3">
                                <strong>經驗年數：</strong>${author.experience_years} 年
                            </div>
                            ` : ''}
                        </div>
                    `,
                    width: '600px',
                    confirmButtonText: '關閉'
                });
            }

            // 刪除文章功能
            function deleteArticle(id, title) {
                Swal.fire({
                    title: '確定要刪除文章嗎？',
                    text: `文章：${title}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '確定刪除',
                    cancelButtonText: '取消',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 顯示刪除進度
                        Swal.fire({
                            title: '正在刪除...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        var formData = new FormData();
                        formData.append('action', 'delete');
                        formData.append('id', id);

                        fetch('php/article_handler.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '刪除成功',
                                        text: '文章已成功刪除',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '刪除失敗',
                                        text: data.message || '刪除操作失敗，請稍後再試'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: '系統錯誤',
                                    text: '網路連線錯誤，請檢查網路狀態後再試'
                                });
                            });
                    }
                });
            }

            // 刪除作者功能
            function deleteAuthor(id, name) {
                Swal.fire({
                    title: '確定要刪除作者嗎？',
                    text: `作者：${name}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '確定刪除',
                    cancelButtonText: '取消',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 顯示刪除進度
                        Swal.fire({
                            title: '正在刪除...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        var formData = new FormData();
                        formData.append('action', 'delete');
                        formData.append('id', id);

                        fetch('php/author_handler.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '刪除成功',
                                        text: '作者已成功刪除',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '刪除失敗',
                                        text: data.message || '刪除操作失敗，請稍後再試'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: '系統錯誤',
                                    text: '網路連線錯誤，請檢查網路狀態後再試'
                                });
                            });
                    }
                });
            }

            // 登出確認
            function confirmLogout() {
                Swal.fire({
                    title: '確定要登出嗎？',
                    text: '您將需要重新登入才能訪問管理後台',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    cancelButtonColor: '#0d6efd',
                    confirmButtonText: '確定登出',
                    cancelButtonText: '取消',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'php/auth.php?action=logout';
                    }
                });
                return false; // 防止默認行為
            }

            // Podcast 播放統計
            function incrementPodcastPlay(podcastId) {
                fetch('php/podcast_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=increment_plays&id=${podcastId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('播放統計已更新');
                        }
                    })
                    .catch(error => {
                        console.error('統計更新失敗:', error);
                    });
            }

            // 頁面載入完成後的初始化
            document.addEventListener('DOMContentLoaded', function() {
                // 初始化工具提示
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // 歡迎訊息（僅首次載入顯示）
                if (!sessionStorage.getItem('welcomeShown')) {
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: '歡迎回來！',
                            text: '管理後台載入完成',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                        sessionStorage.setItem('welcomeShown', 'true');
                    }, 500);
                }
            });
        </script>
</body>

</html>