<?php
/**
 * ========================================
 * SEARCH & FILTER PAGE
 * Advanced product search with filters
 * ========================================
 */

require_once __DIR__ . '/config/database.php';

// Get search query and filters from URL
$query = $_GET['q'] ?? '';
$category = $_GET['category'] ?? null;
$minPrice = $_GET['min_price'] ?? null;
$maxPrice = $_GET['max_price'] ?? null;
$sort = $_GET['sort'] ?? 'relevance';
$page = $_GET['page'] ?? 1;

// Get categories for filter sidebar
$db = getDatabase();
$categoriesStmt = $db->query('SELECT id, name, icon FROM categories ORDER BY name');
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Get price range stats
$priceStmt = $db->query('SELECT MIN(price) as min_price, MAX(price) as max_price FROM products WHERE stock_quantity > 0');
$priceStats = $priceStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $query ? htmlspecialchars($query) . ' - ' : ''; ?>Search Products - Moonlit Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/src/styles/main.css">
    <style>
        .search-container {
            max-width: 1400px;
            margin: 80px auto;
            padding: var(--spacing-2xl);
        }

        .search-header {
            margin-bottom: var(--spacing-3xl);
        }

        .search-bar {
            display: flex;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .search-input {
            flex: 1;
            padding: var(--spacing-lg);
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            color: var(--text-primary);
            font-size: 1rem;
        }

        .search-btn {
            padding: var(--spacing-lg) var(--spacing-xl);
            background: var(--primary-light);
            border: none;
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
        }

        .filters-and-results {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: var(--spacing-2xl);
        }

        .filters-sidebar {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .filter-section {
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-lg);
            border-bottom: 1px solid rgba(100, 116, 139, 0.1);
        }

        .filter-section:last-child {
            border-bottom: none;
        }

        .filter-title {
            font-weight: 600;
            margin-bottom: var(--spacing-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            cursor: pointer;
        }

        .filter-option input {
            cursor: pointer;
        }

        .filter-option label {
            cursor: pointer;
            flex: 1;
            color: var(--text-secondary);
        }

        .results-section {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }

        .sort-select {
            padding: var(--spacing-md);
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-primary);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--spacing-lg);
        }

        .product-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition-base);
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 150px;
            background: rgba(100, 116, 139, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--text-muted);
        }

        .product-info {
            padding: var(--spacing-lg);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
            color: var(--text-primary);
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-rating {
            color: #fbbf24;
            font-size: 0.9rem;
            margin-bottom: var(--spacing-md);
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-light);
            margin-bottom: var(--spacing-md);
            margin-top: auto;
        }

        .product-actions {
            display: flex;
            gap: var(--spacing-sm);
        }

        .product-actions button {
            flex: 1;
            padding: var(--spacing-md);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-base);
        }

        .btn-add-cart {
            background: var(--primary-light);
            color: var(--darker-bg);
        }

        .btn-wishlist {
            display: none;
        }

        .empty-results {
            text-align: center;
            padding: var(--spacing-3xl);
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: var(--spacing-lg);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-2xl);
        }

        .pagination-btn {
            padding: var(--spacing-sm) var(--spacing-md);
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            cursor: pointer;
        }

        .pagination-btn.active {
            background: var(--primary-light);
            color: var(--darker-bg);
        }

        @media (max-width: 1024px) {
            .filters-and-results {
                grid-template-columns: 1fr;
            }

            .filters-sidebar {
                position: static;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .search-container {
                margin-top: 120px;
            }

            .search-bar {
                flex-direction: column;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include 'public/includes/navbar.php'; ?>

    <div class="search-container">
        <!-- Search Header -->
        <div class="search-header">
            <h1 style="margin-bottom: var(--spacing-lg);">
                <?php if ($query): ?>
                    Search Results for "<?php echo htmlspecialchars($query); ?>"
                <?php else: ?>
                    Browse Products
                <?php endif; ?>
            </h1>

            <form method="GET" class="search-bar">
                <input type="text" name="q" class="search-input" placeholder="Search for products..." value="<?php echo htmlspecialchars($query); ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>

        <!-- Filters and Results -->
        <div class="filters-and-results">
            <!-- Filters Sidebar -->
            <aside class="filters-sidebar">
                <div class="filter-section">
                    <div class="filter-title">Categories</div>
                    <div class="filter-options">
                        <?php foreach ($categories as $cat): ?>
                            <div class="filter-option">
                                <input type="checkbox" id="cat-<?php echo $cat['id']; ?>" 
                                    <?php echo $category == $cat['id'] ? 'checked' : ''; ?>
                                    onchange="updateFilters()">
                                <label for="cat-<?php echo $cat['id']; ?>">
                                    <i class="fas fa-<?php echo $cat['icon'] ?? 'box'; ?>"></i> <?php echo htmlspecialchars($cat['name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-title">Price Range</div>
                    <div class="filter-options">
                        <div style="display: flex; gap: var(--spacing-sm); margin-bottom: var(--spacing-md);">
                            <input type="number" id="minPrice" placeholder="Min" value="<?php echo $minPrice ?? ''; ?>" 
                                style="width: 50%; padding: var(--spacing-sm); background: rgba(100, 116, 139, 0.1); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <input type="number" id="maxPrice" placeholder="Max" value="<?php echo $maxPrice ?? ''; ?>" 
                                style="width: 50%; padding: var(--spacing-sm); background: rgba(100, 116, 139, 0.1); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                        </div>
                        <button onclick="updateFilters()" class="btn btn-primary" style="width: 100%;">Apply</button>
                    </div>
                </div>

                <button onclick="clearFilters()" class="btn btn-secondary" style="width: 100%; margin-top: var(--spacing-lg);">
                    Clear Filters
                </button>
            </aside>

            <!-- Results -->
            <main class="results-section">
                <div class="results-header">
                    <div>
                        <span id="resultCount">Loading...</span> results found
                    </div>
                    <select class="sort-select" onchange="updateSort(this.value)">
                        <option value="relevance">Sort: Relevance</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="newest">Newest</option>
                    </select>
                </div>

                <div id="productsContainer" class="products-grid">
                    <!-- Products will be loaded here -->
                </div>

                <div id="paginationContainer" class="pagination">
                    <!-- Pagination will be loaded here -->
                </div>
            </main>
        </div>
    </div>

    <script>
        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });

        function loadProducts() {
            const params = new URLSearchParams({
                q: '<?php echo $query; ?>',
                category: '<?php echo $category; ?>',
                min_price: document.getElementById('minPrice')?.value || '',
                max_price: document.getElementById('maxPrice')?.value || '',
                sort: '<?php echo $sort; ?>',
                page: '<?php echo $page; ?>'
            });

            fetch('/backend/api/search.php?action=search&' + params)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderProducts(data.data.results);
                        document.getElementById('resultCount').textContent = data.data.total;
                        renderPagination(data.data.page, data.data.pages);
                    }
                });
        }

        function renderProducts(products) {
            const container = document.getElementById('productsContainer');

            if (products.length === 0) {
                container.innerHTML = `
                    <div class="empty-results" style="grid-column: 1/-1;">
                        <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                        <h3>No products found</h3>
                        <p style="color: var(--text-muted); margin-bottom: var(--spacing-lg);">Try adjusting your filters</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = products.map(product => `
                <div class="product-card">
                    <div class="product-image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="product-info">
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">₹${product.price}</div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="addToCart(${product.id}, '${product.name}', ${product.price})">
                                <i class="fas fa-cart-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderPagination(currentPage, totalPages) {
            if (totalPages <= 1) return;

            const container = document.getElementById('paginationContainer');
            let html = '';

            for (let i = 1; i <= totalPages; i++) {
                html += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }

            container.innerHTML = html;
        }

        function updateFilters() {
            loadProducts();
        }

        function updateSort(sortValue) {
            const url = new URL(window.location);
            url.searchParams.set('sort', sortValue);
            window.location = url;
        }

        function clearFilters() {
            document.getElementById('minPrice').value = '';
            document.getElementById('maxPrice').value = '';
            window.location.href = window.location.pathname;
        }
    </script>
</body>
</html>
