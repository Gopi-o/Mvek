// const products = {
//     1: {
//         icon: '📱',
//         title: 'Смартфон Pro',
//         description: 'Флагманский смартфон с 6.7" дисплеем, 256GB памяти, тройной камерой 108MP и аккумулятором 5000mAh. Поддержка 5G и быстрой зарядки 65W.',
//         price: '29 999 ₽'
//     },
//     2: {
//         icon: '💻',
//         title: 'Ноутбук Ultra',
//         description: 'Ультрабук с процессором Intel Core i7, 16GB RAM, SSD 512GB, 14" 4K дисплеем. Вес всего 1.2 кг, автономность до 12 часов.',
//         price: '54 999 ₽'
//     },
//     3: {
//         icon: '🎧',
//         title: 'Наушники Max',
//         description: 'Премиальные беспроводные наушники с активным шумоподавлением, пространственным аудио и 30-часовой автономностью. Качество студийного уровня.',
//         price: '12 499 ₽'
//     },
//     4: {
//         icon: '⌚',
//         title: 'Смарт-часы',
//         description: 'Умные часы с GPS, пульсометром, мониторингом сна и SpO2. Защита от воды 5ATM, 100+ спортивных режимов, автономность 14 дней.',
//         price: '8 999 ₽'
//     },
//     5: {
//         icon: '📷',
//         title: 'Фотоаппарат DSLR',
//         description: 'Профессиональная зеркальная камера с матрицей 24.2MP, съемкой 4K видео, системой автофокуса на 45 точек. Идеальна для фотографов.',
//         price: '45 999 ₽'
//     },
//     6: {
//         icon: '🎮',
//         title: 'Игровой контроллер',
//         description: 'Беспроводной контроллер с адаптивными триггерами, тактильной отдачей, встроенным микрофоном и до 12 часов работы от аккумулятора.',
//         price: '4 999 ₽'
//     }
// };

// let modal = document.getElementById("modal");
// let successModal = document.getElementById("successModal");

// function openModal(productId) {
//     const product = products[productId];
//     if (product) {
//         document.getElementById("modal-icon").textContent = product.icon;
//         document.getElementById("modal-title").textContent = product.title;
//         document.getElementById("modal-description").textContent = product.description;
//         document.getElementById("modal-price").textContent = product.price;
        
//         if (modal) {
//             modal.style.display = "block";
//             document.body.style.overflow = "hidden";
//         }
//     }
// }

// function closeModal() {
//     if (modal) {
//         modal.style.display = "none";
//         document.body.style.overflow = "auto";
//     }
//     if (successModal) {
//         successModal.style.display = "none";
//         document.body.style.overflow = "auto";
//     }
// }

// document.addEventListener('click', function(e) {
//     if (e.target.classList.contains('close')) {
//         closeModal();
//     }
// });

// window.onclick = function(event) {
//     if (event.target.classList.contains('modal')) {
//         closeModal();
//     }
// }

// document.addEventListener('keydown', function(e) {
//     if (e.key === 'Escape') {
//         closeModal();
//     }
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const filterBtns = document.querySelectorAll('.filter-btn');
//     const productCards = document.querySelectorAll('.product-card');
    
//     filterBtns.forEach(btn => {
//         btn.addEventListener('click', function() {
//             filterBtns.forEach(b => b.classList.remove('active'));
//             this.classList.add('active');
            
//             const filter = this.getAttribute('data-filter');
            
//             productCards.forEach(card => {
//                 if (filter === 'all' || card.getAttribute('data-category') === filter) {
//                     card.style.display = 'block';
//                     card.style.animation = 'fadeIn 0.5s';
//                 } else {
//                     card.style.display = 'none';
//                 }
//             });
//         });
//     });
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const menuBtns = document.querySelectorAll('.menu-btn');
//     const tabContents = document.querySelectorAll('.tab-content');
    
//     menuBtns.forEach(btn => {
//         btn.addEventListener('click', function() {
//             const tabId = this.getAttribute('data-tab');
            
//             menuBtns.forEach(b => b.classList.remove('active'));
//             tabContents.forEach(t => t.classList.remove('active'));
            
//             this.classList.add('active');
//             document.getElementById(tabId).classList.add('active');
//         });
//     });
// });

// // ===== Форма обратной связи =====
// document.addEventListener('DOMContentLoaded', function() {
//     const contactForm = document.getElementById('contactForm');
    
//     if (contactForm) {
//         contactForm.addEventListener('submit', function(e) {
//             e.preventDefault();
            
//             const btn = this.querySelector('.btn-submit');
//             const originalText = btn.textContent;
//             btn.textContent = 'Отправка...';
//             btn.disabled = true;
            
//             setTimeout(() => {
//                 btn.textContent = originalText;
//                 btn.disabled = false;
//                 this.reset();
                
//                 if (successModal) {
//                     successModal.style.display = 'block';
//                     document.body.style.overflow = 'hidden';
//                 }
//             }, 1500);
//         });
//     }
// });

// document.querySelectorAll('a[href^="#"]').forEach(anchor => {
//     anchor.addEventListener('click', function(e) {
//         e.preventDefault();
//         const target = document.querySelector(this.getAttribute('href'));
//         if (target) {
//             target.scrollIntoView({
//                 behavior: 'smooth',
//                 block: 'start'
//             });
//         }
//     });
// });