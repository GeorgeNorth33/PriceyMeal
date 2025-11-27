    document.addEventListener('DOMContentLoaded', function() {
        const categoryLinks = document.querySelectorAll('.categories-list a');
        const productCards = document.querySelectorAll('.product-card');

        // Фильтрация по категориям
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Обновляем активные ссылки
                categoryLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                
                productCards.forEach(card => {
                    if (category === 'all') {
                        card.style.display = 'flex';
                    } else {
                        if (card.getAttribute('data-category') === category) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
    });