<?php
session_start();
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $reset_code = $_POST['reset_code'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Verify reset code
    $sql = "SELECT * FROM users WHERE email = :email AND reset_token = :reset_token";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email, 'reset_token' => $reset_code]);
    
    if ($stmt->rowCount() > 0) {
        // Reset the password
        $sql = "UPDATE users SET password = :password, reset_token = NULL WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['password' => $new_password, 'email' => $email]);

        $_SESSION['status'] = "Your password has been reset successfully.";
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['error'] = "Invalid reset code or email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto p-5">
        <h2 class="text-2xl mb-5">Reset Password</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-2 rounded mb-4 text-center">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="reset_password.php" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
            <input type="hidden" name="reset_code" value="<?php echo htmlspecialchars($_GET['reset_code']); ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</body>
</html>
