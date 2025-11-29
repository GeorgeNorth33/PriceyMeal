// Функционал избранного для профиля
function removeFromFavorites(productId) {
    if (!confirm('Вы уверены, что хотите удалить товар из избранного?')) {
        return;
    }

    const favoriteItem = document.getElementById(`favorite-${productId}`);
    
    // Добавляем класс для анимации
    favoriteItem.classList.add('removing');

    fetch('includes/favorites_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'remove',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем элемент после анимации
            setTimeout(() => {
                favoriteItem.remove();
                showNotification('Товар удален из избранного', 'success');
                
                // Проверяем, остались ли товары
                checkEmptyFavorites();
            }, 300);
        } else {
            // Убираем класс анимации при ошибке
            favoriteItem.classList.remove('removing');
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error removing favorite:', error);
        favoriteItem.classList.remove('removing');
        showNotification('Ошибка при удалении из избранного', 'error');
    });
}

function checkEmptyFavorites() {
    const favoritesGrid = document.getElementById('favoritesGrid');
    const favoriteItems = favoritesGrid.querySelectorAll('.favorite-item');
    
    if (favoriteItems.length === 0) {
        favoritesGrid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1 / -1;">У вас пока нет избранных товаров</p>';
    }
}

// Функция для массового удаления (если нужно)
function removeAllFavorites() {
    if (!confirm('Вы уверены, что хотите удалить все товары из избранного?')) {
        return;
    }

    const favoriteItems = document.querySelectorAll('.favorite-item');
    let removedCount = 0;

    favoriteItems.forEach(item => {
        const productId = item.getAttribute('data-product-id');
        
        fetch('includes/favorites_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'remove',
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                item.classList.add('removing');
                setTimeout(() => {
                    item.remove();
                    removedCount++;
                    
                    if (removedCount === favoriteItems.length) {
                        checkEmptyFavorites();
                        showNotification('Все товары удалены из избранного', 'success');
                    }
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error removing favorite:', error);
        });
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Добавляем кнопку "Удалить все" если нужно
    addClearAllButton();
});

function addClearAllButton() {
    const favoritesSection = document.getElementById('favorites');
    if (!favoritesSection) return;

    const favoriteItems = document.querySelectorAll('.favorite-item');
    if (favoriteItems.length > 0) {
        const clearAllBtn = document.createElement('button');
        clearAllBtn.textContent = 'Очистить все избранное';
        clearAllBtn.className = 'clear-all-favorites-btn';
        clearAllBtn.style.cssText = `
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        `;
        clearAllBtn.onmouseover = function() {
            this.style.background = '#c82333';
            this.style.transform = 'translateY(-1px)';
        };
        clearAllBtn.onmouseout = function() {
            this.style.background = '#dc3545';
            this.style.transform = 'translateY(0)';
        };
        clearAllBtn.onclick = removeAllFavorites;

        const favoritesGrid = document.getElementById('favoritesGrid');
        favoritesSection.insertBefore(clearAllBtn, favoritesGrid);
    }
}

// Функции для профиля
function removeFromFavorites(productId) {
    if (!confirm('Вы уверены, что хотите удалить товар из избранного?')) {
        return;
    }

    const favoriteItem = document.getElementById(`favorite-${productId}`);
    
    // Добавляем класс для анимации
    favoriteItem.classList.add('removing');

    fetch('includes/favorites_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'remove',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Удаляем элемент после анимации
            setTimeout(() => {
                favoriteItem.remove();
                showNotification('Товар удален из избранного', 'success');
                
                // Проверяем, остались ли товары
                checkEmptyFavorites();
            }, 300);
        } else {
            // Убираем класс анимации при ошибке
            favoriteItem.classList.remove('removing');
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error removing favorite:', error);
        favoriteItem.classList.remove('removing');
        showNotification('Ошибка при удалении из избранного', 'error');
    });
}

function checkEmptyFavorites() {
    const favoritesGrid = document.getElementById('favoritesGrid');
    const favoriteItems = favoritesGrid.querySelectorAll('.favorite-item');
    
    if (favoriteItems.length === 0) {
        favoritesGrid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1 / -1;">У вас пока нет избранных товаров</p>';
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