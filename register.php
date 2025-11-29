<!DOCTYPE html>
<html lang="ru">
<?php
session_start();
require 'includes/db_connection.php';

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $date_birth = mysqli_real_escape_string($connection, $_POST['date_birth']);
    
    // Проверка совпадения паролей
    if ($password !== $confirm_password) {
        $error_message = "Пароли не совпадают";
    } else {
        // Проверка существования email
        $check_query = "SELECT * FROM users WHERE Email = '$email'";
        $check_result = mysqli_query($connection, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "Пользователь с таким email уже существует";
        } else {
            // Валидация даты рождения (без ограничения по возрасту)
            if (!empty($date_birth)) {
                $min_date = strtotime('-120 years'); // Минимальная дата - 120 лет назад
                $max_date = strtotime('today'); // Максимальная дата - сегодня
                $user_birth = strtotime($date_birth);
                
                if ($user_birth < $min_date || $user_birth > $max_date) {
                    $error_message = "Пожалуйста, введите корректную дату рождения";
                } else {
                    // Вставка нового пользователя с датой рождения
                    $insert_query = "INSERT INTO users (FirstName, SecondName, Email, Password, PhoneNumber, Gender, DateBirth) 
                                    VALUES ('', '', '$email', '$password', '', '$gender', '$date_birth')";
                    
                    if (mysqli_query($connection, $insert_query)) {
                        $success_message = "Регистрация успешна! Теперь вы можете войти.";
                    } else {
                        $error_message = "Ошибка регистрации: " . mysqli_error($connection);
                    }
                }
            } else {
                // Если дата рождения не указана, использовать NULL
                $insert_query = "INSERT INTO users (FirstName, SecondName, Email, Password, PhoneNumber, Gender, DateBirth) 
                                VALUES ('', '', '$email', '$password', '', '$gender', NULL)";
                
                if (mysqli_query($connection, $insert_query)) {
                    $success_message = "Регистрация успешна! Теперь вы можете войти.";
                } else {
                    $error_message = "Ошибка регистрации: " . mysqli_error($connection);
                }
            }
        }
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricey Meal</title>
    <link rel="stylesheet" href="register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <a href="index.php"><img src="img/logo_logo копия.png" alt="logo"></a>
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
                <h1 class="login-title">Регистрация</h1>
                
                <form class="login-form" method="POST">
                    <?php if (isset($error_message)): ?>
                        <div class="error-message"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="success-message"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Почта</label>
                            <input type="email" name="email" class="form-input" placeholder="example@mail.ru" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Дата рождения</label>
                        <input type="date" name="date_birth" class="form-input" 
                               max="<?php echo date('Y-m-d'); ?>"
                               onchange="calculateAge(this)">
                        <!-- <div id="age-display" class="age-display"></div> -->
                    </div>

                    <div class="form-group">
                        <label class="form-label">Пол</label>
                        <div class="gender-options">
                            <label class="gender-option">
                                <input type="radio" name="gender" value="М" class="gender-radio" required>
                                <span class="gender-label">Мужской</span>
                            </label>
                            <label class="gender-option">
                                <input type="radio" name="gender" value="Ж" class="gender-radio" required>
                                <span class="gender-label">Женский</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-input" placeholder="Введите пароль" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Подтвердите пароль</label>
                            <input type="password" name="confirm_password" class="form-input" placeholder="Повторите пароль" required>
                        </div>
                    </div>

                    <div class="remember-group">
                        <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                        <label for="remember" class="remember-label">Запомнить меня</label>
                    </div>
                    
                    <button type="submit" class="login-btn">Зарегистрироваться</button>
                </form>
                <div class="register-link">
                    Уже есть аккаунт? <a href="login.php" class="link">Войти</a>
                </div>
            </div>
        </main>
    </div>

    <script>
    function calculateAge(input) {
        const ageDisplay = document.getElementById('age-display');
        const selectedDate = new Date(input.value);
        const today = new Date();
        
        if (input.value) {
            let age = today.getFullYear() - selectedDate.getFullYear();
            const monthDiff = today.getMonth() - selectedDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < selectedDate.getDate())) {
                age--;
            }
            
            if (age < 0) {
                ageDisplay.innerHTML = '<span class="age-warning">Дата рождения не может быть в будущем</span>';
                input.value = '';
            } else if (age < 14) {
                ageDisplay.innerHTML = `<span class="age-warning">Вам ${age} лет. Для детей младше 14 лет требуется согласие родителей</span>`;
            } else if (age < 18) {
                ageDisplay.innerHTML = `<span class="age-info">Вам ${age} лет. Вы регистрируетесь как несовершеннолетний пользователь</span>`;
            } else {
                ageDisplay.innerHTML = `<span class="age-success">Вам ${age} лет</span>`;
            }
        } else {
            ageDisplay.innerHTML = '';
        }
    }

    // Устанавливаем максимальную дату (сегодня)
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="date_birth"]');
        const maxDate = new Date();
        dateInput.max = maxDate.toISOString().split('T')[0];
        
        // Устанавливаем разумную минимальную дату (120 лет назад)
        const minDate = new Date();
        minDate.setFullYear(minDate.getFullYear() - 120);
        dateInput.min = minDate.toISOString().split('T')[0];
    });
    </script>
</body>
</html>