<?php
// Database configuration
$host = 'localhost';         // Replace with your database host (default is localhost)
$dbname = 'sports';          // Replace with your database name
$username = 'root';          // Replace with your database username (default is root for XAMPP)
$password = '';              // Replace with your database password (default is empty for XAMPP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['verify'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $otp = filter_var($_POST['otp'], FILTER_SANITIZE_NUMBER_INT);

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND otp = ?");
    $select->execute([$email, $otp]);

    if ($select->rowCount() > 0) {
        $update = $conn->prepare("UPDATE `users` SET verified = 1, otp = NULL WHERE email = ?");
        $update->execute([$email]);
        $message = 'Email verified successfully!';
        header('Location: login.php');
        exit();
    } else {
        $message = 'Invalid OTP!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        form {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h3 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }

        input.box {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input.box:focus {
            border-color: #66a6ff;
            box-shadow: 0 0 5px rgba(102, 166, 255, 0.8);
            outline: none;
        }

        input[type="submit"] {
            background-color: #66a6ff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #4d8fd1;
        }

        .message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .vk1 {
            width: 99%;
            position: absolute;
            z-index: -2;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    
<img class="vk1" src="78.jpg" alt="sports">
    <form action="" method="POST">
        <h3>Email Verification</h3>
        <input type="email" name="email" class="box" placeholder="Enter your email" required>
        <input type="number" name="otp" class="box" placeholder="Enter the OTP" required>
        <input type="submit" name="verify" value="Verify Email">
    </form>
    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
</body>
</html>