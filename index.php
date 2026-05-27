<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moonlit Aura - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; background:linear-gradient(135deg,#ff9a9e,#fecfef,#a1f0ed,#ffd6a5), url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><pattern id="moonPattern" x="0" y="0" width="200" height="200" patternUnits="userSpaceOnUse"><circle cx="100" cy="100" r="15" fill="white" opacity="0.08"/><circle cx="50" cy="50" r="8" fill="white" opacity="0.05"/><circle cx="150" cy="150" r="6" fill="white" opacity="0.04"/></pattern></defs><rect width="1200" height="800" fill="url(%23moonPattern)"/></svg>'); background-attachment:fixed; min-height:100vh; }
        
        .header { background:rgba(255,255,255,0.9); padding:20px; text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.1); position:sticky; top:0; z-index:100; }
        .header h1 { color:#6a11cb; font-size:36px; margin-bottom:10px; }
        .header-top { display:flex; justify-content:space-between; align-items:center; max-width:1200px; margin:0 auto; }
        .user-info { color:#333; font-weight:600; }
        .header-buttons { display:flex; gap:10px; }
        .btn { padding:10px 20px; border:none; border-radius:8px; cursor:pointer; font-weight:600; transition:all 0.3s; }
        .btn-primary { background:linear-gradient(135deg,#6a11cb,#8e44ad); color:white; }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(106,17,203,0.3); }
        .btn-cart { background:#f0f0f0; border:2px solid #6a11cb; color:#6a11cb; position:relative; }
        .btn-cart:hover { background:#6a11cb; color:white; }
        .cart-count { position:absolute; top:-8px; right:-8px; background:#ff6b6b; color:white; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; }
        
        .container { max-width:1200px; margin:0 auto; padding:40px 20px; }
        .page { display:none; }
        .page.active { display:block; }
        
        .hero { text-align:center; margin-bottom:50px; }
        .hero h2 { font-size:42px; color:#6a11cb; margin-bottom:15px; }
        .hero p { font-size:18px; color:#666; }
        
        .products-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:30px; }
        .product-card { background:white; padding:20px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1); transition:all 0.3s; }
        .product-card:hover { transform:translateY(-10px); box-shadow:0 15px 40px rgba(0,0,0,0.15); }
        .product-emoji { font-size:60px; text-align:center; margin-bottom:15px; }
        .product-name { font-size:18px; font-weight:700; color:#333; margin-bottom:10px; }
        .product-price { font-size:24px; color:#e91e63; font-weight:700; margin-bottom:15px; }
        .product-buttons { display:flex; gap:10px; }
        .product-buttons button { flex:1; padding:10px; border:none; border-radius:8px; cursor:pointer; font-weight:600; transition:all 0.3s; }
        .btn-add-cart { background:linear-gradient(135deg,#6a11cb,#8e44ad); color:white; }
        .btn-add-cart:hover { transform:translateY(-2px); }
        .btn-buy-now { background:linear-gradient(135deg,#f093fb,#f5576c); color:white; }
        .btn-buy-now:hover { transform:translateY(-2px); }
        
        .cart-page { background:white; padding:40px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1); }
        .cart-header { font-size:28px; font-weight:700; margin-bottom:30px; color:#333; }
        .cart-empty { text-align:center; padding:60px 20px; color:#999; }
        .cart-items { margin-bottom:30px; }
        .cart-item { display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:20px; align-items:center; padding:20px; border-bottom:1px solid #f0f0f0; }
        .cart-item-name { font-weight:600; }
        .cart-item-price { color:#e91e63; font-weight:700; }
        .qty-controls { display:flex; gap:10px; align-items:center; }
        .qty-btn { width:32px; height:32px; border:1px solid #ddd; background:#f5f5f5; cursor:pointer; border-radius:5px; font-weight:600; }
        .qty-btn:hover { background:#6a11cb; color:white; border-color:#6a11cb; }
        .remove-btn { padding:8px 15px; background:#ff6b6b; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:600; }
        .remove-btn:hover { background:#ff5252; }
        
        .cart-summary { display:flex; justify-content:flex-end; gap:50px; padding:20px 0; border-top:2px solid #f0f0f0; }
        .summary-item { text-align:right; }
        .summary-label { color:#666; font-weight:600; }
        .summary-value { font-size:18px; color:#6a11cb; font-weight:700; }
        
        .cart-actions { display:flex; gap:15px; margin-top:30px; }
        .cart-actions button { flex:1; padding:15px; border:none; border-radius:8px; cursor:pointer; font-weight:700; font-size:16px; transition:all 0.3s; }
        .btn-continue-shop { background:#f0f0f0; color:#333; }
        .btn-continue-shop:hover { background:#e0e0e0; }
        .btn-checkout { background:linear-gradient(135deg,#6a11cb,#8e44ad); color:white; }
        .btn-checkout:hover { transform:translateY(-3px); box-shadow:0 10px 25px rgba(106,17,203,0.3); }
        
        .success-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:2000; align-items:center; justify-content:center; backdrop-filter:blur(5px); }
        .success-modal.show { display:flex; }
        .success-content { background:white; padding:60px 40px; border-radius:20px; text-align:center; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation:popIn 0.5s ease; }
        @keyframes popIn { 0% { transform:scale(0.8) translateY(20px); opacity:0; } 100% { transform:scale(1); opacity:1; } }
        .success-icon { font-size:80px; margin-bottom:20px; }
        .success-content h2 { font-size:32px; color:#6a11cb; margin-bottom:15px; }
        .success-content p { color:#666; line-height:1.6; margin-bottom:20px; }
        .order-id-box { background:#f0f0f0; padding:20px; border-radius:10px; margin:20px 0; font-weight:700; }
        .success-btn { width:100%; padding:15px; background:linear-gradient(135deg,#6a11cb,#8e44ad); color:white; border:none; border-radius:8px; cursor:pointer; font-weight:700; margin-top:20px; }
        .success-btn:hover { transform:translateY(-3px); box-shadow:0 10px 25px rgba(106,17,203,0.3); }
        
        .confetti { position:fixed; width:10px; height:10px; pointer-events:none; z-index:1999; }
        @keyframes confetti-left { 0% { opacity:1; } 100% { transform:translateX(-150px) translateY(600px) rotate(360deg); opacity:0; } }
        @keyframes confetti-right { 0% { opacity:1; } 100% { transform:translateX(150px) translateY(600px) rotate(-360deg); opacity:0; } }
        
        @media(max-width:768px) { .cart-item { grid-template-columns:1fr; } .cart-summary { flex-direction:column; gap:15px; } }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-top">
        <h1>🌙 MOONLIT AURA</h1>
        <div class="user-info">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></div>
        <div class="header-buttons">
            <a href="address.html" class="btn" style="background:linear-gradient(135deg,#667eea,#764ba2); color:white; text-decoration:none;">📬 Address</a>
            <a href="payment.html" class="btn" style="background:linear-gradient(135deg,#f093fb,#f5576c); color:white; text-decoration:none;">💳 Payment</a>
            <button class="btn btn-cart" onclick="showPage('cart')">
                🛒 Cart
                <span class="cart-count" id="cartCount">0</span>
            </button>
            <button class="btn btn-primary" onclick="logout()">Logout</button>
        </div>
    </div>
</div>

<!-- MAIN CONTAINER -->
<div class="container">

    <!-- HOME PAGE -->
    <div id="home" class="page active">
        <div class="hero">
            <h2>Welcome to Moonlit Aura</h2>
            <p>Discover beautiful handmade products crafted with love and care</p>
        </div>
        <div class="products-grid" id="productsGrid"></div>
    </div>

    <!-- CART PAGE -->
    <div id="cart" class="page">
        <div class="cart-page">
            <div class="cart-header">🛍️ Shopping Cart</div>
            <div id="cartContent"></div>
        </div>
    </div>

</div>

<!-- SUCCESS MODAL -->
<div id="successModal" class="success-modal">
    <div class="success-content">
        <div class="success-icon">✅</div>
        <h2>Order Placed Successfully!</h2>
        <p>Thank you for your purchase!</p>
        <div class="order-id-box">Order ID: <span id="orderId">#ML2026001</span></div>
        <button class="success-btn" onclick="continueShopping()">Continue Shopping</button>
    </div>
</div>

<script>
const products = [
    {id:1,name:'Handmade Necklace',price:1299,emoji:'📿'},
    {id:2,name:'Lavender Candle',price:599,emoji:'🕯️'},
    {id:3,name:'Crochet Handbag',price:1499,emoji:'👜'},
    {id:4,name:'Resin Frame',price:2199,emoji:'🖼️'},
    {id:5,name:'Wooden Box',price:1899,emoji:'📦'},
    {id:6,name:'Handmade Diary',price:799,emoji:'📔'},
    {id:7,name:'Clay Pot',price:1299,emoji:'🏺'},
    {id:8,name:'Wall Hanging',price:1099,emoji:'🎨'},
    {id:9,name:'Natural Soap',price:499,emoji:'🧼'},
    {id:10,name:'Customized Frame',price:1699,emoji:'🎭'}
];

window.addEventListener('DOMContentLoaded',function(){
    renderProducts();
    updateCartCount();
    const params=new URLSearchParams(window.location.search);
    if(params.get('success')==='true'){
        showSuccessModal();
        window.history.replaceState({},' ','index.php');
    }
});

function renderProducts(){
    const grid=document.getElementById('productsGrid');
    grid.innerHTML='';
    products.forEach(p=>{
        const card=document.createElement('div');
        card.className='product-card';
        card.innerHTML=`
            <div class="product-emoji">${p.emoji}</div>
            <div class="product-name">${p.name}</div>
            <div class="product-price">₹${p.price}</div>
            <div class="product-buttons">
                <button class="btn-add-cart" onclick="addToCart(${p.id},'${p.name}',${p.price})">Add to Cart</button>
                <button class="btn-buy-now" onclick="buyNow(${p.id},'${p.name}',${p.price})">Buy Now</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

function addToCart(id,name,price){
    let cart=JSON.parse(localStorage.getItem('cart'))||[];
    let item=cart.find(i=>i.id===id);
    if(item){item.quantity++;}else{cart.push({id,name,price,quantity:1});}
    localStorage.setItem('cart',JSON.stringify(cart));
    updateCartCount();
    alert(`✓ ${name} added to cart!`);
}

function buyNow(id,name,price){
    localStorage.setItem('cart',JSON.stringify([{id,name,price,quantity:1}]));
    updateCartCount();
    window.location.href='address.php';
}

function updateCartCount(){
    let cart=JSON.parse(localStorage.getItem('cart'))||[];
    let count=cart.reduce((sum,item)=>sum+item.quantity,0);
    document.getElementById('cartCount').textContent=count;
}

function showPage(page){
    document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
    document.getElementById(page).classList.add('active');
    if(page==='cart')renderCart();
}

function renderCart(){
    const cart=JSON.parse(localStorage.getItem('cart'))||[];
    const content=document.getElementById('cartContent');
    if(cart.length===0){
        content.innerHTML='<div class="cart-empty"><p>Your cart is empty</p><button class="btn btn-primary" onclick="showPage(\'home\')">Continue Shopping</button></div>';
        return;
    }
    let subtotal=0,html='';
    cart.forEach(item=>{
        let total=item.price*item.quantity;
        subtotal+=total;
        html+=`
            <div class="cart-item">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">₹${item.price}</div>
                <div class="qty-controls">
                    <button class="qty-btn" onclick="decreaseQty(${item.id})">-</button>
                    <span>${item.quantity}</span>
                    <button class="qty-btn" onclick="increaseQty(${item.id})">+</button>
                </div>
                <button class="remove-btn" onclick="removeItem(${item.id})">Remove</button>
            </div>
        `;
    });
    let shipping=50,tax=Math.round(subtotal*0.05),total=subtotal+shipping+tax;
    content.innerHTML=html+`
        <div class="cart-summary">
            <div class="summary-item"><div class="summary-label">Subtotal</div><div class="summary-value">₹${subtotal}</div></div>
            <div class="summary-item"><div class="summary-label">Shipping</div><div class="summary-value">₹${shipping}</div></div>
            <div class="summary-item"><div class="summary-label">Tax (5%)</div><div class="summary-value">₹${tax}</div></div>
            <div class="summary-item"><div class="summary-label">TOTAL</div><div class="summary-value">₹${total}</div></div>
        </div>
        <div class="cart-actions">
            <button class="btn-continue-shop" onclick="showPage('home')">← Continue Shopping</button>
            <button class="btn-checkout" onclick="proceedToPayment()">Proceed to Payment →</button>
        </div>
    `;
}

function increaseQty(id){
    let cart=JSON.parse(localStorage.getItem('cart'))||[];
    let item=cart.find(p=>p.id===id);
    if(item){item.quantity++;localStorage.setItem('cart',JSON.stringify(cart));updateCartCount();renderCart();}
}

function decreaseQty(id){
    let cart=JSON.parse(localStorage.getItem('cart'))||[];
    let item=cart.find(p=>p.id===id);
    if(item&&item.quantity>1){item.quantity--;localStorage.setItem('cart',JSON.stringify(cart));updateCartCount();renderCart();}
}

function removeItem(id){
    let cart=JSON.parse(localStorage.getItem('cart'))||[];
    cart=cart.filter(item=>item.id!==id);
    localStorage.setItem('cart',JSON.stringify(cart));
    updateCartCount();
    renderCart();
}

function proceedToPayment(){
    let cart=JSON.parse(localStorage.getItem('cart'))||[];
    if(cart.length===0){alert('Your cart is empty!');return;}
    window.location.href='address.php';
}

function showSuccessModal(){
    const orderId='#ML'+new Date().getFullYear()+Math.random().toString().substr(2,6);
    document.getElementById('orderId').textContent=orderId;
    document.getElementById('successModal').classList.add('show');
    createConfetti();
}

function createConfetti(){
    const colors=['#ff6b6b','#6a11cb','#e91e63','#ff9800','#4CAF50','#00bcd4'];
    for(let i=0;i<50;i++){
        const c=document.createElement('div');
        c.className='confetti';
        c.style.left=Math.random()*window.innerWidth+'px';
        c.style.top='-10px';
        c.style.backgroundColor=colors[Math.floor(Math.random()*colors.length)];
        const dur=2+Math.random()+'s';
        c.style.animation=Math.random()>0.5?`confetti-left ${dur} ease-in`:`confetti-right ${dur} ease-in`;
        document.body.appendChild(c);
        setTimeout(()=>c.remove(),(2+Math.random())*1000);
    }
}

function continueShopping(){
    document.getElementById('successModal').classList.remove('show');
    localStorage.removeItem('cart');
    updateCartCount();
    showPage('home');
}

function logout(){
    if(confirm('Logout?')){window.location.href='logout.php';}
}
</script>

</body>
</html>
