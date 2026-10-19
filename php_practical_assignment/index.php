<?php require 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <link rel="stylesheet" href="style.css?v=2">
</head>

<body>

<h1>Shopping Cart</h1>

<nav>
    <a href="categories.php">Shop by Category</a>
    <a href="register.php">Register</a>
    <a href="login.php">User Login</a>
    <a href="cart.php">Cart</a>
    <a href="admin/login.php">Admin Login</a>
</nav>

<p>Choose a category to view products. This beginner project includes the requested PHP practical tasks.</p>

<h3>Categories</h3>

<?php
$rows = $pdo->query(
    "SELECT * FROM categories ORDER BY name"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
    <?php foreach ($rows as $c): ?>
        <li>
            <a href="products.php?category_id=<?= $c['id'] ?>">
                <?= htmlspecialchars($c['name']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>