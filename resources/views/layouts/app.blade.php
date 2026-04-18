<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VR-Shop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    @yield('styles')
    @stack('styles')    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
                <i class="fas fa-vr-cardboard me-2"></i>VR-Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Главная</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('catalog.index') }}">Каталог</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('compare.index') }}">Сравнение</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pages.about') }}">О нас</a></li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        @if(Auth::user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">🛠 Админка</a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">👤 Профиль</a></li>
                                @if(!Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('orders.index')}}">📦 Мои заказы</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button class="dropdown-item" type="submit">🚪 Выход</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @endauth
                    
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart.index') }}" id="cart-toggle" title="Корзина">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" 
                                id="cart-count" style="font-size: 0.7em;">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Футер -->
    <footer class="bg-dark text-white mt-5 pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-vr-cardboard fs-3 me-2 text-primary"></i>
                        <span class="fs-4 fw-bold">VR-Shop</span>
                    </div>
                    <p class="text-white-50">Интернет-магазин VR-очков, аксессуаров и игр. Помогаем выбрать комплект под ваши задачи: игры, обучение, симуляторы и бизнес.</p>
                    <div class="d-flex gap-3">
                        <a href="https://t.me" class="text-white-50"><i class="fab fa-telegram fa-lg"></i></a>
                        <a href="https://www.youtube.com" class="text-white-50"><i class="fab fa-youtube fa-lg"></i></a>
                        <a href="mailto:info@vr-shop.ru" class="text-white-50"><i class="fas fa-envelope fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6 class="text-uppercase small text-white-50">Магазин</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ route('catalog.index') }}" class="text-white text-decoration-none d-block py-1">Каталог</a></li>
                        <li><a href="{{ route('compare.index') }}" class="text-white text-decoration-none d-block py-1">Сравнение</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white text-decoration-none d-block py-1">Корзина</a></li>
                        <li><a href="{{ route('pages.about') }}" class="text-white text-decoration-none d-block py-1">О компании</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-uppercase small text-white-50">Информация</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ route('pages.delivery') }}" class="text-white text-decoration-none d-block py-1">Доставка и оплата</a></li>
                        <li><a href="{{ route('pages.vr-games') }}" class="text-white text-decoration-none d-block py-1">Подборка VR-игр</a></li>
                        <li><a href="{{ route('home') }}#faq" class="text-white text-decoration-none d-block py-1">FAQ</a></li>
                        <li><a href="{{ route('home') }}#contact" class="text-white text-decoration-none d-block py-1">Контакты</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-uppercase small text-white-50">Поддержка</h6>
                    <p class="text-white-50 mb-2">Задайте вопрос по подбору VR-комплекта или настройке.</p>
                    <a href="mailto:info@vr-shop.ru" class="btn btn-primary btn-sm w-100 mb-2">Написать нам</a>
                    <div class="small text-white-50">Ежедневно: 10:00–20:00 МСК</div>
                    <div class="small text-white-50">Тел: +7 (900) 000-00-00</div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex justify-content-between flex-wrap text-white-50 small">
                <span>© 2025 VR-Shop. Все права защищены.</span>
                <span>VR-очки • Аксессуары • Игры</span>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title mb-0 fw-bold">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Корзина 
                        <span id="modal-cart-count" class="badge bg-primary rounded-pill fs-6">0</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-0">
                    <!-- Пустая корзина -->
                    <div id="cart-empty" class="text-center py-5 bg-light">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-75"></i>
                        <h6 class="text-muted mb-2">Корзина пуста</h6>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-sm px-4">
                            <i class="fas fa-store me-1"></i>В каталог
                        </a>
                    </div>

                    <!-- Товары -->
                    <div id="cart-items" style="display: none;">
                        <div class="list-group list-group-flush" id="cart-list"></div>
                        
                        <!-- Итог -->
                        <div class="p-3 border-top bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 fw-bold">Итого:</span>
                                <span class="h4 text-danger fw-bold mb-0" id="cart-total">0 ₽</span>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('cart.index') }}" class="btn btn-success btn-lg py-2 fs-6 fw-bold">
                                    <i class="fas fa-credit-card me-2"></i>Оформить заказ
                                </a>
                                <button onclick="clearCart()" class="btn btn-outline-danger py-1 fs-6">
                                    <i class="fas fa-trash me-1"></i>Очистить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    

    <script>
        let cartItems = @json($cartItems ?? []);
        

        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            
            document.getElementById('cart-toggle')?.addEventListener('click', function(e) {
                e.preventDefault();
                renderCart();
                new bootstrap.Modal(document.getElementById('cartModal')).show();
            });
        });

        function goToCart() {
            window.location.href = '{{ route("cart.index") }}';
        }

        function updateCartCount() {
            const count = Object.values(cartItems).reduce((sum, item) => sum + (item.quantity || 0), 0);
            document.getElementById('cart-count').textContent = count;
            document.getElementById('modal-cart-count').textContent = count;
        }

        window.addToCart = function(productId, productName = null, productImage = null, productPrice = null) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    cartItems[productId] = {
                        id: productId,
                        name: data.product?.name || productName || 'VR-очки',
                        price: data.product?.price || productPrice || 12999,
                        image: data.product?.image || productImage || null,
                        quantity: data.count || 1
                    };
                    
                    updateCartCount();
                    renderCart(); 
                    showToast(data.message || 'Товар добавлен');
                }
            })
            .catch(e => showToast('Ошибка добавления'));
        };

        function renderCart() {
            const emptyEl = document.getElementById('cart-empty');
            const itemsEl = document.getElementById('cart-items');
            const listEl = document.getElementById('cart-list');
            const totalEl = document.getElementById('cart-total');
            
            const totalCount = Object.values(cartItems).reduce((sum, item) => sum + (item.quantity || 0), 0);
            
            if (totalCount === 0) {
                emptyEl.style.display = 'block';
                itemsEl.style.display = 'none';
                return;
            }
            
            emptyEl.style.display = 'none';
            itemsEl.style.display = 'block';
            
            let html = '';
            let totalPrice = 0;
            
            Object.entries(cartItems).forEach(([productId, item]) => {
                const price = item.price || 12999;
                const subtotal = (item.quantity || 0) * price;
                totalPrice += subtotal;
                
                const imgHtml = item.image ? 
                    '<img src="/storage/img/' + item.image + '" alt="' + item.name + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">' : 
                    '<i class="fas fa-vr-cardboard text-primary" style="font-size: 1.1rem;"></i>';
                
                html += `
                    <div class="list-group-item px-3 py-2 border-0">
                        <div class="row align-items-center g-2">
                            <div class="col-auto">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                    style="width: 48px; height: 48px;">
                                    ${imgHtml}
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-semibold lh-1">${item.name || 'VR-очки'}</h6>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <span class="badge bg-success">${item.quantity || 0} шт.</span>
                                            <span>${price.toLocaleString()} ₽</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-success fw-bold h6 mb-0">${subtotal.toLocaleString()} ₽</span>
                                        <button onclick="removeFromCart(${productId})" 
                                                class="btn btn-sm btn-outline-danger p-1 ms-1 rounded-circle" 
                                                style="width: 32px; height: 32px;">
                                            <i class="fas fa-trash-alt fs-6"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            
            listEl.innerHTML = html;
            totalEl.textContent = `${totalPrice.toLocaleString()} ₽`;
        }

        function removeFromCart(id) {
            if (!confirm('Удалить товар?')) return;
            
            fetch('{{ route("cart.remove") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({product_id: id})
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    delete cartItems[id];
                    renderCart();
                    updateCartCount();
                    showToast(data.message);
                }
            })
            .catch(e => showToast('Ошибка'));
        }

        function clearCart() {
            if (confirm('Очистить корзину?')) {
                fetch('{{ route("cart.clear") }}', {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
                })
                .then(r => r.json())
                .then(() => {
                    cartItems = {};
                    renderCart();
                    updateCartCount();
                    showToast('Корзина очищена');
                });
            }
        }

        function showToast(msg) {
            const toast = document.createElement('div');
            toast.innerHTML = `<div class="alert alert-success alert-dismissible fade show">${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;width:350px;';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
        </script>


    @stack('scripts')
</body>
</html>
