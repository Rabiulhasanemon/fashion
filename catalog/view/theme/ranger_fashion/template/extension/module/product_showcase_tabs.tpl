<section id="pst-module-<?php echo $module_uid; ?>" class="pst-module-section">
    <div class="pst-module-container">
        <?php if ($tabs) { ?>
        <div class="pst-module-header">
            <h2 class="pst-module-title"><?php echo isset($heading_title) ? $heading_title : 'Popular Categories'; ?></h2>
            <div class="pst-tabs-wrapper">
                <?php $first = true; ?>
                <?php foreach ($tabs as $tab) { ?>
                <button type="button" class="pst-tab-btn <?php echo $first ? 'pst-tab-active' : ''; ?>" 
                        data-tab-id="<?php echo $tab['id']; ?>">
                    <?php echo htmlspecialchars($tab['title']); ?>
                </button>
                <?php $first = false; ?>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        
        <div class="pst-loading-view" id="pst-loading-<?php echo $module_uid; ?>">
            <img src="catalog/view/theme/ranger_fashion/image/ajax_loader.gif" alt="Loading..." onerror="this.style.display='none'">
        </div>
        
        <div class="pst-products-wrapper" id="pst-content-<?php echo $module_uid; ?>">
            <div class="pst-slider owl-carousel" id="pst-slider-<?php echo $module_uid; ?>">
                <!-- Products will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</section>

<style>
/* Simple Premium Tabbed Products Module - Unique pst- Classes */
.pst-module-section {
    padding: 20px 0;
    background: #fff;
    margin: 0;
}

.pst-module-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.pst-module-header {
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.pst-module-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    padding-bottom: 15px;
    position: relative;
    letter-spacing: -0.02em;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.pst-module-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    border-radius: 2px;
}

.pst-tabs-wrapper {
    display: flex;
    flex-wrap: nowrap;
    gap: 10px;
    margin-top: 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #ff6b9d transparent;
    padding-bottom: 5px;
}

.pst-tabs-wrapper::-webkit-scrollbar {
    height: 4px;
}

.pst-tabs-wrapper::-webkit-scrollbar-track {
    background: transparent;
}

.pst-tabs-wrapper::-webkit-scrollbar-thumb {
    background: #ff6b9d;
    border-radius: 2px;
}

.pst-tab-btn {
    padding: 10px 22px;
    border-radius: 25px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #666;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.pst-tab-btn:hover {
    background: #f8f9fa;
    border-color: #ff6b9d;
    color: #ff6b9d;
}

.pst-tab-btn.pst-tab-active {
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);
}

/* Product Card Styles */
.pst-product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pst-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.pst-product-image-box {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.pst-product-image-box img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.pst-product-card:hover .pst-product-image-box img {
    transform: scale(1.05);
}

.pst-product-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #ff6b9d;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    z-index: 2;
}

.pst-product-buttons {
    position: absolute;
    bottom: -50px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    transition: bottom 0.3s ease;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(5px);
}

.pst-product-card:hover .pst-product-buttons {
    bottom: 0;
}

.pst-btn-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #fff;
    color: #333;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 14px;
    text-decoration: none;
}

.pst-btn-action:hover {
    background: #ff6b9d;
    color: #fff;
    transform: scale(1.1);
}

