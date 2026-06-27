<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            
            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid email or password context.';
            }
        }
        $view = __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function register() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (User::findByEmail($email)) {
                $error = 'Email is already registered.';
            } else {
                if (User::create($name, $email, $password)) {
                    header('Location: index.php?controller=auth&action=login');
                    exit;
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
        $view = __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
