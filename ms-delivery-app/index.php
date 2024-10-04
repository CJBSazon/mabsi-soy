<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_delivery";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch menu items
$menu_items = $conn->query("SELECT * FROM menu LIMIT 4");

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY id DESC LIMIT 3");

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mabsi Soy - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        body {
            background-color: #f9f9f9;
        }
    </style>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Header -->
<header class="bg-orange-500 text-white py-4 px-6 flex justify-between items-center">
    <div class="flex items-center">
        <img src="./img/Mabsi Soy Logo.jpg" alt="Mabsi Soy Logo" class="w-12 h-12 rounded-full">
        <h1 class="text-2xl font-bold ml-2">Mabsi Soy</h1>
    </div>
    <div class="hidden md:flex space-x-4">
        <a href="#" class="text-white">Home</a>
        <a href="#" class="text-white">Contact</a>
        <a href="#" class="text-white">About</a>
        <a href="logout.php" class="text-white">Logout</a> <!-- Functional Logout link -->
    </div>
    <div class="md:hidden">
        <button onclick="toggleMenu()" class="focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="md:hidden hidden bg-orange-500 text-white">
    <a href="#" class="block px-4 py-2">Home</a>
    <a href="#" class="block px-4 py-2">Contact</a>
    <a href="#" class="block px-4 py-2">About</a>
    <a href="logout.php" class="block px-4 py-2">Logout</a> <!-- Functional Logout link -->
</div>


    <!-- Hero Section -->
    <section class="bg-orange-100 text-center p-6">
        <h2 class="text-xl font-bold">PEKA MANYAMAN SILOG KENI SASMUAN</h2>
        <div class="mt-4">
            <a href="pickup.php" class="bg-orange-500 text-white py-2 px-4 rounded mx-2">Order Pickup!</a>
            <a href="delivery.php" class="bg-orange-500 text-white py-2 px-4 rounded mx-2">Order Delivery!</a>
            <a href="reserve.php" class="bg-orange-500 text-white py-2 px-4 rounded mx-2">Reserve Table!</a>
        </div>
    </section>

    <!-- Menu Section -->
    <main class="flex-grow p-4">
        <h2 class="text-lg font-bold mb-4">MENU</h2>
        <div class="grid grid-cols-2 gap-4">

            <!-- Sample Food Card -->
            <?php while ($item = $menu_items->fetch_assoc()): ?>
            <div class="bg-white shadow rounded-lg p-4">
                <img src="./img/food-placeholder.jpg" alt="Food Image" class="w-full h-32 object-cover rounded-t-lg mb-4">
                <h3 class="text-md font-bold"><?php echo htmlspecialchars($item['name']); ?></h3>
                <p class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($item['description']); ?></p>
                <p class="text-blue-500 font-semibold">$<?php echo htmlspecialchars($item['price']); ?></p>
                <button class="bg-orange-500 text-white mt-2 w-full py-1 rounded">Add to Cart</button>
            </div>
            <?php endwhile; ?>

        </div>

        <!-- View All Button -->
        <div class="flex justify-center mt-4">
            <a href="full_menu.php" class="bg-orange-500 text-white py-2 px-4 rounded">VIEW ALL!</a>
        </div>
    </main>

    <!-- Announcements Section -->
    <section class="bg-yellow-100 p-4 mt-4 mx-4 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold mb-4">📢 Announcements</h2>

        <!-- Fetch and Display Announcements from Database -->
        <?php while ($announcement = $announcements->fetch_assoc()): ?>
            <div class="bg-white p-4 mb-4 rounded-lg shadow-lg">
                <?php if (!empty($announcement['image'])): ?>
                    <img src="<?php echo htmlspecialchars($announcement['image']); ?>" alt="Announcement Image" class="w-full h-32 object-cover rounded-lg mb-4">
                <?php endif; ?>
                <h3 class="text-md font-bold"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                <p class="text-sm text-gray-700"><?php echo htmlspecialchars($announcement['message']); ?></p>
            </div>
        <?php endwhile; ?>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center py-4">
        <p class="text-gray-600">Keni na mga soy takman yuna rig peka manyaman, mura malinis at quality silog meals keni Sasmuán.</p>
        <p class="text-gray-600">Copyright © 2024 | Mabsi Soy</p>
    </footer>

</body>
</html>



