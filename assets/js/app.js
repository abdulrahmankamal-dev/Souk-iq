/**
 * SOUK.IQ Global Client-Side Logic
 */

const SoukApp = {
    init() {
        this.initTheme();
        this.initLanguageHandler();
        this.initLazyLoading();
    },

    // Initialize UI theme preference
    initTheme() {
        const theme = localStorage.getItem('souk_theme') || 'system';
        this.applyTheme(theme);
    },

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
        } else if (theme === 'light') {
            document.body.classList.remove('dark-mode');
        } else {
            // System preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        }
    },

    setTheme(theme) {
        localStorage.setItem('souk_theme', theme);
        this.applyTheme(theme);
    },

    // Handle language toggles
    initLanguageHandler() {
        const toggles = document.querySelectorAll('.lang-toggle-btn');
        toggles.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const lang = btn.getAttribute('data-lang');
                this.changeLanguage(lang);
            });
        });
    },

    changeLanguage(lang) {
        // Send request to server to store lang in session
        fetch(SITE_URL + '/lang/change', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `lang=${lang}`
        })
        .then(() => {
            window.location.reload();
        });
    },

    // Lazy load images
    initLazyLoading() {
        const lazyImages = document.querySelectorAll('img.lazy-load');
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        image.src = image.dataset.src;
                        image.classList.remove('lazy-load');
                        imageObserver.unobserve(image);
                    }
                });
            });
            lazyImages.forEach(image => imageObserver.observe(image));
        } else {
            // Fallback for older browsers
            lazyImages.forEach(image => {
                image.src = image.dataset.src;
            });
        }
    },

    // Spawn animated Toast messages
    showToast(message, type = 'success') {
        const toastContainer = document.getElementById('souk-toast-container') || this.createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `souk-toast souk-toast--${type}`;
        
        let icon = 'bi-check-circle-fill text-success';
        if (type === 'error') {
            icon = 'bi-exclamation-triangle-fill text-danger';
        } else if (type === 'warning') {
            icon = 'bi-exclamation-circle-fill text-warning';
        }

        toast.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi ${icon}"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
        `;

        toastContainer.appendChild(toast);

        // Auto dismiss after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutToast var(--transition-base)';
            toast.addEventListener('animationend', () => toast.remove());
        }, 3000);
    },

    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'souk-toast-container';
        container.style.position = 'fixed';
        container.style.top = '24px';
        container.style.right = '24px';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }
};

document.addEventListener('DOMContentLoaded', () => SoukApp.init());
window.SoukApp = SoukApp;
