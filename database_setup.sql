-- 建立資料庫
CREATE DATABASE IF NOT EXISTS consultant_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE consultant_db;

-- 建立管理員資料表
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 建立文章資料表
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    category VARCHAR(50) NOT NULL,
    author VARCHAR(100) NOT NULL,
    tags TEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_created_at (created_at)
);

-- 插入預設管理員帳號 (密碼: admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$DnjAuGE.MrrJSKF1et4aP.nnom5Zz58SuNtGASx7dU54KnXwre40q', 'admin@consultant.com');

-- 插入範例文章資料
INSERT INTO articles (title, slug, content, excerpt, featured_image, category, author, tags, status) VALUES 
('如何撰寫成功的政府補助提案書', 'how-to-write-successful-proposal', 
'<p>政府補助提案書的撰寫是一門藝術，需要結合策略思考、專業知識和溝通技巧。以下是我們多年來輔導客戶的成功經驗分享：</p>

<h3>1. 深入了解補助計畫目標</h3>
<p>在開始撰寫提案書之前，必須徹底研究補助計畫的目標、評審標準和申請條件。每個補助計畫都有其特定的政策目標，提案內容必須與這些目標高度契合。</p>

<blockquote class="blockquote bg-light p-3 border-start border-primary border-4 my-4">
<p class="mb-0">"成功的提案不是靠運氣，而是靠深度的準備和策略性的思考。"</p>
</blockquote>

<h3>2. 清楚定義計畫願景</h3>
<p>一個成功的提案必須有清晰的願景和明確的目標。要能夠簡潔地說明計畫要解決什麼問題、如何解決，以及預期達成的成果。</p>

<h4>願景設定的關鍵要素：</h4>
<ul>
<li><strong>問題識別</strong>：清楚描述要解決的核心問題</li>
<li><strong>解決方案</strong>：提出創新且可行的解決方案</li>
<li><strong>預期成果</strong>：設定具體可衡量的目標</li>
<li><strong>社會影響</strong>：說明計畫對社會的正面影響</li>
</ul>

<h3>3. 展現創新性與可行性</h3>
<p>提案內容需要在創新性和可行性之間找到平衡。既要展現新穎的想法，也要證明計畫執行的可能性。</p>

<h3>結論</h3>
<p>記住，一份好的提案書不只是文字的堆砌，而是對計畫深度思考的呈現。如果您需要專業協助，歡迎聯絡我們的團隊。</p>', 
'掌握提案書撰寫的關鍵要素，提高申請成功率。本文將分享實戰經驗與成功策略。', 
'article1.jpg', '提案技巧', '王大明', '提案書,政府補助,申請技巧,計畫撰寫', 'published'),

('2024年文化部補助計畫重點解析', '2024-culture-ministry-grants-analysis', 
'<p>深入分析今年度文化部各項補助計畫的申請條件與評審重點。本文將為您解析最新的政策方向和申請策略。</p>

<h3>主要補助項目</h3>
<p>2024年文化部推出多項重點補助計畫，包括文化創意產業發展、地方文化特色發展、數位文化內容等領域。</p>

<h3>申請重點</h3>
<p>今年度的評審特別注重計畫的創新性、可持續性以及對地方文化的貢獻。</p>', 
'深入分析今年度文化部各項補助計畫的申請條件與評審重點。', 
'article2.jpg', '政策解析', '李小華', '文化部,政府補助,政策解析', 'published'),

('地方創生計畫成功案例分享', 'local-revitalization-success-cases', 
'<p>透過實際案例，了解地方創生計畫從構想到實現的完整過程。本文分享多個成功案例的經驗與啟發。</p>

<h3>案例一：農村文化復興</h3>
<p>某農村社區透過結合傳統文化與現代觀光，成功打造特色產業鏈。</p>

<h3>案例二：海洋文化推廣</h3>
<p>沿海地區利用海洋資源，發展永續觀光與文化教育。</p>

<h3>成功關鍵因素</h3>
<p>分析各案例的共同成功要素，提供未來計畫參考。</p>', 
'透過實際案例，了解地方創生計畫從構想到實現的完整過程。', 
'article3.jpg', '成功案例', '張顧問', '地方創生,成功案例,社區營造', 'published'); 