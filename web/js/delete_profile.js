function openDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
            document.getElementById('confirm_password').focus();
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.getElementById('deleteAccountForm').reset();
        }

        // Закрытие модального окна при клике вне его
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Подтверждение при отправке формы
        document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
            const confirmation = confirm(
                'ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ УДАЛИТЬ АККАУНТ?\n\n' +
                'Это действие невозможно отменить. Все ваши данные будут безвозвратно удалены.'
            );
            
            if (!confirmation) {
                e.preventDefault();
            }
        });

        // Закрытие по ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });