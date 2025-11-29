function performSearch(searchTerm) {
    const productCards = document.querySelectorAll('.product-card');
    let foundResults = false;
    
    productCards.forEach(card => {
        const productName = card.getAttribute('data-name').toLowerCase();
        const searchText = searchTerm.toLowerCase();
        
        if (productName.includes(searchText)) {
            card.style.display = 'block';
            foundResults = true;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Показываем сообщение, если ничего не найдено
    const noProductsMessage = document.querySelector('.no-products');
    if (!foundResults) {
        if (!noProductsMessage) {
            const message = document.createElement('p');
            message.className = 'no-products';
            message.textContent = 'Товары не найдены';
            document.getElementById('productsGrid').appendChild(message);
        }
    } else {
        if (noProductsMessage) {
            noProductsMessage.remove();
        }
    }
}

// Обработчики для поиска
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    // Поиск при нажатии на кнопку
    searchButton.addEventListener('click', () => {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            performSearch(searchTerm);
        }
    });
    
    // Поиск при нажатии Enter
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                performSearch(searchTerm);
            }
        }
    });
    
    // Сброс поиска при очистке поля
    searchInput.addEventListener('input', () => {
        if (searchInput.value.trim() === '') {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.style.display = 'block';
            });
            
            const noProductsMessage = document.querySelector('.no-products');
            if (noProductsMessage && noProductsMessage.textContent === 'Товары не найдены') {
                noProductsMessage.remove();
            }
        }
    });
});