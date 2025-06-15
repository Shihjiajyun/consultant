<?php
require_once 'php/config.php';
require_once 'php/article_handler.php';

$articleHandler = new ArticleHandler();

// 處理搜索和篩選
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 9; // 每頁顯示9篇文章

// 獲取所有分類
$categories = $articleHandler->getCategories();

// 獲取文章總數（用於分頁）
$totalArticles = $articleHandler->getArticleCount('published', $search, $category);
$totalPages = ceil($totalArticles / $perPage);

// 獲取當前頁面的文章
$articles = $articleHandler->getArticlesPaginated('published', $page, $perPage, $search, $category);

// 分類顏色映射
$categoryColors = [
    '提案技巧' => 'bg-primary',
    '政策解析' => 'bg-success',
    '成功案例' => 'bg-info',
    '常見問題' => 'bg-warning',
    '實務經驗' => 'bg-secondary'
];
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章專區 - 共好計畫研究室</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS 動畫效果 -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- 引入繁體中文字體 -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <!-- Font Awesome 圖標 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- 自定義樣式 -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/articles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Hero 區域 -->
    <section class="articles-hero">
        <div class="container">
            <div class="row justify-content-center text-center text-white">
                <div class="col-lg-8" data-aos="fade-up">
                    <h1 class="display-4 fw-bold mb-4">文章專區</h1>
                    <p class="lead mb-0">分享提案經驗與政府補助相關知識，助您提升申請成功率</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 搜索和篩選區域 -->
    <section class="py-5">
        <div class="container">
            <div class="search-filter-section mt-3" data-aos="fade-up" data-aos-delay="200">
                <form method="GET" action="">
                    <div class="row g-4 align-items-end">
                        <!-- 搜索框 -->
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-search me-2"></i>搜索文章
                            </label>
                            <input type="text" name="search" class="form-control search-input"
                                placeholder="輸入關鍵字搜索文章..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>

                        <!-- 分類篩選 -->
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-filter me-2"></i>文章分類
                            </label>
                            <select name="category" class="form-select search-input">
                                <option value="">所有分類</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>"
                                        <?php echo $category === $cat ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- 搜索按鈕 -->
                        <div class="col-lg-2">
                            <button type="submit" class="btn search-btn w-100">
                                <i class="fas fa-search me-2"></i>搜索
                            </button>
                        </div>
                    </div>
                </form>

                <!-- 快速分類篩選 -->
                <div class="mt-4">
                    <div class="d-flex flex-wrap justify-content-center">
                        <a href="?" class="filter-btn <?php echo empty($category) ? 'active' : ''; ?>">
                            <i class="fas fa-th-large me-1"></i>全部
                        </a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="?category=<?php echo urlencode($cat); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"
                                class="filter-btn <?php echo $category === $cat ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($cat); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- 統計資訊 -->
            <div class="stats-section" data-aos="fade-up" data-aos-delay="300">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $totalArticles; ?></div>
                            <div class="stat-label">總文章數</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo count($categories); ?></div>
                            <div class="stat-label">文章分類</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo array_sum(array_column($articles, 'views')); ?></div>
                            <div class="stat-label">總閱讀量</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 文章列表 -->
    <section class="py-5">
        <div class="container">
            <?php if (!empty($articles)): ?>
                <div class="row g-4">
                    <?php
                    $delay = 100;
                    foreach ($articles as $article):
                        $categoryColor = $categoryColors[$article['category']] ?? 'bg-secondary';
                        $publishDate = date('Y年n月j日', strtotime($article['created_at']));
                        $featuredImage = !empty($article['featured_image']) ? 'uploads/' . $article['featured_image'] : './img/article1.jpg';
                    ?>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="card article-card">
                                <div class="article-image">
                                    <img src="<?php echo htmlspecialchars($featuredImage); ?>"
                                        alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <span class="badge category-badge <?php echo $categoryColor; ?>">
                                        <?php echo htmlspecialchars($article['category']); ?>
                                    </span>
                                </div>
                                <div class="article-content">
                                    <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                    <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                                    <div class="article-meta">
                                        <span>
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo $publishDate; ?>
                                        </span>
                                        <span class="ms-auto">
                                            <i class="fas fa-eye me-1"></i>
                                            <?php echo number_format($article['views']); ?> 次閱讀
                                        </span>
                                    </div>
                                    <div class="article-meta mb-3">
                                        <span>
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($article['author']); ?>
                                        </span>
                                    </div>
                                    <a href="article.php?id=<?php echo $article['id']; ?>" class="btn read-more-btn">
                                        閱讀全文 <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                        $delay += 50;
                    endforeach;
                    ?>
                </div>

                <!-- 分頁 -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="文章分頁" data-aos="fade-up" data-aos-delay="400">
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);

                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link"
                                        href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php else: ?>
                <!-- 無文章時的顯示 -->
                <div class="no-articles" data-aos="fade-up">
                    <i class="fas fa-file-alt"></i>
                    <h3>找不到相關文章</h3>
                    <p class="mb-4">
                        <?php if ($search || $category): ?>
                            請嘗試調整搜索條件或瀏覽其他分類
                        <?php else: ?>
                            目前還沒有發布任何文章，敬請期待！
                        <?php endif; ?>
                    </p>
                    <?php if ($search || $category): ?>
                        <a href="articles.php" class="btn search-btn">
                            <i class="fas fa-refresh me-2"></i>查看所有文章
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- 頁尾 -->
    <?php include 'footer.php'; ?>

    <!-- 悬浮联系按钮 -->
    <div class="floating-contact">
        <a href="index.php#contact" class="contact-btn"><i class="fas fa-comments"></i></a>
        <a href="https://line.me/ti/p/gtswill" target="_blank" class="line-btn"><i class="fab fa-line"></i></a>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });

        // 平滑滾動
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