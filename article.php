<?php
require_once 'php/config.php';
require_once 'php/article_handler.php';

$articleHandler = new ArticleHandler();

// 獲取文章ID
$articleId = $_GET['id'] ?? 0;

if (!$articleId) {
    header('Location: index.php');
    exit;
}

// 獲取文章資料
$article = $articleHandler->getArticleById($articleId);

if (!$article || $article['status'] !== 'published') {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// 獲取相關文章
$relatedArticles = $articleHandler->getRelatedArticles($article['category'], $article['id'], 3);

// 處理標籤
$tags = !empty($article['tags']) ? explode(',', $article['tags']) : [];

// 格式化日期
$publishDate = date('Y年n月j日', strtotime($article['created_at']));
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - 共好計畫研究室</title>
    <meta name="description" content="<?php echo htmlspecialchars($article['excerpt']); ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入繁體中文字體 -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <!-- Font Awesome 圖標 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- 自定義樣式 -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/article.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- 文章內容 -->
    <main class="py-5" style="margin-top: 76px;">
        <div class="container">
            <div class="row">
                <!-- 文章主體 -->
                <div class="col-lg-8">
                    <article class="article-content">
                        <!-- 麵包屑導航 -->
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">首頁</a></li>
                                <li class="breadcrumb-item"><a href="articles.php">文章專區</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo htmlspecialchars($article['title']); ?></li>
                            </ol>
                        </nav>

                        <!-- 文章標題與元資訊 -->
                        <header class="article-header mb-4">
                            <div class="mb-3">
                                <span
                                    class="badge bg-primary me-2"><?php echo htmlspecialchars($article['category']); ?></span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> <?php echo $publishDate; ?>
                                    <i class="fas fa-user ms-3 me-1"></i>
                                    <?php echo htmlspecialchars($article['author']); ?>
                                    <i class="fas fa-eye ms-3 me-1"></i> <?php echo number_format($article['views']); ?>
                                    次閱讀
                                </small>
                            </div>
                            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                            <p class="lead text-muted"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                        </header>

                        <!-- 特色圖片 -->
                        <?php if (!empty($article['featured_image'])): ?>
                            <div class="article-image mb-4">
                                <img src="uploads/<?php echo htmlspecialchars($article['featured_image']); ?>"
                                    class="img-fluid rounded" alt="<?php echo htmlspecialchars($article['title']); ?>">
                            </div>
                        <?php endif; ?>

                        <!-- 文章內容 -->
                        <div class="article-body">
                            <?php echo $article['content']; ?>
                        </div>

                        <!-- 文章標籤 -->
                        <?php if (!empty($tags)): ?>
                            <div class="article-tags mt-5 pt-4 border-top">
                                <h6 class="mb-3">相關標籤：</h6>
                                <?php foreach ($tags as $tag): ?>
                                    <span
                                        class="badge bg-light text-dark me-2 mb-2"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- 分享按鈕 -->
                        <div class="article-share mt-4 pt-4 border-top">
                            <h6 class="mb-3">分享文章：</h6>
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="shareToFacebook()">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </button>
                            <button class="btn btn-outline-info btn-sm me-2" onclick="shareToLine()">
                                <i class="fab fa-line me-1"></i>LINE
                            </button>
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="copyLink()">
                                <i class="fas fa-link me-1"></i>複製連結
                            </button>
                        </div>
                    </article>
                </div>

                <!-- 側邊欄 -->
                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <!-- 作者資訊 - 簡化版 -->
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <img src="./img/people.jpg" class="rounded-circle mb-3" width="80" height="80" alt="作者頭像">
                                <h5 class="mb-1"><?php echo htmlspecialchars($article['author']); ?></h5>
                                <p class="text-muted small mb-2">專業顧問</p>
                                <p class="small">專精政府補助提案輔導，協助企業成功獲得補助資源。</p>
                            </div>
                        </div>

                        <!-- 推薦文章 - 熱門文章 -->
                        <?php
                        // 獲取熱門文章（按瀏覽數排序）
                        $popularArticles = $articleHandler->getPopularArticles(5, $article['id']);
                        ?>
                        <?php if (!empty($popularArticles)): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-fire me-2"></i>熱門推薦</h6>
                                </div>
                                <div class="card-body p-0">
                                    <?php foreach ($popularArticles as $index => $popular): ?>
                                        <a href="article.php?id=<?php echo $popular['id']; ?>"
                                            class="d-block p-3 text-decoration-none <?php echo $index < count($popularArticles) - 1 ? 'border-bottom' : ''; ?>">
                                            <div class="fw-bold text-dark small mb-1">
                                                <?php echo htmlspecialchars($popular['title']); ?>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-eye me-1"></i><?php echo number_format($popular['views']); ?> 次閱讀
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar me-1"></i><?php echo date('m/d', strtotime($popular['created_at'])); ?>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- 相關文章 - 同分類 -->
                        <?php if (!empty($relatedArticles)): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-tags me-2"></i>相關文章</h6>
                                </div>
                                <div class="card-body p-0">
                                    <?php foreach ($relatedArticles as $index => $related): ?>
                                        <a href="article.php?id=<?php echo $related['id']; ?>"
                                            class="d-block p-3 text-decoration-none <?php echo $index < count($relatedArticles) - 1 ? 'border-bottom' : ''; ?>">
                                            <div class="fw-bold text-dark small mb-1">
                                                <?php echo htmlspecialchars($related['title']); ?>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($related['category']); ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar me-1"></i><?php echo date('m/d', strtotime($related['created_at'])); ?>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- 聯絡我們 - 簡化版 -->
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="card-title"><i class="fas fa-headset me-2"></i>需要協助？</h6>
                                <p class="card-text small">提供專業提案輔導服務</p>
                                <a href="index.php#contact" class="btn btn-primary btn-sm">
                                    <i class="fas fa-phone me-1"></i>立即諮詢
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 進度條 -->
    <div class="reading-progress"></div>

    <!-- 回到頂部按鈕 -->
    <button class="back-to-top" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 分享到Facebook
        function shareToFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '_blank', 'width=600,height=400');
        }

        // 分享到LINE
        function shareToLine() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent('<?php echo htmlspecialchars($article['title']); ?>');
            window.open('https://social-plugins.line.me/lineit/share?url=' + url + '&text=' + text, '_blank',
                'width=600,height=400');
        }

        // 複製連結
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('連結已複製到剪貼簿！');
            }).catch(function() {
                // 備用方法
                var textArea = document.createElement('textarea');
                textArea.value = window.location.href;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('連結已複製到剪貼簿！');
            });
        }

        // 回到頂部功能
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // 閱讀進度條和回到頂部按鈕
        window.addEventListener('scroll', function() {
            // 計算閱讀進度
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;

            // 更新進度條
            document.querySelector('.reading-progress').style.width = scrolled + '%';

            // 顯示/隱藏回到頂部按鈕
            const backToTop = document.querySelector('.back-to-top');
            if (winScroll > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        // 增加文章瀏覽數（頁面載入後延遲執行）
        window.addEventListener('load', function() {
            setTimeout(function() {
                fetch('php/article_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=increment_views&id=<?php echo $article['id']; ?>'
                }).catch(error => console.log('瀏覽數更新失敗:', error));
            }, 2000); // 2秒後執行，確保用戶真的在閱讀
        });

        // 平滑滾動到錨點
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>