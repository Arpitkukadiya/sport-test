<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <style>


      .btn1,
.delete-btn,
.option-btn{
   display: block;
   width: 100%;
   margin-top: 1rem;
   border-radius: .5rem;
   color:var(--black);
   font-size: 2rem;
   padding:1.3rem 3rem;
   text-transform: capitalize;
   cursor: pointer;
   text-align: center;

}
.btn1{
   background-color: var(--blue);
}

.delete-btn1{
   background-color: var(--red);
}

.option-btn1{
   background-color: var(--orange);
}

.btn1:hover,
.delete-btn1:hover,
.option-btn1:hover{
   background-color: var(--white);
}

.flex-btn1{
   display: flex;
   flex-wrap: wrap;
   gap:1rem;
}

.flex-btn1 > *{
   flex:1;
}

.vk1{
width:100%;
position: absolute;
z-index: -2;
}
      </style>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<img class="vk1" src="" alt=
"sports">
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>
   <?php
   //this is order pendings
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
         };
      ?>
      <a href="admin_orders.php" class="btn1"><?= $total_pendings; ?>/-<br>orders pendings</a>      
      
      <?php
         $total_completed = 0;
         $select_completed = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completed->execute(['completed']);
         while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
            $total_completed += $fetch_completed['total_price'];
         };
      ?>
      

      <?php
        //this is completed order
        $total_completed = 0;
         $select_completed = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completed->execute(['completed']);
         while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
            $total_completed += $fetch_completed['total_price'];
         };
      ?>
   
      <a href="admin_orders.php" class="btn1"><?= $total_completed; ?>/-<br>completed orders</a>
   

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
      ?>
      <a href="admin_orders.php" class="btn1"><?= $number_of_orders; ?><br>see orders</a>
      
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $number_of_products = $select_products->rowCount();
      ?>
      <a href="admin_products.php" class="btn1"><?= $number_of_products; ?><br>see products</a>


      <?php
         $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
         $select_users->execute(['user']);
         $number_of_users = $select_users->rowCount();
      ?>
      <a href="admin_users.php" class="btn1"><?= $number_of_users; ?><br>see user accounts</a>
     
     
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
         $select_admins->execute(['admin']);
         $number_of_admins = $select_admins->rowCount();
      ?>
      <!--<a href="admin_accounts.php" class="btn1"><?= $number_of_admins; ?><br>admin accounts</a>
      
      -->
      <!--<a href="admin_users.php" class="btn1">see total accounts</a>-->
        
      
      
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `message`");
         $select_messages->execute();
         $number_of_messages = $select_messages->rowCount();
      ?>
      <a href="admin_contacts.php" class="btn1"><?= $number_of_messages; ?><br>messages/feedback</a>

</section>













<script src="js/script.js"></script>

</body>
</html>