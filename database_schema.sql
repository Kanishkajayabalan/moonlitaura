-- ============================================================
--  MOONLIT AURA - Database Schema
--  Database: moonlit_aura
--  Created for: Handmade Products E-Commerce Website
-- ============================================================

CREATE DATABASE IF NOT EXISTS moonlit_aura
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE moonlit_aura;

-- ============================================================
-- TABLE 1: users
-- பயனர்களின் account தகவல்கள்
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    username      VARCHAR(50)  UNIQUE NOT NULL,
    email         VARCHAR(100) UNIQUE NOT NULL,
    password      VARCHAR(255) NOT NULL,           -- bcrypt hashed
    full_name     VARCHAR(100) DEFAULT NULL,
    phone         VARCHAR(15)  DEFAULT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE 2: categories
-- பொருள் வகைகள் (Jewelry, Candles, Bags, etc.)
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(100) NOT NULL,
    icon        VARCHAR(50)  DEFAULT 'box',        -- FontAwesome icon name
    description TEXT         DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample categories
INSERT INTO categories (name, icon, description) VALUES
('Jewelry',        'gem',         'Handmade silver, gold & beaded jewelry'),
('Candles',        'fire',        'Organic soy wax & scented candles'),
('Bags',           'shopping-bag','Handcrafted crochet & fabric bags'),
('Home Decor',     'home',        'Wall hangings, frames & art pieces'),
('Pottery',        'paint-brush', 'Clay pots & ceramic products'),
('Stationery',     'book',        'Handmade diaries, journals & notebooks'),
('Skincare',       'leaf',        'Organic soaps & natural skincare');

-- ============================================================
-- TABLE 3: products
-- விற்பனை செய்யப்படும் பொருட்கள்
-- ============================================================
CREATE TABLE IF NOT EXISTS products (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    name                VARCHAR(100) NOT NULL,
    description         TEXT         DEFAULT NULL,
    price               DECIMAL(10,2) NOT NULL,
    discount_price      DECIMAL(10,2) DEFAULT NULL,      -- Sale price (optional)
    discount_percentage INT          DEFAULT 0,
    stock_quantity      INT          DEFAULT 0,
    sku                 VARCHAR(50)  DEFAULT NULL,        -- Stock Keeping Unit
    brand               VARCHAR(100) DEFAULT 'Moonlit Aura',
    image               VARCHAR(255) DEFAULT NULL,        -- Main image file name
    images              JSON         DEFAULT NULL,        -- Additional images (JSON array)
    category_id         INT          DEFAULT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Sample products (matching existing project images)
INSERT INTO products (name, description, price, discount_price, discount_percentage, stock_quantity, sku, image, category_id) VALUES
('Handmade Silver Necklace',  'Elegant handcrafted premium silver necklace.',         1299, 1099, 15, 10, 'JWL-001', 'handmade silver necklace.webp', 1),
('Organic Lavender Candle',   'Natural soy wax relaxing lavender scented candle.',    599,  499,  17,  8, 'CDL-001', 'lavender candle.jpg',           2),
('Crochet Handbag',           'Stylish handmade cotton crochet shoulder bag.',        1499, 1299, 13, 15, 'BAG-001', 'crochet handbag.jpg',           3),
('Premium Resin Art Frame',   'Beautiful handcrafted resin wall art piece.',          2199, NULL,  0,  6, 'DCR-001', 'resin art frame.webp',          4),
('Wooden Jewelry Box',        'Premium handcrafted wood storage box for jewelry.',    1899, 1699, 11, 12, 'DCR-002', 'wood box.webp',                  4),
('Handmade Diary',            'Eco-friendly recycled paper journal with cover.',       799,  699, 13, 20, 'STA-001', 'handmade diary.webp',           6),
('Clay Pot Set',              'Traditional handcrafted clay pots - set of 3.',        1299, NULL,  0,  7, 'POT-001', 'clay pot.jpg',                  5),
('Macrame Wall Hanging',      'Boho-style handmade macrame wall décor piece.',        1099,  949, 14, 14, 'DCR-003', 'wall hanging.jpeg',             4),
('Handmade Organic Soap Pack','Organic herbal soap collection - 3 bar pack.',          499,  449, 10, 25, 'SKN-001', 'soap.jpg',                      7),
('Customized Name Frame',     'Personalized handcrafted gift frame with your name.',  1699, 1499, 12,  9, 'DCR-004', 'customized frame.avif',         4);

-- ============================================================
-- TABLE 4: addresses
-- பயனர்களின் delivery முகவரிகள்
-- ============================================================
CREATE TABLE IF NOT EXISTS addresses (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    user_id        INT          NOT NULL,
    full_name      VARCHAR(100) NOT NULL,
    phone          VARCHAR(15)  NOT NULL,
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255) DEFAULT NULL,
    city           VARCHAR(100) NOT NULL,
    state          VARCHAR(100) NOT NULL,
    postal_code    VARCHAR(10)  NOT NULL,
    country        VARCHAR(50)  DEFAULT 'India',
    address_type   ENUM('home', 'work', 'other') DEFAULT 'home',
    is_default     TINYINT(1)   DEFAULT 0,
    notes          TEXT         DEFAULT NULL,       -- Delivery instructions
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE 5: cart
-- Shopping cart items (logged-in users)
-- ============================================================
CREATE TABLE IF NOT EXISTS cart (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    user_id       INT  NOT NULL,
    product_id    INT  NOT NULL,
    quantity      INT  DEFAULT 1,
    price_at_add  DECIMAL(10,2) NOT NULL,           -- Price when added (snapshot)
    added_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id) -- Ek user, ek product = oru row
);

