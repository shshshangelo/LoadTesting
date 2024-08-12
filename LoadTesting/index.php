<?php
// index.php

// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // If logged in, redirect to the welcome page
    header('Location: welcome.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
        .home-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .home-container:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .home-container h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
            font-weight: 600;
        }
        .home-container p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .home-container a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .home-container a:hover {
            background-color: #0056b3;
        }
        .home-container .link-text {
            color: #333;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="home-container">
        <h1>Hello, there!</h1>
        <p class="link-text">Already have an account?<a href="login.php">Login</a></p>
        <p class="link-text">Not yet registered? <a href="register.php">Sign Up</a></p>
    </div>
</body>
</html>
