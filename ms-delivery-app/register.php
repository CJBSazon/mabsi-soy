<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address']; // Address is now selected from dropdown

    // Prepare SQL statement to insert user data
    $sql = "INSERT INTO users (username, password, email, address) VALUES (:username, :password, :email, :address)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email, 'address' => $address]);
    
    $_SESSION['username'] = $username;
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Food Delivery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        body {
            background-image: url('./img/bg home.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">

    <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 w-96">
        <img class="ml-24 w-32 mb-5 rounded-full" src="./img/Mabsi Soy Logo.jpg" alt="">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-2 rounded mb-4 text-center">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <!-- Username -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Address Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Address (Pampanga Only)</label>
                <select name="address" id="address" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select your address</option>
                    <option value="Angeles City">Angeles City</option>
                    <option value="San Fernando">San Fernando</option>
                    <option value="Mabalacat">Mabalacat</option>
                    <option value="Apalit">Apalit</option>
                    <option value="Bacolor">Bacolor</option>
                    <option value="Candaba">Candaba</option>
                    <option value="Floridablanca">Floridablanca</option>
                    <option value="Guagua">Guagua</option>
                    <option value="Lubao">Lubao</option>
                    <option value="Macabebe">Macabebe</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Porac">Porac</option>
                    <option value="San Simon">San Simon</option>
                    <option value="Santo Tomas">Santo Tomas</option>
                    <option value="Sasmuan">Sasmuan</option>
                </select>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Register
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 hover:text-blue-700">Login</a></p>
        </div>
    </div>

</body>
</html>
