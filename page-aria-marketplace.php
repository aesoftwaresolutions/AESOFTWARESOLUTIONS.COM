<?php
/**
 * Template Name: Aria Marketplace
 * Description: Public marketplace for AI-generated digital assets created by Aria
 *
 * @package AESoftwaresTheme
 */

get_header(); ?>

<div class="aria-marketplace-hero">
    <div class="hero-content">
        <h1>AI-Generated Assets</h1>
        <p>Built by Aria, Sold Here</p>
        <p class="hero-subtext">Premium digital products created by autonomous AI agent</p>
    </div>
</div>

<div class="aria-marketplace-container">
    <div class="marketplace-filters">
        <button class="filter-tab active" data-category="all">All Products</button>
        <button class="filter-tab" data-category="Email Sequences">Email Sequences</button>
        <button class="filter-tab" data-category="LinkedIn Content Pack">LinkedIn Content Pack</button>
        <button class="filter-tab" data-category="Automation Playbook">Automation Playbook</button>
        <button class="filter-tab" data-category="Prospect Research">Prospect Research</button>
        <button class="filter-tab" data-category="Sales Script">Sales Script</button>
    </div>

    <div id="aria-products-grid" class="marketplace-grid">
        <div class="loading-message">Loading products...</div>
    </div>
</div>

<style>
    :root {
        --ae-bg: #0a0e27;
        --ae-accent: #00d4ff;
        --ae-accent-dark: #0099bb;
        --ae-text: #e0e6f2;
        --ae-text-muted: #8a92b2;
        --ae-card-bg: #131b3f;
        --ae-border: #1f2a5c;
    }

    .aria-marketplace-hero {
        background: linear-gradient(135deg, var(--ae-bg) 0%, #1a2560 100%);
        padding: 80px 20px;
        text-align: center;
        border-bottom: 1px solid var(--ae-border);
    }

    .hero-content h1 {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--ae-accent);
        margin: 0 0 10px 0;
        text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
    }

    .hero-content p {
        margin: 0;
        color: var(--ae-text);
    }

    .hero-content p:first-of-type {
        font-size: 1.25rem;
        font-weight: 500;
    }

    .hero-subtext {
        color: var(--ae-text-muted);
        font-size: 1rem;
        margin-top: 10px !important;
    }

    .aria-marketplace-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        background: var(--ae-bg);
    }

    .marketplace-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 40px;
        justify-content: center;
    }

    .filter-tab {
        padding: 10px 18px;
        border: 1px solid var(--ae-border);
        background: transparent;
        color: var(--ae-text-muted);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .filter-tab:hover {
        border-color: var(--ae-accent);
        color: var(--ae-accent);
    }

    .filter-tab.active {
        background: var(--ae-accent);
        color: var(--ae-bg);
        border-color: var(--ae-accent);
    }

    .marketplace-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .product-card {
        background: var(--ae-card-bg);
        border: 1px solid var(--ae-border);
        border-radius: 8px;
        padding: 24px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        border-color: var(--ae-accent);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.15);
        transform: translateY(-4px);
    }

    .product-category {
        display: inline-block;
        background: rgba(0, 212, 255, 0.1);
        color: var(--ae-accent);
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 12px;
        width: fit-content;
    }

    .product-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--ae-text);
        margin: 0 0 12px 0;
    }

    .product-description {
        color: var(--ae-text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0 0 16px 0;
        flex-grow: 1;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid var(--ae-border);
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ae-accent);
    }

    .product-btn {
        padding: 10px 20px;
        background: var(--ae-accent);
        color: var(--ae-bg);
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .product-btn:hover {
        background: var(--ae-accent-dark);
        transform: scale(1.05);
    }

    .loading-message {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: var(--ae-text-muted);
        font-size: 1.1rem;
    }

    .empty-message {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--ae-text-muted);
    }

    .empty-message h3 {
        color: var(--ae-text);
        font-size: 1.3rem;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .marketplace-grid {
            grid-template-columns: 1fr;
        }

        .marketplace-filters {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 10px;
        }
    }
</style>

<script>
(function() {
    const productGrid = document.getElementById('aria-products-grid');
    const filterTabs = document.querySelectorAll('.filter-tab');
    let allProducts = [];
    let activeCategory = 'all';

    // Fetch products on page load
    async function loadProducts() {
        try {
            const response = await fetch(ae_aria_settings.products_url);
            if (!response.ok) throw new Error('Failed to fetch products');

            allProducts = await response.json();
            renderProducts();
        } catch (error) {
            console.error('Error loading products:', error);
            productGrid.innerHTML = '<div class="loading-message">Unable to load products. Please try again later.</div>';
        }
    }

    // Render products based on active category filter
    function renderProducts() {
        const filtered = activeCategory === 'all'
            ? allProducts
            : allProducts.filter(p => p.category === activeCategory);

        if (filtered.length === 0) {
            productGrid.innerHTML = `
                <div class="empty-message">
                    <h3>No products in this category</h3>
                    <p>Check back soon for new AI-generated assets</p>
                </div>
            `;
            return;
        }

        productGrid.innerHTML = filtered.map(product => `
            <div class="product-card">
                <div class="product-category">${escapeHtml(product.category)}</div>
                <h3 class="product-title">${escapeHtml(product.title)}</h3>
                <p class="product-description">${escapeHtml(product.description)}</p>
                <div class="product-footer">
                    <div class="product-price">$${product.price}</div>
                    <a href="${ae_aria_settings.contact_url}?product=${encodeURIComponent(product.title)}" class="product-btn">Buy / Download</a>
                </div>
            </div>
        `).join('');
    }

    // Setup filter tab listeners
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeCategory = this.dataset.category;
            renderProducts();
        });
    });

    // HTML escape utility
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Load products when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadProducts);
    } else {
        loadProducts();
    }
})();
</script>

<?php get_footer(); ?>
