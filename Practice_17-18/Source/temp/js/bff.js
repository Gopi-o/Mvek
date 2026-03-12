const rawProducts = {
    1: {
        id: 1,
        icon: '📱',
        title: 'Смартфон Pro',
        fullTitle: 'Смартфон Pro Max 256GB Midnight Black',
        description: 'Флагманский смартфон с 6.7" дисплеем, 256GB памяти, тройной камерой 108MP и аккумулятором 5000mAh. Поддержка 5G и быстрой зарядки 65W.',
        shortDesc: 'Флагман с 108MP камерой',
        price: 29999,
        priceFormatted: '29 999 ₽',
        category: 'electronics',
        stock: 15,
        rating: 4.8,
        reviews: 128,
        specs: {
            screen: '6.7" OLED',
            battery: '5000mAh',
            storage: '256GB',
            camera: '108MP'
        },
        images: ['smartphone-300.jpg', 'smartphone-600.jpg'],
        createdAt: '2024-01-15'
    },
    2: {
        id: 2,
        icon: '💻',
        title: 'Ноутбук Ultra',
        fullTitle: 'Ноутбук Ultra Book Pro 14"',
        description: 'Ультрабук с процессором Intel Core i7, 16GB RAM, SSD 512GB, 14" 4K дисплеем. Вес всего 1.2 кг, автономность до 12 часов.',
        shortDesc: 'Ультрабук i7/16GB/512GB',
        price: 54999,
        priceFormatted: '54 999 ₽',
        category: 'electronics',
        stock: 8,
        rating: 4.9,
        reviews: 89,
        specs: {
            cpu: 'Intel Core i7',
            ram: '16GB',
            storage: '512GB SSD',
            screen: '14" 4K'
        },
        images: ['laptop-300.jpg', 'laptop-600.jpg'],
        createdAt: '2024-01-10'
    },
    3: {
        id: 3,
        icon: '🎧',
        title: 'Наушники Max',
        fullTitle: 'Наушники Max Wireless Pro',
        description: 'Премиальные беспроводные наушники с активным шумоподавлением, пространственным аудио и 30-часовой автономностью.',
        shortDesc: 'ANC, 30ч автономность',
        price: 12499,
        priceFormatted: '12 499 ₽',
        category: 'accessories',
        stock: 42,
        rating: 4.7,
        reviews: 256,
        specs: {
            type: 'Over-ear',
            anc: 'Да',
            battery: '30ч',
            codec: 'LDAC'
        },
        images: ['headears-300.jpg', 'headears-600.jpg'],
        createdAt: '2024-02-01'
    },
    4: {
        id: 4,
        icon: '⌚',
        title: 'Смарт-часы',
        fullTitle: 'Смарт-часы Series 5 Pro',
        description: 'Умные часы с GPS, пульсометром, мониторингом сна и SpO2. Защита от воды 5ATM, 100+ спортивных режимов.',
        shortDesc: 'GPS, пульсометр, SpO2',
        price: 8999,
        priceFormatted: '8 999 ₽',
        category: 'accessories',
        stock: 23,
        rating: 4.6,
        reviews: 167,
        specs: {
            sensors: 'GPS, SpO2, HR',
            waterproof: '5ATM',
            modes: '100+',
            battery: '14дн'
        },
        images: ['smartclock-300.jpg', 'smartclock-600.jpg'],
        createdAt: '2024-01-20'
    }
};
function mobileAdapter(product) {
    return {
        id: product.id,
        icon: product.icon,
        title: product.title,
        price: product.priceFormatted,
        shortDesc: product.shortDesc,
        rating: product.rating,
        image: product.images[0]
    }
}
function desktopAdapter(product) {
    return {
        id: product.id,
        icon: product.icon,
        title: product.title,
        fullTitle: product.fullTitle,
        description: product.description,
        price: product.priceFormatted,
        category: product.category,
        stock: product.stock,
        rating: product.rating,
        reviews: product.reviews,
        specs: product.specs,
        images: product.images
    }
}
function listAdapter(product) {
    return {
        id: product.id,
        icon: product.icon,
        title: product.title,
        price: product.priceFormatted
    }
}
export const BFF = {
    getProduct(id, clientType = 'desktop') {
        const product = rawProducts[id];
        if (!product) return null;
        switch (clientType) {
        case 'mobile':
            return mobileAdapter(product);
        case 'list':
            return listAdapter(product);
        case 'desktop':
            default:
            return desktopAdapter(product)
        }
    },
    getAllProducts(clientType = 'desktop') {
        const products = Object.values(rawProducts);
        switch (clientType) {
        case 'mobile':
            return products.map(mobileAdapter);
        case 'list':
            return products.map(listAdapter);
        case 'desktop':
            default:
            return products.map(desktopAdapter)
        }
    },
    getByCategory(category, clientType = 'desktop') {
        const filtered = Object.values(rawProducts).filter(p => p.category === category);
        switch (clientType) {
        case 'mobile':
            return filtered.map(mobileAdapter);
        case 'list':
            return filtered.map(listAdapter);
        default:
            return filtered.map(desktopAdapter)
        }
    },
    getDataSize(clientType = 'desktop') {
        const data = this.getAllProducts(clientType);
        const json = JSON.stringify(data);
        return {
            type: clientType,
            items: data.length,
            bytes: new Blob([json]).size,
            kb: (new Blob([json]).size / 1024).toFixed(2) + ' KB'
        }
    }
};
window.BFF = BFF