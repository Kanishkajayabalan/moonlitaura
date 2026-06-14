<?php
/**
 * ========================================
 * PRODUCT DETAIL PAGE
 * Display individual product information
 * ========================================
 */

require_once __DIR__ . '/config/database.php';

$productId = $_GET['id'] ?? null;

if (!$productId) {
    header('Location: /');
    exit;
}

$db = getDatabase();

// Get product details
$stmt = $db->prepare('
    SELECT p.*, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
');
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: /');
    exit;
}

// Get related products
$stmt = $db->prepare('
    SELECT * FROM products
    WHERE category_id = ? AND id != ?
    ORDER BY id DESC
    LIMIT 5
');
$stmt->execute([$product['category_id'], $productId]);
$relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$images = json_decode($product['images'], true) ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Moonlit Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/src/styles/main.css">
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: var(--spacing-2xl);
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-3xl);
            margin-bottom: var(--spacing-3xl);
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
        }

        .product-images {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .main-image {
            width: 100%;
            height: 400px;
            background: rgba(100, 116, 139, 0.1);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--primary-light);
            overflow: hidden;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-images {
            display: flex;
            gap: var(--spacing-md);
            overflow-x: auto;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            background: rgba(100, 116, 139, 0.1);
            border-radius: var(--radius-md);
            cursor: pointer;
            border: 2px solid transparent;
            transition: all var(--transition-base);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: var(--primary-light);
        }

        .product-info h1 {
            margin-bottom: var(--spacing-md);
        }

        .product-pricing {
            margin-bottom: var(--spacing-lg);
            padding: var(--spacing-lg);
            background: rgba(139, 92, 246, 0.05);
            border-radius: var(--radius-md);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .price-display {
            display: flex;
            align-items: center;
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-md);
        }

        .current-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-light);
        }

        .original-price {
            font-size: 1.2rem;
            text-decoration: line-through;
            color: var(--text-muted);
        }

        .discount-tag {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: var(--spacing-xs) var(--spacing-md);
            border-radius: var(--radius-md);
            font-weight: 600;
        }

        .stock-status {
            font-size: 0.9rem;
            margin-top: var(--spacing-md);
        }

        .stock-available {
            color: #10b981;
        }

        .stock-low {
            color: #f59e0b;
        }

        .stock-unavailable {
            color: #ef4444;
        }

        .product-description {
            margin-bottom: var(--spacing-lg);
            line-height: 1.6;
        }

        .product-actions {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .qty-selector {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            background: rgba(100, 116, 139, 0.1);
            padding: var(--spacing-md);
            border-radius: var(--radius-md);
            width: fit-content;
        }

        .qty-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.2rem;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            background: none;
            border: none;
            color: var(--text-primary);
        }

        .wishlist-btn {
            display: none;
        }

        .specifications {
            margin-bottom: var(--spacing-2xl);
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-md);
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
        }

        .related-products {
            margin-top: var(--spacing-3xl);
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--spacing-lg);
        }

        @media (max-width: 1024px) {
            .product-detail {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .product-detail {
                padding: var(--spacing-lg);
            }

            .price-display {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include 'public/includes/navbar.php'; ?>

    <div class="product-detail-container">
        <div class="product-detail">
            <!-- Images -->
            <div class="product-images">
                <div class="main-image" id="mainImage">
                    <?php if (!empty($images)): ?>
                        <img src="<?php echo htmlspecialchars($images[0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <i class="fas fa-image"></i>
                    <?php endif; ?>
                </div>
                <?php if (count($images) > 1): ?>
                    <div class="thumbnail-images">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage('<?php echo htmlspecialchars($image); ?>', this)">
                                <img src="<?php echo htmlspecialchars($image); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="product-pricing">
                    <div class="price-display">
                        <span class="current-price">₹<?php echo number_format($product['discount_price'] ?? $product['price'], 0); ?></span>
                        <?php if ($product['discount_percentage'] > 0): ?>
                            <span class="original-price">₹<?php echo number_format($product['price'], 0); ?></span>
                            <span class="discount-tag"><?php echo $product['discount_percentage']; ?>% OFF</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <div class="stock-status stock-available">
                            <i class="fas fa-check-circle"></i> In Stock <?php echo $product['stock_quantity']; ?> available
                        </div>
                    <?php else: ?>
                        <div class="stock-status stock-unavailable">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </div>
                    <?php endif; ?>
                </div>

                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

                <div class="product-actions">
                    <div class="qty-selector">
                        <button class="qty-btn" onclick="decreaseQty()">−</button>
                        <input type="number" class="qty-input" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                        <button class="qty-btn" onclick="increaseQty(<?php echo $product['stock_quantity']; ?>)">+</button>
                    </div>
                    <button class="btn btn-primary" style="flex: 1;" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['discount_price'] ?? $product['price']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>

                <div class="specifications">
                    <h3 style="margin-bottom: var(--spacing-md);">Specifications</h3>
                    <div class="spec-item">
                        <span>SKU</span>
                        <span><?php echo htmlspecialchars($product['sku']); ?></span>
                    </div>
                    <div class="spec-item">
                        <span>Brand</span>
                        <span><?php echo htmlspecialchars($product['brand']); ?></span>
                    </div>
                    <div class="spec-item">
                        <span>Category</span>
                        <span><?php echo htmlspecialchars($product['category_name']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="related-products">
                <h2 style="margin-bottom: var(--spacing-lg);">Related Products</h2>
                <div class="related-grid">
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="card" onclick="window.location.href='/product.php?id=<?php echo $related['id']; ?>'" style="cursor: pointer;">
                            <div style="height: 150px; background: rgba(100, 116, 139, 0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: var(--spacing-md);">
                                <i class="fas fa-image"></i>
                            </div>
                            <h4 style="margin-bottom: var(--spacing-sm);"><?php echo htmlspecialchars(substr($related['name'], 0, 30)); ?></h4>
                            <p style="color: var(--text-muted); margin-bottom: var(--spacing-md);">₹<?php echo number_format($related['discount_price'] ?? $related['price'], 0); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function changeImage(src, element) {
            document.getElementById('mainImage').innerHTML = '<img src="' + src + '" style="width: 100%; height: 100%; object-fit: cover;">';
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
        }

        function increaseQty(max) {
            const qty = document.getElementById('quantity');
            if (parseInt(qty.value) < max) {
                qty.value = parseInt(qty.value) + 1;
            }
        }

        function decreaseQty() {
            const qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        }

        function addToCart(id, name, price) {
            const qty = document.getElementById('quantity').value;
            const token = localStorage.getItem('authToken');

            if (token) {
                fetch('/backend/api/cart_orders.php?resource=cart&action=add', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: id,
                        quantity: parseInt(qty)
                    })
                }).then(r => r.json()).then(d => {
                    alert('Added to cart!');
                    window.location.href = '/cart.php';
                });
            } else {
                // Add to localStorage for guests
                let cart = JSON.parse(localStorage.getItem('moonlitAuraCart') || '[]');
                const existing = cart.find(i => i.id == id);
                if (existing) {
                    existing.quantity += parseInt(qty);
                } else {
                    cart.push({id, name, price, quantity: parseInt(qty), addedAt: new Date()});
                }
                localStorage.setItem('moonlitAuraCart', JSON.stringify(cart));
                alert('Added to cart!');
                window.location.href = '/cart.php';
            }
        }
    </script>
</body>
</html>
