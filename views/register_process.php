<?php
session_start();

require_once '../includes/2fa.php';
require_once '../classes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $_SESSION['temp_user'] = [
        'username' => $username,
        'email' => $email,
        'password' => $password
    ];

    $verificationCode = rand(100000, 999999); 
    $_SESSION['verification_code'] = $verificationCode;
    $_SESSION['code_expiry'] = time() + 300; 

    if (send2FA($email, $verificationCode)) {
        header("Location: ../views/verify.php"); 
        exit();
    } else {
        echo "Failed to send verification email.";
        session_destroy(); 
        exit();
    }

}
