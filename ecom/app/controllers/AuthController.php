<?php
use Monolog\Logger;
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/SendMail.php';

class AuthController {
    private Logger $logger;
    private EnvLoader $envLoader;
    public function __construct()
    {
        $this->logger = LoggerFactory::getLogger(__CLASS__);
        $this->envLoader = EnvLoader::getInstance();
    }
    public function loginForm() {
        $error = '';
        $view = __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function authenticate()
    {
        
        $error = '';
        $email = trim($_POST['email']);
        $this->logger->info("Authenticating {$email}.......");

        $password = trim($_POST['password']);

        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: /');
            exit;
        } else {
            $error = 'Invalid email or password context.';
        }
        $view = __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function registerForm() {
        $view = __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function register()
    {
        $error = '';
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (User::findByEmail($email)) {
            $error = 'Email is already registered.';
        } else {
            if (User::create($name, $email, $password)) {
                header('Location: /customer/login-form');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $view = __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function forgotPasswordForm() {
        $view = __DIR__ . '/../views/auth/forgot_password.php';
        require_once __DIR__ . '/../views/layout/header.php'; 
    }

    public function generateResetPasswordLink()
    {
        $message = '';
        $isError = false;

        $email = trim($_POST['email'] ?? '');
        $this->logger->info("Generating verification link for {$email}.......");

        $db = Database::getConnection();

        // Verify if the email input matches a registered account
        $stmt = $db->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate a 32-byte secure token and set a 15-minute expiration timestamp
            $token = bin2hex(random_bytes(16));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Save recovery tokens to the user row
            $updateStmt = $db->prepare("UPDATE users SET reset_token = ?, reset_expires_at = ? WHERE id = ?");
            $updateStmt->execute([$token, $expiresAt, $user['id']]);

            // Compile the recovery URL
            $recoveryLink = "http://" . $_SERVER['HTTP_HOST'] . "/customer/verify-reset-password-link?token=" . $token;

            // 1. Fetch template structure from file
            $emailTemplatePath = $_SERVER['DOCUMENT_ROOT'] . $this->envLoader->getProperty("EMAIL_TEMPLATE_FOLDER_LOCATION") . 'email-confirmation.html';
            if (!file_exists($emailTemplatePath)) {
                $this->logger->error("Error: Template file missing. " . $emailTemplatePath);
                die(500);
            }

            $email_html = file_get_contents($emailTemplatePath);

            // 2. Define key-value mappings for content injection
            $placeholders = [
                '{{TITLE}}'     => "Account Verification",
                '{{NAME}}'      => htmlspecialchars($user['name']),
                '{{CTA_URL}}'   => $recoveryLink,
                '{{CTA_TEXT}}'  => "Click Here To Verify Your Email"
            ];

            // 3. Inject variables into placeholders
            $mailBody = str_replace(array_keys($placeholders), array_values($placeholders), $email_html);

            $sendMail = new SendMail($this->envLoader->getProperty("MAIL_FROM"), 
                $this->envLoader->getProperty("MAIL_FROM_NAME"),
                $email, $user['name'], "Email verification link", $mailBody);

            if ($sendMail->sendMail()) {
                $message = "Success: A password reset link has been dispatched to your account.";
            } else {
                $message = "Error: There is some issue verifying your email";
            }

        } else {
            $message = "Error: This email address is not registered in our system.";
            $isError = true;
        }

        $view = __DIR__ . '/../views/auth/forgot_password.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function verifyResetPasswordLink() {
        $message = '';
        $isError = false;
        $token = $_GET['token'] ?? $_POST['token'] ?? '';

        if (empty($token)) {
            header('Location: /customer/login-form');
            exit;
        }

        $db = Database::getConnection();
        
        // Lookup user row matching active token while validating that the expiration window is in the future
        $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires_at > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $message = "Error: This password recovery token is invalid or has expired.";
            $isError = true;
        }

        $view = __DIR__ . '/../views/auth/reset_password.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    // NEW: Validates Token Expiration and Patches Password Records
    public function resetPassword() {
        $message = '';
        $isError = false;
        $token = $_GET['token'] ?? $_POST['token'] ?? '';

        if (empty($token)) {
            header('Location: /customer/login-form');
            exit;
        }

        $db = Database::getConnection();
        
        // Lookup user row matching active token while validating that the expiration window is in the future
        $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires_at > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $message = "Error: This password recovery token is invalid or has expired.";
            $isError = true;
        }

        if (!$isError) {
            $newPassword = trim($_POST['password'] ?? '');

            if (strlen($newPassword) < 4) {
                $message = "Error: Password must be at least 4 characters long.";
                $isError = true;
            } else {
                // Generate a strong fresh BCRYPT hash representation string
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update database records while securely evicting token data properties
                $updateStmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ?");
                $updateStmt->execute([$hashedPassword, $user['id']]);

                // Transfer routing forward straight over to login screen view template layout
                header('Location: /customer/login-form');
                exit;
            }
        }

        $view = __DIR__ . '/../views/auth/reset_password.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
