<!DOCTYPE html>
<html lang="ru">
<?
session_start();
require 'includes/db_connection.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricey Meal</title>
    <link rel="stylesheet" href="_styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
    <div class="logo-container">
        <a href="index.php"><img src="img/logo_logo копия.png"  alt="logo"></a>
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
    <aside class="categories">
        <ul>
            <?php
            $categories_query = "SELECT * FROM Category";
            $categories_result = mysqli_query($connection, $categories_query);
            while ($category = mysqli_fetch_assoc($categories_result)) {
                echo "<li><a href='#' data-category='{$category['id_product_category']}'>{$category['name']}</a></li>";
            }
            ?>
        </ul>
    </aside>

        <main class="products-grid">
            <?php
            // Запрос для получения товаров с минимальными ценами
            $products_query = "
                SELECT p.*, 
                       MIN(ps.price) as min_price
                FROM Product p 
                LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product 
                GROUP BY p.id_product
                ORDER BY p.id_product
            ";
            
            $products_result = mysqli_query($connection, $products_query);
            
            if (mysqli_num_rows($products_result) > 0) {
                while ($product = mysqli_fetch_assoc($products_result)) {
                    // Получаем 3 лучшие цены для этого товара
                    $prices_query = "
                        SELECT ps.price, s.store_name, sl.logo 
                        FROM `Product Store` ps 
                        JOIN Store s ON ps.id_store = s.id_store 
                        LEFT JOIN `Store Logos` sl ON s.id_store = sl.id_store 
                        WHERE ps.id_product = {$product['id_product']} 
                        ORDER BY ps.price ASC 
                        LIMIT 3
                    ";
                    $prices_result = mysqli_query($connection, $prices_query);
                    ?>
                    
                    <div class="product-card" data-product-id="<?php echo $product['id_product']; ?>">
                        <div class="product-image">
                            <img src="products_image/<?php echo $product['image']; ?>" alt="<?php echo $product['Name']; ?>" onerror="this.src='img/placeholder.jpg'">
                        </div>
                        
                        <div class="product-title"><?php echo $product['Name']; ?></div>
                        <div class="price-list">
                            <?php
                            if (mysqli_num_rows($prices_result) > 0) {
                                while ($price = mysqli_fetch_assoc($prices_result)) {
                                    $store_logo = !empty($price['logo']) ? "store_logos/{$price['logo']}" : "icons/default-store.png";
                                    echo "
                                    <div class='price-item'>
                                        <div class='store-icon'>
                                            <img src='{$store_logo}' alt='{$price['store_name']}' width='20' height='20' onerror=\"this.src='icons/default-store.png'\">
                                        </div>
                                        <span>{$price['price']} ₽</span>
                                    </div>";
                                }
                            } else {
                                echo "<div class='price-item no-prices'>Нет в наличии</div>";
                            }
                            ?>
                        </div>
                        <button class="details-btn">
                            <a href="product_page.php?id=<?php echo $product['id_product']; ?>">Подробнее</a>
                        </button>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='no-products'>Товары не найдены</p>";
            }
            ?>
        </main>
    </div>
    <script src="js/main.js"></script>
</body>
</html>