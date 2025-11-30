<?php echo $header; ?>

<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
                    <span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
                </a>
                <meta itemprop="position" content="<?php echo $i; ?>" />
            </li>
            <?php }} ?>
        </ul>
    </div>
</section>

<section id="cp-page-wrapper" class="cp-page-section">
    <div class="cp-page-container">
        
        <?php if (isset($category_modules) && !empty($category_modules)) { ?>
        <div id="cp-modules-wrapper" class="cp-modules-section">
            <?php foreach ($category_modules as $module) { ?>
            <div class="cp-module-item">
                <?php if (!empty($module['description'])) { ?>
                <div class="cp-module-description">
                    <?php 
                    $description = $module['description'];
                    if (htmlspecialchars_decode($description) != $description) {
                        $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                    $description = stripslashes($description);
                    echo $description;
                    ?>
                </div>
                <?php } ?>
                <?php if (isset($module['output']) && !empty(trim($module['output']))) { ?>
                <div class="cp-module-content">
                    <?php echo $module['output']; ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <style>
        /* Hide module titles on category pages */
        .cp-module-content .module-heading-wrapper,
        .cp-module-content .section-head,
        .cp-module-content h2.cosmetics-module-heading,
        .cp-module-content h2.unified-module-heading,
        .cp-module-content .cosmetics-module-heading,
        .cp-module-content .unified-module-heading,
        .cp-module-content .latest-products__title,
        .cp-module-content .popular-products__title,
        .cp-module-content .heading_title,
        .cp-module-content .panel-heading,
        .cp-module-content .section-title h2,
        .cp-module-content .section-title h3,
        .cp-module-content .section-title .h3 {
            display: none !important;
        }
        .cp-module-content .section-title {
            padding-bottom: 0 !important;
            margin-bottom: 15px !important;
        }
        .cp-module-content > div:first-child {
            margin-top: 0;
            padding-top: 0;
        }
        .cp-module-content .deal-of-day-section,
        .cp-module-content .flash-sell-new-section,
        .cp-module-content .newproduct-section {
            margin-top: 0 !important;
        }
        </style>
        <?php } ?>

        <div class="cp-layout-grid">
            <?php echo $column_left; ?>
            
            <div id="cp-main-content" class="cp-main-wrapper">
                <header id="cp-page-header" class="cp-header">
                    <div class="cp-header-top">
                        <div class="cp-title-section">
                            <h1 id="cp-page-title" class="cp-title"><?php echo $heading_title; ?></h1>
                            <button id="cp-filter-toggle" class="cp-filter-button" aria-label="Toggle filters">
                                <i class="fa fa-filter"></i>
                                <span>Filter</span>
                            </button>
                        </div>
                        <div class="cp-controls-section">
                            <div class="cp-control-item">
                                <label class="cp-control-label"><?php echo $text_limit; ?></label>
                                <div class="cp-select-box">
                                    <select id="cp-limit-select" class="cp-select" onchange="location = this.value;">
                                        <?php foreach ($limits as $limits) { ?>
                                        <option value="<?php echo $limits['href']; ?>" <?php echo ($limits['value'] == $limit) ? 'selected="selected"' : ''; ?>>
                                            <?php echo $limits['text']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="cp-control-item">
                                <label class="cp-control-label"><?php echo $text_sort; ?></label>
                                <div class="cp-select-box">
                                    <select id="cp-sort-select" class="cp-select" onchange="location = this.value;">
                                        <?php foreach ($sorts as $sorts) { ?>
                                        <option value="<?php echo $sorts['href']; ?>" <?php echo ($sorts['value'] == $sort . '-' . $order) ? 'selected="selected"' : ''; ?>>
                                            <?php echo $sorts['text']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                
                <div id="cp-products-grid" class="cp-products-grid">
                    <?php foreach ($products as $product) { ?>
                    <article class="cp-product-card" data-product-id="<?php echo $product['product_id']; ?>">
                        <?php if ($product['special']) { ?>
                        <?php
                          $price = floatval(str_replace(['৳', ','], '', $product['price']));
                          $special = floatval(str_replace(['৳', ','], '', $product['special']));
                          $discountAmount = $price - $special;
                          $mark = ($discountAmount / $price) * 100;
                        ?>
                        <div class="cp-badge"><?php echo round($mark, 1); ?>% OFF</div>
                        <?php } ?>

                        <a href="<?php echo $product['href']; ?>" class="cp-product-link">
                            <div class="cp-image-box">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="cp-product-image" loading="lazy" />
                            </div>
                        </a>

                        <div class="cp-product-info">
                            <h3 class="cp-product-name">
                                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3>
                            <div class="cp-price-box">
                                <?php if ($product['special']) { ?>
                                <span class="cp-price-sale"><?php echo $product['special']; ?></span>
                                <span class="cp-price-old"><?php echo $product['price']; ?></span>
                                <?php } else { ?>
                                <span class="cp-price-normal"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            <div class="cp-actions-box">
                                <button class="cp-action-btn cp-wishlist-btn" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" aria-label="Add to wishlist" title="Add to Wishlist">
                                    <i class="fa fa-heart"></i>
                                </button>
                                <?php if($product["disablePurchase"] && $product["restock_request_btn"]) { ?>
                                <button class="cp-action-btn cp-buy-btn cp-buy-btn-full" onclick="restock_request.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["restock_request_btn"]; ?>
                                </button>
                                <?php } elseif ($product["disablePurchase"]) { ?>
                                <button class="cp-action-btn cp-buy-btn cp-buy-btn-full" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["stock_status"]; ?>
                                </button>
                                <?php } else { ?>
                                <button class="cp-action-btn cp-buy-btn cp-buy-btn-full" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Buy Now</span>
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </article>
                    <?php }
                    if (!$products) { ?>
                    <div class="cp-empty-state">
                        <div class="cp-empty-content">
                            <div class="cp-empty-icon">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <h3 class="cp-empty-title">Sorry! No Products Found</h3>
                            <p class="cp-empty-text">Please try searching for something else</p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <footer id="cp-page-footer" class="cp-footer">
                    <div class="cp-footer-content">
                        <div class="cp-pagination-box">
                            <?php echo $pagination; ?>
                        </div>
                        <div class="cp-results-box">
                            <p class="cp-results-text"><?php echo $results; ?></p>
                        </div>
                    </div>
                </footer>
                <?php echo $content_bottom; ?>
            </div>
            
            <?php echo $column_right; ?>
        </div>
    </div>
</section>

<style>
.hvab24_stack {
    gap: 0px !important;
}

/* Premium Category Page - Unique cp- Classes */
#cp-page-wrapper.cp-page-section {
    background: #fff;
    padding: 40px 0 60px 0;
}

.cp-page-container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
}

.cp-modules-section {
    margin-bottom: 40px;
}

.cp-module-item {
    margin-bottom: 30px;
}

.cp-module-description {
    margin-bottom: 20px;
}

.cp-layout-grid {
    display: grid;
    
    gap: 30px;
    align-items: start;
}

/* If no sidebar, use full width */
.cp-layout-grid > :first-child:empty,
.cp-layout-grid > :first-child:not(:has(*)) {
    display: none;
}

.cp-layout-grid:has(> :first-child:empty) {
    grid-template-columns: 1fr;
}

/* Header Styles */
.cp-header {
    margin-bottom: 30px;
}

.cp-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(0,0,0,0.05);
}

.cp-title-section {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.cp-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -0.02em;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.cp-filter-button {
    display: none;
    padding: 10px 20px;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    gap: 8px;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255,107,157,0.2);
}

.cp-filter-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,107,157,0.3);
}

.cp-controls-section {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.cp-control-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cp-control-label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
    white-space: nowrap;
}

.cp-select-box {
    position: relative;
}

.cp-select {
    padding: 10px 35px 10px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23333" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    min-width: 150px;
}

.cp-select:hover {
    border-color: #ff6b9d;
    box-shadow: 0 0 0 3px rgba(255,107,157,0.1);
}

.cp-select:focus {
    outline: none;
    border-color: #ff6b9d;
    box-shadow: 0 0 0 3px rgba(255,107,157,0.15);
}

/* Products Grid */
.cp-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.cp-product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.cp-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: rgba(255,107,157,0.2);
}

.cp-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 12px;
    z-index: 10;
    box-shadow: 0 3px 8px rgba(255,107,107,0.3);
}

.cp-product-link {
    position: relative;
    display: block;
    text-decoration: none;
}

.cp-image-box {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.cp-product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.cp-product-card:hover .cp-product-image {
    transform: scale(1.08);
}

.cp-product-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.cp-product-name {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 12px 0;
    line-height: 1.4;
    min-height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.cp-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.cp-product-name a:hover {
    color: #ff6b9d;
}

.cp-price-box {
    margin-bottom: 15px;
    margin-top: auto;
}

.cp-price-sale {
    font-size: 22px;
    font-weight: 700;
    color: #ff6b9d;
    margin-right: 8px;
}

.cp-price-old {
    font-size: 16px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.cp-price-normal {
    font-size: 22px;
    font-weight: 700;
    color: #1a1a1a;
}

.cp-actions-box {
    display: flex;
    gap: 10px;
}

.cp-action-btn {
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
}

.cp-wishlist-btn {
    flex: 0 0 45px;
    background: #f0f0f0;
    color: #666;
}

.cp-wishlist-btn:hover {
    background: #ff6b9d;
    color: #fff;
    transform: scale(1.05);
}

.cp-buy-btn {
    flex: 1;
    background: linear-gradient(135deg, #ff6b9d 0%, #ff8c9f 100%);
    color: #fff;
}

.cp-buy-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,107,157,0.3);
}

.cp-buy-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.cp-buy-btn-full {
    flex: 2;
}

/* Footer */
.cp-footer {
    padding: 30px 0;
    border-top: 1px solid rgba(0,0,0,0.05);
    margin-top: 40px;
}

.cp-footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.cp-pagination-box {
    flex: 1;
}

.cp-results-box {
    flex-shrink: 0;
}

.cp-results-text {
    color: #666;
    font-size: 14px;
    margin: 0;
}

/* Empty State */
.cp-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}

.cp-empty-content {
    max-width: 400px;
    margin: 0 auto;
}

.cp-empty-icon {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.cp-empty-title {
    font-size: 24px;
    font-weight: 600;
    color: #666;
    margin: 0 0 10px 0;
}

.cp-empty-text {
    color: #999;
    font-size: 16px;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 992px) {
    .cp-layout-grid {
        grid-template-columns: 1fr;
    }
    
    .cp-filter-button {
        display: flex;
    }
    
    .cp-title {
        font-size: 28px;
    }
    
    .cp-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 768px) {
    #cp-page-wrapper.cp-page-section {
        padding: 30px 0 50px 0;
    }
    
    .cp-page-container {
        padding: 0 15px;
    }
    
    .cp-title {
        font-size: 24px;
    }
    
    .cp-header-top {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .cp-title-section {
        justify-content: space-between;
    }
    
    .cp-controls-section {
        width: 100%;
        justify-content: space-between;
    }
    
    .cp-control-item {
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .cp-select {
        width: 100%;
        min-width: auto;
    }
    
    .cp-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .cp-product-card:hover {
        transform: none;
    }
    
    .cp-product-info {
        padding: 15px;
    }
    
    .cp-product-name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .cp-price-sale,
    .cp-price-normal {
        font-size: 20px;
    }
    
    .cp-action-btn {
        padding: 10px;
        font-size: 13px;
    }
    
    .cp-wishlist-btn {
        flex: 0 0 40px;
    }
}

@media (max-width: 480px) {
    .cp-title {
        font-size: 22px;
    }
    
    .cp-control-label {
        font-size: 12px;
    }
    
    .cp-select {
        font-size: 13px;
        padding: 8px 30px 8px 10px;
    }
    
    .cp-products-grid {
        gap: 12px;
    }
    
    .cp-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .cp-price-sale,
    .cp-price-normal {
        font-size: 18px;
    }
    
    .cp-price-old {
        font-size: 14px;
    }
    
    .cp-action-btn {
        padding: 8px;
        font-size: 12px;
    }
    
    .cp-wishlist-btn {
        flex: 0 0 36px;
    }
    
    .cp-empty-icon {
        font-size: 48px;
    }
    
    .cp-empty-title {
        font-size: 20px;
    }
    
    .cp-empty-text {
        font-size: 14px;
    }
}

/* Pagination Styles */
.cp-pagination-box .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.cp-pagination-box .pagination li {
    list-style: none;
}

.cp-pagination-box .pagination a,
.cp-pagination-box .pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.cp-pagination-box .pagination a {
    color: #666;
    background: #f8f9fa;
    color: #333;
}

.cp-pagination-box .pagination a:hover {
    background: #ff6b9d;
    color: #fff;
    transform: translateY(-2px);
}

.cp-pagination-box .pagination .active span {
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    color: #fff;
}

/* Accessibility */
.cp-action-btn:focus,
.cp-select:focus,
.cp-filter-button:focus {
    outline: 2px solid #ff6b9d;
    outline-offset: 2px;
}

/* Print Styles */
@media print {
    .cp-filter-button,
    .cp-controls-section,
    .cp-actions-box {
        display: none;
    }
    
    .cp-products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
(function() {
    // Filter toggle functionality
    var filterToggle = document.getElementById('cp-filter-toggle');
    var columnLeft = document.querySelector('.cp-layout-grid > div:first-child');
    
    if (filterToggle && columnLeft) {
        filterToggle.addEventListener('click', function() {
            columnLeft.classList.toggle('cp-sidebar-open');
        });
    }
})();
</script>

<?php echo $footer; ?>