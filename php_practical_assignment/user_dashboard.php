<?php require 'config.php'; if(!isset($_SESSION['user_id'])){header('Location: login.php');exit;} ?>
<!doctype html><html><head><title>Dashboard</title><link rel="stylesheet" href="style.css"></head><body>
<h1>Welcome, <?=htmlspecialchars($_SESSION['user_name'])?></h1>
<a href="categories.php">Shop</a> | <a href="cart.php">Cart</a> | <a href="logout.php">Logout</a>
</body></html>