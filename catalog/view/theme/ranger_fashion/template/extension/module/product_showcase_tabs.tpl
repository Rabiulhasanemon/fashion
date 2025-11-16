<section class="newproduct-section popular-category-sec mt-50" id="<?php echo $module_uid; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if ($tabs) { ?>
                <div class="section-title">
                    <h2 class="h3"><?php echo isset($heading_title) ? $heading_title : 'Popular Categories'; ?></h2>
                    <div class="links">
                        <?php $first = true; ?>
                        <?php foreach ($tabs as $tab) { ?>
                        <a class="category_get pst-tab-item <?php echo $first ? 'active' : ''; ?>" 
                           data-target="popular_category_view" 
                           data-tab-id="<?php echo $tab['id']; ?>"
                           href="javascript:;"><?php echo $tab['title']; ?></a>
                        <?php $first = false; ?>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="popular_category_view d-none" id="popular_category_view_<?php echo $module_uid; ?>">
            <img src="catalog/view/theme/ranger_fashion/image/ajax_loader.gif" alt="Loading..." onerror="this.style.display='none'">
        </div>
        <div class="row" id="popular_category_view_content_<?php echo $module_uid; ?>">
            <div class="col-lg-12">
                <div class="popular-category-slider owl-carousel" id="popular-category-slider-<?php echo $module_uid; ?>">
                    <!-- Products will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Section Styles */
.newproduct-section.popular-category-sec {
    padding: 50px 0;
    background-color: #f3f5f6;
}

.newproduct-section.popular-category-sec .container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
    box-sizing: border-box;
}

/* Mobile: Full width */
@media (max-width: 767px) {
    .newproduct-section.popular-category-sec .container {
        max-width: 100% !important;
        padding: 0 15px !important;
    }
}

