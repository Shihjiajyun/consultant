<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成功案例 - 共好計畫研究室</title>

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
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/success-cases.css">
</head>

<body>
    <div class="circle-decoration">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>
    <!-- 導航欄 -->
    <?php include 'navbar.php'; ?>

    <!-- 頁面頂部 -->
    <section class="page-header">
        <!-- 背景圖層 -->
        <div class="page-header-bg">
            <div class="overlay"></div>
            <img src="./img/hero.jpg" alt="專業團隊背景" class="w-100 h-100 object-fit-cover" id="heroBackground">
        </div>

        <!-- 內容圖層 -->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-header-content">
                        <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">成功案例</h1>
                        <p class="lead mb-5" data-aos="fade-up" data-aos-delay="100">探索我們的專業實績與服務成果</p>
                    </div>

                    <!-- 統計數據區塊 -->
                    <div class="stats-banner" data-aos="fade-up" data-aos-delay="200">
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number" data-target="44">0</div>
                                    <div class="stat-label">講座經歷</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number" data-target="45">0</div>
                                    <div class="stat-label">輔導單位</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number" data-target="50">0</div>
                                    <div class="stat-label">輔導計畫</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-item">
                                    <div class="stat-number" data-target="7">0</div>
                                    <div class="stat-label">服務年度</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 主要內容區域 -->
    <main class="main-content">
        <div class="container">
            <!-- 篩選與搜尋工具列 -->
            <div class="filter-toolbar" data-aos="fade-up">
                <div class="filter-row">
                    <div class="filter-col search-col">
                        <div class="filter-section">
                            <label for="searchKeyword">關鍵字搜尋</label>
                            <input type="text" id="searchKeyword" class="form-control" placeholder="搜尋計畫名稱、單位名稱...">
                        </div>
                    </div>
                    <div class="filter-col">
                        <div class="filter-section">
                            <label for="categoryFilter">類別</label>
                            <select id="categoryFilter" class="form-select">
                                <option value="">全部</option>
                                <option value="講座資歷">講座資歷</option>
                                <option value="輔導單位">輔導單位</option>
                                <option value="輔導計畫">輔導計畫</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-col">
                        <div class="filter-section">
                            <label for="yearFilter">年份</label>
                            <select id="yearFilter" class="form-select">
                                <option value="">全部</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-col">
                        <div class="filter-section">
                            <label for="sortFilter">排序</label>
                            <select id="sortFilter" class="form-select">
                                <option value="newest">最新優先</option>
                                <option value="oldest">最舊優先</option>
                                <option value="alphabetical">按字母排序</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-col button-col">
                        <div class="filter-section">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-search me-1"></i> 搜尋
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 案例列表 -->
            <div id="casesContainer" class="row g-4" data-aos="fade-up" data-aos-delay="200">
                <!-- 案例卡片將由JavaScript動態生成 -->
            </div>

            <!-- 分頁 -->
            <div class="pagination-wrapper">
                <nav aria-label="成功案例分頁">
                    <ul class="pagination pagination-lg justify-content-center" id="pagination">
                        <!-- 分頁項目將由JavaScript動態生成 -->
                    </ul>
                </nav>
            </div>
        </div>
    </main>

    <!-- 頁尾 -->
    <?php include 'footer.php'; ?>

    <!-- 成功案例數據 -->
    <script src="./js/success-cases-data.js"></script>
    <script>
        // 全局變量
        let currentPage = 1;
        const itemsPerPage = 12;
        let filteredCases = [...allCasesData];

        // 生成案例卡片HTML
        function generateCaseCard(caseItem, index) {
            const imageNumber = (index % 8) + 1;
            const badgeClass = caseItem.category === '講座資歷' ? 'bg-primary' :
                caseItem.category === '輔導單位' ? 'bg-success' : 'bg-warning';

            const metaInfo = caseItem.organization ?
                `<i class="fas fa-building me-1"></i> ${caseItem.organization}` :
                `<i class="fas fa-map-marker-alt me-1"></i> ${caseItem.location || ''}`;

            const yearInfo = caseItem.year ?
                `<i class="fas fa-calendar ms-2 me-1"></i> ${caseItem.year}` : '';

            return `
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="${(index % 6) * 100}">
                    <div class="case-card">
                        <div class="case-image">
                            <img src="./img/success${imageNumber}.jpg" alt="${caseItem.title}">
                            <div class="case-overlay">
                                <span class="badge ${badgeClass}">${caseItem.category}</span>
                            </div>
                        </div>
                        <div class="case-content">
                            <h3>${caseItem.title}</h3>
                            <div class="case-meta">
                                ${metaInfo}${yearInfo}
                            </div>
                            <p class="case-description">${caseItem.description}</p>
                            <div class="case-tags">
                                ${caseItem.tags.map(tag => `<span class="case-tag">${tag}</span>`).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // 渲染案例列表
        function renderCases() {
            const container = document.getElementById('casesContainer');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentCases = filteredCases.slice(startIndex, endIndex);

            if (currentCases.length === 0) {
                container.innerHTML = `
                    <div class="col-12 no-results">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">沒有找到符合條件的案例</h4>
                        <p class="text-muted">請嘗試調整搜尋條件</p>
                    </div>
                `;
            } else {
                container.innerHTML = currentCases.map((caseItem, index) =>
                    generateCaseCard(caseItem, startIndex + index)
                ).join('');
            }

            // 重新初始化AOS動畫
            AOS.refresh();
        }

        // 生成分頁
        function renderPagination() {
            const totalPages = Math.ceil(filteredCases.length / itemsPerPage);
            const pagination = document.getElementById('pagination');

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // 上一頁
            paginationHTML += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;

            // 頁碼
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                        </li>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            // 下一頁
            paginationHTML += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;

            pagination.innerHTML = paginationHTML;
        }

        // 更改頁面
        function changePage(page) {
            const totalPages = Math.ceil(filteredCases.length / itemsPerPage);
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderCases();
                renderPagination();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        // 應用篩選
        function applyFilters() {
            const keyword = document.getElementById('searchKeyword').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const year = document.getElementById('yearFilter').value;
            const sort = document.getElementById('sortFilter').value;

            // 篩選數據
            filteredCases = allCasesData.filter(caseItem => {
                const matchKeyword = !keyword ||
                    caseItem.title.toLowerCase().includes(keyword) ||
                    (caseItem.organization && caseItem.organization.toLowerCase().includes(keyword)) ||
                    (caseItem.location && caseItem.location.toLowerCase().includes(keyword));

                const matchCategory = !category || caseItem.category === category;
                const matchYear = !year || caseItem.year === year;

                return matchKeyword && matchCategory && matchYear;
            });

            // 排序
            if (sort === 'newest') {
                filteredCases.sort((a, b) => (b.year || '0').localeCompare(a.year || '0'));
            } else if (sort === 'oldest') {
                filteredCases.sort((a, b) => (a.year || '0').localeCompare(b.year || '0'));
            } else if (sort === 'alphabetical') {
                filteredCases.sort((a, b) => a.title.localeCompare(b.title));
            }

            currentPage = 1;
            renderCases();
            renderPagination();
        }

        // 數字動畫效果
        function animateNumbers() {
            const statNumbers = document.querySelectorAll('.stat-number');

            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                const duration = 2000; // 2秒
                let start = 0;
                const increment = target / (duration / 16); // 60fps

                function updateNumber() {
                    start += increment;
                    if (start < target) {
                        stat.textContent = Math.floor(start);
                        requestAnimationFrame(updateNumber);
                    } else {
                        stat.textContent = target;
                    }
                }

                updateNumber();
            });
        }

        // 初始化頁面
        document.addEventListener('DOMContentLoaded', function() {
            renderCases();
            renderPagination();

            // 監聽回車鍵搜尋
            document.getElementById('searchKeyword').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });

            // 視差滾動效果
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const heroBackground = document.getElementById('heroBackground');
                if (heroBackground) {
                    const speed = scrolled * 0.3;
                    heroBackground.style.transform = `translateY(${speed}px)`;
                }
            });

            // 頁面載入後直接開始數字動畫
            setTimeout(animateNumbers, 500);
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>

</html>