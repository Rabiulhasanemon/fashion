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

<div id="mfr-wrapper" class="mfr-container">
    <div class="mfr-flex-layout">
        
        <!-- Filter Sidebar -->
        <div class="mfr-filter-sidebar">
            <?php echo $column_left; ?>
        </div>
        
        <!-- Main Content -->
        <div class="mfr-main-content grow">
            
            <?php if (isset($debug_html) && !empty($debug_html)) { ?>
                <?php echo $debug_html; ?>
            <?php } ?>
            
            <!-- Brand Header -->
            <?php if (isset($heading_title)) { ?>
            <div class="mfr-brand-header">
                <div class="mfr-brand-info">
                    <?php if (isset($manufacturer_info) && $manufacturer_info && isset($manufacturer_info['image']) && $manufacturer_info['image']) { ?>
                    <div class="mfr-brand-logo">
                        <img src="<?php echo $manufacturer_info['image']; ?>" alt="<?php echo htmlspecialchars($heading_title); ?>" />
                    </div>
                    <?php } ?>
                    <div class="mfr-brand-details">
                        <h1 class="mfr-brand-title"><?php echo $heading_title; ?></h1>
                        <?php if (isset($description) && $description) { ?>
                        <div class="mfr-brand-description"><?php echo $description; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Top Bar -->
            <?php if (isset($products) && $products) { ?>
            <div class="mfr-top-bar">
                <div class="mfr-controls">
                    <?php if (isset($limits) && $limits) { ?>
                    <div class="mfr-control-group">
                        <label><?php echo isset($text_limit) ? $text_limit : 'Show'; ?></label>
                        <select class="mfr-select" onchange="location = this.value;">
                            <?php foreach ($limits as $limit_item) { ?>
                            <option value="<?php echo isset($limit_item['href']) ? $limit_item['href'] : '#'; ?>" <?php echo (isset($limit_item['value']) && isset($limit) && $limit_item['value'] == $limit) ? 'selected' : ''; ?>>
                                <?php echo isset($limit_item['text']) ? $limit_item['text'] : ''; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php } ?>
                    <?php if (isset($sorts) && $sorts) { ?>
                    <div class="mfr-control-group">
                        <label><?php echo isset($text_sort) ? $text_sort : 'Sort'; ?></label>
                        <select class="mfr-select" onchange="location = this.value;">
                            <?php foreach ($sorts as $sort_item) { ?>
                            <option value="<?php echo isset($sort_item['href']) ? $sort_item['href'] : '#'; ?>" <?php echo (isset($sort_item['value']) && isset($sort) && isset($order) && $sort_item['value'] == $sort . '-' . $order) ? 'selected' : ''; ?>>
                                <?php echo isset($sort_item['text']) ? $sort_item['text'] : ''; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Products Horizontal Scroll -->
            <?php if (isset($products) && $products) { ?>
            <div class="mfr-products-scroll-container">
                <div class="mfr-products-scroll-wrapper">
                    <div class="mfr-products-scroll">
                        <?php foreach ($products as $product) { ?>
                        <div class="mfr-product-card-scroll">
                            <div class="mfr-product-card-inner">
                                <?php if (isset($product['special']) && $product['special'] && isset($product['price']) && $product['price']) { ?>
                                <?php
                                  $price = floatval(str_replace(['৳', ',', ' '], '', $product['price']));
                                  $special = floatval(str_replace(['৳', ',', ' '], '', $product['special']));
                                  if ($price > 0) {
                                      $discountAmount = $price - $special;
                                      $mark = ($discountAmount / $price) * 100;
                                  } else {
                                      $mark = 0;
                                  }
                                ?>
                                <?php if ($mark > 0) { ?>
                                <div class="mfr-discount-badge-scroll"><?php echo round($mark); ?>% OFF</div>
                                <?php } ?>
                                <?php } ?>
                                
                                <a href="<?php echo isset($product['href']) ? $product['href'] : '#'; ?>" class="mfr-product-image-link-scroll">
                                    <div class="mfr-image-wrapper-scroll">
                                        <img src="<?php echo isset($product['thumb']) ? $product['thumb'] : ''; ?>" alt="<?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?>" class="mfr-product-image-scroll" loading="lazy" />
                                    </div>
                                </a>
                                
                                <!-- Delivery Badge -->
                                <div class="mfr-delivery-badge-scroll">
                                    <i class="fa fa-rocket"></i>
                                    <span>12-24 Hours</span>
                                </div>
                                
                                <div class="mfr-product-info-scroll">
                                    <h3 class="mfr-product-name-scroll">
                                        <a href="<?php echo isset($product['href']) ? $product['href'] : '#'; ?>"><?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?></a>
                                    </h3>
                                    
                                    <div class="mfr-rating-wrapper-scroll">
                                        <div class="mfr-rating-scroll">
                                            <?php if (isset($product['rating']) && $product['rating']) { ?>
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                    <?php if ($i <= $product['rating']) { ?>
                                                        <i class="fa fa-star mfr-star-filled-scroll"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-star mfr-star-empty-scroll"></i>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <i class="fa fa-star mfr-star-empty-scroll"></i>
                                                <i class="fa fa-star mfr-star-empty-scroll"></i>
                                                <i class="fa fa-star mfr-star-empty-scroll"></i>
                                                <i class="fa fa-star mfr-star-empty-scroll"></i>
                                                <i class="fa fa-star mfr-star-empty-scroll"></i>
                                            <?php } ?>
                                        </div>
                                        <?php if (isset($product['reviews'])) { ?>
                                        <span class="mfr-review-count-scroll">(<?php echo $product['reviews']; ?>)</span>
                                        <?php } else { ?>
                                        <span class="mfr-review-count-scroll">(0)</span>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="mfr-price-box-scroll">
                                        <?php if (isset($product['special']) && $product['special']) { ?>
                                        <?php if (isset($product['price'])) { ?>
                                        <span class="mfr-price-old-scroll"><?php echo $product['price']; ?></span>
                                        <?php } ?>
                                        <span class="mfr-price-new-scroll"><?php echo $product['special']; ?></span>
                                        <?php } else { ?>
                                        <?php if (isset($product['price'])) { ?>
                                        <span class="mfr-price-new-scroll"><?php echo $product['price']; ?></span>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    
                                    <?php if(isset($product["disablePurchase"]) && $product["disablePurchase"]) { ?>
                                    <button class="mfr-add-btn-scroll" disabled>
                                        <?php echo isset($product["stock_status"]) ? $product["stock_status"] : "Out of Stock"; ?>
                                    </button>
                                    <?php } else { ?>
                                    <button class="mfr-add-btn-scroll" onclick="cart.add('<?php echo isset($product['product_id']) ? $product['product_id'] : 0; ?>');">
                                        ADD
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Scroll Button -->
                <button class="mfr-scroll-btn mfr-scroll-btn-right" onclick="scrollProducts('right')">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            <?php } ?>
            
            <!-- Empty State -->
            <?php if (!isset($products) || !$products) { ?>
            <div class="mfr-empty-state">
                <div class="mfr-empty-content">
                    <i class="fa fa-box-open mfr-empty-icon"></i>
                    <h3 class="mfr-empty-title">Sorry! No Products Found</h3>
                    <p class="mfr-empty-text">This brand doesn't have any products available at the moment.</p>
                </div>
            </div>
            <?php } ?>
            
            <!-- Footer -->
            <?php if (isset($products) && $products) { ?>
            <div class="mfr-footer">
                <?php if (isset($pagination)) { ?>
                <div class="mfr-pagination">
                    <?php echo $pagination; ?>
                </div>
                <?php } ?>
                <?php if (isset($results)) { ?>
                <div class="mfr-results">
                    <p><?php echo $results; ?></p>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>

<style>
/* ============================================
   MANUFACTURER PAGE - Modern Responsive Design
   ============================================ */

#mfr-wrapper.mfr-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}

.mfr-flex-layout {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

@media (min-width: 768px) {
    .mfr-flex-layout {
        flex-direction: row;
        gap: 20px;
    }
}

/* Filter Sidebar */
.mfr-filter-sidebar {
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
    .mfr-filter-sidebar {
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        height: auto;
        display: block !important;
    }
}

.mfr-filter-sidebar::-webkit-scrollbar {
    width: 4px;
}

.mfr-filter-sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.mfr-filter-sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.mfr-filter-sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Main Content */
.mfr-main-content.grow {
    flex: 1;
    min-width: 0;
}

/* Brand Header */
.mfr-brand-header {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mfr-brand-info {
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.mfr-brand-logo {
    flex-shrink: 0;
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e8e8e8;
}

.mfr-brand-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    padding: 10px;
}

.mfr-brand-details {
    flex: 1;
    min-width: 200px;
}

.mfr-brand-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin: 0 0 10px 0;
    line-height: 1.2;
}

.mfr-brand-description {
    font-size: 15px;
    color: #666;
    line-height: 1.6;
    margin-top: 10px;
}

/* Top Bar */
.mfr-top-bar {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mfr-controls {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.mfr-control-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mfr-control-group label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
    white-space: nowrap;
}

.mfr-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    min-width: 150px;
}

.mfr-select:focus {
    outline: none;
    border-color: #6c5ce7;
}

/* Products Horizontal Scroll Container */
.mfr-products-scroll-container {
    position: relative;
    margin-bottom: 30px;
    background: #fef5f5;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.mfr-products-scroll-wrapper {
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    padding-bottom: 10px;
    margin-right: 50px;
}

.mfr-products-scroll-wrapper::-webkit-scrollbar {
    height: 6px;
}

.mfr-products-scroll-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.mfr-products-scroll-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.mfr-products-scroll-wrapper::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.mfr-products-scroll {
    display: flex;
    gap: 20px;
    padding: 10px 0;
}

/* Product Card for Scroll */
.mfr-product-card-scroll {
    flex: 0 0 auto;
    width: 280px;
    min-width: 280px;
}

.mfr-product-card-inner {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.mfr-product-card-inner:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    border-color: #d0d0d0;
}

/* Discount Badge - Blue */
.mfr-discount-badge-scroll {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #007bff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 12px;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
}

/* Image */
.mfr-product-image-link-scroll {
    display: block;
    text-decoration: none;
}

.mfr-image-wrapper-scroll {
    position: relative;
    overflow: hidden;
    background: #fafafa;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    padding-bottom: 50px;
}

.mfr-product-image-scroll {
    width: 100%;
    height: auto;
    max-height: 200px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.mfr-product-card-inner:hover .mfr-product-image-scroll {
    transform: scale(1.05);
}

/* Delivery Badge - Dark Grey with Yellow Icon */
.mfr-delivery-badge-scroll {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #333;
    color: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 500;
    z-index: 5;
}

.mfr-delivery-badge-scroll i {
    color: #ffc107;
    font-size: 14px;
}

/* Product Info */
.mfr-product-info-scroll {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.mfr-product-name-scroll {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 40px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: #000;
}

.mfr-product-name-scroll a {
    color: #000;
    text-decoration: none;
    transition: color 0.2s ease;
}

.mfr-product-name-scroll a:hover {
    color: #007bff;
}

/* Rating */
.mfr-rating-wrapper-scroll {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.mfr-rating-scroll {
    display: flex;
    gap: 2px;
}

.mfr-star-filled-scroll {
    color: #ffc107;
    font-size: 14px;
}

.mfr-star-empty-scroll {
    color: #ddd;
    font-size: 14px;
}

.mfr-review-count-scroll {
    font-size: 12px;
    color: #666;
}

/* Price */
.mfr-price-box-scroll {
    margin-bottom: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.mfr-price-old-scroll {
    font-size: 13px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.mfr-price-new-scroll {
    font-size: 20px;
    font-weight: 700;
    color: #000;
}

/* Add Button - Green */
.mfr-add-btn-scroll {
    margin-top: auto;
    padding: 10px 20px;
    background: #fff;
    border: 2px solid #28a745;
    color: #28a745;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    width: 100%;
}

.mfr-add-btn-scroll:hover:not(:disabled) {
    background: #28a745;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.mfr-add-btn-scroll:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    border-color: #ccc;
    color: #999;
}

/* Scroll Button */
.mfr-scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: #fff;
    border: 2px solid #e8e8e8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.mfr-scroll-btn:hover {
    background: #f8f8f8;
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

.mfr-scroll-btn i {
    color: #333;
    font-size: 16px;
}

.mfr-scroll-btn-right {
    right: 10px;
}

/* Products Grid (Fallback) */
.mfr-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

/* Product Card */
.mfr-product-card {
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

.mfr-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    border-color: #d0d0d0;
}

/* Discount Badge */
.mfr-discount-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #dc3545;
    color: #fff;
    padding: 6px 10px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 12px;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

/* Image */
.mfr-product-image-link {
    display: block;
    text-decoration: none;
}

.mfr-image-wrapper {
    position: relative;
    padding-top: 0% !important;
    overflow: hidden;
    background: #fafafa;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 35px;
}

.mfr-product-image {
    position: relative;
    width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
    padding: 15px;
    transition: transform 0.3s ease;
}

.mfr-product-card:hover .mfr-product-image {
    transform: scale(1.05);
}

/* Delivery Badge */
.mfr-delivery-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #000;
    color: #fff;
    padding: 6px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 500;
    z-index: 5;
}

.mfr-delivery-badge i {
    color: #ffc107;
    font-size: 12px;
}

/* Product Info */
.mfr-product-info {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.mfr-manufacturer-badge {
    margin-bottom: 8px;
}

.mfr-manufacturer-badge img {
    max-height: 20px;
    width: auto;
    object-fit: contain;
}

.mfr-product-name {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 38px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: #000;
}

.mfr-product-name a {
    color: #000;
    text-decoration: none;
    transition: color 0.2s ease;
}

.mfr-product-name a:hover {
    color: #6c5ce7;
}

/* Rating */
.mfr-rating-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.mfr-rating {
    display: flex;
    gap: 2px;
}

.mfr-star-filled {
    color: #ffc107;
    font-size: 13px;
}

.mfr-star-empty {
    color: #ddd;
    font-size: 13px;
}

.mfr-review-count {
    font-size: 12px;
    color: #999;
}

/* Sale Label */
.mfr-sale-label {
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
.mfr-price-box {
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.mfr-price-old {
    font-size: 13px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.mfr-price-new {
    font-size: 18px;
    font-weight: 700;
    color: #000;
}

/* Add Button - Green */
.mfr-add-btn {
    width: 100%;
    padding: 12px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-top: auto;
}

.mfr-add-btn:hover:not(:disabled) {
    background: #218838;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.mfr-add-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: #6c757d;
}

/* Footer */
.mfr-footer {
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

.mfr-pagination {
    flex: 1;
}

.mfr-results {
    flex-shrink: 0;
}

.mfr-results p {
    color: #666;
    font-size: 13px;
    margin: 0;
}

/* Pagination */
.mfr-pagination .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
    margin: 0;
}

.mfr-pagination .pagination li {
    list-style: none;
}

.mfr-pagination .pagination a,
.mfr-pagination .pagination span {
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

.mfr-pagination .pagination a {
    color: #666;
    background: #f5f5f5;
}

.mfr-pagination .pagination a:hover {
    background: #6c5ce7;
    color: #fff;
}

.mfr-pagination .pagination .active span {
    background: #6c5ce7;
    color: #fff;
}

/* Empty State */
.mfr-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mfr-empty-content {
    max-width: 400px;
    margin: 0 auto;
}

.mfr-empty-icon {
    font-size: 56px;
    color: #ddd;
    margin-bottom: 15px;
}

.mfr-empty-title {
    font-size: 22px;
    font-weight: 600;
    color: #666;
    margin: 0 0 8px 0;
}

.mfr-empty-text {
    color: #999;
    font-size: 14px;
    margin: 0;
}

/* Hide module titles */
.mfr-filter-sidebar .module-heading-wrapper,
.mfr-filter-sidebar .section-head,
.mfr-filter-sidebar h2,
.mfr-filter-sidebar h3 {
    display: none !important;
}

/* Responsive */
@media (max-width: 1200px) {
    .mfr-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
    }
    
    .mfr-brand-title {
        font-size: 28px;
    }
}

@media (max-width: 992px) {
    .mfr-filter-sidebar {
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
    
    .mfr-filter-sidebar.filter-toggle-show {
        right: 0;
    }
    
    .mfr-filter-sidebar .lc-close {
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
    
    .mfr-filter-sidebar .lc-close:hover {
        background: #6c5ce7;
        color: #fff;
    }
    
    /* Filter toggle button */
    .mfr-top-bar::before {
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
    #mfr-wrapper.mfr-container {
        padding: 15px;
    }
    
    .mfr-brand-header {
        padding: 20px;
    }
    
    .mfr-brand-info {
        flex-direction: column;
        text-align: center;
    }
    
    .mfr-brand-logo {
        width: 100px;
        height: 100px;
    }
    
    .mfr-brand-title {
        font-size: 24px;
    }
    
    .mfr-top-bar {
        flex-direction: column;
        align-items: stretch;
        padding: 15px;
    }
    
    .mfr-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .mfr-control-group {
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    
    .mfr-select {
        width: 100%;
        min-width: auto;
    }
    
    .mfr-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .mfr-product-card:hover {
        transform: none;
    }
    
    .mfr-product-info {
        padding: 12px;
    }
    
    .mfr-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .mfr-price-new {
        font-size: 16px;
    }
    
    .mfr-add-cart-btn {
        padding: 10px;
        font-size: 12px;
    }
    
    .mfr-footer {
        flex-direction: column;
        align-items: stretch;
    }
}

@media (max-width: 480px) {
    .mfr-brand-title {
        font-size: 20px;
    }
    
    .mfr-products-grid {
        gap: 10px;
    }
    
    .mfr-product-name {
        font-size: 12px;
        min-height: 32px;
    }
    
    .mfr-price-new {
        font-size: 15px;
    }
    
    .mfr-add-cart-btn {
        padding: 9px;
        font-size: 11px;
    }
}
</style>

<script>
(function() {
    // Filter toggle functionality for mobile
    var filterSidebar = document.querySelector('.mfr-filter-sidebar');
    var filterToggle = document.querySelector('.mfr-top-bar');
    
    if (filterSidebar && filterToggle) {
        // Create filter button
        var filterBtn = document.createElement('button');
        filterBtn.className = 'mfr-filter-toggle-btn';
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

// Product Scroll Function
function scrollProducts(direction) {
    const scrollContainer = document.querySelector('.mfr-products-scroll-wrapper');
    if (scrollContainer) {
        const scrollAmount = 300;
        if (direction === 'right') {
            scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        } else {
            scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    }
}

// Auto-hide scroll button on mobile
(function() {
    const scrollBtn = document.querySelector('.mfr-scroll-btn-right');
    const scrollWrapper = document.querySelector('.mfr-products-scroll-wrapper');
    
    if (scrollBtn && scrollWrapper) {
        function checkScroll() {
            const isScrollable = scrollWrapper.scrollWidth > scrollWrapper.clientWidth;
            if (window.innerWidth <= 768) {
                scrollBtn.style.display = isScrollable ? 'flex' : 'none';
            } else {
                scrollBtn.style.display = isScrollable ? 'flex' : 'none';
            }
        }
        
        checkScroll();
        window.addEventListener('resize', checkScroll);
        scrollWrapper.addEventListener('scroll', checkScroll);
    }
})();
</script>

<style>
/* Responsive Styles for Horizontal Scroll */
@media (max-width: 768px) {
    .mfr-products-scroll-container {
        padding: 15px;
        margin-right: 0;
    }
    
    .mfr-products-scroll-wrapper {
        margin-right: 0;
    }
    
    .mfr-product-card-scroll {
        width: 240px;
        min-width: 240px;
    }
    
    .mfr-scroll-btn {
        display: none;
    }
    
    .mfr-image-wrapper-scroll {
        min-height: 180px;
        padding: 15px;
        padding-bottom: 45px;
    }
    
    .mfr-product-image-scroll {
        max-height: 160px;
    }
}

@media (max-width: 480px) {
    .mfr-product-card-scroll {
        width: 200px;
        min-width: 200px;
    }
    
    .mfr-image-wrapper-scroll {
        min-height: 160px;
        padding: 12px;
        padding-bottom: 40px;
    }
    
    .mfr-product-image-scroll {
        max-height: 140px;
    }
    
    .mfr-product-name-scroll {
        font-size: 13px;
        min-height: 36px;
    }
    
    .mfr-price-new-scroll {
        font-size: 18px;
    }
}
</style>

<?php echo $footer; ?>
