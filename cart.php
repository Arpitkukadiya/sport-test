<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$p_qty, $cart_id]);
    $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <link rel="stylesheet" href="css/style.css">
    <style>
       .box-container .box img{
   width: 100%;
  height: 12vw;
   margin-bottom: 1rem;
}
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="shopping-cart">

    <h1 class="title">Products Added</h1>

    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="POST" class="box">
                    <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="Product Image">
                    <div class="name"><?= $fetch_cart['name']; ?></div>
                    <div class="price">₹<?= $fetch_cart['price']; ?>/-</div>
                    <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                    <div class="flex-btn">
                        <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
                        <input type="submit" value="update" name="update_qty" class="option-btn">
                        <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="btn">Product Info</a>
                        <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="btn" onclick="return confirm('Delete this item?');">Delete</a>
                    </div>
                    <div class="sub-total">Sub Total: ₹<?= $sub_total = $fetch_cart['price'] * $fetch_cart['quantity']; ?>/-</div>
                </form>
                <?php
                $grand_total += $sub_total;
            }
        } else {
            echo '<p class="empty">Your cart is empty</p>';
        }
        ?>
    </div>

    <div class="cart-total">
        <p>Grand Total: ₹<?= $grand_total; ?>/-</p>
        <a href="shop.php" class="option-btn">Continue Shopping</a>
        <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Delete All</a>
        <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