-- ============================================================
-- TABLE 6: orders
-- வாங்கிய orders
-- ============================================================
CREATE TABLE IF NOT EXISTS orders (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    user_id        INT           NOT NULL,
    subtotal       DECIMAL(10,2) NOT NULL,
    shipping_cost  DECIMAL(10,2) DEFAULT 99.00,
    tax_amount     DECIMAL(10,2) DEFAULT 0.00,
    total_amount   DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50)   DEFAULT 'Unknown',
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE 7: order_items
-- ஒவ்வொரு order-லும் உள்ள products
-- ============================================================
CREATE TABLE IF NOT EXISTS order_items (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    order_id    INT           NOT NULL,
    product_id  INT           NOT NULL,
    quantity    INT           DEFAULT 1,
    price       DECIMAL(10,2) NOT NULL,              -- Price at time of order
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE 8: payments
-- Payment transaction records (8th and final table)
-- ============================================================
CREATE TABLE IF NOT EXISTS payments (
    id                   INT PRIMARY KEY AUTO_INCREMENT,
    order_id             INT          NOT NULL,
    user_id              INT          NOT NULL,
    payment_method       VARCHAR(50)  NOT NULL,       -- razorpay, stripe, cod, upi, card
    transaction_id       VARCHAR(255) DEFAULT NULL,   -- Razorpay/Stripe transaction ID
    razorpay_order_id    VARCHAR(255) DEFAULT NULL,
    razorpay_payment_id  VARCHAR(255) DEFAULT NULL,
    razorpay_signature   VARCHAR(500) DEFAULT NULL,
    amount               DECIMAL(10,2) NOT NULL,
    currency             VARCHAR(10)  DEFAULT 'INR',
    status               ENUM('pending','success','failed','refunded') DEFAULT 'pending',
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE
);

-- ============================================================
-- DEFAULT TEST USERS (passwords are hashed with bcrypt)
-- Plain passwords: user123, admin123
-- ============================================================
INSERT INTO users (username, email, password, full_name) VALUES
('user',  'user@test.com',  '$2y$10$placeholder_hash_user',  'Test User'),
('admin', 'admin@test.com', '$2y$10$placeholder_hash_admin', 'Admin User');
-- Note: Run config.php to auto-create real hashed users

-- ============================================================
-- USEFUL VIEWS (optional but helpful)
-- ============================================================

-- View: Order details with user & product info
CREATE OR REPLACE VIEW view_order_details AS
SELECT
    o.id          AS order_id,
    o.created_at  AS order_date,
    o.status,
    o.payment_method,
    o.total_amount,
    u.username,
    u.email,
    oi.quantity,
    oi.price      AS item_price,
    p.name        AS product_name,
    p.image       AS product_image
FROM orders o
JOIN users       u  ON o.user_id     = u.id
JOIN order_items oi ON oi.order_id   = o.id
JOIN products    p  ON oi.product_id = p.id;

-- View: Product with category name
CREATE OR REPLACE VIEW view_products AS
SELECT
    p.*,
    c.name AS category_name,
    c.icon AS category_icon
FROM products p
LEFT JOIN categories c ON p.category_id = c.id;

-- ============================================================
-- END OF SCHEMA
-- ============================================================
