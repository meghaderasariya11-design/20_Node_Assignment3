
<?php
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$productCount = $pdo->query(
    "SELECT COUNT(*) FROM products"
)->fetchColumn();

$categoryCount = $pdo->query(
    "SELECT COUNT(*) FROM categories"
)->fetchColumn();

$userCount = $pdo->query(
    "SELECT COUNT(*) FROM users"
)->fetchColumn();

$adminCount = $pdo->query(
    "SELECT COUNT(*) FROM admins"
)->fetchColumn();

// Correct column names according to your database
$stmt = $pdo->query("
    SELECT
        p.id,
        p.pname,
        p.price,
        c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
    LIMIT 5
");

$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>

<header class="admin-header">
    <div class="brand">🛍️ Shopping Inventory System</div>

    <div class="header-right">
        <span>
            Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
        </span>
        <a href="logout.php">Logout ↗</a>
    </div>
</header>

<div class="admin-layout">

    <aside class="sidebar">
        <a class="active" href="dashboard.php">🏠 Dashboard</a>
        <a href="products.php">📦 Products</a>
        <a href="categories.php">📂 Categories</a>
        <a href="../index.php">🌐 User Site</a>
        <a href="logout.php">🚪 Logout</a>
    </aside>

    <main class="main-content">

        <h1 class="page-title">Admin Dashboard</h1>

        <p class="page-description">
            Manage your products, categories and users.
        </p>

        <div class="stats-grid">

            <div class="stat-card blue">
                <h3>📦 Total Products</h3>
                <div class="number"><?= $productCount ?></div>
            </div>

            <div class="stat-card green">
                <h3>📂 Total Categories</h3>
                <div class="number"><?= $categoryCount ?></div>
            </div>

            <div class="stat-card purple">
                <h3>👥 Registered Users</h3>
                <div class="number"><?= $userCount ?></div>
            </div>

            <div class="stat-card orange">
                <h3>🛡️ Total Admins</h3>
                <div class="number"><?= $adminCount ?></div>
            </div>

        </div>

        <div class="content-card">

            <h2>Recent Products</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($products) > 0): ?>

                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?= (int) $product['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['pname']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $product['category_name'] ?? 'Uncategorized'
                                    ) ?>
                                </td>

                                <td>
                                    ₹<?= number_format(
                                        (float) $product['price'], 2
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4">No products available.</td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <br>

            <a href="products.php" class="btn primary">
                View All Products →
            </a>

        </div>

        <div class="content-card">
            <h2>Quick Actions</h2>

            <div class="actions">
                <a href="products.php" class="btn primary">
                    📦 Manage Products
                </a>

                <a href="categories.php" class="btn btn-success">
                    📂 Manage Categories
                </a>
            </div>
        </div>

        <footer class="admin-footer">
            © <?= date('Y') ?> Shopping Inventory System.
            All rights reserved.
        </footer>

    </main>

</div>

</body>
</html>
