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

<div id="premium-manufacturer-wrapper" class="premium-mfr-container">
    <div class="premium-mfr-flex-layout">
        
        <!-- Filter Sidebar -->
        <div id="premium-mfr-sidebar" class="premium-mfr-filter-sidebar">
            <?php echo $column_left; ?>
        </div>
        
        <!-- Main Content -->
        <div id="premium-mfr-main" class="premium-mfr-main-content premium-mfr-grow">
            
            <!-- Premium Brand Header -->
            <?php if (isset($heading_title)) { ?>
            <div id="premium-mfr-brand-header" class="premium-mfr-brand-header">
                <div class="premium-mfr-brand-info-wrapper">
                    <?php if (isset($manufacturer_info) && $manufacturer_info && isset($manufacturer_info['image']) && $manufacturer_info['image']) { ?>
                    <div id="premium-mfr-logo" class="premium-mfr-brand-logo">
                        <img src="<?php echo $manufacturer_info['image']; ?>" alt="<?php echo htmlspecialchars($heading_title); ?>" />
                    </div>
                    <?php } ?>
                    <div id="premium-mfr-details" class="premium-mfr-brand-details">
                        <h1 id="premium-mfr-title" class="premium-mfr-brand-title"><?php echo $heading_title; ?></h1>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Premium Top Bar -->
            <?php if (isset($products) && $products) { ?>
            <div id="premium-mfr-top-bar" class="premium-mfr-top-bar">
                <div class="premium-mfr-controls-wrapper">
                    <?php if (isset($limits) && $limits) { ?>
                    <div class="premium-mfr-control-group">
                        <label class="premium-mfr-control-label"><?php echo isset($text_limit) ? $text_limit : 'Show'; ?></label>
                        <select id="premium-mfr-limit-select" class="premium-mfr-select" onchange="location = this.value;">
                            <?php foreach ($limits as $limit_item) { ?>
                            <option value="<?php echo isset($limit_item['href']) ? $limit_item['href'] : '#'; ?>" <?php echo (isset($limit_item['value']) && isset($limit) && $limit_item['value'] == $limit) ? 'selected' : ''; ?>>
                                <?php echo isset($limit_item['text']) ? $limit_item['text'] : ''; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php } ?>
                    <?php if (isset($sorts) && $sorts) { ?>
                    <div class="premium-mfr-control-group">
                        <label class="premium-mfr-control-label"><?php echo isset($text_sort) ? $text_sort : 'Sort'; ?></label>
                        <select id="premium-mfr-sort-select" class="premium-mfr-select" onchange="location = this.value;">
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
            <?php } ?>
            
            <!-- Premium Products Grid -->
            <?php if (isset($products) && $products) { ?>
            <div id="premium-mfr-products-section" class="premium-mfr-products-section">
                <div class="premium-mfr-products-grid">
                    <?php foreach ($products as $product) { ?>
                    <div class="premium-mfr-product-card">
                        <div class="premium-mfr-product-card-inner">
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
                                <div class="premium-mfr-discount-badge"><?php echo round($mark); ?>% OFF</div>
                                <?php } ?>
                                <?php } ?>
                                
                                <a href="<?php echo isset($product['href']) ? $product['href'] : '#'; ?>" class="premium-mfr-product-image-link">
                                    <div class="premium-mfr-image-wrapper">
                                        <img src="<?php echo isset($product['thumb']) ? $product['thumb'] : ''; ?>" alt="<?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?>" class="premium-mfr-product-image" loading="lazy" />
                                    </div>
                                </a>
                                
                                <!-- Delivery Badge -->
                                <div class="premium-mfr-delivery-badge">
                                    <i class="fa fa-rocket"></i>
                                    <span>12-24 Hours</span>
                                </div>
                                
                                <div class="premium-mfr-product-info">
                                    <h3 class="premium-mfr-product-name">
                                        <a href="<?php echo isset($product['href']) ? $product['href'] : '#'; ?>"><?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?></a>
                                    </h3>
                                    
                                    <div class="premium-mfr-rating-wrapper">
                                        <div class="premium-mfr-rating">
                                            <?php if (isset($product['rating']) && $product['rating']) { ?>
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                    <?php if ($i <= $product['rating']) { ?>
                                                        <i class="fa fa-star premium-mfr-star-filled"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-star premium-mfr-star-empty"></i>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <i class="fa fa-star premium-mfr-star-empty"></i>
                                                <i class="fa fa-star premium-mfr-star-empty"></i>
                                                <i class="fa fa-star premium-mfr-star-empty"></i>
                                                <i class="fa fa-star premium-mfr-star-empty"></i>
                                                <i class="fa fa-star premium-mfr-star-empty"></i>
                                            <?php } ?>
                                        </div>
                                        <?php if (isset($product['reviews'])) { ?>
                                        <span class="premium-mfr-review-count">(<?php echo $product['reviews']; ?>)</span>
                                        <?php } else { ?>
                                        <span class="premium-mfr-review-count">(0)</span>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="premium-mfr-price-box">
                                        <?php if (isset($product['special']) && $product['special']) { ?>
                                        <?php if (isset($product['price'])) { ?>
                                        <span class="premium-mfr-price-old"><?php echo $product['price']; ?></span>
                                        <?php } ?>
                                        <span class="premium-mfr-price-new"><?php echo $product['special']; ?></span>
                                        <?php } else { ?>
                                        <?php if (isset($product['price'])) { ?>
                                        <span class="premium-mfr-price-new"><?php echo $product['price']; ?></span>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    
                                    <?php if(isset($product["disablePurchase"]) && $product["disablePurchase"]) { ?>
                                    <button class="premium-mfr-add-btn" disabled>
                                        <?php echo isset($product["stock_status"]) ? $product["stock_status"] : "Out of Stock"; ?>
                                    </button>
                                    <?php } else { ?>
                                    <button class="premium-mfr-add-btn" onclick="cart.add('<?php echo isset($product['product_id']) ? $product['product_id'] : 0; ?>');">
                                        ADD TO CART
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            
            <!-- Premium Empty State -->
            <?php if (!isset($products) || !$products) { ?>
            <div id="premium-mfr-empty-state" class="premium-mfr-empty-state">
                <div class="premium-mfr-empty-content">
                    <i class="fa fa-box-open premium-mfr-empty-icon"></i>
                    <h3 class="premium-mfr-empty-title">Sorry! No Products Found</h3>
                    <p class="premium-mfr-empty-text">This brand doesn't have any products available at the moment.</p>
                </div>
            </div>
            <?php } ?>
            
            <!-- Footer -->
            <?php if (isset($products) && $products) { ?>
            <div id="premium-mfr-footer" class="premium-mfr-footer">
                <?php if (isset($pagination)) { ?>
                <div class="premium-mfr-pagination">
                    <?php echo $pagination; ?>
                </div>
                <?php } ?>
                <?php if (isset($results)) { ?>
                <div class="premium-mfr-results">
                    <p><?php echo $results; ?></p>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            
            <!-- Premium Description Section -->
            <?php if (isset($description) && $description && trim(strip_tags($description)) != '') { ?>
            <div id="premium-mfr-description-section" class="premium-mfr-description-section">
                <div class="premium-mfr-description-wrapper">
                    <h2 class="premium-mfr-description-title">About <?php echo htmlspecialchars($heading_title); ?></h2>
                    <div id="premium-mfr-description-content" class="premium-mfr-description-content">
                        <?php echo $description; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>

<style>
/* ============================================
   PREMIUM MANUFACTURER PAGE - Premium Design
   Brand Colors: #10503D (Dark Green) & #A68A6A (Brown/Tan)
   ============================================ */

#premium-manufacturer-wrapper.premium-mfr-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
    background: #f8f9fa;
}

.premium-mfr-flex-layout {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

@media (min-width: 768px) {
    .premium-mfr-flex-layout {
        flex-direction: row;
        gap: 25px;
    }
}

/* Premium Filter Sidebar */
#premium-mfr-sidebar.premium-mfr-filter-sidebar {
    flex: none;
    width: 100%;
    max-width: 320px;
    height: auto;
    background: #ffffff;
    border-radius: 12px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(16, 80, 61, 0.08);
    overflow-y: auto;
    transition: all 0.3s ease;
    display: block !important;
    border: 1px solid rgba(16, 80, 61, 0.1);
}

@media (min-width: 768px) {
    #premium-mfr-sidebar.premium-mfr-filter-sidebar {
        position: sticky;
        top: 25px;
        max-height: calc(100vh - 50px);
        height: auto;
        display: block !important;
    }
}

#premium-mfr-sidebar.premium-mfr-filter-sidebar::-webkit-scrollbar {
    width: 6px;
}

#premium-mfr-sidebar.premium-mfr-filter-sidebar::-webkit-scrollbar-track {
    background: #f8f8f8;
    border-radius: 10px;
}

#premium-mfr-sidebar.premium-mfr-filter-sidebar::-webkit-scrollbar-thumb {
    background: #A68A6A;
    border-radius: 10px;
}

#premium-mfr-sidebar.premium-mfr-filter-sidebar::-webkit-scrollbar-thumb:hover {
    background: #10503D;
}

/* Premium Main Content */
#premium-mfr-main.premium-mfr-main-content.premium-mfr-grow {
    flex: 1;
    min-width: 0;
}

/* Premium Brand Header */
#premium-mfr-brand-header.premium-mfr-brand-header {
    background: #ffffff;
    padding: 40px 35px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 8px 30px rgba(16, 80, 61, 0.12);
    border: 1px solid rgba(16, 80, 61, 0.1);
}

.premium-mfr-brand-info-wrapper {
    display: flex;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
}

#premium-mfr-logo.premium-mfr-brand-logo {
    flex-shrink: 0;
    width: 140px;
    height: 140px;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #10503D;
    box-shadow: 0 4px 15px rgba(16, 80, 61, 0.15);
    transition: all 0.3s ease;
}

#premium-mfr-logo.premium-mfr-brand-logo:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(16, 80, 61, 0.25);
}

#premium-mfr-logo.premium-mfr-brand-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    padding: 15px;
}

