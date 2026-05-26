<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Moonlit Aura - Handmade Store</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* ===== BODY ===== */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,#ff9a9e,#fecfef,#a1f0ed,#ffd6a5);
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
}
header h1 { font-size: 42px; }

nav a {
    text-decoration: none;
    color: rgb(10, 10, 10);
    margin: 0 15px;
    font-weight: 600;
    cursor: pointer;
}
nav a:hover { text-decoration: underline; }

/* ===== SECTIONS ===== */
.about, .quotes, .cart-container {
    text-align: center;   
    background: white;
    margin: 40px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.contact {
    background: white;
    margin: 40px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.contact h2 {
    text-align: center;
}

.contact > div {
    text-align: left;
    display: inline-block;
    min-width: 350px;
}

.products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    padding: 40px;
}

.card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.card img {
    grid-column: 1 / -1;
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
}

.card h3,
.card .rating,
.card p,
.card .price {
    grid-column: 1 / -1;
}

.card button {
    padding: 10px 15px;
    font-size: 14px;
    font-weight: 600;
}

.card-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 15px;
}

.buy-now-btn {
    background: #ff6b6b !important;
}

.buy-now-btn:hover {
    background: #ff4545 !important;
}

.price { font-weight: 700; color: #e91e63; }

button {
    background: #6a11cb;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 25px;
    cursor: pointer;
}

.cart-page { display:none; }

.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 80px 80px;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #ddd;
    gap: 15px;
}

/* product name */
.cart-item span:first-child {
    text-align: left;
}

/* price */
.cart-item span:nth-child(2) {
    text-align: center;
    font-weight: 600;
}

.quantity-control {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.quantity-control button {
    padding: 8px 12px;
    font-size: 16px;
    background: #6a11cb;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    min-width: 40px;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.quantity-control button:hover {
    background: #4a0b8b;
    transform: scale(1.05);
}

.quantity-control button:active {
    background: #2a0a5b;
}

.quantity-number {
    font-weight: 600;
    min-width: 30px;
    text-align: center;
}

.cart-item button.remove-btn {
    padding: 8px 15px;
    justify-self: center;
    background: #ff6b6b;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    color: white;
    font-weight: 600;
    transition: all 0.2s;
}

.cart-item button.remove-btn:hover {
    background: #ff4545;
    transform: scale(1.05);
}

.cart-item button.remove-btn:active {
    background: #e63535;
}

.cart-total {
    font-weight:700;
    text-align:right;
    margin-top:10px;
}

#payment label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 1px 0;   /* reduce vertical gap */
}

#payment input[type="radio"] {
    margin: 0;
}

</style>
</head>

<body>

<header>
<h1>MOONLIT AURA🌙</h1>
<nav>
<a onclick="showPage('home')">Home</a>
<a onclick="scrollToSection('about')">About</a>
<a onclick="scrollToSection('products')">Shop</a>
<a onclick="scrollToSection('contact')">Contact</a>
<a onclick="showPage('cart')">Cart</a>
</nav>
</header>

<!-- HOME -->
<div id="home">

<section class="about" id="about">
<h2>About Our Store</h2>
<p> Welcome to our handmade world! ✨
At moonlit Aura, we create and curate beautiful handcrafted items made with love and care. From elegant jewelry to cozy home décor, each piece is designed to bring joy and uniqueness into your life.We proudly support local artisans and small creators who put their heart into every product. Our goal is to offer high-quality handmade items that are special, sustainable, and affordable.Thank you for supporting handmade and choosing something truly one-of-a-kind.</p>
</section>


<section class="quotes" id="quotes">
<h2>Where creativity🎀meets the glow of the moon🌙</h2>
<p>Moonlight🌙handmade magic✨gives perfect harmony🎗️💖</p>
</section>

<section class="products" id="products">

