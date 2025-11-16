<!DOCTYPE html>
<html lang="ru">
<?
session_start();
require 'includes/db_connection.php';

// Проверяем ID товара
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = intval($_GET['id']);

// Получаем информацию о товаре
$product_query = "
    SELECT p.*, c.name as category_name 
    FROM Product p 
    LEFT JOIN Category c ON p.id_product_category = c.id_product_category 
    WHERE p.id_product = $product_id
";
$product_result = mysqli_query($connection, $product_query);

if (!$product_result || mysqli_num_rows($product_result) == 0) {
    header("Location: index.php");
    exit();
}

$product = mysqli_fetch_assoc($product_result);

// Обрабатываем возможные NULL значения
$product['image'] = $product['image'] ?? 'placeholder.jpg';
$product['Name'] = $product['Name'] ?? 'Без названия';
$product['category_name'] = $product['category_name'] ?? 'Без категории';

// Получаем цены в магазинах
$prices_query = "
    SELECT ps.price, s.store_name, s.id_store, sl.logo 
    FROM `Product Store` ps 
    JOIN Store s ON ps.id_store = s.id_store 
    LEFT JOIN `Store Logos` sl ON s.id_store = sl.id_store 
    WHERE ps.id_product = $product_id 
    ORDER BY ps.price ASC
";
$prices_result = mysqli_query($connection, $prices_query);

// Получаем аналогичные товары (из той же категории)
$similar_query = "
    SELECT p.*, MIN(ps.price) as min_price 
    FROM Product p 
    LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product 
    WHERE p.id_product_category = {$product['id_product_category']} 
    AND p.id_product != $product_id 
    GROUP BY p.id_product 
    LIMIT 5
";
$similar_result = mysqli_query($connection, $similar_query);

