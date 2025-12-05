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

<section id="fcp-page-wrapper" class="fcp-page-section">
    <div class="fcp-page-container">
        
        <?php if (isset($category_modules) && !empty($category_modules)) { ?>
        <div id="fcp-modules-wrapper" class="fcp-modules-section">
            <?php foreach ($category_modules as $module) { ?>
            <div class="fcp-module-item">
                <?php if (!empty($module['description'])) { ?>
                <div class="fcp-module-description">
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
                <div class="fcp-module-content">
                    <?php echo $module['output']; ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <style>
        /* Hide module titles on category pages */
        .fcp-module-content .module-heading-wrapper,
        .fcp-module-content .section-head,
        .fcp-module-content h2.cosmetics-module-heading,
        .fcp-module-content h2.unified-module-heading,
        .fcp-module-content .cosmetics-module-heading,
        .fcp-module-content .unified-module-heading,
        .fcp-module-content .latest-products__title,
        .fcp-module-content .popular-products__title,
        .fcp-module-content .heading_title,
        .fcp-module-content .panel-heading,
        .fcp-module-content .section-title h2,
        .fcp-module-content .section-title h3,
        .fcp-module-content .section-title .h3 {
            display: none !important;
        }
        .fcp-module-content .section-title {
            padding-bottom: 0 !important;
            margin-bottom: 15px !important;
        }
        .fcp-module-content > div:first-child {
            margin-top: 0;
            padding-top: 0;
        }
        .fcp-module-content .deal-of-day-section,
        .fcp-module-content .flash-sell-new-section,
        .fcp-module-content .newproduct-section {
            margin-top: 0 !important;
        }
        </style>
        <?php } ?>

        <div class="fcp-layout-grid">
            <?php echo $column_left; ?>
            
            <div id="fcp-main-content" class="fcp-main-wrapper">
                <header id="fcp-page-header" class="fcp-header">
                    <div class="fcp-header-top">
                        <div class="fcp-title-section">
                            <h1 id="fcp-page-title" class="fcp-title"><?php echo $heading_title; ?></h1>
                            <button id="fcp-filter-toggle" class="fcp-filter-button" aria-label="Toggle filters">
                                <i class="fa fa-filter"></i>
                                <span>Filter</span>
                            </button>
                        </div>
                        <div class="fcp-controls-section">
                            <div class="fcp-control-item">
                                <label class="fcp-control-label"><?php echo $text_limit; ?></label>
                                <div class="fcp-select-box">
                                    <select id="fcp-limit-select" class="fcp-select" onchange="location = this.value;">
                                        <?php foreach ($limits as $limits) { ?>
                                        <option value="<?php echo $limits['href']; ?>" <?php echo ($limits['value'] == $limit) ? 'selected="selected"' : ''; ?>>
                                            <?php echo $limits['text']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="fcp-control-item">
                                <label class="fcp-control-label"><?php echo $text_sort; ?></label>
                                <div class="fcp-select-box">
                                    <select id="fcp-sort-select" class="fcp-select" onchange="location = this.value;">
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
                
                <div id="fcp-products-grid" class="fcp-products-grid">
                    <?php foreach ($products as $product) { ?>
                    <article class="fcp-product-card" data-product-id="<?php echo $product['product_id']; ?>">
                        <?php if ($product['special']) { ?>
                        <?php
                          $price = floatval(str_replace(['৳', ','], '', $product['price']));
                          $special = floatval(str_replace(['৳', ','], '', $product['special']));
                          $discountAmount = $price - $special;
                          $mark = ($discountAmount / $price) * 100;
                        ?>
                        <div class="fcp-badge"><?php echo round($mark, 1); ?>% OFF</div>
                        <?php } ?>

                        <a href="<?php echo $product['href']; ?>" class="fcp-product-link">
                            <div class="fcp-image-box">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="fcp-product-image" loading="lazy" />
                            </div>
                        </a>

                        <div class="fcp-product-info">
                            <h3 class="fcp-product-name">
                                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3>
                            <div class="fcp-price-box">
                                <?php if ($product['special']) { ?>
                                <span class="fcp-price-sale"><?php echo $product['special']; ?></span>
                                <span class="fcp-price-old"><?php echo $product['price']; ?></span>
                                <?php } else { ?>
                                <span class="fcp-price-normal"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            <div class="fcp-actions-box">
                                <button class="fcp-action-btn fcp-wishlist-btn" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" aria-label="Add to wishlist" title="Add to Wishlist">
                                    <i class="fa fa-heart"></i>
                                </button>
                                <?php if($product["disablePurchase"] && $product["restock_request_btn"]) { ?>
                                <button class="fcp-action-btn fcp-buy-btn fcp-buy-btn-full" onclick="restock_request.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["restock_request_btn"]; ?>
                                </button>
                                <?php } elseif ($product["disablePurchase"]) { ?>
                                <button class="fcp-action-btn fcp-buy-btn fcp-buy-btn-full" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["stock_status"]; ?>
                                </button>
                                <?php } else { ?>
                                <button class="fcp-action-btn fcp-buy-btn fcp-buy-btn-full" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Buy Now</span>
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </article>
                    <?php }
                    if (!$products) { ?>
                    <div class="fcp-empty-state">
                        <div class="fcp-empty-content">
                            <div class="fcp-empty-icon">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <h3 class="fcp-empty-title">Sorry! No Products Found</h3>
                            <p class="fcp-empty-text">Please try searching for something else</p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <footer id="fcp-page-footer" class="fcp-footer">
                    <div class="fcp-footer-content">
                        <div class="fcp-pagination-box">
                            <?php echo $pagination; ?>
                        </div>
                        <div class="fcp-results-box">
                            <p class="fcp-results-text"><?php echo $results; ?></p>
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

/* ============================================
   MODERN PREMIUM CATEGORY PAGE - FCP Classes
   ============================================ */

#fcp-page-wrapper.fcp-page-section {
    background: #fff;
    padding: 30px 0 50px 0;
}

.fcp-page-container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
}

.fcp-modules-section {
    margin-bottom: 30px;
}

.fcp-module-item {
    margin-bottom: 20px;
}

.fcp-module-description {
    margin-bottom: 15px;
}

/* Layout Grid */
.fcp-layout-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 25px;
    align-items: start;
}

/* If no sidebar, use full width */
.fcp-layout-grid > :first-child:empty,
.fcp-layout-grid > :first-child:not(:has(*)) {
    display: none;
}

.fcp-layout-grid:has(> :first-child:empty) {
    grid-template-columns: 1fr;
}

/* Filter Sidebar Styling */
.fcp-layout-grid > #column-left {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fcp-layout-grid > #column-left::-webkit-scrollbar {
    width: 5px;
}

.fcp-layout-grid > #column-left::-webkit-scrollbar-track {
    background: #f5f7fa;
    border-radius: 10px;
}

.fcp-layout-grid > #column-left::-webkit-scrollbar-thumb {
    background: #10503D;
    border-radius: 10px;
}

