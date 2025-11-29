<!DOCTYPE html>
<html lang="ru">
<?php
session_start();
require 'includes/db_connection.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$version = '1.0.' . time();

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Обработка формы смены пароля
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = mysqli_real_escape_string($connection, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($connection, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    
    // Получаем текущий пароль пользователя
    $query = "SELECT Password FROM users WHERE id_user = $user_id";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);
    
    // Проверка текущего пароля
    if ($user['Password'] !== $current_password) {
        $error_message = "Текущий пароль указан неверно";
    } 
    // Проверка совпадения новых паролей
    elseif ($new_password !== $confirm_password) {
        $error_message = "Новые пароли не совпадают";
    }
    // Проверка длины нового пароля
    elseif (strlen($new_password) < 6) {
        $error_message = "Новый пароль должен содержать минимум 6 символов";
    }
    // Проверка что новый пароль отличается от старого
    elseif ($current_password === $new_password) {
        $error_message = "Новый пароль должен отличаться от текущего";
    }
    // Все проверки пройдены - обновляем пароль
    else {
        $update_query = "UPDATE users SET Password = '$new_password' WHERE id_user = $user_id";
        
        if (mysqli_query($connection, $update_query)) {
            $success_message = "Пароль успешно изменен!";
        } else {
            $error_message = "Ошибка при изменении пароля: " . mysqli_error($connection);
        }
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Смена пароля - Pricey Meal</title>
    <link rel="stylesheet" href="/change_password.css?v=<?php echo $version?>">
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
                <a href="profile.php" class="action-icon"><img src="icons/icons8-user-icon-35.png" alt="profile"></a>
                <a href="profile.php" class="action-label">Профиль</a>
            </div>
            <div class="action-item">
                <a href="logout.php" class="action-icon"><img src="icons/exit.png" alt="profile"></a>
                <a href="logout.php" class="action-label">Выйти</a>
            </div>
        </div>
    </header>

    <div class="main-container">
        <main class="password-main">
            <div class="password-container">
                <h1 class="password-title">Смена пароля</h1>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <form class="password-form" method="POST" onsubmit="return validatePassword()">
                    <div class="form-group">
                        <label for="current_password">Текущий пароль</label>
                        <input type="password" id="current_password" name="current_password" required 
                               placeholder="Введите текущий пароль">
                    </div>

                    <div class="form-group">
                        <label for="new_password">Новый пароль</label>
                        <input type="password" id="new_password" name="new_password" required 
                               placeholder="Введите новый пароль" oninput="checkPasswordStrength()">
                        <div class="password-strength">
                            <div class="strength-bar" id="strength-bar"></div>
                        </div>
                        <div class="password-hint">
                            Пароль должен содержать минимум 6 символов
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Подтвердите новый пароль</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Повторите новый пароль" oninput="checkPasswordMatch()">
                        <div class="password-hint" id="password-match-hint"></div>
                    </div>

                    <button type="submit" class="password-btn" id="submit-btn">Изменить пароль</button>
                </form>

                <div class="back-link">
                    <a href="profile.php">← Вернуться в профиль</a>
                </div>
            </div>
        </main>
    </div>

    <script src="js/change_password.js"></script>
</body>
</html>