<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>頁面未找到 - 共好計畫研究室</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/all.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="py-5" style="margin-top: 76px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="py-5">
                        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                        <h1 class="display-1 fw-bold text-primary mb-3">404</h1>
                        <h2 class="h3 mb-4">頁面未找到</h2>
                        <p class="lead text-muted mb-4">
                            抱歉，您要查看的文章可能已被移除或不存在。
                        </p>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>返回首頁
                            </a>
                            <a href="articles.php" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>瀏覽文章
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>