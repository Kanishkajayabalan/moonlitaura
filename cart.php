<?php
/**
 * ========================================
 * SHOPPING CART PAGE
 * View and manage shopping cart
 * ========================================
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/backend/middleware/Auth.php';

// Get token if available (cart works for guests too)
$token = JWT::getTokenFromHeader();
$userId = null;
if ($token) {
    try {
        $user = JWT::decode($token);
        $userId = $user['user_id'];
    } catch (Exception $e) {
        // Continue as guest
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Moonlit Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/src/styles/main.css">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: var(--spacing-2xl);
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: var(--spacing-2xl);
        }

        .cart-items-section {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
        }

        .cart-item {
            display: flex;
            gap: var(--spacing-lg);
            padding: var(--spacing-lg);
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
            transition: all var(--transition-base);
        }

        .cart-item:hover {
            background: rgba(139, 92, 246, 0.05);
        }

        .cart-item-image {
            width: 120px;
            height: 120px;
            background: rgba(100, 116, 139, 0.1);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .cart-item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
            color: var(--text-primary);
        }

        .item-price {
            color: var(--primary-light);
            font-weight: 600;
            margin-bottom: var(--spacing-md);
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            background: rgba(100, 116, 139, 0.1);
            width: fit-content;
            padding: var(--spacing-sm);
            border-radius: var(--radius-md);
        }

        .qty-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: var(--spacing-xs) var(--spacing-sm);
            font-size: 1rem;
        }

        .qty-btn:hover {
            color: var(--primary-light);
        }

        .qty-input {
            width: 50px;
            text-align: center;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: var(--spacing-xs);
            border-radius: var(--radius-sm);
        }

        .item-actions {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-md);
            font-size: 0.9rem;
        }

        .action-link {
            color: var(--primary-light);
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .cart-summary {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-md);
            padding-bottom: var(--spacing-md);
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
        }

        .summary-row:last-of-type {
            border-bottom: none;
        }

        .summary-label {
            color: var(--text-secondary);
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-lg);
            padding: var(--spacing-lg);
            background: rgba(139, 92, 246, 0.1);
            border-radius: var(--radius-md);
            font-size: 1.2rem;
            font-weight: 700;
            border: 1px solid var(--primary-light);
        }

        .coupon-section {
            margin-bottom: var(--spacing-lg);
        }

        .coupon-input-group {
            display: flex;
            gap: var(--spacing-sm);
        }

        .coupon-input {
            flex: 1;
            padding: var(--spacing-md);
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-primary);
        }

        .coupon-btn {
            padding: var(--spacing-md) var(--spacing-lg);
            background: rgba(139, 92, 246, 0.2);
            border: 1px solid var(--primary-light);
            border-radius: var(--radius-md);
            color: var(--primary-light);
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-base);
        }

        .coupon-btn:hover {
            background: var(--primary-light);
            color: var(--darker-bg);
        }

        .empty-cart {
            text-align: center;
            padding: var(--spacing-3xl);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: var(--spacing-lg);
            opacity: 0.5;
        }

        @media (max-width: 1024px) {
            .cart-container {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .cart-item {
                flex-direction: column;
            }

            .cart-item-image {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'public/includes/navbar.php'; ?>

    <div class="cart-container">
        <!-- Cart Items -->
        <div class="cart-items-section">
            <h1 style="margin-bottom: var(--spacing-lg);"><i class="fas fa-shopping-cart" style="margin-right: var(--spacing-md);"></i>Shopping Cart</h1>
            
            <div id="cartItems">
                <div class="empty-cart">
                    <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
                    <h3>Your cart is empty</h3>
                    <p style="color: var(--text-muted); margin-bottom: var(--spacing-lg);">Start shopping to add items</p>
                    <button class="btn btn-primary" onclick="window.location.href='/'">
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <h3 style="margin-bottom: var(--spacing-lg);">Order Summary</h3>

            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">₹<span id="subtotal">0</span></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Shipping</span>
                <span class="summary-value">₹<span id="shipping">99</span></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Tax (GST 18%)</span>
                <span class="summary-value">₹<span id="tax">0</span></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Discount</span>
                <span class="summary-value" style="color: #10b981;">-₹<span id="discount">0</span></span>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <span>₹<span id="total">0</span></span>
            </div>

            <div class="coupon-section">
                <div class="coupon-input-group">
                    <input type="text" class="coupon-input" placeholder="Enter coupon code" id="couponCode">
                    <button class="coupon-btn" onclick="applyCoupon()">Apply</button>
                </div>
            </div>

            <button class="btn btn-primary" style="width: 100%; margin-bottom: var(--spacing-md);" onclick="goToCheckout()">
                <i class="fas fa-lock"></i> Proceed to Checkout
            </button>

            <button class="btn btn-secondary" style="width: 100%;" onclick="window.location.href='/'">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </button>
        </div>
    </div>

    <script src="/src/js/cart.js"></script>
    <script>
        // Load cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
        });

        function goToCheckout() {
            if (localStorage.getItem('authToken')) {
                window.location.href = '/checkout.php';
            } else {
                alert('Please login first');
                window.location.href = '/login.html';
            }
        }

        function applyCoupon() {
            const code = document.getElementById('couponCode').value;
            if (code) {
                alert('Coupon validation coming soon!');
            }
        }
    </script>
</body>
</html>
