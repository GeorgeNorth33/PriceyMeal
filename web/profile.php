<!DOCTYPE html>
<html lang="ru">
<?
session_start();
require 'includes/db_connection.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получаем данные пользователя
$user_id = $_SESSION['user_id'];
$query_user = "SELECT * FROM users WHERE id_user = $user_id";
$result_user = mysqli_query($connection, $query_user);
$user = mysqli_fetch_assoc($result_user);

// Обработка формы обновления данных
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $second_name = mysqli_real_escape_string($connection, $_POST['second_name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    
    $update_query = "UPDATE users SET 
                    FirstName = '$first_name',
                    SecondName = '$second_name',
                    Email = '$email',
                    PhoneNumber = '$phone'
                    WHERE id_user = $user_id";
    
    if (mysqli_query($connection, $update_query)) {
        $success_message = "Данные успешно обновлены!";
        // Обновляем данные в сессии
        $_SESSION['user_name'] = $first_name . ' ' . $second_name;
        // Перезагружаем страницу для отображения обновленных данных
        header("Location: profile.php");
        exit();
    } else {
        $error_message = "Ошибка при обновлении данных: " . mysqli_error($connection);
    }
}

// Функция для безопасного вывода данных
function safe_echo($data) {
    if ($data === null || $data === '') {
        return 'Не указано';
    }
    return htmlspecialchars($data);
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль - Pricey Meal</title>
    <link rel="stylesheet" href="profile.css">
    <script src="js/profile.js"></script>
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
        <div class="profile-page">
            <!-- Боковая навигация -->
            <aside class="profile-sidebar">
                <div class="user-card">
                    <h2 class="user-name"><?php echo safe_echo($user['FirstName']) . ' ' . safe_echo($user['SecondName']); ?></h2>
                    <p class="user-email"><?php echo safe_echo($user['Email']); ?></p>
                </div>
                
                <nav class="profile-nav">
                    <a href="#personal" class="nav-item active">Личные данные</a>
                    <a href="#favorites" class="nav-item">Избранное</a>
                    <a href="#settings" class="nav-item">Настройки</a>
                </nav>
            </aside>

            <!-- Основной контент -->
            <main class="profile-content">
                <!-- Сообщения об успехе/ошибке -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Раздел личных данных -->
                <section id="personal" class="content-section active">
                    <h2>Личные данные</h2>
                    <form class="profile-form" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Имя</label>
                                <input type="text" name="first_name" 
                                       value="<?php echo safe_echo($user['FirstName']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Фамилия</label>
                                <input type="text" name="second_name" 
                                       value="<?php echo safe_echo($user['SecondName']); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Почта" 
                                   value="<?php echo safe_echo($user['Email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="tel" name="phone" placeholder="Номер телефона" 
                                   value="<?php echo safe_echo($user['PhoneNumber']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Пол</label>
                            <input type="text" value="<?php echo safe_echo($user['Gender']); ?>" disabled>
                        </div>
                        <div class="form-group disabled-field">
                            <label>Дата рождения</label>
                            <input type="text" value="<?php echo safe_echo($user['DateBirth']); ?>" disabled>
                        <small class="date-birth-note">
                            <strong>⚠️ Дата рождения не может быть изменена</strong><br>
                             Это поле защищено от изменений для безопасности вашего аккаунта.
                            Если необходимо исправить дату рождения, обратитесь в службу поддержки.
                        </small>
                        </div>
  
                        <button type="submit" name="update_profile" class="save-btn">Сохранить изменения</button>
                    </form>
                </section>

                <!-- Раздел избранного -->
                <section id="favorites" class="content-section">
                    <h2>Избранные товары</h2>
                    <div class="favorites-grid">
                        <!-- Здесь будут карточки избранных товаров -->
                        <p style="text-align: center; color: #666;">Раздел в разработке</p>
                    </div>
                </section>

                <section id="settings" class="content-section">
                    <h2>Настройки</h2>
                    <div class="settings-list">
                        <!-- Уведомления -->
                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Уведомления о скидках</h3>
                                <p>Получать уведомления об акциях и специальных предложениях</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Email-рассылка</h3>
                                <p>Получать новости и рекомендации на email</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <!-- Безопасность -->
                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Смена пароля</h3>
                                <p>Изменить пароль вашего аккаунта</p>
                            </div>
                            <a href="change_password.php" class="change-password-btn">Сменить пароль</a>
                        </div>

                        <!-- Управление данными -->
                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Экспорт данных</h3>
                                <p>Скачать все ваши персональные данные</p>
                            </div>
                            <button class="export-btn">Экспорт</button>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <h3>Удаление аккаунта</h3>
                                <p>Безвозвратно удалить аккаунт и все данные</p>
                            </div>
                            <button class="delete-btn" onclick="confirmDelete()">Удалить аккаунт</button>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script>
    function confirmDelete() {
        if (confirm('Вы уверены, что хотите удалить аккаунт? Это действие нельзя отменить.')) {
            // Здесь можно добавить AJAX запрос для удаления аккаунта
            window.location.href = 'delete_account.php';
        }
    }
    </script>
</body>
</html>