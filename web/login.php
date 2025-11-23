<!DOCTYPE html>
<html lang="ru">
<?
session_start();
require 'includes/db_connection.php';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    $query = "SELECT * FROM users WHERE Email = '$email' AND Password = '$password'";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['FirstName'] . ' ' . $user['SecondName'];
        $_SESSION['user_email'] = $user['Email'];
        
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Неверный email или пароль";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricey Meal</title>
    <link rel="stylesheet" href="login.css">
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
                <span class="action-icon"><img src="icons/icons8-cart-35.png" alt="Корзина"></span>
                <span class="action-label">Корзина</span>
            </div>
            <div class="action-item">
                <span class="action-icon"><img src="icons/icons8-user-icon-35.png" alt="Профиль"></span>
                <span class="action-label">Профиль</span>
            </div>
        </div>
    </header>

    <div class="main-container">
        <main class="login-main">
            <div class="login-container">
                <h1 class="login-title">Вход</h1>
                    
            <form class="login-form" method="POST">
                        <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                
                <div class="form-group">
                    <label class="form-label">Почта</label>
                    <input type="email" name="email" class="form-input" placeholder="Введите вашу почту" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-input" placeholder="Введите ваш пароль" required>
                </div>
                
                <div class="remember-group">
                    <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                    <label for="remember" class="remember-label">Запомнить меня</label>
                </div>
                
                <button type="submit" class="login-btn">Войти</button>
            </form>
                <div class="register-link">
                    Нет аккаунт? <a href="register.php" class="link">Зарегистрируйтесь</a>
                    <a href="#" class="link"></a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>