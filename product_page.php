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

$version = '1.0.' . time();

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

// Проверяем, добавлен ли товар в избранное (для залогиненных пользователей)
$is_favorite = false;
if (isset($_SESSION['user_id'])) {
    $favorite_check = "SELECT id FROM UserFavorites WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
    $favorite_result = mysqli_query($connection, $favorite_check);
    $is_favorite = mysqli_num_rows($favorite_result) > 0;
}

// Обработчик добавления в корзину
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
        'store_name' => $store_name,
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
    <link rel="stylesheet" href="/_product_page.css?=<?php echo $version?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Компактные стили для блока характеристик */
        .nutrition-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
            margin-top: 15px;
            padding: 15px;
        }
        
        .nutrition-section h3 {
            color: #037D86;
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .compact-nutrition-info {
            margin-top: 8px;
        }
        
        .compact-nutrition-info h4 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .compact-nutrition-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        
        .compact-nutrition-item {
            text-align: center;
            padding: 10px 5px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 6px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .compact-nutrition-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
        }
        
        .compact-nutrition-item.calories::before { background: #FF6B6B; }
        .compact-nutrition-item.proteins::before { background: #4ECDC4; }
        .compact-nutrition-item.fats::before { background: #FFD166; }
        .compact-nutrition-item.carbs::before { background: #06D6A0; }
        
        .compact-nutrition-value {
            font-size: 16px;
            font-weight: 700;
            color: #037D86;
            margin-bottom: 4px;
            line-height: 1;
        }
        
        .compact-nutrition-label {
            font-size: 10px;
            color: #666;
            font-weight: 600;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .compact-nutrition-unit {
            font-size: 9px;
            color: #999;
            font-weight: 400;
        }
        
        .compact-nutrition-missing {
            text-align: center;
            padding: 15px;
            color: #666;
            background: #f8f9fa;
            border-radius: 6px;
            border: 2px dashed #dee2e6;
            font-size: 13px;
        }
        
        .compact-nutrition-missing p {
            margin-bottom: 8px;
        }
        
        @media (max-width: 768px) {
            .compact-nutrition-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .nutrition-section {
                padding: 12px;
                margin-top: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .compact-nutrition-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <a href="index.php"><img src="img/logo_logo копия.png" alt="logo"></a>
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
                </div>
            </div>

            <!-- Правая колонка с ценами и аналогичными товарами -->
            <div class="product-right">
                <div class="product-header">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['Name']); ?></h1>
                    
                    <!-- Кнопка избранного -->
                    <button class="add-to-fav-btn <?php echo $is_favorite ? 'favorite-active' : ''; ?>" 
                            data-product-id="<?php echo $product_id; ?>"
                            <?php echo !isset($_SESSION['user_id']) ? 'disabled title="Войдите для добавления в избранное"' : ''; ?>>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="<?php echo $is_favorite ? '#ff4757' : 'none'; ?>" stroke="<?php echo $is_favorite ? '#ff4757' : '#666'; ?>" stroke-width="2">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span><?php echo $is_favorite ? 'В избранном' : 'В избранное'; ?></span>
                    </button>
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
                                        $similar['image'] = $similar['image'] ?? 'placeholder.jpg';
                                        $similar['Name'] = $similar['Name'] ?? 'Без названия';
                                        $similar['min_price'] = $similar['min_price'] ?? 0;
                                        ?>
                                        <div class="similar-product-card">
                                            <div class="similar-product-image">
                                                <img src="products_image/<?php echo htmlspecialchars($similar['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($similar['Name']); ?>"
                                                     onerror="this.src='img/placeholder.jpg'; this.style.maxWidth='40px'; this.style.maxHeight='40px';">
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

                <!-- Компактный блок характеристик ПОД ВСЕМИ ВКЛАДКАМИ -->
                <div class="nutrition-section">
                    <h3>Характеристики продукта</h3>
                    <div class="compact-nutrition-info">
                        <h4>Пищевая ценность (на 100 г/мл)</h4>
                        <div class="compact-nutrition-grid">
                            <?php if (!empty($product['calories']) && $product['calories'] > 0): ?>
                            <div class="compact-nutrition-item calories">
                                <div class="compact-nutrition-value"><?php echo htmlspecialchars($product['calories']); ?></div>
                                <div class="compact-nutrition-label">Энергетическая ценность</div>
                                <div class="compact-nutrition-unit">ккал</div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($product['proteins']) && $product['proteins'] > 0): ?>
                            <div class="compact-nutrition-item proteins">
                                <div class="compact-nutrition-value"><?php echo htmlspecialchars($product['proteins']); ?></div>
                                <div class="compact-nutrition-label">Белки</div>
                                <div class="compact-nutrition-unit">граммы</div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($product['fats']) && $product['fats'] > 0): ?>
                            <div class="compact-nutrition-item fats">
                                <div class="compact-nutrition-value"><?php echo htmlspecialchars($product['fats']); ?></div>
                                <div class="compact-nutrition-label">Жиры</div>
                                <div class="compact-nutrition-unit">граммы</div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($product['carbohydrates']) && $product['carbohydrates'] > 0): ?>
                            <div class="compact-nutrition-item carbs">
                                <div class="compact-nutrition-value"><?php echo htmlspecialchars($product['carbohydrates']); ?></div>
                                <div class="compact-nutrition-label">Углеводы</div>
                                <div class="compact-nutrition-unit">граммы</div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php 
                        $hasNutritionData = (!empty($product['calories']) && $product['calories'] > 0) || 
                                           (!empty($product['proteins']) && $product['proteins'] > 0) || 
                                           (!empty($product['fats']) && $product['fats'] > 0) || 
                                           (!empty($product['carbohydrates']) && $product['carbohydrates'] > 0);
                        
                        if (!$hasNutritionData): ?>
                            <div class="compact-nutrition-missing">
                                <p>Информация о пищевой ценности отсутствует</p>
                                <small>Данные будут добавлены в ближайшее время</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showTab(tabName) {
        // Убрать активный класс со всех кнопок
        document.querySelectorAll('.tab').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Скрыть все вкладки
        document.querySelectorAll('.tab-pane').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Показать выбранную вкладку
        setTimeout(() => {
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }, 10);
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

    // Функционал избранного
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteBtn = document.querySelector('.add-to-fav-btn');
        
        if (favoriteBtn && !favoriteBtn.disabled) {
            favoriteBtn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const isCurrentlyFavorite = this.classList.contains('favorite-active');
                
                toggleFavorite(productId, !isCurrentlyFavorite, this);
            });
        }
    });

    function toggleFavorite(productId, addToFavorite, button) {
        const action = addToFavorite ? 'add' : 'remove';
        
        fetch('includes/favorites_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (addToFavorite) {
                    button.classList.add('favorite-active');
                    updateFavoriteButton(button, true);
                    showNotification('Товар добавлен в избранное', 'success');
                } else {
                    button.classList.remove('favorite-active');
                    updateFavoriteButton(button, false);
                    showNotification('Товар удален из избранного', 'info');
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error toggling favorite:', error);
            showNotification('Ошибка при обновлении избранного', 'error');
        });
    }

    function updateFavoriteButton(button, isFavorite) {
        const heartSvg = button.querySelector('svg');
        const textSpan = button.querySelector('span');
        
        if (isFavorite) {
            heartSvg.setAttribute('fill', '#ff4757');
            heartSvg.setAttribute('stroke', '#ff4757');
            textSpan.textContent = 'В избранном';
        } else {
            heartSvg.setAttribute('fill', 'none');
            heartSvg.setAttribute('stroke', '#666');
            textSpan.textContent = 'В избранное';
        }
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
            color: white;
            border-radius: 4px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    </script>
</body>
</html>