.fcp-layout-grid > #column-left::-webkit-scrollbar-thumb:hover {
    background: #0a3d2e;
}

/* Header Styles */
.fcp-header {
    margin-bottom: 25px;
}

.fcp-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    padding-bottom: 18px;
    border-bottom: 1px solid rgba(166, 138, 106, 0.15);
}

.fcp-title-section {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.fcp-title {
    font-size: 28px;
    font-weight: 600;
    color: #10503D;
    margin: 0;
    letter-spacing: -0.01em;
    font-family: 'Jost', sans-serif;
}

.fcp-filter-button {
    display: none;
    padding: 8px 16px;
    background: #10503D;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    gap: 6px;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(16, 80, 61, 0.2);
}

.fcp-filter-button:hover {
    background: #0a3d2e;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(16, 80, 61, 0.3);
}

.fcp-controls-section {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.fcp-control-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.fcp-control-label {
    font-size: 13px;
    color: #666;
    font-weight: 500;
    white-space: nowrap;
}

.fcp-select-box {
    position: relative;
}

.fcp-select {
    padding: 8px 32px 8px 12px;
    border: 1px solid #A68A6A;
    border-radius: 6px;
    background: #fff;
    font-size: 13px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%2310503D" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    min-width: 130px;
    font-family: 'Jost', sans-serif;
}

.fcp-select:hover {
    border-color: #10503D;
    box-shadow: 0 0 0 3px rgba(16, 80, 61, 0.08);
}

.fcp-select:focus {
    outline: none;
    border-color: #10503D;
    box-shadow: 0 0 0 3px rgba(16, 80, 61, 0.12);
}

/* Products Grid */
.fcp-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 18px;
    margin-bottom: 35px;
}

.fcp-product-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid rgba(166, 138, 106, 0.12);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}