#premium-mfr-details.premium-mfr-brand-details {
    flex: 1;
    min-width: 200px;
}

#premium-mfr-title.premium-mfr-brand-title {
    font-size: 36px;
    font-weight: 800;
    color: #10503D;
    margin: 0 0 15px 0;
    line-height: 1.3;
    letter-spacing: -0.5px;
}

#premium-mfr-description.premium-mfr-brand-description {
    font-size: 16px;
    color: #666;
    line-height: 1.7;
    margin-top: 10px;
}

/* Premium Top Bar */
#premium-mfr-top-bar.premium-mfr-top-bar {
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 4px 20px rgba(16, 80, 61, 0.08);
    border: 1px solid rgba(16, 80, 61, 0.1);
}

.premium-mfr-controls-wrapper {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.premium-mfr-control-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.premium-mfr-control-label {
    font-size: 15px;
    color: #10503D;
    font-weight: 600;
    white-space: nowrap;
}

.premium-mfr-select {
    padding: 10px 16px;
    border: 2px solid rgba(16, 80, 61, 0.2);
    border-radius: 8px;
    background: #ffffff;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    min-width: 160px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.premium-mfr-select:hover {
    border-color: #10503D;
    box-shadow: 0 4px 12px rgba(16, 80, 61, 0.15);
}

.premium-mfr-select:focus {
    outline: none;
    border-color: #10503D;
    box-shadow: 0 4px 16px rgba(16, 80, 61, 0.2);
}

/* Premium Products Section */
#premium-mfr-products-section.premium-mfr-products-section {
    margin-bottom: 40px;
}

