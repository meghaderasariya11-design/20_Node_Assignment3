
<?php
require 'config.php';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="category-header">
    <h1>Explore Categories</h1>
    <p>Discover products from our different categories.</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="categories.php">Categories</a>
    <a href="add_product.php">Add Product</a>
</nav>

<div class="category-container">

    <?php if (count($categories) > 0): ?>

        <?php foreach ($categories as $category): ?>

            <div class="category-card">

                <div class="category-icon">
                    <?php
                    $name = strtolower($category['name']);

                    if ($name == 'electronics' || $name == 'mobiles') {
                        echo '📱';
                    } elseif ($name == 'clothing' || $name == 'garments') {
                        echo '👕';
                    } elseif ($name == 'books') {
                        echo '📚';
                    } elseif ($name == 'furniture') {
                        echo '🪑';
                    } elseif ($name == 'laptops') {
                        echo '💻';
                    } else {
                        echo '🛍️';
                    }
                    ?>
                </div>

                <h2>
                    <?= htmlspecialchars($category['name']) ?>
                </h2>

                <p>
                    Explore our <?= htmlspecialchars($category['name']) ?> products.
                </p>

                <a class="category-btn"
                   href="products.php?category_id=<?= (int)$category['id'] ?>">
                    View Products →
                </a>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p class="no-category">No categories available.</p>

    <?php endif; ?>

</div>

<footer class="category-footer">
    <p>&copy; 2026 Shopping Inventory System. All rights reserved.</p>
</footer>

</body>
</html>
