<?php
include 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$products = [];

// Fetch all products only if user is logged in
if ($is_logged_in) {
    $products_query = "SELECT * FROM products";
    $products_result = $conn->query($products_query);
    while ($row = $products_result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit Aura - Handmade Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        /* ===== BODY ===== */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fecfef, #a1f0ed, #ffd6a5);
            background-attachment: fixed;
        }

        h1, h2, h3 { font-weight: 700; }
        p, li { font-weight: 500; }

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

        /* ===== HEADER ===== */
        header {
            text-align: center;
            padding: 25px;
            color: rgb(12, 12, 12);
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        header h1 { font-size: 42px; margin: 0; }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        nav a {
            text-decoration: none;
            color: rgb(10, 10, 10);
            font-weight: 600;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        nav a:hover { 
            background: #6a11cb;
            color: white;
        }

        .logout-btn {
            background: #f44336;
            color: white !important;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #da190b;
        }

        .user-info {
            color: #333;
            font-size: 14px;
            margin-top: 10px;
        }

        /* ===== SECTIONS ===== */
        .about, .quotes, .contact, .cart-container {
            text-align: center;   
            background: white;
            margin: 40px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .rating {
            color: #ff9a00;
            font-weight: 600;
            margin: 10px 0;
        }

        .price { 
            font-weight: 700; 
            color: #e91e63; 
            font-size: 20px;
            margin: 10px 0;
        }

        button {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        button:hover {
            background: #5009a8;
        }

        .cart-page { display: none; }

        .cart-item {
            display: grid;
            grid-template-columns: 1fr 120px 100px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .cart-item span:first-child {
            text-align: left;
        }

        .cart-item span:nth-child(2) {
            text-align: right;
            font-weight: 600;
        }

        .cart-item button {
            justify-self: end;
        }

        .cart-total {
            font-weight: 700;
            text-align: right;
            margin-top: 10px;
            font-size: 18px;
            color: #333;
        }

        #payment label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;   
            cursor: pointer;
        }

        #payment input[type="radio"] {
            margin: 0;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .home { display: block; }

    </style>
</head>

<body>

<header>
    <h1>MOONLIT AURA🌙</h1>
    <nav>
        <a onclick="showPage('home')">Home</a>
        <a onclick="scrollToSection('about')">About</a>
        <?php if ($is_logged_in): ?>
            <a onclick="scrollToSection('products')">Shop</a>
            <a onclick="scrollToSection('contact')">Contact</a>
            <a onclick="showPage('cart')">Cart</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a onclick="scrollToSection('contact')">Contact</a>
            <a href="login.php" class="logout-btn" style="background: #4da3f0;">Login</a>
            <a href="register.php" class="logout-btn" style="background: #7cb342;">Register</a>
        <?php endif; ?>
    </nav>
    <?php if ($is_logged_in): ?>
        <div class="user-info">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</div>
    <?php endif; ?>
</header>

<!-- HOME -->
<div id="home" class="home">

<section class="about" id="about">
<h2>About Our Store</h2>
<p>Welcome to our handmade world! ✨
At moonlit Aura, we create and curate beautiful handcrafted items made with love and care. From elegant jewelry to cozy home décor, each piece is designed to bring joy and uniqueness into your life. We proudly support local artisans and small creators who put their heart into every product. Our goal is to offer high-quality handmade items that are special, sustainable, and affordable. Thank you for supporting handmade and choosing something truly one-of-a-kind.</p>
</section>

<section class="quotes" id="quotes">
<h2>Where creativity🎀meets the glow of the moon🌙</h2>
<p>Moonlight🌙handmade magic✨gives perfect harmony🎗️💖</p>
</section>

<section class="products" id="products">
<?php if ($is_logged_in): ?>
    <?php foreach ($products as $product): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <div class="rating">★★★★★ (<?php echo number_format($product['rating'], 1); ?>)</div>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <div class="price">₹<?php echo number_format($product['price'], 2); ?></div>
            <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">Add to Cart</button>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div style="grid-column: 1/-1; text-align: center; padding: 40px; background: white; border-radius: 15px;">
        <h2>Please Login to View Products</h2>
        <p>Sign in to browse our handmade collection and start shopping!</p>
        <a href="login.php" style="background: #4da3f0; color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; display: inline-block; margin-top: 15px;">Login Here</a>
    </div>
<?php endif; ?>
</section>

<section class="contact" id="contact">
<h2>Contact Us</h2>
<div> 
<p>📍 <strong>Address:</strong><br>Moonlit Aura,<br>No. 24, Handmade Street,<br>Salem, Tamil Nadu – 636001,<br>India.</p>
<p>📞 <strong>Phone:</strong> +91 98765 43210</p>
<p>📧 <strong>Email:</strong> support@moonlitaura.com</p>
<p>🕒 <strong>Working Hours:</strong><br>Monday – Saturday : 10:00 AM – 7:00 PM<br>Sunday : Closed</p>
<p>📲 <strong>WhatsApp:</strong> +91 98765 43210</p>
</div>
</section>

</div>

<!-- CART -->
<div id="cart" class="cart-page">
<header><h1>Cart 🛒</h1></header>

<section class="cart-container">
<div id="cart-items"></div>
<br>
<button onclick="goToPayment()">Proceed to Payment</button>
<button onclick="showPage('home')" style="background: #999; margin-left: 10px;">Continue Shopping</button>
</section>
</div>

<!-- PAYMENT -->
<div id="payment" class="cart-page">
<header><h1>Payment 💳</h1></header>

<section class="cart-container">
<h2>Select Payment Method</h2>

<label><input type="radio" name="pay" value="UPI"> UPI</label>
<label><input type="radio" name="pay" value="Card"> Card</label>
<label><input type="radio" name="pay" value="COD"> Cash on Delivery</label>

<br><br>

<button onclick="placeOrder()">Place Order</button>
<button onclick="showPage('cart')" style="background: #999; margin-left: 10px;">Back to Cart</button>

</section>
</div>

<footer>
© 2026 MOONLIT AURA
</footer>

<script>
    // page switch
    function showPage(page) {
        <?php if (!$is_logged_in): ?>
            if (page === 'cart') {
                alert('Please login to access the cart!');
                return;
            }
        <?php endif; ?>
        
        document.getElementById('home').style.display = (page === 'home') ? 'block' : 'none';
        document.getElementById('cart').style.display = (page === 'cart') ? 'block' : 'none';
        document.getElementById('payment').style.display = 'none';

        if (page === 'cart') loadCart();
        if (page === 'home') window.scrollTo(0, 0);
    }

    // scroll
    function scrollToSection(id) {
        showPage('home');
        setTimeout(() => { document.getElementById(id).scrollIntoView({ behavior: 'smooth' }); }, 50);
    }

    // add to cart
    function addToCart(id, name, price) {
        <?php if (!$is_logged_in): ?>
            alert('Please login to add items to cart!');
            window.location.href = 'login.php';
            return;
        <?php endif; ?>
        
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push({ id, name, price });
        localStorage.setItem('cart', JSON.stringify(cart));
        alert(name + ' added to cart!');
    }

    // load cart
    function loadCart() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let container = document.getElementById('cart-items');
        container.innerHTML = "";

        if (cart.length === 0) {
            container.innerHTML = "<p>Your cart is empty 😢</p>";
            return;
        }

        let total = 0;

        cart.forEach((item, index) => {
            total += item.price;

            let div = document.createElement('div');
            div.className = "cart-item";
            div.innerHTML = `
            <span>${item.name}</span>
            <span>₹${item.price.toFixed(2)}</span>
            <button onclick="removeItem(${index})">Remove</button>
            `;
            container.appendChild(div);
        });

        container.innerHTML += `<div class="cart-total">Total: ₹${total.toFixed(2)}</div>`;
    }

    // remove item
    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('cart'));
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
    }

    // go to payment
    function goToPayment() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        if (cart.length === 0) {
            alert("Your cart is empty 😢");
            return;
        }

        document.getElementById('home').style.display = 'none';
        document.getElementById('cart').style.display = 'none';
        document.getElementById('payment').style.display = 'block';
    }

    // place order
    function placeOrder() {
        let method = document.querySelector('input[name="pay"]:checked');

        if (!method) {
            alert("Select payment method!");
            return;
        }

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Send order to server
        $.ajax({
            type: 'POST',
            url: 'save_order.php',
            data: {
                items: JSON.stringify(cart),
                payment_method: method.value
            },
            success: function(response) {
                alert("🎉 Order Placed Successfully..!!! \n Thank you for shopping with Moonlit Aura💗");
                localStorage.removeItem('cart');
                showPage('home');
            },
            error: function() {
                alert("Error placing order. Please try again.");
            }
        });
    }
</script>

</body>
</html>
