/**
 * SOUK.IQ Search Autocomplete & Search page helper
 */

class SoukSearch {
    constructor() {
        this.input = document.querySelector('.search-input-field');
        this.dropdown = document.querySelector('.search-autocomplete-dropdown');
        this.clearBtn = document.querySelector('.search-clear-btn');
        this.debounceTimer = null;
        this.activeIndex = -1;

        if (this.input) {
            this.init();
        }
    }

    init() {
        // Event Listeners
        this.input.addEventListener('input', () => this.handleInput());
        this.input.addEventListener('keydown', (e) => this.handleKeyDown(e));
        this.input.addEventListener('focus', () => this.showDropdownIfNotEmpty());
        
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => this.clearSearch());
        }

        // Hide autocomplete when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.dropdown.contains(e.target)) {
                this.hideDropdown();
            }
        });
    }

    handleInput() {
        const query = this.input.value.trim();
        
        if (this.clearBtn) {
            this.clearBtn.style.display = query.length > 0 ? 'block' : 'none';
        }

        if (query.length < 2) {
            this.hideDropdown();
            return;
        }

        // Debounce search
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.fetchSuggestions(query);
        }, 300);
    }

    fetchSuggestions(query) {
        const url = `${SITE_URL}/api/v1/search?q=${encodeURIComponent(query)}`;
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                this.renderDropdown(data);
            })
            .catch(() => {
                this.hideDropdown();
            });
    }

    renderDropdown(data) {
        if (!data || (data.products.length === 0 && data.stores.length === 0 && data.categories.length === 0)) {
            this.dropdown.innerHTML = '<div class="p-3 text-muted text-center">لا توجد نتائج مطابقة</div>';
            this.showDropdown();
            return;
        }

        this.activeIndex = -1;
        let html = '';

        // Render Categories Matches
        if (data.categories && data.categories.length > 0) {
            html += `<div class="dropdown-header text-muted font-weight-bold p-2 bg-light">الفئات</div>`;
            data.categories.forEach(cat => {
                const name = document.documentElement.dir === 'ltr' ? cat.name_en : cat.name_ar;
                html += `
                    <a href="${SITE_URL}/search?category=${cat.id}" class="dropdown-item autocomplete-item d-flex align-items-center gap-2 p-2">
                        <i class="bi ${cat.icon || 'bi-tag'} text-gold"></i>
                        <span>${name}</span>
                    </a>
                `;
            });
        }

        // Render Products Matches
        if (data.products && data.products.length > 0) {
            html += `<div class="dropdown-header text-muted font-weight-bold p-2 bg-light">المنتجات</div>`;
            data.products.forEach(prod => {
                const name = document.documentElement.dir === 'ltr' ? prod.name_en : prod.name_ar;
                const formattedPrice = prod.price_formatted || prod.price;
                const img = prod.thumbnail ? `${SITE_URL}/uploads/products/${prod.thumbnail}` : 'https://placehold.co/50';
                html += `
                    <a href="${SITE_URL}/product/${prod.store_slug}/${prod.slug}" class="dropdown-item autocomplete-item d-flex align-items-center gap-2 p-2">
                        <img src="${img}" alt="${name}" width="32" height="32" class="rounded object-cover">
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-semibold text-truncate text-dark">${name}</div>
                            <small class="text-gold fw-bold">${formattedPrice}</small>
                        </div>
                    </a>
                `;
            });
        }

        // Render Stores Matches
        if (data.stores && data.stores.length > 0) {
            html += `<div class="dropdown-header text-muted font-weight-bold p-2 bg-light">المتاجر</div>`;
            data.stores.forEach(store => {
                const name = document.documentElement.dir === 'ltr' ? store.name_en : store.name_ar;
                const img = store.logo ? `${SITE_URL}/uploads/store-logos/${store.logo}` : 'https://placehold.co/50';
                html += `
                    <a href="${SITE_URL}/store/${store.slug}" class="dropdown-item autocomplete-item d-flex align-items-center gap-2 p-2">
                        <img src="${img}" alt="${name}" width="32" height="32" class="rounded-circle object-cover">
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-semibold text-dark">${name}</div>
                            <small class="text-muted"><i class="bi bi-geo-alt"></i> ${store.governorate}</small>
                        </div>
                    </a>
                `;
            });
        }

        this.dropdown.innerHTML = html;
        this.showDropdown();
    }

    handleKeyDown(e) {
        const items = this.dropdown.querySelectorAll('.autocomplete-item');
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.activeIndex = (this.activeIndex + 1) % items.length;
            this.highlightItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.activeIndex = (this.activeIndex - 1 + items.length) % items.length;
            this.highlightItem(items);
        } else if (e.key === 'Enter') {
            if (this.activeIndex > -1) {
                e.preventDefault();
                items[this.activeIndex].click();
            }
        }
    }

    highlightItem(items) {
        items.forEach((item, index) => {
            if (index === this.activeIndex) {
                item.classList.add('active');
                item.focus();
            } else {
                item.classList.remove('active');
            }
        });
    }

    showDropdownIfNotEmpty() {
        if (this.dropdown.innerHTML.trim() !== '') {
            this.showDropdown();
        }
    }

    showDropdown() {
        this.dropdown.classList.add('show');
    }

    hideDropdown() {
        this.dropdown.classList.remove('show');
    }

    clearSearch() {
        this.input.value = '';
        this.hideDropdown();
        if (this.clearBtn) {
            this.clearBtn.style.display = 'none';
        }
        this.input.focus();
    }
}

document.addEventListener('DOMContentLoaded', () => new SoukSearch());
