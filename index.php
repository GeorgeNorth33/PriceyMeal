<!DOCTYPE html>
<html lang="ru">
<?
session_start();
require 'includes/db_connection.php';

// Добавьте версию для предотвращения кэширования
$version = '1.0.' . time();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Pricey Meal</title>
    <link rel="stylesheet" href="/_styles.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="additional_styles/chatbot.css?v=<?php echo $version; ?>">
</head>
<body>

    <header class="header">
        <div class="logo-container">
            <a href="index.php"><img src="img/logo_logo копия.png" alt="logo"></a>
        </div>
        
        <div class="search-container">
            <div class="search-wrapper">
                <input type="text" class="search-input" placeholder="Поиск товаров..." id="searchInput">
                <button class="search-btn" aria-label="Найти" id="searchButton">
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
            <!-- Контент категорий -->
            <div class="categories-content">
                <ul class="categories-list">
                    <li><a href="#" class="show-all-link active" data-category="all" id="showAllLink">Все товары</a></li>
                    <?php
                    $categories_query = "SELECT * FROM Category";
                    $categories_result = mysqli_query($connection, $categories_query);
                    while ($category = mysqli_fetch_assoc($categories_result)) {
                        echo "<li><a href='#' data-category='{$category['id_product_category']}'>{$category['name']}</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </aside>

<main class="products-grid" id="productsGrid">
    <?php
    // Определяем, активна ли категория "Все товары"
    $is_all_category = !isset($_GET['category']) || $_GET['category'] === 'all';
    
    $products_query = "
        SELECT p.*, 
               MIN(ps.price) as min_price
        FROM Product p 
        LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product 
        GROUP BY p.id_product
    ";
    
    // Добавляем случайный порядок только для категории "Все товары"
    if ($is_all_category) {
        $products_query .= " ORDER BY RAND()";
    } else {
        $products_query .= " ORDER BY p.id_product";
    }
    
    $products_result = mysqli_query($connection, $products_query);
    
    if (mysqli_num_rows($products_result) > 0) {
        while ($product = mysqli_fetch_assoc($products_result)) {
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
            
            <a href="product_page.php?id=<?php echo $product['id_product']; ?>" class="product-card" 
               data-product-id="<?php echo $product['id_product']; ?>" 
               data-category="<?php echo $product['id_product_category']; ?>" 
               data-price="<?php echo $product['min_price'] ? $product['min_price'] : 0; ?>"
               data-name="<?php echo htmlspecialchars($product['Name']); ?>">
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
                                    <img src='{$store_logo}' alt='{$price['store_name']}' onerror=\"this.src='icons/default-store.png'\">
                                </div>
                                <span>{$price['price']} ₽</span>
                            </div>";
                        }
                    } else {
                        echo "<div class='price-item no-prices'>Нет в наличии</div>";
                    }
                    ?>
                </div>
            </a>
            <?php
        }
    } else {
        echo "<p class='no-products'>Товары не найдены</p>";
    }
    ?>
</main>
    <script src="js/filters.js"></script>

<!-- УМНЫЙ ЧАТ-БОТ 2025 -->
<div class="chatbot-toggle" id="chatbotToggle">Chat</div>

<div class="chatbot-container" id="smartChatbot" style="display:none;">
    <div class="chatbot-header">
        <h3>Помощник Pricey Meal</h3>
        <button class="chatbot-close" id="chatbotClose">×</button>
    </div>
    <div class="chatbot-body" id="chatMessages">
        <div class="chatbot-message bot-message">
            Привет<?php echo isset($_SESSION['user_id']) ? ', ' . htmlspecialchars($_SESSION['FirstName'] ?? 'друг') : '' ?>! 
            Я знаю, что тебе нравится<br><br>
            <strong><?php
                if (isset($_SESSION['user_id'])) {
                    $uid = $_SESSION['user_id'];
                    $res = mysqli_fetch_assoc(mysqli_query($connection, "SELECT interests FROM UserPreferences WHERE user_id = $uid"));
                    echo $res['interests'] ?? 'качественные продукты';
                }
            ?></strong>
            <br><br>Хочешь — подберу лучшие товары именно для тебя?
        </div>
    </div>
    <div class="chatbot-input-area">
        <input type="text" id="chatInput" placeholder="Например: подбери молочку подешевле" autocomplete="off">
        <button id="sendBtn">Send</button>
    </div>
</div>

<script src="js/search.js"></script>
<script>
// Функция поиска товаров


// Умный чат-бот с персональными рекомендациями
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('chatbotToggle');
    const chat = document.getElementById('smartChatbot');
    const close = document.getElementById('chatbotClose');
    const messages = document.getElementById('chatMessages');
    const input = document.getElementById('chatInput');
    const send = document.getElementById('sendBtn');

    toggle.onclick = () => chat.style.display = 'flex';
    close.onclick = () => chat.style.display = 'none';

    const addMessage = (html, isBot = true) => {
        const div = document.createElement('div');
        div.className = `chatbot-message ${isBot ? 'bot-message' : 'user-message'}`;
        div.innerHTML = html;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    };

    const showRecommendations = () => {
        addMessage('Секунду, подбираю лучшее специально для вас...');

        fetch('api/recommendations.php')
            .then(r => r.json())
            .then(products => {
                messages.lastElementChild.remove(); // убираем "секунду..."

                if (products.length === 0) {
                    addMessage('Вот что я нашёл специально для вас:');

                    const grid = document.createElement('div');
                    grid.className = 'recommendations-wrapper';

                    products.forEach(p => {
                        const card = document.createElement('a');
                        card.href = p.link;
                        card.className = 'recommendation-mini-card';
                        card.innerHTML = `
                            <img src="${p.image}?v=${Date.now()}" 
                                 alt="${p.name}" 
                                 onerror="this.src='img/placeholder.jpg'">
                            <div class="mini-card-info">
                                <div class="mini-card-title">${p.name}</div>
                                <div class="mini-card-price">${p.price}</div>
                                <small style="color:#037D86; font-weight:600">${p.reason}</small>
                            </div>
                        `;
                        grid.appendChild(card);
                    });

                    messages.appendChild(grid);
                    messages.scrollTop = messages.scrollHeight;
                } else {
                    addMessage('Пока не нашёл подходящего');
                }
            });
    };

    send.onclick = () => {
        const text = input.value.trim().toLowerCase();
        if (!text) return;

        addMessage(text, false);
        input.value = '';

        if (text.includes('подбер') || text.includes('рекоменд') || text.includes('посоветуй') || text.includes('что') && text.includes('взять')) {
            showRecommendations();
        } else {
            addMessage('Понял! Сейчас найду: «' + text + '»');
            // В будущем тут будет настоящий поиск по тексту
            setTimeout(showRecommendations, 1000);
        }
    };

    input.addEventListener('keypress', e => e.key === 'Enter' && send.click());

    // Автозапуск для залогиненных
    <?php if (isset($_SESSION['user_id'])): ?>
    setTimeout(showRecommendations, 4000);
    <?php endif; ?>
});
</script>
</body>
</html>