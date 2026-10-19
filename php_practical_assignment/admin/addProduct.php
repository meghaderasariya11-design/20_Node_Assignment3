<?php
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

if (isset($_POST['submit'])) {

    $productName = trim($_POST['product_name'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $categoryId = $_POST['category_id'] ?? '';

    if (
        $productName === '' ||
        !is_numeric($price) ||
        $price < 0 ||
        filter_var($quantity, FILTER_VALIDATE_INT) === false ||
        $quantity < 0 ||
        filter_var($categoryId, FILTER_VALIDATE_INT) === false ||
        $categoryId < 1
    ) {
        $message = 'Please enter valid product details.';
        $messageType = 'error';
    } elseif (
        !isset($_FILES['image']) ||
        $_FILES['image']['error'] !== UPLOAD_ERR_OK
    ) {
        $message = 'Please select a product image.';
        $messageType = 'error';
    } else {

        $uploadDir = '../uploads/';

        // Create uploads folder if it doesn't exist.
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate the uploaded image.
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];

        $mimeType = $imageInfo['mime'] ?? '';

        if (
            $imageInfo === false ||
            !isset($allowedTypes[$mimeType]) ||
            $_FILES['image']['size'] > 2 * 1024 * 1024
        ) {
            $message = 'Choose a valid JPG, PNG, GIF or WEBP image under 2 MB.';
            $messageType = 'error';
        } else {

            $newImageName = bin2hex(random_bytes(16))
                . '.' . $allowedTypes[$mimeType];

            $uploadPath = $uploadDir . $newImageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {

                try {
                    // Check that the category exists.
                    $check = $pdo->prepare(
                        "SELECT id FROM categories WHERE id = ?"
                    );

                    $check->execute([$categoryId]);

                    if (!$check->fetch()) {
                        unlink($uploadPath);
                        throw new Exception('Please select a valid category.');
                    }

                    // Insert product into database.
                    $stmt = $pdo->prepare(
                        "INSERT INTO products
                        (pname, price, category_id, picture)
                        VALUES (?, ?, ?, ?)"
                    );

                    $stmt->execute([
                        $productName,
                        $price,
                        $categoryId,
                        $newImageName
                    ]);

                    $message = 'Product added successfully!';
                    $messageType = 'success';

                } catch (Exception $e) {
                    $message = $e instanceof PDOException
                        ? 'Unable to save the product. Please check your database.'
                        : $e->getMessage();

                    $messageType = 'error';

                    if (is_file($uploadPath)) {
                        unlink($uploadPath);
                    }
                }

            } else {
                $message = 'Image upload failed. Check the uploads folder permissions.';
                $messageType = 'error';
            }
        }
    }
}

$categories = $pdo->query(
    "SELECT id, name FROM categories ORDER BY name"
)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product</title>

    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>

<header class="admin-header">

    <div class="brand">
        🛍️ Shopping Inventory System
    </div>

    <div class="header-right">
        <span>
            Welcome,
            <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
        </span>

        <a href="logout.php">Logout</a>
    </div>

</header>

<div class="admin-layout">

    <aside class="sidebar">

        <a href="dashboard.php">🏠 Dashboard</a>

        <a href="products.php" class="active">📦 Products</a>

        <a href="categories.php">📂 Categories</a>

        <a href="../index.php">🌐 User Site</a>

        <a href="logout.php">🚪 Logout</a>

    </aside>

    <main class="main-content">

        <h1 class="page-title">Add New Product</h1>

        <p class="page-description">
            Enter product details and upload a product image.
        </p>

        <?php if ($message !== ''): ?>

            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>

        <div class="content-card">

            <h2>Product Information</h2>

            <form method="POST"
                  enctype="multipart/form-data"
                  class="form-card">

                <label>Product Name</label>

                <input
                    type="text"
                    name="product_name"
                    placeholder="Enter product name"
                    maxlength="150"
                    required
                >

                <label>Price (₹)</label>

                <input
                    type="number"
                    name="price"
                    placeholder="Enter price"
                    min="0"
                    step="0.01"
                    required
                >

                <label>Quantity</label>

                <input
                    type="number"
                    name="quantity"
                    placeholder="Enter available quantity"
                    min="0"
                    required
                >

                <label>Category</label>

                <select name="category_id" required>

                    <option value="">Select Category</option>

                    <?php foreach ($categories as $category): ?>

                        <option value="<?= (int)$category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <label>Product Image</label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.gif,.webp"
                    required
                >

                <button type="submit" name="submit" class="btn primary">
                    + Add Product
                </button>

                <a href="products.php" class="btn btn-danger">
                    Cancel
                </a>

            </form>

        </div>

        <footer class="admin-footer">
            © <?= date('Y') ?> Shopping Inventory System.
        </footer>

    </main>

</div>

</body>
</html>