// В обработчике добавления в корзину в product_page.php замените этот блок:
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    $store_id = intval($_POST['store_id']);
    $quantity = intval($_POST['quantity']);
    
    // Создаем временную корзину в сессии
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Находим информацию о магазине
    $store_query = "SELECT store_name FROM Store WHERE id_store = $store_id";
    $store_result = mysqli_query($connection, $store_query);
    $store_data = mysqli_fetch_assoc($store_result);
    $store_name = $store_data['store_name'] ?? 'Неизвестный магазин';
    
    $cart_item = [
        'product_id' => $product_id,
        'product_name' => $product['Name'],
        'product_image' => $product['image'],
        'store_id' => $store_id,
        'store_name' => $store_name, // Сохраняем название магазина
        'price' => 0,
        'quantity' => $quantity
    ];
    
    // Находим цену для выбранного магазина
    $price_query = "SELECT price FROM `Product Store` WHERE id_product = $product_id AND id_store = $store_id";
    $price_result = mysqli_query($connection, $price_query);
    if ($price_row = mysqli_fetch_assoc($price_result)) {
        $cart_item['price'] = $price_row['price'];
    }
    
    // Добавляем товар в корзину
    $_SESSION['cart'][] = $cart_item;
    
    header("Location: cart_page.php");
    exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['Name']); ?> - Pricey Meal</title>
    <link rel="stylesheet" href="product_page.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <a href="index.php"><img src="img/logo_logo копия.png" alt="logo"></a>
        </div>
        <div class="search-container">
            <div class="search-wrapper">
                <input type="text" class="search-input" placeholder="Поиск товаров...">
                <button class="search-btn" aria-label="Найти">
                    <img src="icons/icons8-loupe-25-black.png" alt="Найти" width="16" height="16">
                </button>
            </div>
        </div>
        
        <div class="header-actions">
            <div class="action-item">
                <a href="cart_page.php" class="action-icon"><img src="icons/icons8-cart-35.png" alt="cart"></a>
                <a href="cart_page.php" class="action-label">Корзина</a>
            </div>
            <div class="action-item">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="action-icon"><img src="icons/icons8-user-icon-35.png" alt="profile"></a>
                    <a href="profile.php" class="action-label">Профиль</a>
                <?php else: ?>
                    <a href="login.php" class="action-icon"><img src="icons/icons8-user-icon-35.png" alt="profile"></a>
                    <a href="login.php" class="action-label">Войти</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="product-page">
            <!-- Левая колонка с изображением и информацией -->
            <div class="product-left">
                <div class="product-image-large">
                    <img src="products_image/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['Name']); ?>"
                         onerror="this.src='img/placeholder.jpg'; this.style.maxWidth='200px'; this.style.maxHeight='200px';">
                </div>
                
                <div class="product-info">
                    <h2>О продукте</h2>
                    <div class="category-info">
                        <strong>Категория:</strong> <?php echo htmlspecialchars($product['category_name']); ?>
                    </div>
                    <!-- Здесь можно добавить дополнительную информацию о товаре -->
                </div>
            </div>

            <!-- Правая колонка с ценами и аналогичными товарами -->
            <div class="product-right">
                <div class="product-header">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['Name']); ?></h1>
                    <a href="#" class="add-to-fav-btn">Добавить в избранное</a>
                </div>

                <div class="price-section">
                    <div class="section-tabs">
                        <button class="tab active" onclick="showTab('prices')">Цены</button>
                        <button class="tab" onclick="showTab('similar')">Аналоги</button>
                    </div>

                    <div class="tab-content">
                        <!-- Вкладка с ценами -->
                        <div id="prices-tab" class="tab-pane active">
                            <div class="price-filters">
                                <button class="filter-btn active" onclick="sortPrices('price')">По цене</button>
                                <button class="filter-btn" onclick="sortPrices('store')">По магазину</button>
                            </div>

                            <div class="price-list-container">
                                <div class="price-list" id="priceList">
                                    <?php
                                    if ($prices_result && mysqli_num_rows($prices_result) > 0) {
                                        while ($price = mysqli_fetch_assoc($prices_result)) {
                                            ?>
                                            <div class="price-item">
                                                <div class="price-main">
                                                    <div class="store-info">
                                                        <?php if (!empty($price['logo'])): ?>
                                                            <img src="store_logos/<?php echo htmlspecialchars($price['logo']); ?>" alt="<?php echo htmlspecialchars($price['store_name']); ?>" width="30" height="30">
                                                        <?php else: ?>
                                                            <div class="store-placeholder">🏪</div>
                                                        <?php endif; ?>
                                                        <span class="store-name"><?php echo htmlspecialchars($price['store_name']); ?></span>
                                                    </div>
                                                    <span class="price"><?php echo htmlspecialchars($price['price']); ?>₽</span>
                                                </div>
                                                <form method="POST" class="add-to-cart-form">
                                                    <input type="hidden" name="store_id" value="<?php echo htmlspecialchars($price['id_store']); ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                                        Добавить в корзину
                                                    </button>
                                                </form>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "<p class='no-prices'>Товар временно отсутствует в магазинах</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Вкладка с аналогами -->
                        <div id="similar-tab" class="tab-pane">
                            <div class="similar-header">
                                <h3 class="similar-title">Похожие товары</h3>
                                <p class="similar-subtitle">Аналоги из той же категории</p>
                            </div>
                            
                            <div class="similar-products">
                                <?php
                                if ($similar_result && mysqli_num_rows($similar_result) > 0) {
                                    while ($similar = mysqli_fetch_assoc($similar_result)) {
                                        // Обрабатываем возможные NULL значения для аналогичных товаров
                                        $similar['image'] = $similar['image'] ?? 'placeholder.jpg';
                                        $similar['Name'] = $similar['Name'] ?? 'Без названия';
                                        $similar['min_price'] = $similar['min_price'] ?? 0;
                                        ?>
                                        <div class="similar-product-card">

                                            </div>
                                            <div class="similar-product-info">
                                                <h4 class="similar-product-name"><?php echo htmlspecialchars($similar['Name']); ?></h4>
                                                <div class="similar-product-price">
                                                    от <?php echo htmlspecialchars($similar['min_price']); ?>₽
                                                </div>
                                                <a href="product_page.php?id=<?php echo htmlspecialchars($similar['id_product']); ?>" class="similar-view-btn">
                                                    Посмотреть товар
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<div class="no-similar-products">
                                        <p>Аналогичные товары не найдены</p>
                                        <a href="index.php" class="browse-catalog-btn">Перейти в каталог</a>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showTab(tabName) {
        // Скрыть все вкладки
        document.querySelectorAll('.tab-pane').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Убрать активный класс со всех кнопок
        document.querySelectorAll('.tab').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Показать выбранную вкладку
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Активировать кнопку
        event.target.classList.add('active');
    }

    function sortPrices(sortBy) {
        const priceList = document.getElementById('priceList');
        const items = Array.from(priceList.getElementsByClassName('price-item'));
        
        items.sort((a, b) => {
            if (sortBy === 'price') {
                const priceA = parseFloat(a.querySelector('.price').textContent);
                const priceB = parseFloat(b.querySelector('.price').textContent);
                return priceA - priceB;
            } else if (sortBy === 'store') {
                const storeA = a.querySelector('.store-name').textContent;
                const storeB = b.querySelector('.store-name').textContent;
                return storeA.localeCompare(storeB);
            }
            return 0;
        });
        
        // Очистить и перезаполнить список
        priceList.innerHTML = '';
        items.forEach(item => priceList.appendChild(item));
        
        // Обновить активную кнопку фильтра
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
    }
    </script>
</body>
</html>