// main.js
document.addEventListener('DOMContentLoaded', function() {
    const minimalSelects = document.querySelectorAll('.minimal-select');
    const minimalInputs = document.querySelectorAll('.minimal-input');
    const minimalCheckbox = document.querySelector('.minimal-checkbox input');
    const filterReset = document.querySelector('.filter-reset');
    
    // Функция валидации ввода - запрет отрицательных значений
    function validatePriceInput(input) {
        // Удаляем все нечисловые символы, кроме точки и минуса
        let value = input.value.replace(/[^\d.-]/g, '');
        
        // Если значение отрицательное или начинается с минуса
        if (value.startsWith('-') || parseFloat(value) < 0) {
            // Удаляем минус и оставляем только положительное число
            value = value.replace('-', '');
            // Если после удаления минуса осталась пустая строка, оставляем пустую
            if (value === '') value = '';
        }
        
        // Обновляем значение в поле ввода
        input.value = value;
        
        return value;
    }
    
    // Функция для обработки ввода с валидацией
    function handlePriceInput(input) {
        // Валидируем значение
        const validatedValue = validatePriceInput(input);
        
        // Если значение изменилось, запускаем таймер для применения фильтров
        if (validatedValue !== input.getAttribute('data-last-value')) {
            input.setAttribute('data-last-value', validatedValue);
            
            clearTimeout(input.timer);
            input.timer = setTimeout(applyMinimalFilters, 500);
        }
    }
    
    // Функция применения фильтров
    function applyMinimalFilters() {
        const filters = {
            sort: document.querySelector('.minimal-select').value,
            store: document.querySelectorAll('.minimal-select')[1].value,
            priceFrom: document.querySelectorAll('.minimal-input')[0].value,
            priceTo: document.querySelectorAll('.minimal-input')[1].value,
            inStock: minimalCheckbox.checked
        };
        
        console.log('Применены фильтры:', filters);
        // Здесь будет реальная логика фильтрации товаров
    }
    
    // Сброс фильтров
    function resetMinimalFilters() {
        minimalSelects.forEach((select, index) => {
            select.value = index === 0 ? 'popular' : 'all';
        });
        
        minimalInputs.forEach(input => {
            input.value = '';
            input.setAttribute('data-last-value', '');
        });
        
        minimalCheckbox.checked = true;
        
        applyMinimalFilters();
    }
    
    // Слушатели событий для полей ввода цены
    minimalInputs.forEach(input => {
        // Валидация при вводе
        input.addEventListener('input', function() {
            handlePriceInput(this);
        });
        
        // Валидация при потере фокуса (на случай, если пользователь ввел отрицательное значение)
        input.addEventListener('blur', function() {
            validatePriceInput(this);
            applyMinimalFilters();
        });
        
        // Валидация при вставке из буфера обмена
        input.addEventListener('paste', function(e) {
            // Даем событию paste завершиться, затем валидируем
            setTimeout(() => {
                validatePriceInput(this);
            }, 0);
        });
        
        // Предотвращаем ввод минуса
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'Minus') {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Слушатели событий для остальных элементов
    minimalSelects.forEach(select => {
        select.addEventListener('change', applyMinimalFilters);
    });
    
    minimalCheckbox.addEventListener('change', applyMinimalFilters);
    filterReset.addEventListener('click', resetMinimalFilters);
    
    // Инициализация - устанавливаем начальные значения
    minimalInputs.forEach(input => {
        input.setAttribute('data-last-value', '');
    });
});