<?php
/**
 * ========================================
 * CHECKOUT PAGE
 * Multi-step checkout process
 * ======================================== */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/backend/middleware/Auth.php';

// Require authentication
$token = JWT::getTokenFromHeader();
if (!$token) {
    header('Location: /login.html');
    exit;
}

try {
    $user = JWT::decode($token);
    $userId = $user['user_id'];
} catch (Exception $e) {
    header('Location: /login.html');
    exit;
}

$db = getDatabase();

// Get user info
$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user addresses
$stmt = $db->prepare('SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC');
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get cart items
$stmt = $db->prepare('
    SELECT c.*, p.name, p.price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
');
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    header('Location: /cart.php');
    exit;
}

// Calculate totals
$subtotal = array_sum(array_map(fn($item) => $item['price_at_add'] * $item['quantity'], $cartItems));
$shipping = $subtotal >= 499 ? 0 : 99;
$tax = $subtotal * 0.18;
$total = $subtotal + $shipping + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Moonlit Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/src/styles/main.css">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: var(--spacing-2xl);
        }

        .checkout-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-3xl);
            position: relative;
        }

        .checkout-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(100, 116, 139, 0.2);
            z-index: -1;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: rgba(100, 116, 139, 0.1);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            margin: 0 auto var(--spacing-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all var(--transition-base);
        }

        .step.active .step-number {
            background: var(--primary-light);
            border-color: var(--primary-light);
            color: var(--darker-bg);
        }

        .step.completed .step-number {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .step.active .step-label {
            color: var(--primary-light);
            font-weight: 600;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: var(--spacing-2xl);
        }

        .checkout-form {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
        }

        .form-section {
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-2xl);
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .address-options {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .address-option {
            padding: var(--spacing-lg);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .address-option:hover {
            border-color: var(--primary-light);
        }

        .address-option.selected {
            border-color: var(--primary-light);
            background: rgba(139, 92, 246, 0.05);
        }

        .address-option input[type="radio"] {
            margin-right: var(--spacing-md);
        }

        .address-text {
            margin-left: 28px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .shipping-options {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .shipping-option {
            padding: var(--spacing-lg);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .shipping-option:hover {
            border-color: var(--primary-light);
        }

        .shipping-option.selected {
            border-color: var(--primary-light);
            background: rgba(139, 92, 246, 0.05);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
        }

        .payment-method {
            padding: var(--spacing-lg);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            cursor: pointer;
            text-align: center;
            transition: all var(--transition-base);
        }

        .payment-method:hover {
            border-color: var(--primary-light);
        }

        .payment-method.selected {
            border-color: var(--primary-light);
            background: rgba(139, 92, 246, 0.05);
        }

        .payment-icon {
            font-size: 2rem;
            margin-bottom: var(--spacing-md);
            color: var(--primary-light);
        }

        .order-summary {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-md);
            padding-bottom: var(--spacing-md);
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-lg);
            background: rgba(139, 92, 246, 0.1);
            border-radius: var(--radius-md);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: var(--spacing-lg);
            border: 1px solid var(--primary-light);
        }

        .place-order-btn {
            width: 100%;
            padding: var(--spacing-lg);
            background: var(--primary-light);
            border: none;
            border-radius: var(--radius-md);
            color: var(--darker-bg);
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: all var(--transition-base);
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
        }

        @media (max-width: 1024px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
</head>
<body>
    <?php include 'public/includes/navbar.php'; ?>

    <div class="checkout-container">
        <h1 style="margin-bottom: var(--spacing-2xl);"><i class="fas fa-lock" style="margin-right: var(--spacing-md);"></i>Secure Checkout</h1>

        <!-- Progress -->
        <div class="checkout-progress">
            <div class="step completed">
                <div class="step-number">✓</div>
                <div class="step-label">Cart</div>
            </div>
            <div class="step active">
                <div class="step-number">2</div>
                <div class="step-label">Details</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-label">Confirm</div>
            </div>
        </div>

        <form id="checkoutForm" method="POST" action="/backend/api/cart_orders.php?resource=orders&action=create">
            <div class="checkout-content">
                <!-- Checkout Form -->
                <div class="checkout-form">
                    <!-- Delivery Address -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-map-marker-alt"></i> Delivery Address
                        </div>
                        <div class="address-options">
                            <?php if (!empty($addresses)): ?>
                                <?php foreach ($addresses as $address): ?>
                                    <label class="address-option <?php echo $address['is_default'] ? 'selected' : ''; ?>">
                                        <input type="radio" name="address_id" value="<?php echo $address['id']; ?>" <?php echo $address['is_default'] ? 'checked' : ''; ?> required>
                                        <div class="address-text">
                                            <strong><?php echo htmlspecialchars($address['full_name']); ?></strong> • <?php echo ucfirst($address['address_type']); ?><br>
                                            <?php echo htmlspecialchars($address['address_line_1']); ?><br>
                                            <?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['state']); ?> - <?php echo htmlspecialchars($address['postal_code']); ?><br>
                                            <small><?php echo htmlspecialchars($address['phone']); ?></small>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-truck"></i> Shipping Method
                        </div>
                        <div class="shipping-options">
                            <label class="shipping-option selected">
                                <div>
                                    <div style="font-weight: 600;">Standard Delivery</div>
                                    <small style="color: var(--text-muted);">Delivery in 5-7 days</small>
                                </div>
                                <div style="font-weight: 600; color: var(--primary-light);">₹99</div>
                            </label>
                            <label class="shipping-option">
                                <div>
                                    <div style="font-weight: 600;">Express Delivery</div>
                                    <small style="color: var(--text-muted);">Delivery in 2-3 days</small>
                                </div>
                                <div style="font-weight: 600; color: var(--primary-light);">₹199</div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-credit-card"></i> Payment Method
                        </div>
                        <div class="payment-methods">
                            <label class="payment-method selected">
                                <input type="radio" name="payment_method" value="razorpay" checked style="display: none;">
                                <div class="payment-icon"><i class="fas fa-wallet"></i></div>
                                <div style="font-weight: 600;">Card/UPI</div>
                                <small style="color: var(--text-muted);">Razorpay</small>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cod" style="display: none;">
                                <div class="payment-icon"><i class="fas fa-hand-holding-usd"></i></div>
                                <div style="font-weight: 600;">Cash</div>
                                <small style="color: var(--text-muted);">On Delivery</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3 style="margin-bottom: var(--spacing-lg);">Order Summary</h3>

                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: var(--spacing-lg);">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="summary-item">
                                <div>
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <small style="color: var(--text-muted);">Qty: <?php echo $item['quantity']; ?></small>
                                </div>
                                <div style="font-weight: 600;">₹<?php echo number_format($item['price_at_add'] * $item['quantity'], 0); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>₹<?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>₹<?php echo number_format($shipping, 2); ?></span>
                    </div>

                    <div class="summary-item">
                        <span>Tax (18%)</span>
                        <span>₹<?php echo number_format($tax, 2); ?></span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>₹<?php echo number_format($total, 2); ?></span>
                    </div>

                    <button type="submit" class="place-order-btn">
                        <i class="fas fa-check"></i> Place Order
                    </button>

                    <button type="button" class="btn btn-secondary" style="width: 100%; margin-top: var(--spacing-md);" onclick="window.location.href='/cart.php'">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Make payment methods interactive
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Make shipping options interactive
        document.querySelectorAll('.shipping-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.shipping-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Make address options interactive
        document.querySelectorAll('.address-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.address-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'razorpay') {
                // Redirect to payment page
                window.location.href = '/payment.php';
            } else {
                // Place order with COD
                this.submit();
            }
        });
    </script>
</body>
</html>