<div class="card">
<img src="handmade silver necklace.webp" alt="Handmade Silver Necklace">
<h3>Handmade Silver Necklace</h3>
<div class="rating">★★★★★ (4.8)</div>
<p>Elegant handcrafted premium necklace.</p>
<div class="price">₹1299</div>
<button onclick="addToCart('Handmade Silver Necklace',1299)">Add to Cart</button>
<button onclick="buyNow('Handmade Silver Necklace',1299)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="lavender candle.jpg" alt="Organic Lavender Candle">
<h3>Organic Lavender Candle</h3>
<div class="rating">★★★★☆ (4.6)</div>
<p>Natural soy wax relaxing candle.</p>
<div class="price">₹599</div>
<button onclick="addToCart('Organic Lavender Candle',599)">Add to Cart</button>
<button onclick="buyNow('Organic Lavender Candle',599)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="crochet handbag.jpg" alt="Crochet Handbag">
<h3>Crochet Handmade Handbag</h3>
<div class="rating">★★★★★ (5.0)</div>
<p>Stylish handmade cotton crochet bag.</p>
<div class="price">₹1499</div>
<button onclick="addToCart('Crochet Handbag',1499)">Add to Cart</button>
<button onclick="buyNow('Crochet Handbag',1499)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="resin art frame.webp" alt="Resin Art Frame">
<h3>Premium Resin Art Frame</h3>
<div class="rating">★★★★☆ (4.7)</div>
<p>Beautiful handcrafted resin wall art.</p>
<div class="price">₹2199</div>
<button onclick="addToCart('Resin Art Frame',2199)">Add to Cart</button>
<button onclick="buyNow('Resin Art Frame',2199)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="wood box.webp" alt="Wooden Jewelry Box">
<h3>Wooden Jewelry Box</h3>
<div class="rating">★★★★★ (4.9)</div>
<p>Premium handcrafted storage box.</p>
<div class="price">₹1899</div>
<button onclick="addToCart('Wooden Jewelry Box',1899)">Add to Cart</button>
<button onclick="buyNow('Wooden Jewelry Box',1899)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="handmade diary.webp" alt="Handmade Diary">
<h3>Handmade Diary</h3>
<div class="rating">★★★★☆ (4.5)</div>
<p>Eco-friendly recycled paper journal.</p>
<div class="price">₹799</div>
<button onclick="addToCart('Handmade Diary',799)">Add to Cart</button>
<button onclick="buyNow('Handmade Diary',799)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="clay pot.jpg" alt="Clay Pot Set">
<h3>Clay Pot Set</h3>
<div class="rating">★★★★★ (5.0)</div>
<p>Traditional handcrafted clay pots.</p>
<div class="price">₹1299</div>
<button onclick="addToCart('Clay Pot Set',1299)">Add to Cart</button>
<button onclick="buyNow('Clay Pot Set',1299)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="wall hanging.jpeg" alt="Macrame Wall Hanging">
<h3>Macrame Wall Hanging</h3>
<div class="rating">★★★★☆ (4.6)</div>
<p>Boho-style handmade décor piece.</p>
<div class="price">₹1099</div>
<button onclick="addToCart('Macrame Wall Hanging',1099)">Add to Cart</button>
<button onclick="buyNow('Macrame Wall Hanging',1099)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="soap.jpg" alt="Handmade Soap Pack">
<h3>Handmade Organic Soap</h3>
<div class="rating">★★★★★ (4.9)</div>
<p>Organic herbal soap pack collection.</p>
<div class="price">₹499</div>
<button onclick="addToCart('Handmade Soap Pack',499)">Add to Cart</button>
<button onclick="buyNow('Handmade Soap Pack',499)" style="background: #ff6b6b;">Buy Now</button>
</div>

<div class="card">
<img src="customized frame.avif" alt="Customized Name Frame">
<h3>Customized Name Frame</h3>
<div class="rating">★★★★★ (5.0)</div>
<p>Personalized handcrafted gift frame.</p>
<div class="price">₹1699</div>
<button onclick="addToCart('Customized Name Frame',1699)">Add to Cart</button>
<button onclick="buyNow('Customized Name Frame',1699)" style="background: #ff6b6b;">Buy Now</button>
</div>

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

</section>
</div>

<!-- PAYMENT -->
<div id="payment" class="cart-page">
<header><h1>Payment 💳</h1></header>

<section class="cart-container">

<h2>Select Payment Method</h2>

<label><input type="radio" name="pay" value="UPI"> UPI</label><br><br>
<label><input type="radio" name="pay" value="Card"> Card</label><br><br>
<label><input type="radio" name="pay" value="COD"> Cash on Delivery</label>

