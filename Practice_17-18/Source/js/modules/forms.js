import { openSuccessModal } from './modal.js';

export function initForms() {
    const contactForm = document.getElementById('contactForm');
    
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('.btn-submit');
        const originalText = btn.textContent;
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        if (!data.name || !data.email || !data.message) {
            alert('Заполните все обязательные поля');
            return;
        }
        
        btn.textContent = 'Отправка...';
        btn.disabled = true;
        
        try {
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            btn.textContent = originalText;
            btn.disabled = false;
            this.reset();
            
            openSuccessModal();
        } catch (error) {
            console.error('Ошибка отправки:', error);
            btn.textContent = 'Ошибка';
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        }
    });
}