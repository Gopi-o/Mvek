const modal = document.getElementById("modal");
const successModal = document.getElementById("successModal");

let productsCache = null;

async function loadProducts() {
    if (!productsCache) {
        const { products } = await import('./data.js');
        productsCache = products;
    }
    return productsCache;
}

export async function openModal(productId) {
    const products = await loadProducts();
    const product = products[productId];
    
    if (!product) {
        console.error('Product not found:', productId);
        return;
    }
    
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    const iconEl = document.getElementById("modal-icon");
    const titleEl = document.getElementById("modal-title");
    const descEl = document.getElementById("modal-description");
    const priceEl = document.getElementById("modal-price");
    
    if (iconEl) iconEl.textContent = product.icon;
    if (titleEl) titleEl.textContent = product.title;
    if (descEl) descEl.textContent = product.description;
    if (priceEl) priceEl.textContent = product.price;
    
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
}

export function closeModal() {
    if (modal) {
        modal.style.display = "none";
    }
    if (successModal) {
        successModal.style.display = "none";
    }
    document.body.style.overflow = "auto";
}

export function openSuccessModal() {
    if (!successModal) {
        console.error('Success modal not found');
        return;
    }
    successModal.style.display = "block";
    document.body.style.overflow = "hidden";
}

export function initModal() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('close')) {
            closeModal();
        }
    });

    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    
    console.log('Modal initialized');
}