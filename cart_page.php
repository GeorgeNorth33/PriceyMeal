<!DOCTYPE html>
<html lang="ru">
<?
session_start();
require 'includes/db_connection.php';

// Временное решение с корзиной в сессии
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Расчет общей суммы и получение названий магазинов
foreach ($cart_items as &$item) {
    // Если есть только store_id, получаем название магазина из БД
    if (!isset($item['store_name']) && isset($item['store_id'])) {
        $store_id = intval($item['store_id']);
        $store_query = "SELECT store_name FROM Store WHERE id_store = $store_id";
        $store_result = mysqli_query($connection, $store_query);
        if ($store_result && mysqli_num_rows($store_result) > 0) {
            $store_data = mysqli_fetch_assoc($store_result);
            $item['store_name'] = $store_data['store_name'];
        } else {
            $item['store_name'] = 'Неизвестный магазин';
        }
    }
    $total += $item['price'] * $item['quantity'];
}
unset($item); // разрываем ссылку

// Обработка изменения количества
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        $index = intval($_POST['item_index']);
        $quantity = intval($_POST['quantity']);
        
        if ($quantity > 0) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Переиндексация массива
        }
        
        header("Location: cart_page.php");
        exit();
    }
    
    if (isset($_POST['remove_item'])) {
        $index = intval($_POST['item_index']);
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        header("Location: cart_page.php");
        exit();
    }
    
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        header("Location: cart_page.php");
        exit();
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина - Pricey Meal</title>
    <link rel="stylesheet" href="cart_page.css">
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
                <?php if (!empty($cart_items)): ?>
                    <span class="cart-count"><?php echo count($cart_items); ?></span>
                <?php endif; ?>
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
        <div class="cart-page">
            <!-- <h1 class="cart-title">Корзина</h1> -->
            
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <h2>Корзина пуста</h2>
                    <p>Добавьте товары из каталога</p>
                    <a href="index.php" class="continue-shopping-btn">Продолжить покупки</a>
                </div>
            <?php else: ?>
            
            <div class="cart-content">
                <!-- Левая колонка с товарами -->
                <div class="cart-items">
                    <?php foreach ($cart_items as $index => $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="products_image/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" onerror="this.src='img/placeholder.jpg'">
                        </div>
                        <div class="item-info">
                            <h2 class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></h2>
                            <div class="item-store">
                                <span class="store-icon">Магазин: </span>
                                <span class="store-name"><?php echo htmlspecialchars($item['store_name'] ?? 'Магазин не указан'); ?></span>
                            </div>
                        </div>
                        <div class="item-price">
                            <span class="price"><?php echo htmlspecialchars($item['price']); ?>₽</span>
                            <form method="POST" class="quantity-form">
                                <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                <div class="quantity-controls">
                                    <button type="submit" name="update_quantity" class="quantity-btn" 
                                            onclick="this.form.quantity.value=<?php echo $item['quantity'] - 1; ?>">-</button>
                                    <span class="quantity"><?php echo htmlspecialchars($item['quantity']); ?></span>
                                    <button type="submit" name="update_quantity" class="quantity-btn" 
                                            onclick="this.form.quantity.value=<?php echo $item['quantity'] + 1; ?>">+</button>
                                </div>
                                <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                            </form>
                            <form method="POST" class="remove-form">
                                <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Кнопка очистки корзины -->
                    <form method="POST" class="clear-cart-form">
                        <button type="submit" name="clear_cart" class="clear-cart-btn">
                            <p>Очистить корзину</p>
                        </button>
                    </form>
                </div>

                <!-- Правая колонка с итогами -->
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3 class="summary-title">Итого</h3>
                        <div class="summary-row">
                            <span>Товары (<?php echo count($cart_items); ?>)</span>
                            <span><?php echo number_format($total, 2); ?>₽</span>
                        </div>
                        <div class="summary-row">
                            <span>Скидка</span>
                            <span class="discount">-0₽</span>
                        </div>
                        <div class="summary-row total">
                            <span>Общая сумма</span>
                            <span><?php echo number_format($total, 2); ?>₽</span>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/search.js"></script>
</body>
</html>