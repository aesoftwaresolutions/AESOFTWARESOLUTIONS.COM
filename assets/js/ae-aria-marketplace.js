/**
 * Aria Marketplace frontend
 */

(function() {
    const productGrid = document.getElementById('aria-products-grid');
    const filterTabs = document.querySelectorAll('.filter-tab');
    let allProducts = [];
    let activeCategory = 'all';

    /**
     * Fetch products from REST endpoint
     */
    async function loadProducts() {
        try {
            const response = await fetch(ae_aria_settings.products_url);
            if (!response.ok) throw new Error('Failed to fetch products');

            allProducts = await response.json();
            if (!Array.isArray(allProducts)) {
                allProducts = [];
            }
            renderProducts();
        } catch (error) {
            console.error('Error loading products:', error);
            productGrid.innerHTML =
                '<div class="loading-message">Unable to load products. Please try again later.</div>';
        }
    }

    /**
     * Render products filtered by active category
     */
    function renderProducts() {
        const filtered =
            activeCategory === 'all'
                ? allProducts
                : allProducts.filter((p) => p.category === activeCategory);

        if (filtered.length === 0) {
            productGrid.innerHTML = `
                <div class="empty-message">
                    <h3>No products in this category</h3>
                    <p>Check back soon for new AI-generated assets</p>
                </div>
            `;
            return;
        }

        productGrid.innerHTML = filtered
            .map(
                (product) => `
            <div class="product-card">
                <div class="product-category">${escapeHtml(product.category)}</div>
                <h3 class="product-title">${escapeHtml(product.title)}</h3>
                <p class="product-description">${escapeHtml(product.description)}</p>
                <div class="product-footer">
                    <div class="product-price">$${parseFloat(product.price).toFixed(2)}</div>
                    <a href="${ae_aria_settings.contact_url}?product=${encodeURIComponent(
                        product.title
                    )}" class="product-btn">Buy / Download</a>
                </div>
            </div>
        `
            )
            .join('');
    }

    /**
     * Setup filter tab event listeners
     */
    function setupFilterTabs() {
        filterTabs.forEach((tab) => {
            tab.addEventListener('click', function () {
                filterTabs.forEach((t) => t.classList.remove('active'));
                this.classList.add('active');
                activeCategory = this.dataset.category;
                renderProducts();
            });
        });
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Initialize on page load
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setupFilterTabs();
            loadProducts();
        });
    } else {
        setupFilterTabs();
        loadProducts();
    }
})();
