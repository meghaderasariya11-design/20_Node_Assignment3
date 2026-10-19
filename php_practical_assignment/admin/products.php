
<?php
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$categoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

$sql = "
    SELECT
        p.id,
        p.pname,
        p.price,
        p.picture,
        c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
";

$params = [];

if ($categoryId !== false && $categoryId !== null && $categoryId > 0) {
    $sql .= " WHERE p.category_id = ?";
    $params[] = $categoryId;
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("
    SELECT id, name
    FROM categories
    ORDER BY name
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
        <a href="dashboard.php">🏠 Dashboard</a>
        <a class="active" href="products.php">📦 Products</a>
        <a href="categories.php">📂 Categories</a>
        <a href="../index.php">🌐 User Site</a>
        <a href="logout.php">🚪 Logout</a>
    </aside>

    <main class="main-content">

        <h1 class="page-title">Manage Products</h1>

        <p class="page-description">
            View and manage your inventory.
        </p>

        <div class="content-card">

            <div style="display:flex; justify-content:space-between;
                        align-items:center; gap:15px; flex-wrap:wrap;">

                <h2>All Products (<?= count($products) ?>)</h2>

                <a href="addProduct.php" class="btn primary">
                    + Add Product
                </a>

            </div>

            <form method="get" style="margin-bottom:20px;">

                <label for="category_id">Filter by Category</label>

                <select
                    id="category_id"
                    name="category_id"
                    onchange="this.form.submit()"
                    style="padding:10px; margin-top:8px; border-radius:5px;"
                >
                    <option value="">All Categories</option>

                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= (int)$category['id'] ?>"
                            <?= $categoryId == $category['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </form>

            <div class="table-wrapper">

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($products) > 0): ?>

                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= (int)$product['id'] ?></td>

                                <td>
                                    <?php
                                    $picture = $product['picture'] ?? '';
                                    $imagePath = '../uploads/' . basename($picture);
                                    ?>

                                    <?php if (
                                        $picture !== '' &&
                                        is_file(__DIR__ . '/../uploads/' . basename($picture))
                                    ): ?>
                                        <img
                                            src="<?= htmlspecialchars($imagePath) ?>"
                                            alt="<?= htmlspecialchars($product['pname']) ?>"
                                            style="width:60px;height:60px;object-fit:cover;"
                                        >
                                    <?php else: ?>
                                        📦
                                    <?php endif; ?>
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
                                    ₹<?= number_format((float)$product['price'], 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5">No products found.</td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>

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
