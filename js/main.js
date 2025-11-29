// main.js - исправленная версия для горизонтальных фильтров
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing filters');
    
    // Инициализация фильтров
    initFilters();
    
    // Инициализация поиска
    initSearch();
    
    // Инициализация категорий
    initCategories();
});

function initFilters() {
    const filterForm = document.getElementById('filterForm');
    if (!filterForm) return;
    
    const minimalSelects = document.querySelectorAll('.minimal-select');
    const minimalInputs = document.querySelectorAll('.minimal-input');
    const filterReset = document.querySelector('.filter-reset');
    
    console.log('Found elements:', {
        selects: minimalSelects.length,
        inputs: minimalInputs.length,
        reset: !!filterReset
    });

    // Функция применения фильтров
    function applyMinimalFilters() {
        console.log('Applying filters...');
        
        // Показываем индикатор загрузки
        const productsGrid = document.querySelector('.products-grid');
        if (productsGrid) {
            productsGrid.innerHTML = '<div class="loading">Загрузка товаров...</div>';
        }
        
        // Отправляем форму
        filterForm.submit();
    }

    // Сброс фильтров
    function resetMinimalFilters() {
        console.log('Resetting filters');
        window.location.href = 'index.php';
    }

    // Валидация ввода цены
    function validatePriceInput(input) {
        let value = input.value.replace(/[^\d.]/g, '');
        
        // Удаляем лишние точки
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        
        // Ограничиваем до 2 знаков после запятой
        if (parts.length === 2 && parts[1].length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }
        
        input.value = value;
        return value;
    }

    // Обработчик ввода с задержкой
    function handlePriceInput(input) {
        validatePriceInput(input);
        
        clearTimeout(input.timer);
        input.timer = setTimeout(() => {
            applyMinimalFilters();
        }, 800);
    }

    // Назначаем обработчики для select элементов
    minimalSelects.forEach(select => {
        select.addEventListener('change', function() {
            console.log('Select changed:', this.name, this.value);
            applyMinimalFilters();
        });
    });

    // Назначаем обработчики для input элементов
    minimalInputs.forEach(input => {
        // Валидация при вводе
        input.addEventListener('input', function() {
            handlePriceInput(this);
        });
        
        // Валидация при потере фокуса
        input.addEventListener('blur', function() {
            validatePriceInput(this);
            applyMinimalFilters();
        });
        
        // Предотвращаем ввод минуса
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'Minus') {
                e.preventDefault();
            }
        });
    });

    // Обработчик кнопки сброса
    if (filterReset) {
        filterReset.addEventListener('click', function(e) {
            e.preventDefault();
            resetMinimalFilters();
        });
    }

    console.log('Filters initialized successfully');
}

function initSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchForm = document.querySelector('#searchForm');
    
    if (searchInput && searchForm) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    console.log('Submitting search form');
                    searchForm.submit();
                }
            }, 800);
        });
    }
}

function initCategories() {
    const categoryLinks = document.querySelectorAll('.categories a[data-category]');
    
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.getAttribute('data-category');
            filterByCategory(categoryId);
        });
    });
}

// Глобальные функции для использования в HTML
function filterByCategory(categoryId) {
    const form = document.getElementById('filterForm');
    if (!form) return;
    
    // Удаляем старый параметр категории если есть
    const oldCategoryInput = form.querySelector('input[name="category"]');
    if (oldCategoryInput) {
        oldCategoryInput.remove();
    }
    
    // Добавляем новый параметр категории
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'category';
    input.value = categoryId;
    form.appendChild(input);
    
    console.log('Filtering by category:', categoryId);
    form.submit();
}

function resetFilters() {
    window.location.href = 'index.php';
}

// Функции для очистки отдельных фильтров
function clearSearch() {
    const url = new URL(window.location);
    url.searchParams.delete('search');
    window.location.href = url.toString();
}

function clearStoreFilter() {
    const url = new URL(window.location);
    url.searchParams.delete('store');
    window.location.href = url.toString();
}

function clearPriceFrom() {
    const url = new URL(window.location);
    url.searchParams.delete('price_from');
    window.location.href = url.toString();
}

function clearPriceTo() {
    const url = new URL(window.location);
    url.searchParams.delete('price_to');
    window.location.href = url.toString();
}

function clearSort() {
    const url = new URL(window.location);
    url.searchParams.delete('sort');
    window.location.href = url.toString();
}