<?php
// 在輸出任何HTML之前處理PHP
require_once 'php/config.php';
require_once 'php/article_handler.php';
require_once 'php/podcast_handler.php';

$articleHandler = new ArticleHandler();
$podcastHandler = new PodcastHandler();
$latestArticles = $articleHandler->getAllArticles('published', 3);
$latestPodcasts = $podcastHandler->getAllPodcasts('published', 5);
$featuredPodcast = !empty($latestPodcasts) ? $latestPodcasts[0] : null;

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
    <title>共好計畫研究室 - 您的專業提案顧問</title>

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
</head>

<body>
    <div class="circle-decoration">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>

    <?php include 'navbar.php'; ?>

    <!-- Hero 區域優化 -->
    <section id="home" class="hero min-vh-100 d-flex align-items-center">
        <!-- 背景圖層 -->
        <div class="hero-bg position-absolute w-100 h-100">
            <div class="overlay"></div>
            <img src="./img/hero.jpg" alt="專業團隊背景" class="w-100 h-100 object-fit-cover" id="heroBackground">
        </div>

        <!-- 內容圖層 -->
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-6">
                    <div class="hero-content text-white">
                        <h1 class="display-4 fw-bold mb-4">
                            共好計畫研究室
                            <span class="d-block h2 mt-3 fw-light">您的專業提案顧問</span>
                        </h1>
                        <p class="lead mb-5">助您獲得最佳補助資源，實現計畫願景</p>
                        <div class="d-flex gap-3">
                            <a href="#contact" class="btn btn-primary btn-lg">
                                立即諮詢 <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <a href="#services" class="btn btn-outline-light btn-lg">
                                了解服務
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="podcast-highlight text-white" data-aos="fade-left" data-aos-delay="300">
                        <?php if ($featuredPodcast): ?>
                        <div class="podcast-card bg-dark bg-opacity-50 p-4 rounded-3 backdrop-blur">
                            <div class="d-flex align-items-center mb-3">
                                <div class="podcast-icon me-3">
                                    <i class="fas fa-podcast fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">共好計畫研究室 Podcast</h5>
                                    <small class="text-light opacity-75">最新集數</small>
                                </div>
                            </div>
                            <h6 class="podcast-title mb-2">EP.<?php echo $featuredPodcast['episode_number']; ?>
                                <?php echo htmlspecialchars($featuredPodcast['title']); ?></h6>
                            <p class="podcast-description small mb-3">
                                <?php echo htmlspecialchars($featuredPodcast['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span
                                    class="badge bg-primary"><?php echo date('Y/m/d', strtotime($featuredPodcast['release_date'])); ?></span>
                                <?php if (!empty($featuredPodcast['podcast_link'])): ?>
                                <a href="<?php echo htmlspecialchars($featuredPodcast['podcast_link']); ?>"
                                    target="_blank" class="btn btn-outline-light btn-sm"
                                    onclick="incrementPodcastPlay(<?php echo $featuredPodcast['id']; ?>)">
                                    <i class="fas fa-play me-1"></i> 立即收聽
                                </a>
                                <?php else: ?>
                                <a href="#podcast" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-play me-1"></i> 立即收聽
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="podcast-card bg-dark bg-opacity-50 p-4 rounded-3 backdrop-blur">
                            <div class="d-flex align-items-center mb-3">
                                <div class="podcast-icon me-3">
                                    <i class="fas fa-podcast fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">共好計畫研究室 Podcast</h5>
                                    <small class="text-light opacity-75">敬請期待</small>
                                </div>
                            </div>
                            <h6 class="podcast-title mb-2">即將推出精彩內容</h6>
                            <p class="podcast-description small mb-3">我們正在準備豐富的 Podcast 內容，敬請期待！</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary">即將推出</span>
                                <a href="#contact" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-bell me-1"></i> 訂閱通知
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 關於我們 -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2>關於我們</h2>
                <p>共好計畫研究室的故事</p>
            </div>
            <div class="about-content">
                <div class="about-image">
                    <img src="./img/about_1.jpg" alt="團隊合影">
                </div>
                <div class="about-text">
                    <h3>品牌故事</h3>
                    <p>共好計畫研究室成立於2018年，源於我們對台灣地方創生與文化產業發展的熱忱。我們相信，每個優秀的計畫都能為社區與產業帶來長遠的價值，而專業的提案協助能讓更多夢想實現。</p>
                    <p>多年來，我們協助了數十個團隊成功獲得政府補助，並看到他們的計畫為社會帶來正面影響。這正是"共好"的核心理念—創造共同美好的未來。</p>
                    <div class="founder">
                        <div class="founder-img">
                            <img src="./img/people.jpg" alt="創辦人">
                        </div>
                        <div class="founder-info">
                            <h4>孫昱碩</h4>
                            <p>創辦人 / 共好計畫研究室 </p>
                            <p>嘉赫創業股份有限公司共同創辦人<br>前集集廊帶村落印象專案總監</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 服務內容區優化 -->
    <section id="services" class="services py-8 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">服務內容</h2>
                <p class="lead text-muted">我們提供全方位的專業輔導方案</p>
            </div>

            <div class="row g-4">
                <!-- 提案顧問輔導卡片 -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon mb-4">
                            <i class="fas fa-file-signature fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-4">提案顧問輔導</h3>
                        <div class="price-tag mb-4">
                            <span class="h3 fw-bold">40,000</span>
                            <span class="text-muted">元 / 案</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                資源盤點與分析
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                提案策略規劃
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                計畫書撰寫指導
                            </li>
                        </ul>
                        <a href="#contact" class="btn btn-primary w-100">立即諮詢</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon mb-4">
                            <i class="fas fa-file-signature fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-4">提案顧問輔導</h3>
                        <div class="price-tag mb-4">
                            <span class="h3 fw-bold">40,000</span>
                            <span class="text-muted">元 / 案</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                資源盤點與分析
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                提案策略規劃
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                計畫書撰寫指導
                            </li>
                        </ul>
                        <a href="#contact" class="btn btn-primary w-100">立即諮詢</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon mb-4">
                            <i class="fas fa-file-signature fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-4">提案顧問輔導</h3>
                        <div class="price-tag mb-4">
                            <span class="h3 fw-bold">40,000</span>
                            <span class="text-muted">元 / 案</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                資源盤點與分析
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                提案策略規劃
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-primary me-2"></i>
                                計畫書撰寫指導
                            </li>
                        </ul>
                        <a href="#contact" class="btn btn-primary w-100">立即諮詢</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 主要優勢 -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>我們的優勢</h2>
                <p>為什麼選擇共好計畫研究室</p>
            </div>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon"><i class="fas fa-award"></i></div>
                    <h3>政府補助提案專家</h3>
                    <p>成功輔導多項地方創生與產業計畫，豐富的提案經驗</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                    <h3>完整輔導流程</h3>
                    <p>從策略諮詢到簡報製作，全方位協助您獲得最大成功機會</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                    <h3>成功案例豐富</h3>
                    <p>與政府、企業、學術機構長期合作，累積多項成功經驗</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <h3>高效溝通與專業團隊</h3>
                    <p>快速理解需求，量身打造專屬計畫書，提高申請成功率</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 成功案例區塊 -->
    <section id="cases" class="cases py-8">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <div class="d-flex justify-content-center align-items-center mb-3 position-relative">
                    <h2 class="display-6 fw-bold mb-0">成功案例</h2>
                    <a href="success-cases.php" class="btn btn-outline-primary position-absolute" style="right: 0;">
                        查看更多 <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <p class="lead text-muted">我們的專業實績</p>
            </div>

            <div class="row g-4">
                <!-- 講座資歷案例 -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="case-card">
                        <div class="case-image">
                            <img src="./img/success1.jpg" alt="講座案例">
                            <div class="case-overlay">
                                <span class="badge bg-primary">講座資歷</span>
                            </div>
                        </div>
                        <div class="case-content">
                            <h3>計畫書撰寫實戰工作坊-生成式AI工具課程</h3>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-calendar me-1"></i> 2025年
                                <i class="fas fa-map-marker-alt ms-2 me-1"></i> 勞動力發展署雲嘉南分署
                            </p>
                            <p>結合AI科技與傳統提案技巧，提升計畫書撰寫效率與品質</p>
                            <ul class="case-highlights">
                                <li>AI工具應用教學</li>
                                <li>撰寫效率提升</li>
                                <li>創新教學模式</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 輔導單位案例 -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="case-card">
                        <div class="case-image">
                            <img src="./img/success2.jpg" alt="輔導單位案例">
                            <div class="case-overlay">
                                <span class="badge bg-success">輔導單位</span>
                            </div>
                        </div>
                        <div class="case-content">
                            <h3>南投縣水里鄉永興牛轀轆社區發展協會</h3>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> 南投縣水里鄉
                                <i class="fas fa-users ms-2 me-1"></i> 社區發展協會
                            </p>
                            <p>協助社區發展協會建立永續經營模式，推動在地文化保存與產業發展</p>
                            <ul class="case-highlights">
                                <li>社區營造規劃</li>
                                <li>文化保存推廣</li>
                                <li>產業轉型輔導</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 輔導計畫案例 1 -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="case-card">
                        <div class="case-image">
                            <img src="./img/success3.jpg" alt="輔導計畫案例">
                            <div class="case-overlay">
                                <span class="badge bg-warning">輔導計畫</span>
                            </div>
                        </div>
                        <div class="case-content">
                            <h3>地方創生事業構想書</h3>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-building me-1"></i> 國家發展委員會
                                <i class="fas fa-tag ms-2 me-1"></i> 地方創生
                            </p>
                            <p>協助地方團隊規劃創生事業構想，打造可持續發展的地方特色產業</p>
                            <ul class="case-highlights">
                                <li>在地資源盤點</li>
                                <li>創生策略規劃</li>
                                <li>商業模式設計</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 輔導計畫案例 2 -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="case-card">
                        <div class="case-image">
                            <img src="./img/success4.jpg" alt="輔導計畫案例">
                            <div class="case-overlay">
                                <span class="badge bg-warning">輔導計畫</span>
                            </div>
                        </div>
                        <div class="case-content">
                            <h3>小型企業創新研發計畫(SBIR)</h3>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-building me-1"></i> 經濟部
                                <i class="fas fa-tag ms-2 me-1"></i> 創新研發
                            </p>
                            <p>輔導中小企業申請SBIR計畫，提升技術創新能力與競爭優勢</p>
                            <ul class="case-highlights">
                                <li>技術創新規劃</li>
                                <li>研發策略制定</li>
                                <li>產業競爭分析</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 創作足跡區塊 -->
    <section id="courses" class="courses py-8">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">創作足跡</h2>
                <p class="lead text-muted">分享專業知識，傳承實戰經驗</p>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="course-image">
                        <img src="./img/hahow-course.jpg" alt="Hahow線上課程" class="img-fluid rounded-3 shadow-lg">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="course-content ps-lg-4">
                        <div class="platform-badge mb-3">
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-graduation-cap me-2"></i>Hahow 好學校
                            </span>
                        </div>
                        <h3 class="h2 fw-bold mb-4">政府補助提案實戰課程</h3>
                        <p class="lead text-muted mb-4">
                            從零開始學習政府補助申請，掌握提案書撰寫技巧，提高申請成功率的完整課程。
                        </p>
                        <div class="course-highlights mb-4">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="highlight-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>6小時完整教學</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="highlight-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>實戰案例分析</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="highlight-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>提案書模板下載</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="highlight-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>永久觀看權限</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="course-stats mb-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat">
                                        <h4 class="fw-bold text-primary mb-1">1200+</h4>
                                        <small class="text-muted">學員人數</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat">
                                        <h4 class="fw-bold text-primary mb-1">4.9</h4>
                                        <small class="text-muted">課程評分</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat">
                                        <h4 class="fw-bold text-primary mb-1">85%</h4>
                                        <small class="text-muted">完課率</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="course-actions">
                            <a target="_blank" href="https://hahow.in/cr/co-hands" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-external-link-alt me-2"></i>前往 Hahow
                            </a>
                            <a href="#contact" class="btn btn-outline-primary btn-lg">
                                諮詢課程
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Podcast 區塊 -->
    <section id="podcast" class="podcast py-8 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">共好計畫研究室 Podcast</h2>
                <p class="lead text-muted">每週分享提案技巧與政府補助最新資訊</p>
            </div>

            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="podcast-intro">
                        <div class="podcast-cover mb-4">
                            <img src="./img/podcast-cover.jpg" alt="Podcast封面" class="img-fluid rounded-3 shadow">
                        </div>
                        <div class="podcast-stats">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat">
                                        <h4 class="fw-bold text-primary mb-1"><?php echo count($latestPodcasts); ?></h4>
                                        <small class="text-muted">總集數</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat">
                                        <h4 class="fw-bold text-primary mb-1">
                                            <?php echo number_format(array_sum(array_column($latestPodcasts, 'play_count'))); ?>+
                                        </h4>
                                        <small class="text-muted">總收聽</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat">
                                        <h4 class="fw-bold text-primary mb-1">4.8</h4>
                                        <small class="text-muted">平均評分</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="podcast-episodes ps-lg-4">
                        <h3 class="h4 fw-bold mb-4">最新集數</h3>

                        <?php if (!empty($latestPodcasts)): ?>
                        <?php foreach (array_slice($latestPodcasts, 0, 3) as $index => $podcast): ?>
                        <div class="episode-card mb-3">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                <div class="episode-number me-3">
                                    <span
                                        class="badge <?php echo $index === 0 ? 'bg-primary' : 'bg-secondary'; ?> fs-6">
                                        EP.<?php echo $podcast['episode_number']; ?>
                                    </span>
                                </div>
                                <div class="episode-info flex-grow-1">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($podcast['title']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo date('Y/m/d', strtotime($podcast['release_date'])); ?> •
                                        <?php echo htmlspecialchars($podcast['duration']); ?>
                                    </small>
                                </div>
                                <div class="episode-action">
                                    <?php if (!empty($podcast['podcast_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($podcast['podcast_link']); ?>" target="_blank"
                                        class="btn btn-outline-primary btn-sm"
                                        onclick="incrementPodcastPlay(<?php echo $podcast['id']; ?>)">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    <?php else: ?>
                                    <button class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-podcast fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">即將推出精彩內容</h6>
                            <p class="text-muted small">我們正在準備豐富的 Podcast 內容，敬請期待！</p>
                        </div>
                        <?php endif; ?>

                        <div class="podcast-platforms">
                            <h6 class="mb-3">收聽平台</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="https://podcasts.apple.com/tw/podcast/%E7%A2%A9%E5%93%A5%E9%96%92%E8%81%8A-s2-ep1-%E7%A2%A9%E5%93%A5%E7%9A%84%E9%A1%A7%E5%95%8F%E7%AD%86%E8%A8%98%E7%AC%AC%E4%BA%8C%E5%AD%A3%E9%96%8B%E5%BC%B5/id1675756298?i=1000688582724"
                                    target="_blank" class="btn btn-outline-dark btn-sm">
                                    <i class="fab fa-apple me-1"></i>Apple Podcasts
                                </a>
                                <a href="https://open.spotify.com/episode/1CX04j42Kunsj24krXtaYU" target="_blank"
                                    class="btn btn-outline-dark btn-sm">
                                    <i class="fab fa-spotify me-1"></i>Spotify
                                </a>
                                <a href="https://podcast.kkbox.com/tw/episode/4sh2ILqsoBfxWpZvCW" target="_blank"
                                    class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-music me-1"></i>KKBOX
                                </a>
                                <a href="https://pay.soundon.fm/podcasts/7bfcebcc-c411-465d-a1e5-8b1174d44c6a"
                                    target="_blank" class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-headphones me-1"></i>SoundOn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 文章專區區塊 -->
    <section id="articles" class="articles-preview py-8 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">最新文章</h2>
                <p class="lead text-muted">分享提案經驗與政府補助相關知識</p>
            </div>

            <div class="row g-4">
                <?php
                $delay = 100;
                foreach ($latestArticles as $article):
                    $categoryColor = $categoryColors[$article['category']] ?? 'bg-secondary';
                    $publishDate = date('Y年n月j日', strtotime($article['created_at']));
                    $featuredImage = !empty($article['featured_image']) ? 'uploads/' . $article['featured_image'] : './img/article-default.jpg';
                ?>
                <!-- 文章卡片 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="<?php echo htmlspecialchars($featuredImage); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($article['title']); ?>"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <span
                                class="badge <?php echo $categoryColor; ?> mb-2 align-self-start"><?php echo htmlspecialchars($article['category']); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <div class="mt-auto">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> <?php echo $publishDate; ?>
                                    <i class="fas fa-user ms-3 me-1"></i>
                                    <?php echo htmlspecialchars($article['author']); ?>
                                </small>
                                <div class="mt-3">
                                    <a href="article.php?id=<?php echo $article['id']; ?>"
                                        class="btn btn-outline-primary">閱讀更多</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    $delay += 100;
                endforeach;

                // 如果沒有文章，顯示預設內容
                if (empty($latestArticles)):
                ?>
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">尚無文章發布</h5>
                        <p class="text-muted">管理員可以透過後台新增文章</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- 查看更多按鈕 -->
            <?php if (!empty($latestArticles)): ?>
            <div class="row mt-5">
                <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="400">
                    <a href="articles.php" class="btn btn-primary btn-lg">
                        查看所有文章 <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- 常見問題 -->
    <section id="faq" class="faq py-8">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-6 fw-bold mb-3" data-aos="fade-up">常見問題</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">解答您可能有的疑問</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="accordion" id="faqAccordion" data-aos="fade-up" data-aos-delay="200">
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                    提案輔導的流程是怎樣的？
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>我們的提案輔導通常經過四個階段：</p>
                                    <ol>
                                        <li>初步諮詢與需求分析</li>
                                        <li>資源盤點與提案策略制定</li>
                                        <li>計畫書撰寫與修改</li>
                                        <li>簡報製作與提案練習</li>
                                    </ol>
                                    <p>整個流程約需3-4週完成。</p>
                                </div>
                            </div>
                        </div>
                        <!-- 其他FAQ項目 -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 聯絡我們 -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2>聯絡我們</h2>
                <p>讓我們開始為您的計畫提供專業協助</p>
            </div>
            <div class="contact-content">
                <div class="contact-form">
                    <h3>免費諮詢評估</h3>
                    <form id="consultForm">
                        <div class="form-group">
                            <label for="name">姓名</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">聯絡電話</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="projectType">計畫類型</label>
                            <select id="projectType" name="projectType">
                                <option value="">請選擇</option>
                                <option value="culture">文化創意</option>
                                <option value="tourism">觀光旅遊</option>
                                <option value="community">社區營造</option>
                                <option value="startup">創業提案</option>
                                <option value="other">其他</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">需求描述</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">提交諮詢</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 行動呼籲區域 -->
    <section class="cta-section py-8 text-white position-relative overflow-hidden">
        <div class="container position-relative">
            <div class="row justify-content-center text-center">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">準備好開始您的提案之旅了嗎？</h2>
                    <p class="lead mb-5">讓共好計畫研究室協助您實現計畫目標，獲得最適合的補助資源</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="#contact" class="btn btn-light btn-lg px-5">
                            立即諮詢 <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="https://line.me/ti/p/gtswill" target="_blank"
                            class="btn btn-outline-light btn-lg px-5">
                            加入LINE好友 <i class="fab fa-line ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- 背景裝飾 -->
        <div class="cta-decoration">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
        </div>
    </section>

    <!-- 頁尾 -->
    <?php include 'footer.php'; ?>

    <!-- 悬浮联系按钮 -->
    <div class="floating-contact">
        <a href="#contact" class="contact-btn"><i class="fas fa-comments"></i></a>
        <a href="https://line.me/ti/p/gtswill" target="_blank" class="line-btn"><i class="fab fa-line"></i></a>
    </div>

    <!-- 添加JS文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init();

    // 視差滾動效果
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroBackground = document.getElementById('heroBackground');
        if (heroBackground) {
            const speed = scrolled * 0.3;
            heroBackground.style.transform = `translateY(${speed}px)`;
        }
    });

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
    </script>
</body>

</html>