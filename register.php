<?php
// Database configuration
$host = 'localhost';         // Replace with your database host (default is localhost)
$dbname = 'sports';          // Replace with your database name
$username = 'root';          // Replace with your database username (default is root for XAMPP)
$password = '';              // Replace with your database password (default is empty for XAMPP)

// Database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = md5(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));
    $cpass = md5(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $otp = rand(100000, 999999); // Generate OTP

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);

    if ($select->rowCount() > 0) {
        $message[] = 'User  email already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Passwords do not match!';
        } else {
            $insert = $conn->prepare("INSERT INTO `users` (name, email, password, image, otp) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$name, $email, $pass, $image, $otp]);

            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);

                // Send OTP
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'arpitkukadiya10@gmail.com'; // Change to your email
                    $mail->Password = 'crmscaebqyzqvist'; // Change to your email password
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;
                    $mail->setFrom('your_email@gmail.com', 'Sports Website');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification - OTP';
                    $mail->Body = "<p>Hello $name,</p><p>Your OTP is <strong>$otp</strong>.</p>";

                    $mail->send();
                    $message[] = 'Registration successful! Verify your email.';
                    header('Location: verify_email.php?email=' . urlencode($email));
                    exit();
                } catch (Exception $e) {
                    $message[] = 'Error sending email: ' . $e->getMessage();
                }
            }
        }
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
        }

        input.box {
            width: 100%;
            padding: 12px;
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

        .btn {
            background-color: #66a6ff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #4d8fd1;
        }

        a {
            color: #66a6ff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .vk1 {
            width: 99%;
            position: absolute;
            z-index: -2;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <img class="vk1" src="78.jpg" alt="sports">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Register</h3>
        <table>
            <tr>
                <td><input type="text" name="name" class="box" placeholder="Enter your name" required></td>
            </tr>
            <tr>
                <td><input type="email" name="email" class="box" placeholder="Enter your email" required></td>
            </tr>
            <tr>
                <td><input type="password" name="pass" class="box" placeholder="Enter your password" required></td>
            </tr>
            <tr>
                <td><input type="password" name="cpass" class="box" placeholder="Confirm your password" required></td>
            </tr>
            <tr>
                <td><input type="file" name="image" required></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" class="btn" value="Register"></td>
            </tr>
        </table>
    </form>
    <?php if (isset($message)) { foreach ($message as $msg) { echo "<p class='message'>$msg</p>"; } } ?>
</body>
</html>