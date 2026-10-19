
<?php
require 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = '';

/* ADD PRODUCT TO CART */

if (isset($_POST['add'])) {

    $id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

    if (!$id || !$qty || $qty < 1) {

        $message = 'Please select a valid product and quantity.';

    } else {

        // Fetch product details using your actual database columns.
        $stmt = $pdo->prepare("
            SELECT id, pname, price
            FROM products
            WHERE id = ?
        ");

        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {

            $message = 'This product is unavailable.';

        } else {

            $currentQty = $_SESSION['cart'][$id]['qty'] ?? 0;
            $newQty = $currentQty + $qty;

            $_SESSION['cart'][$id] = [
                'pname' => $product['pname'],
                'price' => $product['price'],
                'qty' => $newQty
            ];

            header('Location: cart.php');
            exit;
        }
    }
}

/* REMOVE PRODUCT FROM CART */

if (isset($_GET['remove'])) {

    $removeId = filter_input(
        INPUT_GET,
        'remove',
        FILTER_VALIDATE_INT
    );

    if ($removeId) {
        unset($_SESSION['cart'][$removeId]);
    }

    header('Location: cart.php');
    exit;
}

/* CALCULATE TOTAL */

$total = 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css?v=2">
    
</head>

<body>

<h1>Shopping Cart 🛒</h1>

<nav>
    <a href="index.php">Home</a>
    <a href="categories.php">Categories</a>
    <a href="products.php">Continue Shopping</a>
</nav>

<?php if ($message !== ''): ?>

    <p style="color:red; text-align:center;">
        <?= htmlspecialchars($message) ?>
    </p>

<?php endif; ?>

<div class="table-wrapper">

    <table>

        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!empty($_SESSION['cart'])): ?>

            <?php foreach ($_SESSION['cart'] as $id => $item): ?>

                <?php
                $subtotal = (float)$item['price'] * (int)$item['qty'];
                $total += $subtotal;
                ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($item['pname']) ?>
                    </td>

                    <td>
                        ₹<?= number_format((float)$item['price'], 2) ?>
                    </td>

                    <td>
                        <?= (int)$item['qty'] ?>
                    </td>

                    <td>
                        ₹<?= number_format($subtotal, 2) ?>
                    </td>

                    <td>
                        <a
                            href="cart.php?remove=<?= (int)$id ?>"
                            onclick="return confirm('Remove this product from your cart?')"
                            class="btn-danger"
                        >
                            Remove
                        </a>
                    </td>

                </tr>

            <?php endforeach; ?>

            <tr>
                <th colspan="3">Grand Total</th>

                <th>
                    ₹<?= number_format($total, 2) ?>
                </th>

                <th></th>
            </tr>

        <?php else: ?>

            <tr>
                <td colspan="5" style="text-align:center;">
                    Your cart is empty.
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<br>

<div style="text-align:center;">

    <a href="products.php" class="btn-primary">
        Continue Shopping
    </a>

</div>

</body>
</html>