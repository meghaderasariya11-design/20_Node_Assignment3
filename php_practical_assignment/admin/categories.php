<?php
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {

        $categoryName = trim($_POST['category_name'] ?? '');

        if ($categoryName === '') {
            $message = 'Please enter a category name.';
            $messageType = 'error';
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO categories (name) VALUES (?)"
                );

                $stmt->execute([$categoryName]);

                $message = 'Category added successfully.';
                $messageType = 'success';

            } catch (PDOException $e) {
                $message = 'Unable to add category.';
                $messageType = 'error';
            }
        }
    }

    if ($action === 'delete') {

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            try {
                $stmt = $pdo->prepare(
                    "DELETE FROM categories WHERE id = ?"
                );

                $stmt->execute([$id]);

                $message = $stmt->rowCount()
                    ? 'Category deleted successfully.'
                    : 'Category not found.';

                $messageType = $stmt->rowCount()
                    ? 'success'
                    : 'error';

            } catch (PDOException $e) {
                $message = 'Cannot delete this category. Products may be using it.';
                $messageType = 'error';
            }
        }
    }
}

$categories = $pdo->query("
    SELECT
        c.id,
        c.name AS category_name,
        COUNT(p.id) AS product_count
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id, c.name
    ORDER BY c.name
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>

<header class="admin-header">
    <div class="brand">🛍️ Shopping Inventory System</div>

    <div class="header-right">
        <span>Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
        <a href="logout.php">Logout ↗</a>
    </div>
</header>

<div class="admin-layout">

    <aside class="sidebar">
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="products.php">📦 Products</a>
        <a class="active" href="categories.php">📂 Categories</a>
        <a href="../index.php">🌐 User Site</a>
        <a href="logout.php">🚪 Logout</a>
    </aside>

    <main class="main-content">

        <h1 class="page-title">Manage Categories</h1>

        <p class="page-description">
            Add and manage product categories.
        </p>

        <?php if ($message !== ''): ?>
            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="content-card">

            <h2>Add New Category</h2>

            <form method="post" class="form-card">

                <input type="hidden" name="action" value="add">

                <label>Category Name</label>

                <input
                    type="text"
                    name="category_name"
                    placeholder="Example: Electronics"
                    maxlength="100"
                    required
                >

                <button type="submit" class="btn primary">
                    + Add Category
                </button>

            </form>

        </div>

        <h2 style="margin-bottom: 18px;">All Categories</h2>

        <div class="category-grid">

            <?php if (count($categories) > 0): ?>

                <?php foreach ($categories as $category): ?>

                    <div class="category-card">

                        <div class="category-icon">📂</div>

                        <h3>
                            <?= htmlspecialchars($category['category_name']) ?>
                        </h3>

                        <p>
                            <?= (int)$category['product_count'] ?>
                            product(s) in this category
                        </p>

                        <div class="actions">

                            <a
                                href="products.php?category_id=<?= (int)$category['id'] ?>"
                                class="btn primary"
                            >
                                View Products
                            </a>

                            <form method="post" class="inline-form"
                                  onsubmit="return confirm('Delete this category?')">

                                <input type="hidden" name="action" value="delete">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int)$category['id'] ?>"
                                >

                                <button type="submit" class="btn btn-danger">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No categories found. Add your first category above.</p>

            <?php endif; ?>

        </div>

        <footer class="admin-footer">
            © <?= date('Y') ?> Shopping Inventory System.
        </footer>

    </main>

</div>

</body>
</html>