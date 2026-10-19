<?php
require 'config.php';

// Get category ID
$categoryId = isset($_GET['category_id'])
    ? (int) $_GET['category_id']
    : 0;

// Fetch category
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Check category
if (!$category) {
    die("Category not found. <a href='categories.php'>Back to Categories</a>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($category['name']) ?> - Products</title>

    <link rel="stylesheet" href="style.css?v=3">

    <style>
        .page-title {
            text-align: center;
            margin: 30px 0;
            color: #263445;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 25px;
            padding: 20px;
        }

        .product-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 190px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .product-card h3 {
            margin: 10px 0;
            color: #263445;
        }

        .product-price {
            color: #16803c;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-stock {
            color: #666;
            margin-bottom: 12px;
        }

        .quantity-input {
            width: 70px;
            padding: 8px;
            margin: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-cart-btn {
            display: inline-block;
            width: 100%;
            padding: 11px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .add-cart-btn:hover {
            background: #1d4ed8;
        }

        .add-cart-btn:disabled {
            background: #999;
            cursor: not-allowed;
        }

        .pagination {
            text-align: center;
            margin: 25px 0;
        }

        .pagination button {
            padding: 9px 14px;
            margin: 4px;
            border: none;
            border-radius: 5px;
            background: #e5e7eb;
            cursor: pointer;
        }

        .pagination button.active {
            background: #2563eb;
            color: white;
        }

        .message {
            text-align: center;
            padding: 15px;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 20px;
            padding: 18px 25px;
            background: #263445;
        }

        nav a {
            color: white;
            text-decoration: none;
        }

        nav a:hover {
            color: #93c5fd;
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="cart.php">Cart</a>
    </nav>

    <h1 class="page-title">
        <?= htmlspecialchars($category['name']) ?>
    </h1>

    <div id="message" class="message"></div>

    <div id="productList">
        <p class="message">Loading products...</p>
    </div>

    <div id="pages" class="pagination"></div>

    <script>
        const categoryId = <?= json_encode($categoryId) ?>;

        function loadProducts(page = 1) {
            const productList = document.getElementById('productList');

            productList.innerHTML = '<p class="message">Loading products...</p>';

            fetch(
                'products_ajax.php?category_id=' +
                categoryId + '&page=' + page
            )
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch products');
                }

                return response.json();
            })
            .then(data => {
                productList.innerHTML = data.html || 
                    '<p class="message">No products found in this category.</p>';

                let links = '';

                for (let i = 1; i <= data.pages; i++) {
                    links += `
                        <button
                            class="${i === page ? 'active' : ''}"
                            onclick="loadProducts(${i})">
                            ${i}
                        </button>
                    `;
                }

                document.getElementById('pages').innerHTML = links;
            })
            .catch(error => {
                console.error(error);

                productList.innerHTML =
                    '<p class="message">Could not load products. Please check products_ajax.php.</p>';
            });
        }

        // Show add-to-cart result
        const params = new URLSearchParams(window.location.search);

        if (params.get('cart') === 'added') {
            document.getElementById('message').textContent =
                'Product added to cart successfully!';
        } else if (params.get('cart') === 'error') {
            document.getElementById('message').textContent =
                'Could not add product. Please check the quantity and available stock.';
        }

        loadProducts();
    </script>

</body>

</html>