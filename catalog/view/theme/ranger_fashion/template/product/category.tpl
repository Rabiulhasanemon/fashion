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

<div id="scp-wrapper" class="scp-container">
    <div class="scp-flex-layout">
        
        <!-- Filter Sidebar -->
        <div class="scp-filter-sidebar">
            <?php echo $column_left; ?>
        </div>
        
        <!-- Main Content -->
        <div class="scp-main-content grow">
            
            <!-- Top Bar -->
            <div class="scp-top-bar">
                <h1 class="scp-page-title"><?php echo $heading_title; ?></h1>
                <div class="scp-controls">
                    <div class="scp-control-group">
                        <label><?php echo $text_limit; ?></label>
                        <select class="scp-select" onchange="location = this.value;">
                            <?php foreach ($limits as $limits) { ?>
                            <option value="<?php echo $limits['href']; ?>" <?php echo ($limits['value'] == $limit) ? 'selected' : ''; ?>>
                                <?php echo $limits['text']; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="scp-control-group">
                        <label><?php echo $text_sort; ?></label>
                        <select class="scp-select" onchange="location = this.value;">
                            <?php foreach ($sorts as $sorts) { ?>
                            <option value="<?php echo $sorts['href']; ?>" <?php echo ($sorts['value'] == $sort . '-' . $order) ? 'selected' : ''; ?>>
                                <?php echo $sorts['text']; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="scp-products-grid">
                <?php foreach ($products as $product) { ?>
                <div class="scp-product-card">
                    <?php if ($product['special']) { ?>
                    <?php
                      $price = floatval(str_replace(['৳', ','], '', $product['price']));
                      $special = floatval(str_replace(['৳', ','], '', $product['special']));
                      $discountAmount = $price - $special;
                      $mark = ($discountAmount / $price) * 100;
                    ?>
                    <div class="scp-discount-badge"><?php echo round($mark); ?>% OFF</div>
                    <?php } ?>
                    
                    <a href="<?php echo $product['href']; ?>" class="scp-product-image-link">
                        <div class="scp-image-wrapper">
                            <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="scp-product-image" loading="lazy" />
                        </div>
                    </a>
                    
                    <div class="scp-product-info">
                        <h3 class="scp-product-name">
                            <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        
                        <div class="scp-rating">
                            <?php if ($product['rating']) { ?>
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <?php if ($i <= $product['rating']) { ?>
                                        <i class="fa fa-star scp-star-filled"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-star scp-star-empty"></i>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <i class="fa fa-star scp-star-empty"></i>
                                <i class="fa fa-star scp-star-empty"></i>
                                <i class="fa fa-star scp-star-empty"></i>
                                <i class="fa fa-star scp-star-empty"></i>
                                <i class="fa fa-star scp-star-empty"></i>
                            <?php } ?>
                        </div>
                        
                        <?php if ($product['special']) { ?>
                        <div class="scp-sale-label">SALE</div>
                        <?php } ?>
                        
                        <div class="scp-price-box">
                            <?php if ($product['special']) { ?>
                            <span class="scp-price-old"><?php echo $product['price']; ?></span>
                            <span class="scp-price-new"><?php echo $product['special']; ?></span>
                            <?php } else { ?>
                            <span class="scp-price-new"><?php echo $product['price']; ?></span>
                            <?php } ?>
                        </div>
                        
                        <?php if($product["disablePurchase"] && $product["restock_request_btn"]) { ?>
                        <button class="scp-add-cart-btn" onclick="restock_request.add('<?php echo $product['product_id']; ?>');">
                            <?php echo $product["restock_request_btn"]; ?>
                        </button>
                        <?php } elseif ($product["disablePurchase"]) { ?>
                        <button class="scp-add-cart-btn" disabled>
                            <?php echo $product["stock_status"]; ?>
                        </button>
                        <?php } else { ?>
                        <button class="scp-add-cart-btn" onclick="cart.add('<?php echo $product['product_id']; ?>');">
                            ADD TO CART
                        </button>
                        <?php } ?>
                    </div>
                </div>
                <?php }
                if (!$products) { ?>
                <div class="scp-empty-state">
                    <div class="scp-empty-content">
                        <i class="fa fa-box-open scp-empty-icon"></i>
                        <h3 class="scp-empty-title">Sorry! No Products Found</h3>
                        <p class="scp-empty-text">Please try searching for something else</p>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <!-- Footer -->
            <div class="scp-footer">
                <div class="scp-pagination">
                    <?php echo $pagination; ?>
                </div>
                <div class="scp-results">
                    <p><?php echo $results; ?></p>
                </div>
            </div>
            
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>

<style>
/* ============================================
   SHAJGOJ STYLE CATEGORY PAGE - SCP Design
   Matches reference image exactly
   ============================================ */

#scp-wrapper.scp-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}

.scp-flex-layout {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

@media (min-width: 768px) {
    .scp-flex-layout {
        flex-direction: row;
        gap: 20px;
    }
}

/* Filter Sidebar */
.scp-filter-sidebar {
    flex: none;
    width: 100%;
    max-width: 320px;
    height: auto;
    background: #fff;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    transition: all 0.5s ease-in-out;
    display: block !important;
}

@media (min-width: 768px) {
    .scp-filter-sidebar {
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        height: auto;
        display: block !important;
    }
}

.scp-filter-sidebar::-webkit-scrollbar {
    width: 4px;
}

.scp-filter-sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.scp-filter-sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.scp-filter-sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Filter Content Styling */
.scp-filter-sidebar column#column-left,
.scp-filter-sidebar #column-left {
    background: transparent;
    padding: 0;
    border: none;
    box-shadow: none;
    position: static;
    max-height: none;
    overflow: visible;
}

.scp-filter-sidebar .panel-heading {
    font-size: 18px;
    font-weight: 500;
    color: #333;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #e8e8e8;
}

.scp-filter-sidebar .panel .filters {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.scp-filter-sidebar .filter-group,
.scp-filter-sidebar .price-filter {
    margin-bottom: 0;
    padding: 0;
    background: transparent;
    border: none;
}

.scp-filter-sidebar .filter-group .label,
.scp-filter-sidebar .price-filter .label {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e8e8e8;
    cursor: pointer;
}

.scp-filter-sidebar .filter-group .items {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 12px;
}

.scp-filter-sidebar .filter-group .items label.filter {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    color: #666;
    cursor: pointer;
    padding: 5px 0;
}

.scp-filter-sidebar .filter-group .items label.filter:hover {
    color: #6c5ce7;
}

.scp-filter-sidebar .filter-group .items label.filter input {
    margin-right: 10px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #6c5ce7;
}

/* Main Content */
.scp-main-content.grow {
    flex: 1;
    min-width: 0;
}

/* Top Bar */
.scp-top-bar {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.scp-page-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.scp-controls {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.scp-control-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.scp-control-group label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
    white-space: nowrap;
}

.scp-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    min-width: 150px;
}

.scp-select:focus {
    outline: none;
    border-color: #6c5ce7;
}

/* Products Grid */
.scp-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Product Card */
.scp-product-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.scp-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    border-color: #d0d0d0;
}

/* Discount Badge */
.scp-discount-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #e84393;
    color: #fff;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(232, 67, 147, 0.3);
}

/* Image */
.scp-product-image-link {
    display: block;
    text-decoration: none;
}

.scp-image-wrapper {
    position: relative;
    padding-top: 0% !important;
    overflow: hidden;
    background: #fafafa;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.scp-product-image {
    position: relative;
    width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
    padding: 15px;
    transition: transform 0.3s ease;
}

.scp-product-card:hover .scp-product-image {
    transform: scale(1.05);
}

/* Product Info */
.scp-product-info {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.scp-product-name {
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

.scp-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.scp-product-name a:hover {
    color: #6c5ce7;
}

/* Rating */
.scp-rating {
    display: flex;
    gap: 2px;
    margin-bottom: 8px;
}

.scp-star-filled {
    color: #ffc107;
    font-size: 13px;
}

.scp-star-empty {
    color: #ddd;
    font-size: 13px;
}

/* Sale Label */
.scp-sale-label {
    display: inline-block;
    background: #ff6348;
    color: #fff;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 10px;
    width: fit-content;
    letter-spacing: 0.5px;
}

/* Price */
.scp-price-box {
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.scp-price-old {
    font-size: 13px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.scp-price-new {
    font-size: 18px;
    font-weight: 600;
    color: #e84393;
}

/* Add to Cart Button */
.scp-add-cart-btn {
    width: 100%;
    padding: 12px;
    background: #6c5ce7;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: auto;
}

.scp-add-cart-btn:hover:not(:disabled) {
    background: #5f4fd1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
}

.scp-add-cart-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Footer */
.scp-footer {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.scp-pagination {
    flex: 1;
}

.scp-results {
    flex-shrink: 0;
}

.scp-results p {
    color: #666;
    font-size: 13px;
    margin: 0;
}

/* Pagination */
.scp-pagination .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
    margin: 0;
}

.scp-pagination .pagination li {
    list-style: none;
}

.scp-pagination .pagination a,
.scp-pagination .pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 13px;
}

.scp-pagination .pagination a {
    color: #666;
    background: #f5f5f5;
}

.scp-pagination .pagination a:hover {
    background: #6c5ce7;
    color: #fff;
}

.scp-pagination .pagination .active span {
    background: #6c5ce7;
    color: #fff;
}

/* Empty State */
.scp-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.scp-empty-content {
    max-width: 400px;
    margin: 0 auto;
}

.scp-empty-icon {
    font-size: 56px;
    color: #ddd;
    margin-bottom: 15px;
}

.scp-empty-title {
    font-size: 22px;
    font-weight: 600;
    color: #666;
    margin: 0 0 8px 0;
}

.scp-empty-text {
    color: #999;
    font-size: 14px;
    margin: 0;
}

/* Hide module titles */
.scp-filter-sidebar .module-heading-wrapper,
.scp-filter-sidebar .section-head,
.scp-filter-sidebar h2,
.scp-filter-sidebar h3 {
    display: none !important;
}

/* Responsive */
@media (max-width: 1200px) {
    .scp-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
    }
}

@media (max-width: 992px) {
    .scp-filter-sidebar {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100vh;
        z-index: 1000;
        border-radius: 0;
        box-shadow: -2px 0 20px rgba(0, 0, 0, 0.15);
        transition: right 0.4s ease;
        max-height: 100vh;
        padding-top: 60px;
    }
    
    .scp-filter-sidebar.filter-toggle-show {
        right: 0;
    }
    
    .scp-filter-sidebar .lc-close {
        display: flex !important;
        position: fixed;
        top: 16px;
        right: 16px;
        z-index: 1001;
        background: #f5f7fa;
        color: #64748b;
        border-radius: 8px;
        padding: 8px;
        width: 32px;
        height: 32px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .scp-filter-sidebar .lc-close:hover {
        background: #6c5ce7;
        color: #fff;
    }
    
    /* Filter toggle button */
    .scp-top-bar::before {
        content: 'Filter';
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #6c5ce7;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
}

@media (max-width: 768px) {
    #scp-wrapper.scp-container {
        padding: 15px;
    }
    
    .scp-top-bar {
        flex-direction: column;
        align-items: stretch;
        padding: 15px;
    }
    
    .scp-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .scp-control-group {
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    
    .scp-select {
        width: 100%;
        min-width: auto;
    }
    
    .scp-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .scp-product-card:hover {
        transform: none;
    }
    
    .scp-product-info {
        padding: 12px;
    }
    
    .scp-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .scp-price-new {
        font-size: 16px;
    }
    
    .scp-add-cart-btn {
        padding: 10px;
        font-size: 12px;
    }
    
    .scp-footer {
        flex-direction: column;
        align-items: stretch;
    }
}

@media (max-width: 480px) {
    .scp-page-title {
        font-size: 18px;
    }
    
    .scp-products-grid {
        gap: 10px;
    }
    
    .scp-product-name {
        font-size: 12px;
        min-height: 32px;
    }
    
    .scp-price-new {
        font-size: 15px;
    }
    
    .scp-add-cart-btn {
        padding: 9px;
        font-size: 11px;
    }
}
</style>

<script>
(function() {
    // Filter toggle functionality for mobile
    var filterSidebar = document.querySelector('.scp-filter-sidebar');
    var filterToggle = document.querySelector('.scp-top-bar');
    
    if (filterSidebar && filterToggle) {
        // Create filter button
        var filterBtn = document.createElement('button');
        filterBtn.className = 'scp-filter-toggle-btn';
        filterBtn.innerHTML = '<i class="fa fa-filter"></i> Filter';
        filterBtn.style.cssText = 'display: none; padding: 8px 16px; background: #6c5ce7; color: #fff; border: none; border-radius: 4px; font-weight: 500; font-size: 13px; cursor: pointer; gap: 6px; align-items: center;';
        
        if (window.innerWidth <= 992) {
            filterBtn.style.display = 'flex';
            filterToggle.insertBefore(filterBtn, filterToggle.firstChild);
        }
        
        filterBtn.addEventListener('click', function() {
            filterSidebar.classList.toggle('filter-toggle-show');
        });
        
        // Close button
        var closeBtn = filterSidebar.querySelector('.lc-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                filterSidebar.classList.remove('filter-toggle-show');
            });
        }
        
        // Close on overlay click
        filterSidebar.addEventListener('click', function(e) {
            if (e.target === filterSidebar) {
                filterSidebar.classList.remove('filter-toggle-show');
            }
        });
        
        // Show/hide filter button on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 992) {
                filterBtn.style.display = 'flex';
            } else {
                filterBtn.style.display = 'none';
                filterSidebar.classList.remove('filter-toggle-show');
            }
        });
    }
})();
</script>

<?php echo $footer; ?>
