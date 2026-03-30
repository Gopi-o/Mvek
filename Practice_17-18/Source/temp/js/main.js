import { initModal } from './modules/modal.js';
async function initApp() {
    const[
    {
        initSmoothScroll
    }] = await Promise.all([import('./modules/utils.js'), import('./modules/utils.js')]);
    initModal();
    initSmoothScroll();
    const path = window.location.pathname;
    const page = path.split('/').pop() || 'index.html';
    const lazyModules = [];
    if (page === 'catalog.html' || document.querySelector('.filter-btn')) {
        lazyModules.push(import('./modules/filters.js').then(({
            initFilters
        }) => initFilters()))
    }
    if (page === 'profile.html' || document.querySelector('.menu-btn')) {
        lazyModules.push(import('./modules/tabs.js').then(({
            initTabs
        }) => initTabs()))
    }
    if (document.getElementById('contactForm')) {
        lazyModules.push(import('./modules/forms.js').then(({
            initForms
        }) => initForms()))
    }
    if (lazyModules.length > 0) {
        await Promise.all(lazyModules)
    }
    console.log('App initialized')
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp)
} else {
    initApp()
}