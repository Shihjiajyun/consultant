<?php
require_once 'php/config.php';
require_once 'php/auth.php';
require_once 'php/article_handler.php';

$auth = new Auth();
$auth->requireAdminLogin();

$articleHandler = new ArticleHandler();
$currentAdmin = $auth->getCurrentAdmin();

// 獲取所有作者
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM authors WHERE is_active = 1 ORDER BY name");
    $stmt->execute();
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $authors = [];
    error_log("獲取作者列表失敗: " . $e->getMessage());
}

// 檢查是否為編輯模式
$editMode = isset($_GET['id']) && !empty($_GET['id']);
$article = null;

if ($editMode) {
    $article = $articleHandler->getArticleById($_GET['id']);
    if (!$article) {
        header('Location: admin_dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editMode ? '編輯文章' : '新增文章'; ?> - 管理後台</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/ftlwjozrmnqxiys1yzigpetlelz6h5c8dubbe74dx8dz21or/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <link rel="stylesheet" href="css/article_editor.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- 側邊欄 -->
            <div class="col-md-3 sidebar text-white p-3">
                <div class="text-center mb-4">
                    <i class="fas fa-edit fa-2x mb-2"></i>
                    <h4>文章編輯器</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="admin_dashboard.php">
                        <i class="fas fa-home me-2"></i>返回後台
                    </a>
                    <a class="nav-link text-white active" href="article_editor.php">
                        <i class="fas fa-plus me-2"></i>新增文章
                    </a>
                    <a class="nav-link text-white" href="php/auth.php?action=logout">
                        <i class="fas fa-sign-out-alt me-2"></i>登出
                    </a>
                </nav>
            </div>

            <!-- 主要內容 -->
            <div class="col-md-9 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><?php echo $editMode ? '編輯文章' : '新增文章'; ?></h1>
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>返回後台
                    </a>
                </div>

                <div class="row">
                    <div class="col-lg-9">
                        <form id="articleForm" onsubmit="handleSubmit(event)">
                            <?php if ($editMode): ?>
                                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                <input type="hidden" name="action" value="update">
                            <?php else: ?>
                                <input type="hidden" name="action" value="create">
                            <?php endif; ?>

                            <!-- 基本資訊 -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">基本資訊</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">文章標題 *</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="<?php echo $editMode ? htmlspecialchars($article['title']) : ''; ?>"
                                            required>
                                    </div>

                                    <div class="mb-3" style="display: none;">
                                        <label for="slug" class="form-label">網址別名</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="<?php echo $editMode ? htmlspecialchars($article['slug']) : ''; ?>"
                                            placeholder="留空將自動生成">
                                        <div class="form-text">用於SEO友好的URL，僅能包含英文字母、數字和連字號</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="excerpt" class="form-label">文章摘要 *</label>
                                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                                            required><?php echo $editMode ? htmlspecialchars($article['excerpt']) : ''; ?></textarea>
                                        <div class="form-text">將顯示在文章列表中的簡短描述</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="category" class="form-label">分類 *</label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">選擇分類</option>
                                                <option value="提案技巧"
                                                    <?php echo ($editMode && $article['category'] === '提案技巧') ? 'selected' : ''; ?>>
                                                    提案技巧</option>
                                                <option value="政策解析"
                                                    <?php echo ($editMode && $article['category'] === '政策解析') ? 'selected' : ''; ?>>
                                                    政策解析</option>
                                                <option value="成功案例"
                                                    <?php echo ($editMode && $article['category'] === '成功案例') ? 'selected' : ''; ?>>
                                                    成功案例</option>
                                                <option value="常見問題"
                                                    <?php echo ($editMode && $article['category'] === '常見問題') ? 'selected' : ''; ?>>
                                                    常見問題</option>
                                                <option value="實務經驗"
                                                    <?php echo ($editMode && $article['category'] === '實務經驗') ? 'selected' : ''; ?>>
                                                    實務經驗</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="author" class="form-label">作者 *</label>
                                            <select class="form-select" id="author" name="author" required>
                                                <option value="">選擇作者</option>
                                                <?php foreach ($authors as $author): ?>
                                                    <option value="<?php echo htmlspecialchars($author['name']); ?>"
                                                        <?php echo ($editMode && $article['author'] === htmlspecialchars($author['name'])) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($author['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 特色圖片 -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">特色圖片</h5>
                                </div>
                                <div class="card-body">
                                    <div id="imageUploadArea" class="upload-area mb-3">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="mb-2">拖曳圖片到此處或點擊上傳</p>
                                        <input type="file" id="imageInput" name="image" accept="image/*" class="d-none">
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="document.getElementById('imageInput').click()">
                                            選擇圖片
                                        </button>
                                    </div>

                                    <div id="imagePreview"
                                        class="<?php echo ($editMode && $article['featured_image']) ? '' : 'd-none'; ?>">
                                        <?php if ($editMode && $article['featured_image']): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($article['featured_image']); ?>"
                                                class="image-preview mb-2" alt="預覽圖片">
                                            <input type="hidden" name="featured_image"
                                                value="<?php echo htmlspecialchars($article['featured_image']); ?>">
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                            <i class="fas fa-trash me-1"></i>移除圖片
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- 文章內容 -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">文章內容 *</h5>
                                </div>
                                <div class="card-body">
                                    <textarea class="form-control editor-container" id="content" name="content"
                                        required><?php echo $editMode ? $article['content'] : ''; ?></textarea>
                                    <div class="form-text">支援HTML格式</div>
                                </div>
                            </div>

                            <!-- 標籤和發布設定 -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">標籤和發布設定</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="tags" class="form-label">標籤</label>
                                        <input type="text" class="form-control" id="tags" name="tags"
                                            value="<?php echo $editMode ? htmlspecialchars($article['tags']) : ''; ?>"
                                            placeholder="用逗號分隔多個標籤">
                                        <div class="form-text">例如：政府補助,提案書,申請技巧</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">發布狀態</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="draft"
                                                <?php echo ($editMode && $article['status'] === 'draft') ? 'selected' : ''; ?>>
                                                草稿</option>
                                            <option value="published"
                                                <?php echo ($editMode && $article['status'] === 'published') ? 'selected' : ''; ?>>
                                                發布</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 操作按鈕 -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    <?php echo $editMode ? '更新文章' : '建立文章'; ?>
                                </button>
                                <a href="admin_dashboard.php" class="btn btn-outline-secondary">取消</a>
                            </div>
                        </form>
                    </div>

                    <!-- 預覽區域 -->
                    <div class="col-lg-3">
                        <div class="card preview-card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-eye me-2"></i>即時預覽</h6>
                            </div>
                            <div class="card-body">
                                <div id="titlePreview" class="fw-bold mb-3 h5">文章標題</div>
                                <div id="excerptPreview" class="text-muted mb-3">文章摘要將在此顯示...</div>
                                <div id="categoryPreview" class="mb-3">
                                    <span class="badge bg-secondary">選擇分類</span>
                                </div>
                                <div id="tagsPreview" class="mb-3"></div>
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        即時預覽您的文章外觀
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 初始化 TinyMCE
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family: "Noto Sans TC", Arial, sans-serif; font-size:14px }',
            language: 'zh_TW',
            branding: false,
            promotion: false,
            usage_statistics: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });

        // 預覽功能
        document.getElementById('title').addEventListener('input', function() {
            document.getElementById('titlePreview').textContent = this.value || '文章標題';
        });

        document.getElementById('excerpt').addEventListener('input', function() {
            document.getElementById('excerptPreview').textContent = this.value || '文章摘要將在此顯示...';
        });

        document.getElementById('category').addEventListener('change', function() {
            var preview = document.getElementById('categoryPreview');
            var categoryColors = {
                '提案技巧': 'bg-primary',
                '政策解析': 'bg-success',
                '成功案例': 'bg-info',
                '常見問題': 'bg-warning',
                '實務經驗': 'bg-secondary'
            };

            if (this.value) {
                var colorClass = categoryColors[this.value] || 'bg-secondary';
                preview.innerHTML = '<span class="badge ' + colorClass + '">' + this.value + '</span>';
            } else {
                preview.innerHTML = '<span class="badge bg-secondary">選擇分類</span>';
            }
        });

        document.getElementById('tags').addEventListener('input', function() {
            var preview = document.getElementById('tagsPreview');
            if (this.value) {
                var tags = this.value.split(',');
                var html = '';
                tags.forEach(function(tag) {
                    tag = tag.trim();
                    if (tag) {
                        html += '<span class="badge bg-light text-dark me-1 mb-1">' + tag + '</span>';
                    }
                });
                preview.innerHTML = html;
            } else {
                preview.innerHTML = '';
            }
        });

        // 圖片上傳功能
        var imageInput = document.getElementById('imageInput');
        var uploadArea = document.getElementById('imageUploadArea');
        var imagePreview = document.getElementById('imagePreview');
        var selectedImageFile = null; // 儲存選中的圖片文件

        imageInput.addEventListener('change', handleImageSelect);

        // 拖拽上傳
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            var files = e.dataTransfer.files;
            if (files.length > 0) {
                handleImageFile(files[0]);
            }
        });

        function handleImageSelect() {
            var file = imageInput.files[0];
            if (file) {
                handleImageFile(file);
            }
        }

        function handleImageFile(file) {
            // 檢查檔案類型
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: '檔案格式錯誤',
                    text: '請選擇圖片檔案'
                });
                return;
            }

            // 檢查檔案大小 (5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: '檔案過大',
                    text: '圖片大小不能超過 5MB'
                });
                return;
            }

            // 儲存文件引用
            selectedImageFile = file;

            // 使用 FileReader 預覽圖片
            var reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `
                    <div class="text-center">
                        <img src="${e.target.result}" class="image-preview mb-2" alt="預覽圖片">
                        <div class="mt-2">
                            <small class="text-muted d-block">檔案名稱: ${file.name}</small>
                            <small class="text-muted d-block">檔案大小: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            <small class="text-success d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>圖片將在儲存文章時上傳
                            </small>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                <i class="fas fa-trash me-1"></i>移除圖片
                            </button>
                        </div>
                    </div>
                `;
                imagePreview.classList.remove('d-none');

                Swal.fire({
                    icon: 'success',
                    title: '圖片預覽成功',
                    text: '圖片將在儲存文章時上傳',
                    timer: 1500,
                    showConfirmButton: false
                });
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            imagePreview.classList.add('d-none');
            imagePreview.innerHTML = '';
            imageInput.value = '';
            selectedImageFile = null;
        }

        // 表單提交
        function handleSubmit(event) {
            event.preventDefault();

            // 確保 TinyMCE 內容同步到 textarea
            tinymce.triggerSave();

            // 顯示保存進度
            Swal.fire({
                title: '<?php echo $editMode ? "正在更新文章..." : "正在建立文章..."; ?>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // 準備表單數據
            var formData = new FormData(event.target);

            // 如果沒有設定slug，自動生成
            if (!formData.get('slug')) {
                var title = formData.get('title');
                var slug = title.toLowerCase().replace(/[^a-z0-9\u4e00-\u9fa5]+/g, '-').replace(/^-|-$/g, '');
                formData.set('slug', slug);
            }

            // 如果有選擇新圖片，添加到表單數據中
            if (selectedImageFile) {
                formData.append('new_image', selectedImageFile);
            }

            fetch('php/article_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $editMode ? "更新成功" : "建立成功"; ?>',
                            text: '<?php echo $editMode ? "文章已成功更新" : "文章已成功建立"; ?>',
                            confirmButtonText: '返回後台'
                        }).then(() => {
                            window.location.href = 'admin_dashboard.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '操作失敗',
                            text: data.message || '未知錯誤，請稍後再試'
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

        // 初始化預覽
        document.addEventListener('DOMContentLoaded', function() {
            // 觸發預覽更新
            document.getElementById('title').dispatchEvent(new Event('input'));
            document.getElementById('excerpt').dispatchEvent(new Event('input'));
            document.getElementById('category').dispatchEvent(new Event('change'));
            document.getElementById('tags').dispatchEvent(new Event('input'));
        });
    </script>
</body>

</html>