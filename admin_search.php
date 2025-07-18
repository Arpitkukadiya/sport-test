<?php

@include 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- Custom CSS -->
    
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         background: #f4f7f8;
      }

      .search-form {
         display: flex;
         justify-content: center;
         padding: 20px;
         background: #007bff;
      }

      .search-form form {
         display: flex;
         gap: 10px;
         width: 100%;
         max-width: 600px;
      }

      .search-form .box {
         flex: 1;
         padding: 10px;
         font-size: 1rem;
         border: 1px solid #ddd;
         border-radius: 5px;
      }

      .search-form .btn {
         padding: 10px 20px;
         background: #0056b3;
         color: #fff;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s;
      }

      .search-form .btn:hover {
         background: #003f7f;
      }

      .products {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
         padding: 20px;
         gap: 20px;
         min-height: 100vh;
      }

      .box {
         background-color: #fff;
         border: 1px solid #ddd;
         border-radius: 8px;
         padding: 15px;
         text-align: center;
         width: 280px;
         box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .box:hover {
         transform: translateY(-5px);
         box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);
      }

      .box img {
         max-width: 100%;
         height: auto;
         border-radius: 6px;
         margin-bottom: 10px;
      }

      .box .name {
         font-size: 1.1rem;
         font-weight: bold;
         color: #333;
         margin: 10px 0;
      }

      .box .details {
         font-size: 0.9rem;
         color: #666;
         margin-bottom: 15px;
      }

      .box-container {
         display: flex;
         flex-wrap: wrap;
         justify-content: space-around;
         gap: 20px;
      }

      .empty {
         font-size: 1.2rem;
         color: #777;
         text-align: center;
         width: 100%;
      }
   </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="search-form">
   <form action="" method="POST">
      <input type="text" class="box" name="search_box" placeholder="Search products...">
      <input type="submit" name="search_btn" value="Search" class="btn">
   </form>
</section>

<section class="products">
   <div class="box-container">
      <?php
         if(isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ? OR category LIKE ?");
            $search_term = "%{$search_box}%";
            $select_products->execute([$search_term, $search_term]);
            if($select_products->rowCount() > 0){
               while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
      ?>
      <form action="" class="box" method="POST">
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <div class="name"><?= $fetch_products['name']; ?> <b>₹<?= $fetch_products['price']; ?></b>/-</div>
         <div class="details"><b><?= $fetch_products['details']; ?></b></div>
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      </form>
      <?php
               }
            } else {
               echo '<p class="empty">No results found!</p>';
            }
         }
      ?>
   </div>
</section>

</body>
</html>
