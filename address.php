<?php
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Moonlit Aura</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fecfef, #a1f0ed, #ffd6a5);
            background-attachment: fixed;
            min-height: 100vh;
            padding: 20px;
        }

        body::before, body::after {
            content: "";
            position: fixed;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            z-index: 0;
        }
        body::before { top: -120px; left: -120px; }
        body::after { bottom: -120px; right: -120px; }

        .container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        header {
            text-align: center;
            padding: 20px;
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 42px;
            background: linear-gradient(135deg, #6a11cb, #e91e63);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }

        .breadcrumb {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 600;
        }

        .breadcrumb span {
            margin: 0 10px;
            color: #999;
        }

        .breadcrumb span.active {
            color: #6a11cb;
        }

        .checkout-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .form-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .form-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 3px solid #6a11cb;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            height: fit-content;
            position: sticky;
            top: 20px;
            transition: all 0.3s ease;
        }

        .order-summary:hover {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .order-summary h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 3px solid #ff6b6b;
            padding-bottom: 10px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-item span:first-child {
            flex: 1;
            font-weight: 500;
        }

        .order-item span:last-child {
            font-weight: 700;
            color: #e91e63;
        }

        .order-quantity {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 0 10px;
            font-weight: 600;
        }

        .price-breakdown {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .price-row.total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .price-row.total span:last-child {
            color: #6a11cb;
            font-size: 22px;
        }

        .payment-options {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .payment-options h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 16px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .payment-option:hover {
            border-color: #6a11cb;
            background: #f5f0ff;
        }

        .payment-option input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .payment-option label {
            margin: 0;
            flex: 1;
            cursor: pointer;
            font-weight: 500;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #8e44ad);
            color: white;
            grid-column: 1 / -1;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(106, 17, 203, 0.3);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .button-group {
                grid-template-columns: 1fr;
            }

            header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <header>
        <h1>🌙 MOONLIT AURA</h1>
        <div class="breadcrumb">
            Cart <span>→</span> <span class="active">Address</span> <span>→</span> Confirmation
        </div>
    </header>

    <div class="checkout-container">

        <!-- Address Form -->
        <div class="form-section">
            <h2>📍 Delivery Address</h2>
            <form id="addressForm">
                
                <div class="form-group">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
                </div>

                <div class="form-group">
                    <label for="address">Street Address *</label>
                    <input type="text" id="address" name="address" placeholder="House No, Building Name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State *</label>
                        <input type="text" id="state" name="state" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="zip">Postal Code *</label>
                        <input type="text" id="zip" name="zip" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country *</label>
                        <input type="text" id="country" name="country" value="India" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Delivery Instructions (Optional)</label>
                    <textarea id="notes" name="notes" placeholder="e.g., Leave at door, Ring bell twice, etc."></textarea>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" id="terms" name="terms" required style="width: 18px; height: 18px; margin-right: 10px;">
                        <span>I agree to the Terms & Conditions and Privacy Policy</span>
                    </label>
                </div>

            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2>Order Summary</h2>
            <div id="summaryItems"></div>
            <div class="price-breakdown">
                <div class="price-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">₹0</span>
                </div>
                <div class="price-row">
                    <span>Shipping:</span>
                    <span id="shipping">₹50</span>
                </div>
                <div class="price-row">
                    <span>Tax (5%):</span>
                    <span id="tax">₹0</span>
                </div>
                <div class="price-row total">
                    <span>TOTAL:</span>
                    <span id="totalPrice">₹0</span>
                </div>
            </div>

            <div class="payment-options">
                <h3>💳 Payment Method</h3>
                <div class="payment-option">
                    <input type="radio" id="upi" name="payment" value="UPI" required>
                    <label for="upi">UPI (Recommended)</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="card" name="payment" value="Card">
                    <label for="card">Credit/Debit Card</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="cod" name="payment" value="COD">
                    <label for="cod">Cash on Delivery</label>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="goBack()">← Back to Cart</button>
                <button type="button" class="btn btn-primary" onclick="placeOrder()">Place Order 🛍️</button>
            </div>
        </div>

    </div>

</div>

<script>

// Load cart and display summary
function loadOrderSummary() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let summaryDiv = document.getElementById('summaryItems');
    let subtotal = 0;

    if (cart.length === 0) {
        summaryDiv.innerHTML = '<p>Your cart is empty</p>';
        return;
    }

    summaryDiv.innerHTML = '';
    
    cart.forEach(item => {
        let itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        let itemDiv = document.createElement('div');
        itemDiv.className = 'order-item';
        itemDiv.innerHTML = `
            <span>${item.name}</span>
            <span class="order-quantity">x${item.quantity}</span>
            <span>₹${itemTotal}</span>
        `;
        summaryDiv.appendChild(itemDiv);
    });

    // Calculate totals
    let shipping = 50;
    let tax = Math.round(subtotal * 0.05);
    let total = subtotal + shipping + tax;

    document.getElementById('subtotal').textContent = '₹' + subtotal;
    document.getElementById('tax').textContent = '₹' + tax;
    document.getElementById('totalPrice').textContent = '₹' + total;
}

// Place order - save to DATABASE
function placeOrder() {
    let form = document.getElementById('addressForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    let payment = document.querySelector('input[name="payment"]:checked');
    if (!payment) {
        alert('Please select a payment method');
        return;
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let shipping = 50;
    let tax = Math.round(subtotal * 0.05);
    let total = subtotal + shipping + tax;

    // Disable button to prevent double submit
    let btn = document.querySelector('.btn-primary');
    btn.textContent = 'Placing Order...';
    btn.disabled = true;

    // Get user_id from localStorage
    let user_id = localStorage.getItem('user_id') || 0;

    // Send to save_order.php
    let formData = new FormData();
    formData.append('user_id', user_id);
    formData.append('items', JSON.stringify(cart));
    formData.append('payment_method', payment.value);
    formData.append('total', total);
    formData.append('fullName', document.getElementById('fullName').value);
    formData.append('address', document.getElementById('address').value);
    formData.append('city', document.getElementById('city').value);
    formData.append('state', document.getElementById('state').value);
    formData.append('zip', document.getElementById('zip').value);
    formData.append('phone', document.getElementById('phone').value);

    fetch('save_order.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Clear cart
            localStorage.removeItem('cart');
            // Redirect with success
            window.location.href = 'index.php?success=true&order_id=' + data.order_id;
        } else {
            alert('Error: ' + (data.error || 'Order failed. Please try again.'));
            btn.textContent = 'Place Order 🛍️';
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Network error. Please try again.');
        btn.textContent = 'Place Order 🛍️';
        btn.disabled = false;
    });
}

// Go back to cart
function goBack() {
    window.location.href = 'index.php?page=cart';
}

// Initialize on load
window.addEventListener('DOMContentLoaded', function() {
    loadOrderSummary();
    
    // Set UPI as default
    document.getElementById('upi').checked = true;
});

</script>

</body>
</html>
