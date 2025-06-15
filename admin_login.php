<?php
require_once 'php/config.php';
require_once 'php/auth.php';

$auth = new Auth();

// 如果已經登入，重定向到後台首頁
if ($auth->isAdminLoggedIn()) {
    header('Location: admin_dashboard.php');
    exit;
}

$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員登入 - 共好計畫研究室</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            /* 只隱藏橫向滾動 */
        }

        /* 背景動畫圓圈 */
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 10%;
            right: 25%;
            animation-delay: 1s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            /* 確保在內容過多時可以滾動 */
            box-sizing: border-box;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow:
                0 25px 45px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 35px 55px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.3);
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .login-header h3 {
            position: relative;
            z-index: 1;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .login-header p {
            position: relative;
            z-index: 1;
            margin-bottom: 0;
            opacity: 0.9;
            font-weight: 300;
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.75rem;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-1px);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 12px;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-info {
            background: rgba(13, 202, 240, 0.1);
            color: #0c63e4;
            border-left: 4px solid #0dcaf0;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-5px);
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 24px;
        }

        @media (max-width: 576px) {
            body {
                min-height: auto;
                /* 在小螢幕上不強制最小高度 */
            }

            .login-container {
                padding: 1rem;
                min-height: auto;
                /* 允許內容自然流動 */
                align-items: flex-start;
                /* 從頂部開始對齊 */
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .login-card {
                margin: 1rem 0;
                /* 增加上下邊距 */
            }
        }
    </style>
</head>

<body>
    <!-- 背景動畫元素 -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- 返回首頁連結 -->
    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>返回首頁
    </a>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>管理員登入</h3>
                <p>共好計畫研究室後台管理系統</p>
            </div>

            <div class="login-body">
                <?php if ($message): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div id="errorMessage" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorText"></span>
                </div>

                <form id="loginForm" onsubmit="handleLogin(event)">
                    <div class="mb-4">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>管理員帳號
                        </label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="請輸入管理員帳號"
                            required autocomplete="username">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>登入密碼
                        </label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="請輸入登入密碼"
                            required autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn btn-login w-100" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span id="btnText">登入系統</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block">
                            <i class="fas fa-key me-1"></i>測試帳號資訊
                        </small>
                        <small class="text-primary fw-bold">
                            帳號: admin &nbsp;|&nbsp; 密碼: admin123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleLogin(event) {
            event.preventDefault();

            const form = event.target;
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');

            // 顯示載入狀態
            loginBtn.disabled = true;
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>登入中...';
            errorMessage.classList.add('d-none');

            const formData = new FormData(form);
            formData.append('action', 'login');

            fetch('php/auth.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btnText.innerHTML = '<i class="fas fa-check me-2"></i>登入成功';
                        btnText.parentElement.classList.add('btn-success');

                        // 延遲跳轉，讓用戶看到成功狀態
                        setTimeout(() => {
                            window.location.href = 'admin_dashboard.php';
                        }, 1000);
                    } else {
                        // 恢復按鈕狀態
                        loginBtn.disabled = false;
                        btnText.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>登入系統';

                        // 顯示錯誤訊息
                        errorText.textContent = data.message;
                        errorMessage.classList.remove('d-none');

                        // 震動效果
                        form.classList.add('shake');
                        setTimeout(() => {
                            form.classList.remove('shake');
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // 恢復按鈕狀態
                    loginBtn.disabled = false;
                    btnText.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>登入系統';

                    // 顯示錯誤訊息
                    errorText.textContent = '系統錯誤，請稍後再試';
                    errorMessage.classList.remove('d-none');
                });
        }

        // 添加震動動畫樣式
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            .shake {
                animation: shake 0.5s ease-in-out;
            }
            .btn-success {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
                color: white !important;
            }
        `;
        document.head.appendChild(style);

        // 預填帳號密碼（開發環境）
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');

            // 雙擊預填帳號密碼
            usernameInput.addEventListener('dblclick', function() {
                usernameInput.value = 'admin';
                passwordInput.value = 'admin123';
            });
        });
    </script>
</body>

</html>