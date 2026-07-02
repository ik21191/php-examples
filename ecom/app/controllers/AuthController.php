<?php
// controllers/AuthController.php
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function loginForm() {
        $error = '';
        $view = __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function authenticate()
    {
        $error = '';
        $email = trim($_POST['email']);
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

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
