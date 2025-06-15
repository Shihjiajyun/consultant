-- 建立 Podcast 資料表
CREATE TABLE IF NOT EXISTS podcasts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    episode_number INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    duration VARCHAR(20) NOT NULL,
    release_date DATE NOT NULL,
    status ENUM('draft', 'published') DEFAULT 'published',
    audio_url VARCHAR(500),
    podcast_link VARCHAR(500),
    cover_image VARCHAR(255),
    view_count INT DEFAULT 0,
    play_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_release_date (release_date),
    INDEX idx_episode_number (episode_number)
);

-- 建立 Podcast 統計資料表
CREATE TABLE IF NOT EXISTS podcast_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_episodes INT DEFAULT 0,
    total_listens INT DEFAULT 0,
    average_rating DECIMAL(3,2) DEFAULT 0.00,
    total_views INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 插入預設 Podcast 資料
INSERT INTO podcasts (episode_number, title, description, duration, release_date, status) VALUES 
(15, '政府補助申請的成功秘訣', '分享如何提高政府補助申請成功率，從計畫書撰寫到面試技巧的完整攻略。', '25分鐘', '2024-01-15', 'published'),
(14, '地方創生計畫實戰分享', '深入探討地方創生計畫的實際執行經驗，包含資源整合、社區參與及永續經營策略。', '32分鐘', '2024-01-08', 'published'),
(13, 'SBIR申請完整攻略', '詳細解析小型企業創新研發計畫的申請流程、評審重點及成功案例分享。', '28分鐘', '2024-01-01', 'published'),
(12, '文化創意產業補助解析', '解讀最新文化創意產業補助政策，分析申請要點與成功策略。', '30分鐘', '2023-12-25', 'published'),
(11, '新創團隊資金募集策略', '探討新創企業如何透過政府資源與民間投資獲得營運資金。', '35分鐘', '2023-12-18', 'published');

-- 插入預設統計資料
INSERT INTO podcast_stats (total_episodes, total_listens, average_rating, total_views) VALUES 
(15, 5000, 4.8, 12000);

-- 建立 Podcast 平台連結資料表
CREATE TABLE IF NOT EXISTS podcast_platforms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform_name VARCHAR(50) NOT NULL,
    platform_url VARCHAR(500) NOT NULL,
    icon_class VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 插入預設平台資料
INSERT INTO podcast_platforms (platform_name, platform_url, icon_class, sort_order) VALUES 
('Spotify', '#', 'fab fa-spotify', 1),
('Apple Podcasts', '#', 'fab fa-apple', 2),
('Google Podcasts', '#', 'fab fa-google', 3),
('YouTube', '#', 'fab fa-youtube', 4),
('SoundCloud', '#', 'fab fa-soundcloud', 5);

-- 建立作者資料表
CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    bio TEXT,
    avatar VARCHAR(255),
    title VARCHAR(100),
    company VARCHAR(100),
    website VARCHAR(200),
    facebook VARCHAR(200),
    linkedin VARCHAR(200),
    twitter VARCHAR(200),
    specialties TEXT,
    experience_years INT DEFAULT 0,
    achievements TEXT,
    education TEXT,
    certifications TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 插入預設作者資料
INSERT INTO authors (name, email, bio, title, company, specialties, experience_years, achievements) VALUES 
('張顧問', 'consultant@example.com', '專精政府補助提案輔導，協助眾多企業成功獲得補助資源。擁有豐富的實務經驗和深厚的政策理解，致力於幫助企業發展創新項目。', '資深顧問', '共好計畫研究室', '政府補助申請,提案書撰寫,創新輔導,政策解析', 8, '已協助 100+ 企業,成功率 95%,累計輔導金額超過 5000 萬'),
('李專員', 'specialist@example.com', '專業的政策分析專家，深度了解各類政府補助計畫，提供精準的申請策略建議。', '政策分析專員', '共好計畫研究室', '政策分析,法規解讀,申請策略,風險評估', 5, '政策解析文章 50+ 篇,輔導成功案例 80+ 件');

-- 修改文章表，添加作者ID外鍵
ALTER TABLE articles ADD COLUMN author_id INT AFTER author;
ALTER TABLE articles ADD INDEX idx_author_id (author_id);

-- 將現有文章的作者關聯到作者表（假設都是張顧問）
UPDATE articles SET author_id = 1 WHERE author = '張顧問' OR author = 'admin'; 