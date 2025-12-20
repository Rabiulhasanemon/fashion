<section id="pst-module-<?php echo $module_uid; ?>" class="pst-module-section">
    <div class="container">
        <?php if ($tabs) { ?>
        <div class="psh-module-header" id="psh-header-<?php echo $module_uid; ?>">
            <div class="psh-header-left">
                <h2 class="psh-module-title">
                    <?php echo isset($heading_title) && $heading_title ? htmlspecialchars($heading_title) : 'Module Name'; ?>
                </h2>
            </div>
            <div class="psh-header-center">
                <div class="psh-tabs-nav">
                    <?php $first = true; ?>
                    <?php foreach ($tabs as $tab) { ?>
                    <button type="button" class="psh-tab-btn <?php echo $first ? 'psh-tab-active' : ''; ?>" 
                            data-tab-id="<?php echo $tab['id']; ?>">
                        <?php echo htmlspecialchars($tab['title']); ?>
                    </button>
                    <?php $first = false; ?>
                    <?php } ?>
                </div>
            </div>
            <div class="psh-header-right">
                <div class="psh-nav-arrows" id="psh-nav-<?php echo $module_uid; ?>">
                    <button type="button" class="psh-nav-btn psh-nav-prev" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" class="psh-nav-btn psh-nav-next" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
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
    padding: 40px 0;
    background: #fff;
}

.pst-module-container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
}

/* Product Showcase Header - Premium Style (matches flash deal) */
.psh-module-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
    padding: 15px 20px;
    background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
    border-left: 4px solid #fafafa;
    border-radius: 0 8px 8px 0;
    position: relative;
    gap: 16px;
    flex-wrap: wrap;
}

.psh-module-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, #FF6A00 0%, rgba(255, 106, 0, 0.1) 100%);
}

.psh-header-left {
    flex-shrink: 0;
}

.psh-module-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.4;
}

.psh-header-center {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.psh-tabs-nav {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 0 10px;
}

.psh-tab-btn {
    padding: 8px 16px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #555;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    text-transform: uppercase;
    position: relative;
    border-radius: 6px;
}

.psh-tab-btn:hover {
    border-color: #FF6A00;
    color: #FF6A00;
}

.psh-tab-btn.psh-tab-active {
    background: #FF6A00;
    color: #fff;
    border-color: #FF6A00;
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.25);
}