.fcp-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(16, 80, 61, 0.12);
    border-color: rgba(166, 138, 106, 0.3);
}

.fcp-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #10503D;
    color: #fff;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 11px;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(16, 80, 61, 0.25);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fcp-product-link {
    position: relative;
    display: block;
    text-decoration: none;
}

.fcp-image-box {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #fafafa;
}

.fcp-product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 12px;
    transition: transform 0.4s ease;
}

.fcp-product-card:hover .fcp-product-image {
    transform: scale(1.05);
}

.fcp-product-info {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.fcp-product-name {
    font-size: 14px;
    font-weight: 500;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 38px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.fcp-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.fcp-product-name a:hover {
    color: #10503D;
}

.fcp-price-box {
    margin-bottom: 12px;
    margin-top: auto;
}

.fcp-price-sale {
    font-size: 18px;
    font-weight: 600;
    color: #10503D;
    margin-right: 6px;
}

.fcp-price-old {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.fcp-price-normal {
    font-size: 18px;
    font-weight: 600;
    color: #10503D;
}

.fcp-actions-box {
    display: flex;
    gap: 8px;
}

.fcp-action-btn {
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    font-family: 'Jost', sans-serif;
}

.fcp-wishlist-btn {
    flex: 0 0 40px;
    background: #f5f5f5;
    color: #666;
}

.fcp-wishlist-btn:hover {
    background: #10503D;
    color: #fff;
    transform: scale(1.05);
}

.fcp-buy-btn {
    flex: 1;
    background: #10503D;
    color: #fff;
}

.fcp-buy-btn:hover:not(:disabled) {
    background: #0a3d2e;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(16, 80, 61, 0.3);
}

.fcp-buy-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.fcp-buy-btn-full {
    flex: 2;
}

/* Footer */
.fcp-footer {
    padding: 25px 0;
    border-top: 1px solid rgba(166, 138, 106, 0.15);
    margin-top: 35px;
}

.fcp-footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.fcp-pagination-box {
    flex: 1;
}

.fcp-results-box {
    flex-shrink: 0;
}

.fcp-results-text {
    color: #666;
    font-size: 13px;
    margin: 0;
}

/* Empty State */
.fcp-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.fcp-empty-content {
    max-width: 400px;
    margin: 0 auto;
}

.fcp-empty-icon {
    font-size: 56px;
    color: #ddd;
    margin-bottom: 15px;
}

.fcp-empty-title {
    font-size: 22px;
    font-weight: 600;
    color: #666;
    margin: 0 0 8px 0;
}

.fcp-empty-text {
    color: #999;
    font-size: 14px;
    margin: 0;
}

/* Pagination Styles */
.fcp-pagination-box .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.fcp-pagination-box .pagination li {
    list-style: none;
}

.fcp-pagination-box .pagination a,
.fcp-pagination-box .pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 13px;
}

.fcp-pagination-box .pagination a {
    color: #666;
    background: #f5f5f5;
    color: #333;
}

.fcp-pagination-box .pagination a:hover {
    background: #10503D;
    color: #fff;
    transform: translateY(-1px);
}

.fcp-pagination-box .pagination .active span {
    background: #10503D;
    color: #fff;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .fcp-page-container {
        max-width: 90%;
    }
    
    .fcp-layout-grid {
        grid-template-columns: 260px 1fr;
        gap: 20px;
    }
}

@media (max-width: 992px) {
    .fcp-layout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .fcp-filter-button {
        display: flex;
    }
    
    .fcp-title {
        font-size: 24px;
    }
    
    .fcp-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }
    
    /* Mobile Filter Sidebar */
    .fcp-layout-grid > #column-left {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100vh;
        z-index: 1000;
        border-radius: 0;
        box-shadow: -2px 0 20px rgba(0, 0, 0, 0.15);
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-height: 100vh;
        padding-top: 60px;
    }
    
    .fcp-layout-grid > #column-left.fcp-sidebar-open {
        right: 0;
    }
    
    /* Overlay for mobile filter */
    .fcp-layout-grid::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .fcp-layout-grid.fcp-sidebar-open::before {
        opacity: 1;
        visibility: visible;
    }
}

