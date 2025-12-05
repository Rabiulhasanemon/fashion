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

<section id="ncp-wrapper" class="ncp-section">
    <div class="ncp-container">
        
        <?php if (isset($category_modules) && !empty($category_modules)) { ?>
        <div id="ncp-mods" class="ncp-mods-wrap">
            <?php foreach ($category_modules as $module) { ?>
            <div class="ncp-mod-item">
                <?php if (!empty($module['description'])) { ?>
                <div class="ncp-mod-desc">
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
                <div class="ncp-mod-out">
                    <?php echo $module['output']; ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <div class="ncp-grid-layout">
            <?php echo $column_left; ?>
            
            <div id="ncp-content" class="ncp-content-area">
                <div id="ncp-topbar" class="ncp-topbar">
                    <div class="ncp-topbar-left">
                        <h1 class="ncp-page-title"><?php echo $heading_title; ?></h1>
                        <button id="ncp-filter-btn" class="ncp-filter-toggle">
                            <i class="fa fa-filter"></i>
                            <span>Filter</span>
                        </button>
                    </div>
                    <div class="ncp-topbar-right">
                        <div class="ncp-ctrl-group">
                            <label class="ncp-ctrl-label"><?php echo $text_limit; ?></label>
                            <select class="ncp-select-ctrl" onchange="location = this.value;">
                                <?php foreach ($limits as $limits) { ?>
                                <option value="<?php echo $limits['href']; ?>" <?php echo ($limits['value'] == $limit) ? 'selected' : ''; ?>>
                                    <?php echo $limits['text']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="ncp-ctrl-group">
                            <label class="ncp-ctrl-label"><?php echo $text_sort; ?></label>
                            <select class="ncp-select-ctrl" onchange="location = this.value;">
                                <?php foreach ($sorts as $sorts) { ?>
                                <option value="<?php echo $sorts['href']; ?>" <?php echo ($sorts['value'] == $sort . '-' . $order) ? 'selected' : ''; ?>>
                                    <?php echo $sorts['text']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div id="ncp-grid" class="ncp-product-grid">
                    <?php foreach ($products as $product) { ?>
                    <div class="ncp-card" data-id="<?php echo $product['product_id']; ?>">
                        <?php if ($product['special']) { ?>
                        <?php
                          $price = floatval(str_replace(['৳', ','], '', $product['price']));
                          $special = floatval(str_replace(['৳', ','], '', $product['special']));
                          $discountAmount = $price - $special;
                          $mark = ($discountAmount / $price) * 100;
                        ?>
                        <div class="ncp-tag-discount"><?php echo round($mark); ?>% OFF</div>
                        <?php } ?>

                        <a href="<?php echo $product['href']; ?>" class="ncp-img-link">
                            <div class="ncp-img-wrap">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="ncp-img" loading="lazy" />
                            </div>
                        </a>

                        <div class="ncp-body">
                            <h3 class="ncp-name">
                                <a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h3>
                            
                            <div class="ncp-rating">
                                <?php if ($product['rating']) { ?>
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <?php if ($i <= $product['rating']) { ?>
                                            <i class="fa fa-star ncp-star-filled"></i>
                                        <?php } else { ?>
                                            <i class="fa fa-star ncp-star-empty"></i>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <i class="fa fa-star ncp-star-empty"></i>
                                    <i class="fa fa-star ncp-star-empty"></i>
                                    <i class="fa fa-star ncp-star-empty"></i>
                                    <i class="fa fa-star ncp-star-empty"></i>
                                    <i class="fa fa-star ncp-star-empty"></i>
                                <?php } ?>
                            </div>
                            
                            <?php if ($product['special']) { ?>
                            <div class="ncp-label-sale">SALE</div>
                            <?php } ?>
                            
                            <div class="ncp-price-wrap">
                                <?php if ($product['special']) { ?>
                                <div class="ncp-price-row">
                                    <span class="ncp-price-old"><?php echo $product['price']; ?></span>
                                    <span class="ncp-price-now"><?php echo $product['special']; ?></span>
                                </div>
                                <?php } else { ?>
                                <span class="ncp-price-now"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            
                            <?php if($product["disablePurchase"] && $product["restock_request_btn"]) { ?>
                            <button class="ncp-btn-cart" onclick="restock_request.add('<?php echo $product['product_id']; ?>');">
                                <?php echo $product["restock_request_btn"]; ?>
                            </button>
                            <?php } elseif ($product["disablePurchase"]) { ?>
                            <button class="ncp-btn-cart" disabled onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                <?php echo $product["stock_status"]; ?>
                            </button>
                            <?php } else { ?>
                            <button class="ncp-btn-cart" onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                ADD TO CART
                            </button>
                            <?php } ?>
                        </div>
                    </div>
                    <?php }
                    if (!$products) { ?>
                    <div class="ncp-empty-wrap">
                        <div class="ncp-empty-box">
                            <i class="fa fa-box-open ncp-empty-icon"></i>
                            <h3 class="ncp-empty-title">Sorry! No Products Found</h3>
                            <p class="ncp-empty-text">Please try searching for something else</p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <div id="ncp-footer" class="ncp-footer-bar">
                    <div class="ncp-footer-content">
                        <div class="ncp-pagination-wrap">
                            <?php echo $pagination; ?>
                        </div>
                        <div class="ncp-results-wrap">
                            <p class="ncp-results-text"><?php echo $results; ?></p>
                        </div>
                    </div>
                </div>
                <?php echo $content_bottom; ?>
            </div>
            
            <?php echo $column_right; ?>
        </div>
    </div>
</section>

<style>
/* ============================================
   NEW CATEGORY PAGE - NCP DESIGN
   Matches the provided image exactly
   ============================================ */

#ncp-wrapper.ncp-section {
    background: #f5f5f5;
    padding: 20px 0 50px 0;
}

.ncp-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.ncp-mods-wrap {
    margin-bottom: 20px;
}

.ncp-mod-item {
    margin-bottom: 15px;
}

/* Main Layout Grid */
.ncp-grid-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 20px;
    align-items: start;
}

/* Sidebar Filter - Target column-left element */
.ncp-grid-layout > column#column-left,
.ncp-grid-layout > #column-left {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    flex: none !important;
}

/* Override Bootstrap column classes */
.ncp-grid-layout > column#column-left.col-sm-12,
.ncp-grid-layout > column#column-left.col-sm-3,
.ncp-grid-layout > #column-left.col-sm-12,
.ncp-grid-layout > #column-left.col-sm-3 {
    width: 100% !important;
    max-width: 100% !important;
    flex: none !important;
    padding: 20px !important;
}

.ncp-grid-layout > column#column-left::-webkit-scrollbar,
.ncp-grid-layout > #column-left::-webkit-scrollbar {
    width: 4px;
}

.ncp-grid-layout > column#column-left::-webkit-scrollbar-track,
.ncp-grid-layout > #column-left::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.ncp-grid-layout > column#column-left::-webkit-scrollbar-thumb,
.ncp-grid-layout > #column-left::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

/* Hide empty sidebar */
.ncp-grid-layout > column#column-left:empty,
.ncp-grid-layout > #column-left:empty {
    display: none !important;
}

/* If sidebar is empty, use full width */
.ncp-grid-layout:has(> column#column-left:empty),
.ncp-grid-layout:has(> #column-left:empty) {
    grid-template-columns: 1fr;
}

/* Top Bar */
.ncp-topbar {
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ncp-topbar-left {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.ncp-page-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0;
    font-family: 'Jost', sans-serif;
}

.ncp-filter-toggle {
    display: none;
    padding: 8px 16px;
    background: #6c5ce7;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    gap: 6px;
    align-items: center;
    transition: all 0.2s ease;
}

.ncp-filter-toggle:hover {
    background: #5f4fd1;
}

.ncp-topbar-right {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.ncp-ctrl-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.ncp-ctrl-label {
    font-size: 13px;
    color: #666;
    font-weight: 500;
    white-space: nowrap;
}

.ncp-select-ctrl {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    font-size: 13px;
    color: #333;
    cursor: pointer;
    min-width: 150px;
    font-family: 'Jost', sans-serif;
}

.ncp-select-ctrl:focus {
    outline: none;
    border-color: #6c5ce7;
}

/* Product Grid */
.ncp-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Product Card */
.ncp-card {
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

.ncp-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    border-color: #d0d0d0;
}

/* Discount Badge */
.ncp-tag-discount {
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
.ncp-img-link {
    display: block;
    text-decoration: none;
}

.ncp-img-wrap {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #fafafa;
}

.ncp-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 15px;
    transition: transform 0.3s ease;
}

.ncp-card:hover .ncp-img {
    transform: scale(1.05);
}

/* Card Body */
.ncp-body {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.ncp-name {
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

.ncp-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.ncp-name a:hover {
    color: #6c5ce7;
}

/* Star Rating */
.ncp-rating {
    display: flex;
    gap: 2px;
    margin-bottom: 8px;
}

.ncp-star-filled {
    color: #ffc107;
    font-size: 13px;
}

.ncp-star-empty {
    color: #ddd;
    font-size: 13px;
}

/* Sale Label */
.ncp-label-sale {
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
.ncp-price-wrap {
    margin-bottom: 12px;
}

.ncp-price-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.ncp-price-old {
    font-size: 13px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.ncp-price-now {
    font-size: 18px;
    font-weight: 600;
    color: #e84393;
}

/* Add to Cart Button */
.ncp-btn-cart {
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
    font-family: 'Jost', sans-serif;
}

.ncp-btn-cart:hover:not(:disabled) {
    background: #5f4fd1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
}

.ncp-btn-cart:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Footer */
.ncp-footer-bar {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ncp-footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.ncp-pagination-wrap {
    flex: 1;
}

.ncp-results-wrap {
    flex-shrink: 0;
}

.ncp-results-text {
    color: #666;
    font-size: 13px;
    margin: 0;
}

/* Empty State */
.ncp-empty-wrap {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.ncp-empty-box {
    max-width: 400px;
    margin: 0 auto;
}

.ncp-empty-icon {
    font-size: 56px;
    color: #ddd;
    margin-bottom: 15px;
}

.ncp-empty-title {
    font-size: 22px;
    font-weight: 600;
    color: #666;
    margin: 0 0 8px 0;
}

.ncp-empty-text {
    color: #999;
    font-size: 14px;
    margin: 0;
}

/* Pagination */
.ncp-pagination-wrap .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.ncp-pagination-wrap .pagination li {
    list-style: none;
}

.ncp-pagination-wrap .pagination a,
.ncp-pagination-wrap .pagination span {
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

.ncp-pagination-wrap .pagination a {
    color: #666;
    background: #f5f5f5;
}

.ncp-pagination-wrap .pagination a:hover {
    background: #6c5ce7;
    color: #fff;
}

.ncp-pagination-wrap .pagination .active span {
    background: #6c5ce7;
    color: #fff;
}

/* Responsive */
@media (max-width: 1200px) {
    .ncp-container {
        max-width: 100%;
    }
    
    .ncp-grid-layout {
        grid-template-columns: 260px 1fr;
        gap: 15px;
    }
    
    .ncp-product-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
    }
}

@media (max-width: 992px) {
    .ncp-grid-layout {
        grid-template-columns: 1fr;
    }
    
    .ncp-filter-toggle {
        display: flex;
    }
    
    .ncp-page-title {
        font-size: 22px;
    }
    
    .ncp-product-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
    
    /* Mobile Sidebar */
    .ncp-grid-layout > column#column-left,
    .ncp-grid-layout > #column-left {
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
    
    .ncp-grid-layout > column#column-left.ncp-sidebar-show,
    .ncp-grid-layout > #column-left.ncp-sidebar-show {
        right: 0;
    }
    
    /* Overlay */
    .ncp-grid-layout::before {
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
    
    .ncp-grid-layout.ncp-sidebar-show::before {
        opacity: 1;
        visibility: visible;
    }
}

@media (max-width: 768px) {
    #ncp-wrapper.ncp-section {
        padding: 15px 0 40px 0;
    }
    
    .ncp-container {
        padding: 0 15px;
    }
    
    .ncp-topbar {
        flex-direction: column;
        align-items: stretch;
        padding: 12px 15px;
    }
    
    .ncp-topbar-left {
        justify-content: space-between;
    }
    
    .ncp-topbar-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .ncp-ctrl-group {
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    
    .ncp-select-ctrl {
        width: 100%;
        min-width: auto;
    }
    
    .ncp-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .ncp-card:hover {
        transform: none;
    }
    
    .ncp-body {
        padding: 12px;
    }
    
    .ncp-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .ncp-price-now {
        font-size: 16px;
    }
    
    .ncp-btn-cart {
        padding: 10px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .ncp-page-title {
        font-size: 18px;
    }
    
    .ncp-product-grid {
        gap: 10px;
    }
    
    .ncp-name {
        font-size: 12px;
        min-height: 32px;
    }
    
    .ncp-price-now {
        font-size: 15px;
    }
    
    .ncp-btn-cart {
        padding: 9px;
        font-size: 11px;
    }
}

/* Hide old conflicting styles */
.ncp-mod-out .module-heading-wrapper,
.ncp-mod-out .section-head,
.ncp-mod-out h2,
.ncp-mod-out h3 {
    display: none !important;
}
</style>

<script>
(function() {
    var filterBtn = document.getElementById('ncp-filter-btn');
    var sidebar = document.querySelector('.ncp-grid-layout > column#column-left') || document.querySelector('.ncp-grid-layout > #column-left');
    var layout = document.querySelector('.ncp-grid-layout');
    
    if (filterBtn && sidebar) {
        filterBtn.addEventListener('click', function() {
            sidebar.classList.toggle('ncp-sidebar-show');
            if (layout) {
                layout.classList.toggle('ncp-sidebar-show');
            }
        });
        
        // Close on overlay click
        if (layout) {
            layout.addEventListener('click', function(e) {
                if (e.target === layout && sidebar.classList.contains('ncp-sidebar-show')) {
                    sidebar.classList.remove('ncp-sidebar-show');
                    layout.classList.remove('ncp-sidebar-show');
                }
            });
        }
        
        // Close button
        var closeBtn = document.querySelector('column#column-left .lc-close') || document.querySelector('#column-left .lc-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('ncp-sidebar-show');
                if (layout) {
                    layout.classList.remove('ncp-sidebar-show');
                }
            });
        }
    }
})();
</script>

<?php echo $footer; ?>
