<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0){

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }else{
         $message[] = 'no user found!';
      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <style>body {
    font-family: 'Verdana', sans-serif;
    background-image: url('78.jpg'); /* Replace '78.jpg' with the actual image */
    background-size: cover;
    background-attachment: fixed;
    background-position: center;
    color: #444; /* Subtle text color */
    margin: 0;
    padding: 0;
    text-align: center;
}

form {
    max-width: 320px;
    margin: 2rem auto;
    background-color: rgba(240, 248, 255, 0.9); /* Soft translucent background */
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    text-align: center;
}

h3 {
    color: #1a73e8; /* Bright heading color */
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

input.box {
    width: 95%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    background-color: #f9f9f9;
    transition: all 0.3s;
}

input.box:focus {
    border-color: #1a73e8;
    background-color: #fff;
    outline: none;
    box-shadow: 0 0 6px rgba(26, 115, 232, 0.3);
}

.btn {
    background: linear-gradient(135deg, #1a73e8, #005cbf);
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(26, 115, 232, 0.4);
}

a {
    color: #0066cc;
    font-size: 0.9rem;
    text-decoration: underline;
    transition: color 0.3s;
}

a:hover {
    color: #004999;
}

.message {
    background-color: #f44336;
    color: white;
    padding: 10px;
    border-radius: 5px;
    margin: 15px auto;
    max-width: 320px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.message i {
    cursor: pointer;
    font-weight: bold;
    padding-left: 10px;
    color: #fff;
}

   
     
        .vk1{
width:99%;
position: absolute;
z-index: -2;
}

    </style>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"-->

   <!-- custom css file link  -->
   <!--link rel="stylesheet" href="css/components.css"-->

</head>
<body>
<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<section class="form-container">

<center>
   <form action="" method="POST">
      <h3>login now</h3>

      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
          <input type="submit" value="login now" class="btn" name="submit">
          <h4> <p>don't have an account? <a href="register.php">register now</a></p></h4>
   </form>
   </section>
</body>
</html>