.pst-product-info {
    padding: 18px;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.pst-product-category {
    font-size: 12px;
    color: #999;
    margin-bottom: 6px;
}

.pst-product-category a {
    color: #999;
    text-decoration: none;
    transition: color 0.2s;
}

.pst-product-category a:hover {
    color: #ff6b9d;
}

.pst-product-name {
    font-size: 15px;
    font-weight: 500;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 42px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.pst-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.pst-product-name a:hover {
    color: #ff6b9d;
}

.pst-product-rating {
    margin-bottom: 10px;
}

.pst-product-rating i {
    color: #e0e0e0;
    font-size: 12px;
    margin-right: 2px;
}

.pst-product-rating i.filled {
    color: #ffc107;
}

.pst-product-price-box {
    margin-top: auto;
    padding-top: 10px;
}

.pst-product-price {
    font-size: 20px;
    font-weight: 700;
    color: #ff6b9d;
}

.pst-product-price del {
    font-size: 15px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
    margin-right: 8px;
}

/* Loading View */
.pst-loading-view {
    width: 100%;
    min-height: 300px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 10px;
    margin-bottom: 30px;
}

.pst-loading-view img {
    width: 70px;
}

.pst-loading-view.d-none {
    display: none !important;
}

/* Slider Item */
.pst-slider-item {
    padding: 10px;
}

/* Owl Carousel Navigation */
.pst-slider.owl-carousel .owl-nav div {
    width: 40px;
    height: 40px;
    line-height: 40px;
    border: 0;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background: #ff6b9d !important;
    color: #fff !important;
    opacity: 1 !important;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
}

.pst-slider.owl-carousel .owl-prev {
    left: -20px;
}

.pst-slider.owl-carousel .owl-next {
    right: -20px;
}

.pst-slider.owl-carousel .owl-nav div:hover {
    background: #ff8c9f !important;
    transform: translateY(-50%) scale(1.1);
}

.pst-slider.owl-carousel .owl-nav div.disabled {
    background: #f5f6f9 !important;
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 992px) {
    .pst-module-title {
        font-size: 24px;
    }
    
    .pst-module-title::after {
        width: 50px;
        height: 2.5px;
    }
    
    .pst-module-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .pst-tabs-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 5px;
    }
    
    .pst-tab-btn {
        font-size: 13px;
        padding: 8px 18px;
    }
}

@media (max-width: 768px) {
    .pst-module-section {
        padding: 15px 0;
    }
    
    .pst-tabs-wrapper {
        gap: 8px;
        padding-bottom: 8px;
    }
    
    .pst-tab-btn {
        flex-shrink: 0;
        white-space: nowrap;
    }
    
    .pst-module-container {
        padding: 0 15px;
    }
    
    .pst-module-title {
        font-size: 22px;
        padding-bottom: 12px;
    }
    
    .pst-module-title::after {
        width: 45px;
        height: 2px;
    }
    
    .pst-product-info {
        padding: 15px;
    }
    
    .pst-product-name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .pst-product-price {
        font-size: 18px;
    }
    
    .pst-product-buttons {
        bottom: 0;
        background: transparent;
        position: absolute;
        top: 10px;
        right: 10px;
        left: auto;
        flex-direction: column;
        width: auto;
        padding: 0;
    }
    
    .pst-btn-action {
        width: 32px;
        height: 32px;
        font-size: 12px;
        margin-bottom: 6px;
    }
    
    .pst-slider-item {
        padding: 8px;
    }
    
    .pst-slider.owl-carousel .owl-nav div {
        width: 32px;
        height: 32px;
        line-height: 32px;
    }
    
    .pst-slider.owl-carousel .owl-prev {
        left: -10px;
    }
    
    .pst-slider.owl-carousel .owl-next {
        right: -10px;
    }
}

@media (max-width: 480px) {
    .pst-module-title {
        font-size: 20px;
    }
    
    .pst-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .pst-product-price {
        font-size: 16px;
    }
    
    .pst-slider-item {
        padding: 5px;
    }
}
</style>

<script>
(function() {
    var root = document.getElementById('pst-module-<?php echo $module_uid; ?>');
    if (!root || root.dataset.pstInitialized) return;
    root.dataset.pstInitialized = 'true';

    var moduleId = <?php echo $module_id; ?>;
    var ajaxUrl = '<?php echo $ajax_url; ?>';
    var tabs = <?php echo json_encode($tabs); ?>;
    
    var tabButtons = root.querySelectorAll('.pst-tab-btn');
    var sliderContainer = root.querySelector('#pst-slider-<?php echo $module_uid; ?>');
    var loadingEl = root.querySelector('#pst-loading-<?php echo $module_uid; ?>');
    var currentTabId = tabs.length > 0 ? tabs[0].id : null;
    var owlCarousel = null;

    function showLoading() {
        if (loadingEl) {
            loadingEl.classList.remove('d-none');
        }
        if (sliderContainer) {
            sliderContainer.style.display = 'none';
        }
    }

    function hideLoading() {
        if (loadingEl) {
            loadingEl.classList.add('d-none');
        }
        if (sliderContainer) {
            sliderContainer.style.display = 'block';
        }
    }

    function loadTabProducts(tabId) {
        if (!sliderContainer || !loadingEl) return;
        
        showLoading();
        
        // Destroy existing carousel
        if (typeof jQuery !== 'undefined' && sliderContainer) {
            var $slider = jQuery(sliderContainer);
            if ($slider.data('owl.carousel')) {
                $slider.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
            }
            owlCarousel = null;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', ajaxUrl + '&tab_id=' + tabId + '&module_id=' + moduleId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success && response.products && response.products.length > 0) {
                        renderProducts(response.products);
                        hideLoading();
                        initCarousel();
                    } else {
                        hideLoading();
                        sliderContainer.innerHTML = '<div class="text-center p-4">No products found in this category.</div>';
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    hideLoading();
                    sliderContainer.innerHTML = '<div class="text-center p-4">Error loading products.</div>';
                }
            } else {
                hideLoading();
                sliderContainer.innerHTML = '<div class="text-center p-4">Error loading products.</div>';
            }
        };
        xhr.onerror = function() {
            hideLoading();
            sliderContainer.innerHTML = '<div class="text-center p-4">Error loading products.</div>';
        };
        xhr.send();
    }

    function renderProducts(products) {
        var html = '';
        
        products.forEach(function(product) {
            html += '<div class="pst-slider-item">';
            html += '<div class="pst-product-card">';
            html += '<div class="pst-product-image-box">';
            
            // Discount badge
            if (product.discount) {
                html += '<div class="pst-product-badge">-' + product.discount + '%</div>';
            }
            
            html += '<img alt="' + (product.name || 'Product') + '" src="' + product.thumb + '" />';
            html += '<div class="pst-product-buttons">';
            html += '<button type="button" class="pst-btn-action" onclick="wishlist.add(\'' + product.product_id + '\');" title="Wishlist"><i class="fa fa-heart"></i></button>';
            html += '<button type="button" class="pst-btn-action" onclick="compare.add(\'' + product.product_id + '\');" title="Compare"><i class="fa fa-exchange"></i></button>';
            html += '<button type="button" class="pst-btn-action" onclick="cart.add(\'' + product.product_id + '\');" title="Add to Cart"><i class="fa fa-shopping-cart"></i></button>';
            html += '</div>';
            html += '</div>';
            html += '<div class="pst-product-info">';
            
            // Category
            if (product.category_name) {
                html += '<div class="pst-product-category"><a href="' + (product.category_href || '#') + '">' + product.category_name + '</a></div>';
            }
            
            // Product title
            html += '<h3 class="pst-product-name"><a href="' + product.href + '">' + product.name + '</a></h3>';
            
            // Rating
            if (product.rating) {
                html += '<div class="pst-product-rating">';
                for (var i = 1; i <= 5; i++) {
                    html += '<i class="fa fa-star' + (i <= product.rating ? ' filled' : '') + '"></i>';
                }
                html += '</div>';
            }
            
            // Price
            html += '<div class="pst-product-price-box">';
            html += '<div class="pst-product-price">';
            if (product.special) {
                html += '<del>' + product.price + '</del> ' + product.special;
            } else if (product.price) {
                html += product.price;
            }
            html += '</div>';
            html += '</div>';
            
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });
        
        sliderContainer.innerHTML = html;
    }

    function initCarousel() {
        if (!sliderContainer) return;
        
        // Destroy existing carousel if it exists
        if (typeof jQuery !== 'undefined' && jQuery.fn.owlCarousel) {
            var $slider = jQuery(sliderContainer);
            if ($slider.data('owl.carousel')) {
                $slider.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
            }
            
            // Re-initialize with unique instance
            owlCarousel = $slider.addClass('owl-carousel').owlCarousel({
                loop: false,
                margin: 15,
                nav: true,
                dots: false,
                autoplay: false,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
                responsive: {
                    0: {
                        items: 2,
                        margin: 8,
                        slideBy: 2
                    },
                    576: {
                        items: 2,
                        margin: 10,
                        slideBy: 2
                    },
                    768: {
                        items: 4,
                        margin: 12,
                        slideBy: 2
                    },
                    992: {
                        items: 4,
                        margin: 15
                    },
                    1200: {
                        items: 5,
                        margin: 15
                    }
                }
            });
        }
    }

    // Tab click handlers
    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var tabId = parseInt(this.getAttribute('data-tab-id'));
            
            // Update active tab
            tabButtons.forEach(function(b) { b.classList.remove('pst-tab-active'); });
            this.classList.add('pst-tab-active');
            
            // Load products
            currentTabId = tabId;
            loadTabProducts(tabId);
        });
    });

    // Load first tab on init
    if (currentTabId) {
        loadTabProducts(currentTabId);
    }
})();
</script>