@media (max-width: 768px) {
    #fcp-page-wrapper.fcp-page-section {
        padding: 25px 0 40px 0;
    }
    
    .fcp-page-container {
        padding: 0 15px;
        max-width: 100%;
    }
    
    .fcp-title {
        font-size: 22px;
    }
    
    .fcp-header-top {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    
    .fcp-title-section {
        justify-content: space-between;
    }
    
    .fcp-controls-section {
        width: 100%;
        justify-content: space-between;
    }
    
    .fcp-control-item {
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    
    .fcp-select {
        width: 100%;
        min-width: auto;
    }
    
    .fcp-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .fcp-product-card:hover {
        transform: none;
    }
    
    .fcp-product-info {
        padding: 12px;
    }
    
    .fcp-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .fcp-price-sale,
    .fcp-price-normal {
        font-size: 16px;
    }
    
    .fcp-action-btn {
        padding: 8px;
        font-size: 12px;
    }
    
    .fcp-wishlist-btn {
        flex: 0 0 36px;
    }
}

@media (max-width: 480px) {
    .fcp-title {
        font-size: 20px;
    }
    
    .fcp-control-label {
        font-size: 12px;
    }
    
    .fcp-select {
        font-size: 12px;
        padding: 7px 28px 7px 10px;
    }
    
    .fcp-products-grid {
        gap: 10px;
    }
    
    .fcp-product-name {
        font-size: 12px;
        min-height: 32px;
    }
    
    .fcp-price-sale,
    .fcp-price-normal {
        font-size: 15px;
    }
    
    .fcp-price-old {
        font-size: 12px;
    }
    
    .fcp-action-btn {
        padding: 7px;
        font-size: 11px;
    }
    
    .fcp-wishlist-btn {
        flex: 0 0 32px;
    }
    
    .fcp-empty-icon {
        font-size: 48px;
    }
    
    .fcp-empty-title {
        font-size: 18px;
    }
    
    .fcp-empty-text {
        font-size: 13px;
    }
}

/* Accessibility */
.fcp-action-btn:focus,
.fcp-select:focus,
.fcp-filter-button:focus {
    outline: 2px solid #10503D;
    outline-offset: 2px;
}

/* Print Styles */
@media print {
    .fcp-filter-button,
    .fcp-controls-section,
    .fcp-actions-box {
        display: none;
    }
    
    .fcp-products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<script>
(function() {
    // Filter toggle functionality
    var filterToggle = document.getElementById('fcp-filter-toggle');
    var columnLeft = document.querySelector('.fcp-layout-grid > #column-left');
    var layoutGrid = document.querySelector('.fcp-layout-grid');
    
    if (filterToggle && columnLeft) {
        filterToggle.addEventListener('click', function() {
            columnLeft.classList.toggle('fcp-sidebar-open');
            if (layoutGrid) {
                layoutGrid.classList.toggle('fcp-sidebar-open');
            }
        });
        
        // Close sidebar when clicking overlay
        if (layoutGrid) {
            layoutGrid.addEventListener('click', function(e) {
                if (e.target === layoutGrid && columnLeft.classList.contains('fcp-sidebar-open')) {
                    columnLeft.classList.remove('fcp-sidebar-open');
                    layoutGrid.classList.remove('fcp-sidebar-open');
                }
            });
        }
        
        // Close sidebar when clicking close button
        var closeBtn = document.querySelector('#column-left .lc-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                columnLeft.classList.remove('fcp-sidebar-open');
                if (layoutGrid) {
                    layoutGrid.classList.remove('fcp-sidebar-open');
                }
            });
        }
    }
})();
</script>

<?php echo $footer; ?>