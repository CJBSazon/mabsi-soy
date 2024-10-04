<?php
// Start session
session_start();

// Database connection
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password (leave blank if you have no password)
$dbname = "food_delivery"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $confirm_pass = $_POST['confirm_password'];

    // Validate input (basic example)
    if (empty($user) || empty($pass) || empty($mobile) || empty($address) || empty($confirm_pass)) {
        echo "All fields are required.";
        exit;
    }

    // Check if passwords match
    if ($confirm_pass !== $_POST['password']) {
        echo "Passwords do not match.";
        exit;
    }

    // Prepare SQL statement to insert user
    $stmt = $conn->prepare("INSERT INTO users (username, password, mobile, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $pass, $mobile, $address);

    // Execute and check for success
    if ($stmt->execute()) {
        // Assign the 'admin' role to the first user registered
        $last_id = $stmt->insert_id; // Get the ID of the newly created user
        if ($last_id === 1) { // Check if it's the first user
            $conn->query("UPDATE users SET role = 'admin' WHERE id = $last_id");
        }
        echo "Registration successful!";
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
