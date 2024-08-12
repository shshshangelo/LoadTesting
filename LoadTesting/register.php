<?php
// register.php

// Database configuration
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "loadtesting";

// Start the session
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
    if (empty($input_username) || empty($input_password) || empty($confirm_password)) {
        $error_message = 'All fields are required.';
    } elseif ($input_password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM loadtesting WHERE username = ?");
        $stmt->bind_param("s", $input_username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error_message = 'Username already taken.';
        } else {
            // Hash the password
            $password_hash = password_hash($input_password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO loadtesting (username, password_hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $input_username, $password_hash);
            if ($stmt->execute()) {
                header('Location: login.php'); // Redirect to login page after successful registration
                exit;
            } else {
                $error_message = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        .register-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .register-container:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .register-container h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
            font-weight: 600;
        }
        .register-container input[type="text"],
        .register-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .register-container input[type="text"]:focus,
        .register-container input[type="password"]:focus {
            border-color: #007bff;
        }
        .register-container input[type="submit"] {
            width: 50%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .register-container input[type="submit"]:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .error-message {
            color: #e74c3c;
            margin: 10px 0;
            font-size: 14px;
        }
        .register-container p {
            margin-top: 10px;
        }
        .register-container a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
        .password-container {
            position: relative;
            width: 100%;
        }
        .password-container input[type="password"] {
            padding-right: 15px;
        }
        .password-container .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInputs = document.querySelectorAll('input[type="password"]');
            passwordInputs.forEach(passwordInput => {
                const togglePassword = passwordInput.parentElement.querySelector('.toggle-password');
                togglePassword.addEventListener('click', () => {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        togglePassword.textContent = '👁️'; // Eye icon
                    } else {
                        passwordInput.type = 'password';
                        togglePassword.textContent = '🙈'; // Monkey icon
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="register-container">
        <h1>Sign Up</h1>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <div class="password-container">
                <input type="password" name="password" placeholder="Password" required>
                <span class="toggle-password">🙈</span> <!-- Monkey icon for hidden password -->
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password">🙈</span> <!-- Monkey icon for hidden password -->
            </div>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