.psh-header-right {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.psh-nav-arrows {
    display: flex;
    gap: 8px;
}

.psh-nav-btn {
    width: 36px;
    height: 36px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 8px;
    font-size: 14px;
}

.psh-nav-btn:hover {
    background: #FF6A00;
    color: #fff;
    border-color: #FF6A00;
}

.psh-nav-btn:active {
    transform: scale(0.95);
}

/* Old styles kept for backward compatibility */
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
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 0;
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

/* Reward Points - Top Right Position */
.pst-product-image-box .pst-reward-points {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    background: #fff7e6;
    color: #b37400;
    font-size: 12px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.pst-product-image-box .pst-reward-points i {
    color: #ff9800;
    font-size: 14px;
}

/* Adjust badge position when reward points are present */
.pst-product-image-box:has(.pst-reward-points) .pst-product-badge {
    top: 50px; /* Move discount badge down if reward points are present */
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

/* Slider Item - Fixed Height for Alignment */
.pst-slider-item {
    padding: 10px;
    height: 100%;
    display: flex;
}

.pst-slider-item .pst-product-card {
    min-height: 480px; /* Fixed minimum height for all products */
    width: 100%;
}

.pst-slider-item .psh-new-product-card {
    min-height: auto;
    width: 100%;
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
        padding: 30px 0;
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

@media (max-width: 768px) {
    .psh-module-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .psh-header-center {
        width: 100%;
        order: 2;
    }
    
    .psh-header-right {
        order: 3;
        width: 100%;
        justify-content: flex-end;
    }
    
    .psh-tabs-nav {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .psh-tabs-nav::-webkit-scrollbar {
        display: none;
    }
    
    .psh-tab-btn {
        font-size: 12px;
        padding: 6px 14px;
    }
}

@media (max-width: 480px) {
    .pst-module-title {
        font-size: 20px;
    }
    
    .psh-nav-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
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

/* =================================================
   NEW PRODUCT SHOWCASE STYLE - psh-new- Classes
   Matches Uploaded Image Design
   ================================================= */

.psh-new-product-card {
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.psh-new-product-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.psh-new-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 75%;
    overflow: hidden;
    background: #f8f9fa;
}

.psh-new-product-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 15px;
    transition: transform 0.3s ease;
}

.psh-new-product-card:hover .psh-new-product-img {
    transform: scale(1.05);
}

/* Discount Badge - Top Right - Matching Uploaded Image Style (Red Banner with Lightning Bolt) */
.psh-new-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
    color: #ffffff;
    z-index: 2;
    line-height: 1.3;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

.psh-new-badge-blue {
    background: #2196F3;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
}

.psh-new-badge-red {
    background: #e53e3e;
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    box-shadow: 0 2px 8px rgba(229, 62, 62, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.psh-new-badge i {
    font-size: 13px;
    margin-right: 2px;
    display: inline-block;
    color: #ffffff;
    font-weight: 700;
}

.psh-new-badge-red i {
    color: #ffffff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

/* Delivery Time - Below Image, Left-Aligned */
.psh-new-delivery {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 8px;
    font-size: 10px;
    font-weight: 600;
    color: #666666;
}

.psh-new-delivery i {
    font-size: 10px;
    color: #2196F3;
}

/* Product Details */
.psh-new-product-details {
    padding: 12px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.psh-new-product-name {
    font-size: 13px;
    font-weight: 500;
    margin: 0 0 8px 0;
    line-height: 1.4;
    min-height: 36px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.psh-new-product-name a {
    color: #333333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.psh-new-product-name a:hover {
    color: #10503D;
}

/* Rating Box */
.psh-new-rating-box {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}

.psh-new-stars {
    display: flex;
    gap: 2px;
}

.psh-new-stars i {
    font-size: 11px;
    color: #e0e0e0;
}

.psh-new-stars i.psh-new-star-filled {
    color: #ffc107;
}

.psh-new-review-count {
    font-size: 11px;
    color: #999999;
    font-weight: 500;
}

/* Bottom Section - Price and ADD Button */
.psh-new-bottom-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    gap: 10px;
}

/* Price Box */
.psh-new-price-box {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex: 1;
}

.psh-new-price-old {
    font-size: 13px;
    color: #999999;
    text-decoration: line-through;
    font-weight: 400;
}

.psh-new-price-current {
    font-size: 16px;
    font-weight: 700;
    color: #333333;
}

/* ADD Button - Bottom Right */
.psh-new-add-btn {
    padding: 8px 20px;
    background: transparent;
    border: 2px solid #10503D;
    border-radius: 6px;
    color: #10503D;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    flex-shrink: 0;
}

.psh-new-add-btn:hover {
    background: #10503D;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(16, 80, 61, 0.3);
}

.psh-new-add-btn:active {
    transform: translateY(0);
}

/* Responsive Design for New Style */
@media (max-width: 768px) {
    .psh-new-product-details {
        padding: 10px;
    }
    
    .psh-new-product-name {
        font-size: 12px;
        min-height: 32px;
    }
    
    .psh-new-price-current {
        font-size: 15px;
    }
    
    .psh-new-price-old {
        font-size: 12px;
    }
    
    .psh-new-add-btn {
        padding: 7px 16px;
        font-size: 12px;
    }
    
    .psh-new-badge {
        font-size: 11px;
        padding: 5px 10px;
        gap: 3px;
    }
    
    .psh-new-badge i {
        font-size: 10px;
    }
    
    .psh-new-delivery {
        font-size: 9px;
        margin-bottom: 6px;
    }
    
    .psh-new-bottom-section {
        flex-wrap: wrap;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .psh-new-product-name {
        font-size: 11px;
        min-height: 30px;
    }
    
    .psh-new-price-current {
        font-size: 14px;
    }
    
    .psh-new-price-old {
        font-size: 11px;
    }
    
    .psh-new-add-btn {
        padding: 6px 14px;
        font-size: 11px;
    }
    
    .psh-new-badge {
        font-size: 10px;
        padding: 4px 8px;
        gap: 2px;
    }
    
    .psh-new-badge i {
        font-size: 9px;
    }
    
    .psh-new-delivery {
        font-size: 8px;
        margin-bottom: 5px;
    }
    
    .psh-new-stars i {
        font-size: 10px;
    }
    
    .psh-new-review-count {
        font-size: 10px;
    }
    
    .psh-new-bottom-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    
    .psh-new-add-btn {
        align-self: flex-end;
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
    
    var tabButtons = root.querySelectorAll('.pst-tab-btn, .psh-tab-btn');
    var sliderContainer = root.querySelector('#pst-slider-<?php echo $module_uid; ?>');
    var loadingEl = root.querySelector('#pst-loading-<?php echo $module_uid; ?>');
    var currentTabId = tabs.length > 0 ? tabs[0].id : null;
    var owlCarousel = null;
    var navPrevBtn = root.querySelector('.psh-nav-prev');
    var navNextBtn = root.querySelector('.psh-nav-next');

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
            // Calculate discount percentage if not provided
            var discountPercent = product.discount || 0;
            if (!discountPercent && product.price && product.special) {
                var priceNum = parseFloat(product.price.replace(/[^\d.]/g, ''));
                var specialNum = parseFloat(product.special.replace(/[^\d.]/g, ''));
                if (priceNum > 0) {
                    discountPercent = Math.round(((priceNum - specialNum) / priceNum) * 100);
                }
            }
            
            // Get review count (default to 0 if not provided)
            var reviewCount = product.reviews || 0;
            
            html += '<div class="pst-slider-item">';
            html += '<div class="psh-new-product-card">';
            html += '<div class="psh-new-image-wrapper">';
            
            // Discount badge - Top Right (Red banner with lightning bolt - matching image style)
            if (discountPercent > 0) {
                html += '<div class="psh-new-badge psh-new-badge-red">';
                html += '<i class="fa fa-bolt"></i> ';
                html += discountPercent + '% OFF';
                html += '</div>';
            }
            
            html += '<img alt="' + (product.name || 'Product') + '" src="' + product.thumb + '" class="psh-new-product-img" />';
            html += '</div>';
            
            html += '<div class="psh-new-product-details">';
            
            // Delivery time indicator - Below image, left-aligned
            html += '<div class="psh-new-delivery">';
            html += '<i class="fa fa-rocket"></i>';
            html += '<span>24-48 Hours</span>';
            html += '</div>';
            
            // Product name
            html += '<h3 class="psh-new-product-name"><a href="' + product.href + '">' + product.name + '</a></h3>';
            
            // Rating with review count
            html += '<div class="psh-new-rating-box">';
            html += '<div class="psh-new-stars">';
            var ratingValue = product.rating || 0;
            for (var i = 1; i <= 5; i++) {
                html += '<i class="fa fa-star' + (i <= Math.round(ratingValue) ? ' psh-new-star-filled' : '') + '"></i>';
            }
            html += '</div>';
            html += '<span class="psh-new-review-count">(' + reviewCount + ')</span>';
            html += '</div>';
            
            // Price and ADD button container
            html += '<div class="psh-new-bottom-section">';
            html += '<div class="psh-new-price-box">';
            if (product.special) {
                html += '<span class="psh-new-price-old">' + product.price + '</span>';
                html += '<span class="psh-new-price-current">' + product.special + '</span>';
            } else if (product.price) {
                html += '<span class="psh-new-price-current">' + product.price + '</span>';
            }
            html += '</div>';
            
            // ADD button - Bottom Right
            html += '<button type="button" class="psh-new-add-btn" onclick="cart.add(\'' + product.product_id + '\');">ADD</button>';
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
            
            // Re-initialize with unique instance - AUTOMATIC SLIDING ENABLED
            owlCarousel = $slider.addClass('owl-carousel').owlCarousel({
                loop: true,
                margin: 15,
                nav: false, // Disable default nav, use custom buttons
                dots: false,
                autoplay: true, // ENABLE AUTOMATIC SLIDING
                autoplayTimeout: 4000, // 4 seconds between slides
                autoplayHoverPause: true, // Pause on hover
                autoplaySpeed: 800,
                smartSpeed: 600,
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
            
            // Connect custom navigation buttons
            if (navPrevBtn && owlCarousel) {
                navPrevBtn.addEventListener('click', function() {
                    owlCarousel.trigger('prev.owl.carousel');
                });
            }
            
            if (navNextBtn && owlCarousel) {
                navNextBtn.addEventListener('click', function() {
                    owlCarousel.trigger('next.owl.carousel');
                });
            }
        }
    }

    // Tab click handlers - Support both old and new classes
    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var tabId = parseInt(this.getAttribute('data-tab-id'));
            
            // Update active tab - Support both old and new classes
            tabButtons.forEach(function(b) { 
                b.classList.remove('pst-tab-active', 'psh-tab-active'); 
            });
            this.classList.add('pst-tab-active', 'psh-tab-active');
            
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

