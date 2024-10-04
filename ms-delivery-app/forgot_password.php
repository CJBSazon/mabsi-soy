<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Food Delivery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="flex items-center justify-center h-screen bg-gray-200">

    <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-8 w-96">
        <h2 class="text-center text-2xl font-bold mb-4">Reset Password</h2>
        
        <form action="send_reset_link.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Send Reset Link
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Remembered your password? <a href="login.php" class="text-blue-500 hover:text-blue-700">Login</a></p>
        </div>
    </div>

</body>
</html>
