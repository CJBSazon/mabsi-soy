<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Database connection details
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

// Handle menu CRUD operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add menu item
    if (isset($_POST['add_menu'])) {
        $name = $_POST['menu_name'];
        $description = $_POST['menu_description'];
        $price = $_POST['menu_price'];

        $stmt = $conn->prepare("INSERT INTO menu (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $description, $price);
        $stmt->execute();
        $stmt->close();
    } 
    // Delete menu item
    elseif (isset($_POST['delete_menu'])) {
        $menu_id = $_POST['menu_id'];
        $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $stmt->close();
    } 
    // Add announcement
    elseif (isset($_POST['add_announcement'])) {
        $title = $_POST['announcement_title'];
        $message = $_POST['announcement_message'];
        $image = ""; // Initialize image variable

        // Handle image upload
        if (isset($_FILES['announcement_image']) && $_FILES['announcement_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/"; // Ensure this directory exists
            $target_file = $target_dir . basename($_FILES["announcement_image"]["name"]);
            move_uploaded_file($_FILES["announcement_image"]["tmp_name"], $target_file);
            $image = $target_file; // Store the image path
        }

        $stmt = $conn->prepare("INSERT INTO announcements (title, message, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $message, $image);
        $stmt->execute();
        $stmt->close();
    } 
    // Delete announcement
    elseif (isset($_POST['delete_announcement'])) {
        $announcement_id = $_POST['announcement_id'];
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->bind_param("i", $announcement_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch menu items
$menu_items = $conn->query("SELECT * FROM menu");

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements");

// Handle logout
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <a href="?logout=true" class="bg-red-500 text-white px-4 py-2 rounded">Logout</a>

        <!-- Menu Management -->
        <h2 class="mt-6 text-xl font-semibold">Menu Management</h2>
        <form method="POST" class="mt-4">
            <input type="text" name="menu_name" placeholder="Menu Name" required class="border p-2">
            <input type="text" name="menu_description" placeholder="Description" required class="border p-2">
            <input type="number" name="menu_price" placeholder="Price" step="0.01" required class="border p-2">
            <button type="submit" name="add_menu" class="bg-blue-500 text-white px-4 py-2 rounded">Add Menu Item</button>
        </form>

        <ul class="mt-4">
            <?php while ($item = $menu_items->fetch_assoc()): ?>
                <li class="flex justify-between items-center border-b py-2">
                    <span><?php echo htmlspecialchars($item['name']); ?> - $<?php echo htmlspecialchars($item['price']); ?></span>
                    <form method="POST" class="inline">
                        <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="delete_menu" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Announcements Management -->
        <h2 class="mt-6 text-xl font-semibold">Announcements Management</h2>
        <form method="POST" enctype="multipart/form-data" class="mt-4">
            <input type="text" name="announcement_title" placeholder="Announcement Title" required class="border p-2">
            <textarea name="announcement_message" placeholder="Announcement Message" required class="border p-2"></textarea>
            <input type="file" name="announcement_image" class="border p-2">
            <button type="submit" name="add_announcement" class="bg-blue-500 text-white px-4 py-2 rounded">Add Announcement</button>
        </form>

        <ul class="mt-4">
            <?php while ($announcement = $announcements->fetch_assoc()): ?>
                <li class="flex justify-between items-center border-b py-2">
                    <span><?php echo htmlspecialchars($announcement['title']); ?></span>
                    <form method="POST" class="inline">
                        <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                        <button type="submit" name="delete_announcement" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>

        <h2 class="mt-6 text-xl font-semibold">Customer Orders</h2>
        <table class="mt-4 border-collapse border border-gray-400 w-full">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Order ID</th>
                    <th class="border border-gray-300 px-4 py-2">Customer</th>
                    <th class="border border-gray-300 px-4 py-2">Menu Item</th>
                    <th class="border border-gray-300 px-4 py-2">Quantity</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch orders
                $orders = $conn->query("SELECT o.*, u.username, m.name FROM orders o JOIN users u ON o.user_id = u.id JOIN menu m ON o.menu_id = m.id");
                while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $order['id']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($order['username']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($order['name']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $order['quantity']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($order['status']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $order['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
