// modules/modal.js
export function initModal() {
    const modal = document.getElementById('modal');
    if (!modal) return;

    const modalContent = modal.querySelector('.modal-content');
    const closeBtn = modal.querySelector('.close');

    // Закрытие по клику на крестик
    closeBtn.addEventListener('click', closeModal);

    // Закрытие по клику вне контента (на фон)
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Закрытие по Escape — добавляем только один раз
    document.addEventListener('keydown', handleEscKey, { once: false });

    function handleEscKey(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.openModal = async function(id) {
        const modal = document.getElementById('modal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';

        const modalBody = modal.querySelector('.modal-body');
        modalBody.innerHTML = '<p style="text-align:center;padding:40px;">Загрузка...</p>';

        try {
            const product = BFF.getProduct(id, 'desktop');
            if (!product) throw new Error('Товар не найден');

            document.getElementById('modal-icon').textContent = product.icon || '📦';
            document.getElementById('modal-title').textContent = product.fullTitle || product.title;
            
            const descEl = document.getElementById('modal-description');
            descEl.textContent = product.description || 'Описание отсутствует';
            
            document.getElementById('modal-price').textContent = product.price || '— ₽';

        } catch (err) {
            console.error(err);
            modalBody.innerHTML = '<p style="color:red;text-align:center;">Ошибка загрузки товара</p>';
        }
    };
}