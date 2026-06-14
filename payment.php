<?php
/**
 * ========================================
 * PAYMENT INTEGRATION PAGE
 * Razorpay & Stripe Payment Processing
 * ========================================
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/backend/middleware/Auth.php';

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

// This page would normally handle payment processing
// For now, it's a template showing payment methods
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Moonlit Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/src/styles/main.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .payment-container {
            max-width: 800px;
            margin: 100px auto;
            padding: var(--spacing-2xl);
        }

        .payment-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
            text-align: center;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-lg);
            margin: var(--spacing-2xl) 0;
        }

        .method-box {
            padding: var(--spacing-lg);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .method-box:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
        }

        .method-icon {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-md);
            color: var(--primary-light);
        }

        .method-name {
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
        }

        .loading {
            text-align: center;
            padding: var(--spacing-2xl);
        }

        .spinner {
            border: 4px solid rgba(139, 92, 246, 0.2);
            border-top: 4px solid var(--primary-light);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto var(--spacing-lg);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include 'public/includes/navbar.php'; ?>

    <div class="payment-container">
        <div class="payment-card">
            <h1 style="margin-bottom: var(--spacing-lg);"><i class="fas fa-credit-card" style="margin-right: var(--spacing-md);"></i>Payment</h1>
            <p style="color: var(--text-muted); margin-bottom: var(--spacing-2xl);">Choose your preferred payment method</p>

            <div class="payment-methods">
                <!-- Razorpay -->
                <div class="method-box" onclick="initiateRazorpay()">
                    <div class="method-icon"><i class="fas fa-wallet"></i></div>
                    <div class="method-name">Razorpay</div>
                    <small style="color: var(--text-muted);">UPI, Cards, Wallets</small>
                </div>

                <!-- Stripe -->
                <div class="method-box" onclick="initiateStripe()">
                    <div class="method-icon"><i class="fas fa-credit-card"></i></div>
                    <div class="method-name">Stripe</div>
                    <small style="color: var(--text-muted);">International Cards</small>
                </div>
            </div>

            <button class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </div>

    <script>
        // Get order amount from URL or session
        const orderAmount = new URLSearchParams(window.location.search).get('amount') || 50000; // Amount in paise

        function initiateRazorpay() {
            const options = {
                key: 'YOUR_RAZORPAY_KEY_ID', // Replace with actual key
                amount: orderAmount,
                currency: 'INR',
                name: 'Moonlit Aura',
                description: 'Purchase from Moonlit Aura',
                image: '/public/assets/images/logo.png',
                handler: function(response) {
                    handleRazorpayResponse(response);
                },
                prefill: {
                    name: document.querySelector('[data-user-name]')?.textContent || '',
                    email: document.querySelector('[data-user-email]')?.textContent || ''
                },
                notes: {
                    address: 'Moonlit Aura E-Commerce'
                },
                theme: {
                    color: '#8b5cf6'
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }

        function handleRazorpayResponse(response) {
            // Verify payment on server
            fetch('/backend/api/payments.php?action=verify', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_signature: response.razorpay_signature
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = '/order-confirmation.php?order_id=' + data.order_id;
                } else {
                    alert('Payment verification failed: ' + data.message);
                }
            });
        }

        function initiateStripe() {
            alert('Stripe integration coming soon!');
        }
    </script>
</body>
</html>
