<?php
// controllers/AdminController.php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Product.php';

class AdminController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAuth();
        $kpi = Admin::getKPIStats();
        $revenueTrend = Admin::getDailyRevenueTrend();
        $paymentDistribution = Admin::getPaymentBreakdown();

        $view = __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function products() {
        $this->checkAuth();
        $message = '';

        //PHP default file size limit is 2MB
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionType = $_POST['action_type'] ?? '';
            $name  = trim($_POST['name'] ?? '');
            $price = trim($_POST['price'] ?? 0);
            $description = trim($_POST['description'] ?? '');
            $imagePath = $_POST['existing_image'] ?? ''; 

            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['product_image']['tmp_name'];
                $fileName    = $_FILES['product_image']['name'];
                $fileSize    = $_FILES['product_image']['size']; // Capture file size in bytes
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

                // LAYER 1: Enforce server-side file size restrictions
                if ($fileSize > $maxFileSize) {
                    $message = 'Error: File is too large. Maximum allowed size is 2MB.';
                } 
                // LAYER 2: Enforce file extension constraints
                elseif (!in_array($fileExtension, $allowedExtensions)) {
                    $message = 'Error: Invalid file type. Only JPG, JPEG, and PNG are allowed.';
                } 
                // Process clean files safely
                else {
                    $newFileName = 'prod_' . md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../uploads/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $imagePath = 'uploads/' . $newFileName;
                    } else {
                        $message = 'Error: Failed to move file to storage directory.';
                    }
                }
            } elseif (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Intercept server-level overflow flags (e.g., matching php.ini post_max_size breaches)
                $message = 'Error: File upload failed. The file may exceed server limitations.';
            }

            // Database persistence logic execution block
            if (strpos($message, 'Error') === false) {
                if ($actionType === 'create') {
                    if (empty($imagePath)) { $imagePath = 'https://unsplash.com'; }
                    if (Product::create($name, $price, $imagePath)) {
                        $message = 'Product created successfully!';
                    }
                } elseif ($actionType === 'update') {
                    $id = (int)($_POST['product_id'] ?? 0);
                    if (Product::update($id, $name, $price, $imagePath)) {
                        $message = 'Product modified successfully!';
                    }
                }
            }
        }

        if (isset($_GET['delete_id'])) {
            if (Product::delete((int)$_GET['delete_id'])) {
                header('Location: index.php?controller=admin&action=products');
                exit;
            }
        }

        $products = Product::getAll();
        $view = __DIR__ . '/../views/admin/products.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