<br><br>

<button onclick="placeOrder()">Place Order</button>

</section>
</div>

<footer>
    <p>© 2026 Moonlit Aura | All Rights Reserved</p>
</footer>

<script>

// page switch
function showPage(page){
document.getElementById('home').style.display = (page==='home')?'block':'none';
document.getElementById('cart').style.display = (page==='cart')?'block':'none';
document.getElementById('payment').style.display = 'none';

if(page==='cart') loadCart();
}

// scroll
function scrollToSection(id){
showPage('home');
setTimeout(()=>{document.getElementById(id).scrollIntoView({behavior:'smooth'});},50);
}

// add cart
function addToCart(name,price){
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let existing = cart.find(item => item.name === name);
if(existing){
    existing.quantity += 1;
} else {
    cart.push({name, price, quantity: 1});
}
localStorage.setItem('cart',JSON.stringify(cart));
}

// buy now - direct checkout
function buyNow(name, price){
localStorage.removeItem('cart');
let cart = [{name, price, quantity: 1}];
localStorage.setItem('cart', JSON.stringify(cart));
document.getElementById('home').style.display='none';
document.getElementById('cart').style.display='none';
document.getElementById('payment').style.display='block';
}

// load cart
function loadCart(){
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let container = document.getElementById('cart-items');
container.innerHTML="";

if(cart.length===0){
container.innerHTML="<p>Your cart is empty 😢</p>";
return;
}

let total=0;

cart.forEach((item,index)=>{
let itemTotal = item.price * item.quantity;
total+=itemTotal;

let div=document.createElement('div');
div.className="cart-item";
div.innerHTML=`
<span>${item.name}</span>
<span>₹${itemTotal}</span>
<div class="quantity-control">
    <button type="button" onclick="decreaseQty(${index})" style="cursor: pointer;">−</button>
    <span class="quantity-number">${item.quantity}</span>
    <button type="button" onclick="increaseQty(${index})" style="cursor: pointer;">+</button>
</div>
<button type="button" class="remove-btn" onclick="removeItem(${index})" style="cursor: pointer;">Remove</button>
`;
container.appendChild(div);
});

container.innerHTML += `<div class="cart-total">Total: ₹${total}</div>`;
}

// remove
function removeItem(index){
let cart = JSON.parse(localStorage.getItem('cart')) || [];
if(cart[index]){
    cart.splice(index,1);
    localStorage.setItem('cart',JSON.stringify(cart));
    loadCart();
}
}

// increase quantity
function increaseQty(index){
let cart = JSON.parse(localStorage.getItem('cart')) || [];
if(cart[index]){
    cart[index].quantity = (cart[index].quantity || 1) + 1;
    localStorage.setItem('cart',JSON.stringify(cart));
    loadCart();
}
}

// decrease quantity
function decreaseQty(index){
let cart = JSON.parse(localStorage.getItem('cart')) || [];
if(cart[index]){
    if(cart[index].quantity > 1){
        cart[index].quantity -= 1;
    } else {
        cart.splice(index,1);
    }
    localStorage.setItem('cart',JSON.stringify(cart));
    loadCart();
}
}

// go payment
function goToPayment(){
let cart = JSON.parse(localStorage.getItem('cart')) || [];

if(cart.length===0){
alert("Your cart is empty 😢");
return;
}

document.getElementById('home').style.display='none';
document.getElementById('cart').style.display='none';
document.getElementById('payment').style.display='block';
}

// buy now - direct checkout
function buyNow(name, price){
localStorage.removeItem('cart');
let cart = [{name, price}];
localStorage.setItem('cart', JSON.stringify(cart));
document.getElementById('home').style.display='none';
document.getElementById('cart').style.display='none';
document.getElementById('payment').style.display='block';
}

// place order
function placeOrder(){
let method = document.querySelector('input[name="pay"]:checked');

if(!method){
alert("Select payment method!");
return;
}

alert("🎉 Order Placed Successfully..!!! \n Thank you for shopping with Moonlit Aura💗");

localStorage.removeItem('cart');

showPage('home');
}
</script>

</body>
</html>