/* Section Title */
.section-title {
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
    padding-bottom: 0;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title h2 {
    padding-bottom: 12px;
    margin-bottom: 0;
    font-weight: 600;
    font-size: 24px;
    position: relative;
}

.section-title h2::before {
    position: absolute;
    content: "";
    height: 2px;
    width: 100%;
    bottom: -2px;
    left: 0;
    background: #377dff;
}

.section-title .links {
    display: flex;
    flex-wrap: wrap;
    gap: 0;
}

.section-title .links a {
    color: #444;
    margin-left: 20px;
    position: relative;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    padding-bottom: 12px;
}

.section-title .links a::before {
    position: absolute;
    content: "";
    bottom: -10px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #A68A6A;
    opacity: 0;
    transition: 0.3s linear;
}

.section-title .links a:hover,
.section-title .links a.active {
    color: #A68A6A;
}

.section-title .links a:hover::before,
.section-title .links a.active::before {
    opacity: 1;
}

/* Product Card Styles */
.product-card {
    display: block;
    position: relative;
    width: 100%;
    border-radius: 10px;
    background-color: #fff;
    overflow: visible;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.product-card:hover {
    border-color: #A68A6A;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
}

.product-card .product-thumb {
    display: block;
    width: 100%;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    overflow: hidden;
    position: relative;
}

.product-card .product-thumb > img {
    display: block;
    width: 100%;
    padding-top: 0;
    transform: scale(1);
    transition: 0.3s linear;
}

.product-card:hover .product-thumb > img {
    transform: scale(1.1);
}

.product-card .product-badge {
    position: absolute;
    top: 15px;
    left: 0;
    border-radius: 0 9px 30px 0;
    padding: 0 12px 0 10px;
    height: 24px;
    color: #fff;
    font-size: 12px;
    font-weight: 400;
    line-height: 24px;
    z-index: 9;
}

.product-card .product-badge.product-badge2 {
    left: auto;
    right: 0;
    border-radius: 9px 0 0 30px;
    padding: 0 10px 0 12px;
    background: #daa520 !important;
}

.product-card .product-badge.bg-info {
    background: #0dcaf0 !important;
}

.product-card .product-button-group {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: 15px;
    width: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 0;
    visibility: hidden;
    z-index: 15;
    transition: all 0.3s ease;
    pointer-events: none;
}

.product-card:hover .product-button-group {
    bottom: 15px;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.product-card .product-button-group .product-button {
    height: 40px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    padding: 0;
    text-align: center;
    text-decoration: none;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    margin: 0;
    background: #A68A6A !important;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    z-index: 16;
}

.product-card .product-button-group .product-button:hover {
    background: #A68A6A !important;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(166, 138, 106, 0.4);
}

.product-card .product-button-group .product-button i {
    font-size: 16px;
    color: #ffffff;
    line-height: 1;
    display: block;
}

.product-card .product-card-body {
    padding: 15px 15px 10px;
}

.product-card .product-category {
    width: 100%;
    margin-bottom: 6px;
    font-size: 13px;
}

.product-card .product-category > a {
    transition: color 0.2s;
    color: #999;
    text-decoration: none;
}

.product-card .product-category > a:hover {
    color: #A68A6A;
}

.product-card .product-title {
    margin-bottom: 5px;
    font-size: 16px;
    font-weight: 400;
}

.product-card .product-title > a {
    transition: color 0.3s;
    color: #232323;
    text-decoration: none;
    font-size: 14px;
    height: 37px;
    display: block;
    font-weight: 500;
    line-height: 18px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-card .product-title > a:hover {
    color: #A68A6A;
}

.product-card .rating-stars {
    display: block;
    margin-bottom: 5px;
}

.product-card .rating-stars > i {
    display: inline-block;
    margin-right: 2px;
    color: #c7c7c7;
    font-size: 12px;
}

.product-card .rating-stars > i.filled {
    color: #ffa500;
}

.product-card .product-price {
    display: inline-block;
    margin-bottom: 10px;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
    color: #A68A6A;
}

.product-card .product-price > del {
    margin-right: 5px;
    color: #999;
    font-weight: 400;
    font-size: 14px;
}

/* Slider Item */
.slider-item {
    padding: 10px 6px;
}

/* Popular Category View Loading */
.popular_category_view {
    width: 100%;
    height: 398px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 10px;
    margin-bottom: 30px;
}

.popular_category_view img {
    width: 70px;
    display: inline-block;
}

.popular_category_view.d-none {
    display: none !important;
}

/* Owl Carousel Navigation */
.popular-category-slider.owl-carousel .owl-nav div {
    width: 26px;
    height: 26px;
    line-height: 26px;
    border: 0;
    border-radius: 50px;
    box-shadow: 1px 1px 4px 0 rgba(0, 0, 0, 0.13);
    background: #A68A6A !important;
    color: #fff !important;
    opacity: 1 !important;
    top: 50%;
    transform: translateY(-50%);
    transition: 0.3s linear;
}

.popular-category-slider.owl-carousel .owl-prev {
    right: 33px;
    left: auto;
}

.popular-category-slider.owl-carousel .owl-next {
    right: -10px;
}

.popular-category-slider.owl-carousel .owl-nav div:hover {
    background: #A68A6A !important;
}

.popular-category-slider.owl-carousel .owl-nav div.disabled {
    background: #f5f6f9 !important;
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 992px) {
    .section-title {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .section-title .links {
        margin-top: 15px;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .section-title .links a {
        margin-left: 0;
        margin-right: 20px;
        white-space: nowrap;
    }
    
    .section-title h2 {
        font-size: 20px;
    }
}

@media (max-width: 767px) {
    .newproduct-section.popular-category-sec {
        padding: 30px 0;
    }
    
    .section-title h2 {
        font-size: 18px;
    }
    
    .section-title .links a {
        font-size: 14px;
        margin-right: 15px;
    }
    
    .product-card .product-title > a {
        font-size: 13px;
        height: 34px;
    }
    
    .product-card .product-price {
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .newproduct-section.popular-category-sec {
        padding: 20px 0;
    }
    
    .section-title {
        margin-bottom: 20px;
    }
    
    .section-title h2 {
        font-size: 16px;
    }
    
    .section-title .links a {
        font-size: 13px;
        margin-right: 12px;
        padding-bottom: 10px;
    }
    
    /* Premium mobile product card sizing */
    .product-showcase-tabs .product-card,
    .newproduct-section.popular-category-sec .product-card {
        padding: 8px;
        border-radius: 8px;
    }
    
    .product-showcase-tabs .product-card .product-card-body,
    .newproduct-section.popular-category-sec .product-card .product-card-body {
        padding: 10px 8px 8px;
    }
    
    .product-showcase-tabs .product-card .product-title > a,
    .newproduct-section.popular-category-sec .product-card .product-title > a {
        font-size: 12px;
        line-height: 1.3;
        height: auto;
        min-height: 32px;
        -webkit-line-clamp: 2;
    }
    
    .product-showcase-tabs .product-card .product-price,
    .newproduct-section.popular-category-sec .product-card .product-price {
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .product-showcase-tabs .product-card .product-category,
    .newproduct-section.popular-category-sec .product-card .product-category {
        font-size: 11px;
        margin-bottom: 4px;
    }
    
    .product-showcase-tabs .product-card .product-button-group .product-button,
    .newproduct-section.popular-category-sec .product-card .product-button-group .product-button {
        width: 32px;
        height: 32px;
    }
    
    .product-showcase-tabs .product-card .product-button-group .product-button i,
    .newproduct-section.popular-category-sec .product-card .product-button-group .product-button i {
        font-size: 14px;
    }
    
    /* Reduce slider item padding for mobile */
    .slider-item {
        padding: 5px 4px;
    }
}

/* Tablet view - 4 products premium design */
@media (min-width: 577px) and (max-width: 991px) {
    .product-showcase-tabs .product-card,
    .newproduct-section.popular-category-sec .product-card {
        padding: 10px;
        border-radius: 10px;
    }
    
    .product-showcase-tabs .product-card .product-card-body,
    .newproduct-section.popular-category-sec .product-card .product-card-body {
        padding: 12px 10px 10px;
    }
    
    .product-showcase-tabs .product-card .product-title > a,
    .newproduct-section.popular-category-sec .product-card .product-title > a {
        font-size: 13px;
        line-height: 1.4;
        height: auto;
        min-height: 36px;
        -webkit-line-clamp: 2;
    }
    
    .product-showcase-tabs .product-card .product-price,
    .newproduct-section.popular-category-sec .product-card .product-price {
        font-size: 14px;
    }
    
    .product-showcase-tabs .product-card .product-category,
    .newproduct-section.popular-category-sec .product-card .product-category {
        font-size: 12px;
    }
    
    /* Reduce slider item padding for tablet */
    .slider-item {
        padding: 8px 5px;
    }
}
</style>

<script>
(function() {
    var root = document.getElementById('<?php echo $module_uid; ?>');
    if (!root || root.dataset.pstInitialized) return;
    root.dataset.pstInitialized = 'true';

    var moduleId = <?php echo $module_id; ?>;
    var ajaxUrl = '<?php echo $ajax_url; ?>';
    var tabs = <?php echo json_encode($tabs); ?>;
    
    var tabItems = root.querySelectorAll('.pst-tab-item');
    var sliderContainer = root.querySelector('#popular-category-slider-<?php echo $module_uid; ?>');
    var loadingEl = root.querySelector('#popular_category_view_<?php echo $module_uid; ?>');
    var currentTabId = tabs.length > 0 ? tabs[0].id : null;
    var owlCarousel = null;

    function showLoading() {
        loadingEl.classList.remove('d-none');
        sliderContainer.style.display = 'none';
    }

    function hideLoading() {
        loadingEl.classList.add('d-none');
        sliderContainer.style.display = 'block';
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
            html += '<div class="slider-item">';
            html += '<div class="product-card">';
            html += '<div class="product-thumb">';
            
            // Discount badge
            if (product.discount) {
                html += '<div class="product-badge product-badge2 bg-info">-' + product.discount + '%</div>';
            }
            
            html += '<img class="lazy" alt="' + (product.name || 'Product') + '" src="' + product.thumb + '" />';
            html += '<div class="product-button-group">';
            html += '<a class="product-button wishlist_store" onclick="wishlist.add(\'' + product.product_id + '\');" href="javascript:;" title="Wishlist"><i class="icon-heart"></i></a>';
            html += '<a class="product-button product_compare" onclick="compare.add(\'' + product.product_id + '\');" href="javascript:;" title="Compare"><i class="icon-repeat"></i></a>';
            html += '<a class="product-button add_to_single_cart" onclick="cart.add(\'' + product.product_id + '\');" href="javascript:;" title="To Cart"><i class="icon-shopping-cart"></i></a>';
            html += '</div>';
            html += '</div>';
            html += '<div class="product-card-body">';
            
            // Category
            if (product.category_name) {
                html += '<div class="product-category"><a href="' + (product.category_href || '#') + '">' + product.category_name + '</a></div>';
            }
            
            // Product title
            html += '<h3 class="product-title"><a href="' + product.href + '">' + product.name + '</a></h3>';
            
            // Rating
            html += '<div class="rating-stars">';
            for (var i = 1; i <= 5; i++) {
                html += '<i class="fas fa-star' + (i <= product.rating ? ' filled' : '') + '"></i>';
            }
            html += '</div>';
            
            // Price
            html += '<h4 class="product-price">';
            if (product.special) {
                html += '<del>' + product.price + '</del> ' + product.special;
            } else if (product.price) {
                html += product.price;
            }
            html += '</h4>';
            
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
                nav: false,
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
    tabItems.forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            var tabId = parseInt(this.getAttribute('data-tab-id'));
            
            // Update active tab
            tabItems.forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            
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
