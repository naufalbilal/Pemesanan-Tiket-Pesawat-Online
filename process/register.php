<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Validation
    if ($password !== $confirm_password) {
        header('Location: ../register.php?error=password_mismatch');
        exit();
    }
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        header('Location: ../register.php?error=user_exists');
        exit();
    }
    
    // Create user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$username, $email, $hashed_password, $full_name, $phone, $address])) {
        header('Location: ../login.php?success=registered');
        exit();
    } else {
        header('Location: ../register.php?error=registration_failed');
        exit();
    }
} else {
    header('Location: ../register.php');
    exit();
}
?>