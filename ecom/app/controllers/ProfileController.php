<?php
// controllers/ProfileController.php
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/Order.php';

class ProfileController {
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /customer/login-form');
            exit;
        }
    }

    public function orders() {
        $this->checkAuth();
        // Fetch current contextual records matching active session index
        $userOrders = Order::getOrdersByUserId($_SESSION['user_id']);

        // Pass control straight forward over onto our responsive profile dashboard view layer
        $view = __DIR__ . '/../views/profile/orders.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function fetchProfile()
    {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        $db = Database::getConnection();

        $message = '';
        $isError = false;

        // 2. Fetch the freshest contextual dataset for the user row
        $userStmt = $db->prepare("SELECT name, phone, email FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch();

        // Direct control flow over to the responsive profile updating form view
        $view = __DIR__ . '/../views/profile/edit.php';
        require_once __DIR__ . '/../views/layout/header.php';
    
    }

    public function updateProfile() {
        $this->checkAuth();
        $userId = $_SESSION['user_id'];
        $db = Database::getConnection();

        $message = '';
        $isError = false;

        // 1. Process Profile Update POST Requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Basic Field Validation
            if (empty($name) || empty($phone) || empty($email)) {
                $message = 'Error: Name, Mobile Number, and Email fields are required.';
                $isError = true;
            } 
            // Mobile Number Validation (Strict 10-digit Indian Number format)
            elseif (!preg_match('/^[6-9]\d{9}$/', $phone)) {
                $message = 'Error: Please enter a valid 10-digit Indian mobile number.';
                $isError = true;
            } 
            else {
                // Check if the chosen phone number is already taken by another account
                $checkPhone = $db->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
                $checkPhone->execute([$phone, $userId]);
                
                // Check if the chosen email is already taken by another account
                $checkEmail = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $checkEmail->execute([$email, $userId]);

                if ($checkPhone->fetch()) {
                    $message = 'Error: This mobile number is already linked to another user.';
                    $isError = true;
                } elseif ($checkEmail->fetch()) {
                    $message = 'Error: This email address is already registered to another user.';
                    $isError = true;
                } else {
                    // Update core profile records inside MySQL users table
                    $stmt = $db->prepare("UPDATE users SET name = ?, phone = ?, email = ? WHERE id = ?");
                    $stmt->execute([$name, $phone, $email, $userId]);
                    
                    // Sync active session name indicator array
                    $_SESSION['user_name'] = $name;

                    // If a new password string is supplied, compile a fresh secure BCRYPT hash
                    if (!empty($password)) {
                        if (strlen($password) < 4) {
                            $message = 'Profile data saved, but password must be at least 4 characters.';
                            $isError = true;
                        } else {
                            $hashedPass = password_hash($password, PASSWORD_BCRYPT);
                            $passStmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                            $passStmt->execute([$hashedPass, $userId]);
                        }
                    }

                    if (!$isError) {
                        $message = 'Success: Profile records modified successfully!';
                    }
                }
            }
        }

        // 2. Fetch the freshest contextual dataset for the user row
        $userStmt = $db->prepare("SELECT name, phone, email FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch();

        // Direct control flow over to the responsive profile updating form view
        $view = __DIR__ . '/../views/profile/edit.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
