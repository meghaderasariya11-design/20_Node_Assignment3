
<?php
require 'config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $categoryId = isset($_GET['category_id'])
        ? (int) $_GET['category_id']
        : 0;

    $page = isset($_GET['page'])
        ? max(1, (int) $_GET['page'])
        : 1;

    if ($categoryId <= 0) {
        http_response_code(400);
        echo json_encode([
            'html' => "<p class='message'>Invalid category.</p>",
            'pages' => 0
        ]);
        exit;
    }

    $limit = 5;
    $offset = ($page - 1) * $limit;

    // Count products in the selected category
    $stmt = $pdo->prepare(
        "SELECT COUNT(*)
         FROM products
         WHERE category_id = ?"
    );
    $stmt->execute([$categoryId]);

    $totalProducts = (int) $stmt->fetchColumn();
    $pages = (int) ceil($totalProducts / $limit);

    // Fetch products using your actual database columns
    $stmt = $pdo->prepare(
        "SELECT id, pname, price, picture
         FROM products
         WHERE category_id = ?
         ORDER BY pname
         LIMIT $limit OFFSET $offset"
    );
    $stmt->execute([$categoryId]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '';

    if (count($products) > 0) {
        $html .= "<div class='product-container'>";

        foreach ($products as $product) {
            $id = (int) $product['id'];

            $name = htmlspecialchars(
                $product['pname'],
                ENT_QUOTES,
                'UTF-8'
            );

            $price = number_format(
                (float) $product['price'],
                2
            );

            $picture = $product['picture'] ?? '';

            $html .= "<div class='product-card'>";

            // Product image
            $imagePath = __DIR__ . '/uploads/' . basename($picture);

            if ($picture !== '' && is_file($imagePath)) {
                $image = 'uploads/' . rawurlencode(basename($picture));

                $html .= "
                    <img
                        src='" . htmlspecialchars(
                            $image,
                            ENT_QUOTES,
                            'UTF-8'
                        ) . "'
                        alt='" . $name . "'
                        onerror=\"this.style.display='none'\">
                ";
            } else {
                $html .= "<p>No Image Available</p>";
            }

            // Product details and cart form
            $html .= "
                <h3>" . $name . "</h3>

                <p class='product-price'>
                    ₹" . $price . "
                </p>

                <form action='cart.php' method='POST'>
                    <input
                        type='hidden'
                        name='product_id'
                        value='" . $id . "'>

                    <label>
                        Quantity:
                        <input
                            type='number'
                            name='qty'
                            value='1'
                            min='1'
                            required>
                    </label>

                    <button
                        type='submit'
                        name='add'
                        class='add-cart-btn'>
                        Add to Cart
                    </button>
                </form>
            ";

            $html .= "</div>";
        }

        $html .= "</div>";
    } else {
        $html = "<p class='message'>No products found in this category.</p>";
    }

    echo json_encode([
        'html' => $html,
        'pages' => $pages
    ]);

} catch (Throwable $e) {
    http_response_code(500);

    error_log('Products AJAX error: ' . $e->getMessage());

    echo json_encode([
        'html' => "<p class='message'>Unable to load products.</p>",
        'pages' => 0
    ]);
}
?>
