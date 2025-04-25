<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $index = (int)$_POST['index'];
  $new_quantity = (int)$_POST['quantity'];

  if (isset($_SESSION['cart'][$index])) {
    if ($new_quantity > 0) {
      $_SESSION['cart'][$index]['quantity'] = $new_quantity;
      echo 'Quantity updated successfully.';
    } else {
      unset($_SESSION['cart'][$index]); // Remove the item if quantity is 0
      echo 'Product removed from cart.';
    }
  } else {
    echo 'Invalid product index.';
  }
}
?>