.premium-mfr-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
    padding: 0;
}

/* Premium Product Card */
.premium-mfr-product-card {
    width: 100%;
}

.premium-mfr-product-card-inner {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid rgba(16, 80, 61, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 20px rgba(16, 80, 61, 0.08);
    height: 100%;
}

.premium-mfr-product-card-inner:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(16, 80, 61, 0.15);
    border-color: #A68A6A;
}

/* Premium Discount Badge */
.premium-mfr-discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #10503D 0%, #A68A6A 100%);
    color: #fff;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 13px;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(16, 80, 61, 0.4);
    letter-spacing: 0.5px;
}

/* Premium Product Image */
.premium-mfr-product-image-link {
    display: block;
    text-decoration: none;
}

.premium-mfr-image-wrapper {
    position: relative;
    overflow: hidden;
    background: #ffffff;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 25px;
    padding-bottom: 60px;
}

.premium-mfr-product-image {
    width: 100%;
    height: auto;
    max-height: 240px;
    object-fit: contain;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.premium-mfr-product-card-inner:hover .premium-mfr-product-image {
    transform: scale(1.08);
}

/* Premium Delivery Badge */
.premium-mfr-delivery-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #10503D 0%, #1a6b52 100%);
    color: #fff;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 600;
    z-index: 5;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

.premium-mfr-delivery-badge i {
    color: #A68A6A;
    font-size: 16px;
}

/* Premium Product Info */
.premium-mfr-product-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.premium-mfr-product-name {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 12px 0;
    line-height: 1.5;
    min-height: 45px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: #10503D;
}

.premium-mfr-product-name a {
    color: #10503D;
    text-decoration: none;
    transition: color 0.3s ease;
}

.premium-mfr-product-name a:hover {
    color: #A68A6A;
}

/* Premium Rating */
.premium-mfr-rating-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.premium-mfr-rating {
    display: flex;
    gap: 3px;
}

.premium-mfr-star-filled {
    color: #A68A6A;
    font-size: 16px;
    text-shadow: 0 1px 2px rgba(166, 138, 106, 0.3);
}

.premium-mfr-star-empty {
    color: #e0e0e0;
    font-size: 16px;
}

.premium-mfr-review-count {
    font-size: 13px;
    color: #888;
    font-weight: 500;
}

/* Premium Price */
.premium-mfr-price-box {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.premium-mfr-price-old {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
    font-weight: 500;
}

.premium-mfr-price-new {
    font-size: 24px;
    font-weight: 800;
    color: #10503D;
    letter-spacing: -0.5px;
}

/* Premium Add Button */
.premium-mfr-add-btn {
    margin-top: auto;
    padding: 14px 24px;
    background: linear-gradient(135deg, #10503D 0%, #1a6b52 100%);
    border: none;
    color: #ffffff;
    border-radius: 10px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(16, 80, 61, 0.3);
}

.premium-mfr-add-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(16, 80, 61, 0.4);
    background: linear-gradient(135deg, #1a6b52 0%, #10503D 100%);
}

.premium-mfr-add-btn:active:not(:disabled) {
    transform: translateY(0);
}

.premium-mfr-add-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: #ccc;
    box-shadow: none;
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

/* Premium Footer */
#premium-mfr-footer.premium-mfr-footer {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    margin-top: 40px;
    box-shadow: 0 4px 20px rgba(16, 80, 61, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    border: 1px solid rgba(16, 80, 61, 0.1);
}

.premium-mfr-pagination {
    flex: 1;
}

.premium-mfr-results {
    flex-shrink: 0;
}

.premium-mfr-results p {
    color: #666;
    font-size: 14px;
    margin: 0;
    font-weight: 500;
}

/* Premium Pagination */
.premium-mfr-pagination .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
    margin: 0;
}

.premium-mfr-pagination .pagination li {
    list-style: none;
}

.premium-mfr-pagination .pagination a,
.premium-mfr-pagination .pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 0 12px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.premium-mfr-pagination .pagination a {
    color: #666;
    background: #ffffff;
    border: 1px solid rgba(16, 80, 61, 0.2);
}

.premium-mfr-pagination .pagination a:hover {
    background: linear-gradient(135deg, #10503D 0%, #A68A6A 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(16, 80, 61, 0.3);
    border-color: transparent;
}

.premium-mfr-pagination .pagination .active span {
    background: linear-gradient(135deg, #10503D 0%, #A68A6A 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(16, 80, 61, 0.3);
}

/* Premium Empty State */
#premium-mfr-empty-state.premium-mfr-empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 30px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(16, 80, 61, 0.08);
    border: 1px solid rgba(16, 80, 61, 0.1);
}

.premium-mfr-empty-content {
    max-width: 450px;
    margin: 0 auto;
}

.premium-mfr-empty-icon {
    font-size: 72px;
    color: #A68A6A;
    margin-bottom: 20px;
}

.premium-mfr-empty-title {
    font-size: 26px;
    font-weight: 700;
    color: #10503D;
    margin: 0 0 12px 0;
}

.premium-mfr-empty-text {
    color: #888;
    font-size: 16px;
    margin: 0;
    line-height: 1.6;
}

/* Premium Description Section */
#premium-mfr-description-section.premium-mfr-description-section {
    background: #ffffff;
    padding: 50px 40px;
    border-radius: 16px;
    margin-top: 40px;
    box-shadow: 0 8px 30px rgba(16, 80, 61, 0.12);
    border: 1px solid rgba(16, 80, 61, 0.1);
}

.premium-mfr-description-wrapper {
    max-width: 1000px;
    margin: 0 auto;
}

.premium-mfr-description-title {
    font-size: 32px;
    font-weight: 800;
    color: #10503D;
    margin: 0 0 25px 0;
    padding-bottom: 15px;
    border-bottom: 3px solid #A68A6A;
    letter-spacing: -0.5px;
}

#premium-mfr-description-content.premium-mfr-description-content {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
}

#premium-mfr-description-content.premium-mfr-description-content p {
    margin-bottom: 15px;
}

