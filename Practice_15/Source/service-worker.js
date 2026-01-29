const CACHE_NAME = 'static-cache-v1';
const FILES_TO_CACHE = [
    '/',
    '/index.html',
    '/style.css',
    '/js/app.js',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(FILES_TO_CACHE);
            })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keyList => {
                return Promise.all(
                    keyList.map(key => {
                        if (key !== CACHE_NAME) {
                            return caches.delete(key);
                        }
                    })
                );
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
            .catch(() => {
                return caches.match('/offline.html');
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request)
            .catch(() => {
                return caches.match(event.request);
            })
    );
});


self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(cachedResponse => {
                const fetchPromise = fetch(event.request)
                    .then(networkResponse => {
                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(event.request, networkResponse.clone());
                            });
                        return networkResponse;
                    });
                return cachedResponse || fetchPromise;
            })
    );
});