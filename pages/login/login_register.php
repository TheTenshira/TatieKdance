<?php

session_start();
require_once '../../config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmail = $conn->query("SELECT email FROM login WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'l\'email existe déjà.';
        $_SESSION['active_form'] = 'register';
    }else {
        $conn->query("INSERT INTO login (user, email, password) VALUES ('$name', '$email', '$password')");
    }

    header("Location: login.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Email ou mot de passe incorrect.';
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    exit();
}

?>