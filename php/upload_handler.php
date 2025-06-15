<?php
require_once 'config.php';

class UploadHandler
{
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private $maxFileSize;
    private $uploadPath;

    public function __construct()
    {
        $this->maxFileSize = MAX_FILE_SIZE;
        $this->uploadPath = UPLOAD_PATH;
    }

    // 上傳圖片
    public function uploadImage($file)
    {
        try {
            // 檢查文件是否上傳成功
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('文件上傳失敗');
            }

            // 檢查文件大小
            if ($file['size'] > $this->maxFileSize) {
                throw new Exception('文件大小超過限制 (' . ($this->maxFileSize / 1024 / 1024) . 'MB)');
            }

            // 檢查文件類型
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $this->allowedTypes)) {
                throw new Exception('不支援的文件類型');
            }

            // 生成唯一文件名
            $extension = $this->getFileExtension($file['name']);
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $filepath = $this->uploadPath . $filename;

            // 移動文件
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('文件移動失敗');
            }

            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'url' => '../uploads/' . $filename
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // 刪除圖片
    public function deleteImage($filename)
    {
        $filepath = $this->uploadPath . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }

    // 獲取文件擴展名
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    // 驗證圖片
    public function validateImage($file)
    {
        $errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = '文件上傳失敗';
        }

        if ($file['size'] > $this->maxFileSize) {
            $errors[] = '文件大小超過限制';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            $errors[] = '不支援的文件類型';
        }

        return empty($errors) ? true : $errors;
    }
}

// 處理上傳請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $uploader = new UploadHandler();
    $result = $uploader->uploadImage($_FILES['image']);

    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// 處理刪除請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $filename = $_POST['filename'] ?? '';
    if ($filename) {
        $uploader = new UploadHandler();
        $result = $uploader->deleteImage($filename);
        echo json_encode(['success' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => '未提供文件名']);
    }
    exit;
}
