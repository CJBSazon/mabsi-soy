<?php
// update_password.php

session_start();
require 'database.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Update the password in the database
    $stmt = $pdo->prepare('UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?');
    if ($stmt->execute([$newPassword, $token])) {
        $_SESSION['status'] = 'Your password has been reset successfully. You can now log in.';
        header('Location: login.php');
    } else {
        $_SESSION['error'] = 'Invalid token or something went wrong.';
        header('Location: reset_password.php?token=' . $token);
    }
}