#premium-mfr-description-content.premium-mfr-description-content h1,
#premium-mfr-description-content.premium-mfr-description-content h2,
#premium-mfr-description-content.premium-mfr-description-content h3 {
    color: #10503D;
    margin-top: 25px;
    margin-bottom: 15px;
}

#premium-mfr-description-content.premium-mfr-description-content a {
    color: #A68A6A;
    text-decoration: none;
    transition: color 0.3s ease;
}

#premium-mfr-description-content.premium-mfr-description-content a:hover {
    color: #10503D;
    text-decoration: underline;
}

/* Hide module titles */
#premium-mfr-sidebar.premium-mfr-filter-sidebar .module-heading-wrapper,
#premium-mfr-sidebar.premium-mfr-filter-sidebar .section-head,
#premium-mfr-sidebar.premium-mfr-filter-sidebar h2,
#premium-mfr-sidebar.premium-mfr-filter-sidebar h3 {
    display: none !important;
}

/* Responsive */
@media (max-width: 1200px) {
    .premium-mfr-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }
    
    #premium-mfr-title.premium-mfr-brand-title {
        font-size: 32px;
    }
}

@media (max-width: 992px) {
    #premium-mfr-sidebar.premium-mfr-filter-sidebar {
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
    
    #premium-mfr-sidebar.premium-mfr-filter-sidebar.filter-toggle-show {
        right: 0;
    }
    
    #premium-mfr-sidebar.premium-mfr-filter-sidebar .lc-close {
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
    
    #premium-mfr-sidebar.premium-mfr-filter-sidebar .lc-close:hover {
        background: #10503D;
        color: #fff;
    }
    
    /* Filter toggle button */
    #premium-mfr-top-bar.premium-mfr-top-bar::before {
        content: 'Filter';
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: linear-gradient(135deg, #10503D 0%, #A68A6A 100%);
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
    #premium-manufacturer-wrapper.premium-mfr-container {
        padding: 15px;
    }
    
    #premium-mfr-brand-header.premium-mfr-brand-header {
        padding: 25px 20px;
    }
    
    .premium-mfr-brand-info-wrapper {
        flex-direction: column;
        text-align: center;
    }
    
    #premium-mfr-logo.premium-mfr-brand-logo {
        width: 120px;
        height: 120px;
    }
    
    #premium-mfr-title.premium-mfr-brand-title {
        font-size: 28px;
    }
    
    #premium-mfr-top-bar.premium-mfr-top-bar {
        flex-direction: column;
        align-items: stretch;
        padding: 20px;
    }
    
    .premium-mfr-controls-wrapper {
        width: 100%;
        justify-content: space-between;
    }
    
    .premium-mfr-control-group {
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .premium-mfr-select {
        width: 100%;
        min-width: auto;
    }
    
    .premium-mfr-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .premium-mfr-product-card-inner:hover {
        transform: translateY(-4px);
    }
    
    .premium-mfr-product-info {
        padding: 16px;
    }
    
    .premium-mfr-product-name {
        font-size: 14px;
        min-height: 40px;
    }
    
    .premium-mfr-price-new {
        font-size: 20px;
    }
    
    #premium-mfr-description-section.premium-mfr-description-section {
        padding: 30px 20px;
    }
    
    .premium-mfr-description-title {
        font-size: 24px;
    }
    
    #premium-mfr-footer.premium-mfr-footer {
        flex-direction: column;
        align-items: stretch;
    }
}

@media (max-width: 480px) {
    #premium-mfr-title.premium-mfr-brand-title {
        font-size: 24px;
    }
    
    .premium-mfr-products-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .premium-mfr-product-name {
        font-size: 13px;
        min-height: 36px;
    }
    
    .premium-mfr-price-new {
        font-size: 18px;
    }
    
    .premium-mfr-description-title {
        font-size: 20px;
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
