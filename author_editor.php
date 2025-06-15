<?php
require_once 'php/config.php';
require_once 'php/auth.php';
require_once 'php/author_handler.php';

$auth = new Auth();
$auth->requireAdminLogin();

$authorHandler = new AuthorHandler();
$currentAdmin = $auth->getCurrentAdmin();

// 檢查是否為編輯模式
$editMode = isset($_GET['id']) && !empty($_GET['id']);
$author = null;

if ($editMode) {
    $author = $authorHandler->getAuthorById($_GET['id']);
    if (!$author) {
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
    <title><?php echo $editMode ? '編輯作者' : '新增作者'; ?> - 共好計畫研究室</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- 側邊欄 -->
            <div class="col-md-3 sidebar text-white p-3">
                <div class="text-center mb-4">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4>作者管理</h4>
                    <small class="opacity-75">歡迎，<?php echo htmlspecialchars($currentAdmin['username']); ?></small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="admin_dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>儀表板
                    </a>
                    <a class="nav-link text-white" href="admin_dashboard.php">
                        <i class="fas fa-users me-2"></i>作者管理
                    </a>
                    <a class="nav-link text-white active" href="#">
                        <i class="fas fa-user-edit me-2"></i><?php echo $editMode ? '編輯作者' : '新增作者'; ?>
                    </a>
                    <hr class="my-3 opacity-25">
                    <a class="nav-link text-white" href="php/auth.php?action=logout">
                        <i class="fas fa-sign-out-alt me-2"></i>安全登出
                    </a>
                </nav>
            </div>

            <!-- 主要內容 -->
            <div class="col-md-9 main-content">
                <!-- 標題 -->
                <div class="welcome-banner mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-user-edit me-2"></i>
                                <?php echo $editMode ? '編輯作者' : '新增作者'; ?>
                            </h2>
                            <p class="mb-0 opacity-90">
                                <?php echo $editMode ? '修改作者資料和簡介' : '建立新的作者檔案'; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="admin_dashboard.php" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>返回管理
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 作者編輯表單 -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>作者資料
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="authorForm" method="POST">
                            <input type="hidden" name="action" value="<?php echo $editMode ? 'update' : 'create'; ?>">
                            <?php if ($editMode): ?>
                                <input type="hidden" name="id" value="<?php echo $author['id']; ?>">
                            <?php endif; ?>

                            <div class="row">
                                <!-- 基本資料 -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>基本資料</h6>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">姓名 *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo $editMode ? htmlspecialchars($author['name']) : ''; ?>"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">電子郵件</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo $editMode ? htmlspecialchars($author['email']) : ''; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">職稱</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="<?php echo $editMode ? htmlspecialchars($author['title']) : ''; ?>"
                                            placeholder="例如：資深顧問">
                                    </div>

                                    <div class="mb-3">
                                        <label for="company" class="form-label">公司</label>
                                        <input type="text" class="form-control" id="company" name="company"
                                            value="<?php echo $editMode ? htmlspecialchars($author['company']) : ''; ?>"
                                            placeholder="例如：共好計畫研究室">
                                    </div>

                                    <div class="mb-3">
                                        <label for="experience_years" class="form-label">經驗年數</label>
                                        <input type="number" class="form-control" id="experience_years"
                                            name="experience_years"
                                            value="<?php echo $editMode ? $author['experience_years'] : '0'; ?>"
                                            min="0">
                                    </div>
                                </div>

                                <!-- 專業資料 -->
                                <div class="col-md-6">
                                    <h6 class="text-success mb-3"><i class="fas fa-briefcase me-2"></i>專業資料</h6>

                                    <div class="mb-3">
                                        <label for="specialties" class="form-label">專業領域</label>
                                        <textarea class="form-control" id="specialties" name="specialties" rows="3"
                                            placeholder="以逗號分隔，例如：政府補助申請,提案書撰寫,創新輔導"><?php echo $editMode ? htmlspecialchars($author['specialties']) : ''; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="achievements" class="form-label">主要成就</label>
                                        <textarea class="form-control" id="achievements" name="achievements" rows="3"
                                            placeholder="例如：已協助 100+ 企業,成功率 95%"><?php echo $editMode ? htmlspecialchars($author['achievements']) : ''; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="education" class="form-label">學歷</label>
                                        <textarea class="form-control" id="education" name="education" rows="2"
                                            placeholder="例如：國立台灣大學 企業管理學系"><?php echo $editMode ? htmlspecialchars($author['education']) : ''; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="certifications" class="form-label">證照資格</label>
                                        <textarea class="form-control" id="certifications" name="certifications"
                                            rows="2"
                                            placeholder="例如：中小企業榮譽指導員,ISO 9001 主導稽核員"><?php echo $editMode ? htmlspecialchars($author['certifications']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- 簡介 -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-info mb-3"><i class="fas fa-file-text me-2"></i>作者簡介</h6>
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">個人簡介</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="4"
                                            placeholder="描述作者的背景、經驗和專業特長..."><?php echo $editMode ? htmlspecialchars($author['bio']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- 社交連結 -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-warning mb-3"><i class="fas fa-link me-2"></i>社交連結</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">個人網站</label>
                                        <input type="url" class="form-control" id="website" name="website"
                                            value="<?php echo $editMode ? htmlspecialchars($author['website']) : ''; ?>"
                                            placeholder="https://example.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="linkedin" class="form-label">LinkedIn</label>
                                        <input type="url" class="form-control" id="linkedin" name="linkedin"
                                            value="<?php echo $editMode ? htmlspecialchars($author['linkedin']) : ''; ?>"
                                            placeholder="https://linkedin.com/in/username">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="url" class="form-control" id="facebook" name="facebook"
                                            value="<?php echo $editMode ? htmlspecialchars($author['facebook']) : ''; ?>"
                                            placeholder="https://facebook.com/username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="twitter" class="form-label">Twitter/X</label>
                                        <input type="url" class="form-control" id="twitter" name="twitter"
                                            value="<?php echo $editMode ? htmlspecialchars($author['twitter']) : ''; ?>"
                                            placeholder="https://twitter.com/username">
                                    </div>
                                </div>
                            </div>

                            <!-- 狀態 -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                <?php echo (!$editMode || $author['is_active']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_active">
                                                啟用作者 (顯示在作者選單中)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 提交按鈕 -->
                            <div class="row">
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="admin_dashboard.php" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>取消
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            <?php echo $editMode ? '更新作者' : '建立作者'; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('authorForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // 顯示載入中
            Swal.fire({
                title: '正在保存...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('php/author_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '保存成功',
                            text: '作者資料已成功保存',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'admin_dashboard.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '保存失敗',
                            text: data.message || '操作失敗，請稍後再試'
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
        });
    </script>
</body>

</html>