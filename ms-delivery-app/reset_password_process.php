<?php
require 'db.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in the database and clear the token
    $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, token_expires = NULL WHERE reset_token = :token");
    $stmt->execute(['password' => $hashedPassword, 'token' => $token]);

    echo "Password has been reset successfully. You can now log in.";
}
?>
