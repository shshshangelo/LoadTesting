<?php
// welcome.php

// Database configuration
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "loadtesting"; // Updated to match the database used in login and register

// Start the session
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit;
}

// Log out functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Fetch the logged-in user's information
$user_id = $_SESSION['user_id'] ?? null;
$username = '';

if ($user_id) {
    $user_query = $conn->prepare("SELECT username FROM loadtesting WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_query->bind_result($username);
    $user_query->fetch();
    $user_query->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1d2b64, #f8cdd6);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .welcome-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .welcome-container:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .welcome-container h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
            font-weight: 600;
        }
        .welcome-container p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .welcome-container a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }
        .welcome-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome!</h1>
        <?php if (!empty($username)): ?>
            <p>Hello, <?php echo htmlspecialchars($username); ?>! You are successfully logged in.</p>
        <?php else: ?>
            <p>Hello! You are successfully logged in.</p>
        <?php endif; ?>
        <p><a href="?logout=true">Log out</a></p>
    </div>
</body>
</html>
