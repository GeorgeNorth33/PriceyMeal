 function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const strengthBar = document.getElementById('strength-bar');
            
            // Сброс классов
            strengthBar.className = 'strength-bar';
            
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                return;
            }
            
            let strength = 0;
            
            // Проверка длины
            if (password.length >= 6) strength += 1;
            if (password.length >= 8) strength += 1;
            
            // Проверка на наличие цифр
            if (/\d/.test(password)) strength += 1;
            
            // Проверка на наличие букв в разных регистрах
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
            
            // Проверка на специальные символы
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
            
            // Установка визуального отображения силы пароля
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const hint = document.getElementById('password-match-hint');
            const submitBtn = document.getElementById('submit-btn');
            
            if (confirmPassword.length === 0) {
                hint.textContent = '';
                hint.style.color = '#666';
                submitBtn.disabled = false;
                return;
            }
            
            if (password === confirmPassword) {
                hint.textContent = '✓ Пароли совпадают';
                hint.style.color = '#28a745';
                submitBtn.disabled = false;
            } else {
                hint.textContent = '✗ Пароли не совпадают';
                hint.style.color = '#dc3545';
                submitBtn.disabled = true;
            }
        }
        
        function validatePassword() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword.length < 6) {
                alert('Пароль должен содержать минимум 6 символов');
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                alert('Пароли не совпадают');
                return false;
            }
            
            return true;
        }
        
        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            checkPasswordStrength();
            checkPasswordMatch();
        });