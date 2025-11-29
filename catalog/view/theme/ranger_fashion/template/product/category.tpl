<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
            <?php }} ?>
        </ul>
    </div>
</section>
<section id="lux-category-page" class="lux-category-section">
    <div class="lux-container">
        
        <?php if (isset($category_modules) && !empty($category_modules)) { ?>
        <div id="lux-category-modules" class="lux-category-modules-wrapper">
            <?php foreach ($category_modules as $module) { ?>
            <div class="lux-module-item">
                <?php if (!empty($module['description'])) { ?>
                <div class="lux-module-description">
                    <?php 
                    $description = $module['description'];
                    // Decode HTML entities if needed
                    if (htmlspecialchars_decode($description) != $description) {
                        $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                    // Strip slashes if double-encoded
                    $description = stripslashes($description);
                    // Remove any padding/background styling from wrapper - let the HTML control its own styling
                    // Output the description as raw HTML (it's already sanitized from admin)
                    echo $description;
                    ?>
                </div>
                <?php } ?>
                <?php if (isset($module['output']) && !empty(trim($module['output']))) { ?>
                <div class="lux-category-module-content">
                    <?php echo $module['output']; ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <style>
        /* Hide module titles and section headings when displayed on category pages */
        .lux-category-module-content .module-heading-wrapper,
        .lux-category-module-content .section-head,
        .lux-category-module-content h2.cosmetics-module-heading,
        .lux-category-module-content h2.unified-module-heading,
        .lux-category-module-content .cosmetics-module-heading,
        .lux-category-module-content .unified-module-heading,
        .lux-category-module-content .latest-products__title,
        .lux-category-module-content .popular-products__title,
        .lux-category-module-content .heading_title,
        .lux-category-module-content .panel-heading {
            display: none !important;
        }
        
        /* Hide titles within section-title but keep other content like tabs/links */
        .lux-category-module-content .section-title h2,
        .lux-category-module-content .section-title h3,
        .lux-category-module-content .section-title .h3,
        .lux-category-module-content .section-title > div:first-child > h2,
        .lux-category-module-content .section-title > div:first-child > h3,
        .lux-category-module-content h2.h3 {
            display: none !important;
        }
        
        /* Hide entire section-title if it only contains a title (no tabs/links) */
        .lux-category-module-content .section-title:only-child,
        .lux-category-module-content .section-title:has(> h2:only-child),
        .lux-category-module-content .section-title:has(> h3:only-child) {
            display: none !important;
        }
        
        /* For modules with section-title containing only title in first div */
        .lux-category-module-content .section-title > div:first-child:has(> h2:only-child),
        .lux-category-module-content .section-title > div:first-child:has(> h3:only-child) {
            display: none !important;
        }
        
        /* Adjust section-title padding when title is hidden but tabs remain */
        .lux-category-module-content .section-title {
            padding-bottom: 0 !important;
            margin-bottom: 15px !important;
        }
        
        /* Adjust spacing when titles are hidden */
        .lux-category-module-content > div:first-child {
            margin-top: 0;
            padding-top: 0;
        }
        
        /* Remove top margin from module containers */
        .lux-category-module-content .deal-of-day-section,
        .lux-category-module-content .flash-sell-new-section,
        .lux-category-module-content .newproduct-section {
            margin-top: 0 !important;
        }
        </style>
        <?php } ?>

        <div class="lux-category-layout">
            <?php echo $column_left; ?>
            <div id="lux-category-content" class="lux-category-main-content">
                <header id="lux-category-header" class="lux-category-header">
                    <div class="lux-header-top">
                        <div class="lux-header-title-section">
                            <h1 id="lux-category-title" class="lux-category-title"><?php echo $heading_title; ?></h1>
                            <button id="lux-filter-toggle" class="lux-filter-btn">
                                <i class="material-icons">filter_list</i>
                                <span>Filter</span>
                            </button>
                        </div>
                        <div class="lux-header-controls">
                            <div class="lux-control-group">
                                <label class="lux-control-label"><?php echo $text_limit; ?></label>
                                <div class="lux-select-wrapper">
                                    <select id="lux-limit-select" class="lux-select" onchange="location = this.value;">
                                        <?php foreach ($limits as $limits) { ?><?php if ($limits['value'] == $limit) { ?>
                                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                        <?php } ?><?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="lux-control-group">
                                <label class="lux-control-label"><?php echo $text_sort; ?></label>
                                <div class="lux-select-wrapper">
                                    <select id="lux-sort-select" class="lux-select" onchange="location = this.value;">
                                        <?php foreach ($sorts as $sorts) { ?><?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                                        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                                        <?php } ?><?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                
                <div id="lux-products-grid" class="lux-products-grid">
                    <?php foreach ($products as $product) { ?>
                    <article class="lux-product-card" data-product-id="<?php echo $product['product_id']; ?>">
                        <?php if ($product['special']) { ?>
                        <?php
                          $price = floatval(str_replace(['৳', ','], '', $product['price']));
                          $special = floatval(str_replace(['৳', ','], '', $product['special']));
                          $discountAmount = $price - $special;
                          $mark = ($discountAmount / $price) * 100;
                        ?>
                        <div class="lux-product-badge"><?php echo round($mark, 1); ?>% OFF</div>
                        <?php } ?>

                        <a href="<?php echo $product['href']; ?>" class="lux-product-image-link">
                            <div class="lux-product-image-wrapper">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="lux-product-image" />
                            </div>
                        </a>

                        <div class="lux-product-details">
                            <h3 class="lux-product-name">
                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                            </h3>
                            <div class="lux-product-pricing">
                                <?php if ($product['special']) { ?>
                                <span class="lux-price-current"><?php echo $product['special']; ?></span>
                                <span class="lux-price-original"><?php echo $product['price']; ?></span>
                                <?php } else { ?>
                                <span class="lux-price-current"><?php echo $product['price']; ?></span>
                                <?php } ?>
                            </div>
                            <div class="lux-product-actions">
                                <button class="lux-action-btn lux-wishlist-btn" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" aria-label="Add to wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 21L10.55 19.7C8.86667 18.1834 7.475 16.875 6.375 15.775C5.275 14.675 4.4 13.6917 3.75 12.825C3.1 11.9417 2.64167 11.1334 2.375 10.4C2.125 9.66669 2 8.91669 2 8.15002C2 6.58336 2.525 5.27502 3.575 4.22502C4.625 3.17502 5.93333 2.65002 7.5 2.65002C8.36667 2.65002 9.19167 2.83336 9.975 3.20002C10.7583 3.56669 11.4333 4.08336 12 4.75003C12.5667 4.08336 13.2417 3.56669 14.025 3.20002C14.8083 2.83336 15.6333 2.65002 16.5 2.65002C18.0667 2.65002 19.375 3.17502 20.425 4.22502C21.475 5.27502 22 6.58336 22 8.15002C22 8.91669 21.8667 9.66669 21.6 10.4C21.35 11.1334 20.9 11.9417 20.25 12.825C19.6 13.6917 18.725 14.675 17.625 15.775C16.525 16.875 15.1333 18.1834 13.45 19.7L12 21ZM12 18.3C13.6 16.8667 14.9167 15.6417 15.95 14.625C16.9833 13.5917 17.8 12.7 18.4 11.95C19 11.1834 19.4167 10.5084 19.65 9.92503C19.8833 9.32503 20 8.73336 20 8.15002C20 7.15002 19.6667 6.31669 19 5.65003C18.3333 4.98336 17.5 4.65003 16.5 4.65003C15.7167 4.65003 14.9917 4.87503 14.325 5.32503C13.6583 5.75836 13.2 6.31669 12.95 7.00003H11.05C10.8 6.31669 10.3417 5.75836 9.675 5.32503C9.00833 4.87503 8.28333 4.65003 7.5 4.65003C6.5 4.65003 5.66667 4.98336 5 5.65003C4.33333 6.31669 4 7.15002 4 8.15002C4 8.73336 4.11667 9.32503 4.35 9.92503C4.58333 10.5084 5 11.1834 5.6 11.95C6.2 12.7 7.01667 13.5917 8.05 14.625C9.08333 15.6417 10.4 16.8667 12 18.3Z" fill="currentColor"/>
                                    </svg>
                                </button>
                                <?php if($product["disablePurchase"] && $product["restock_request_btn"]) { ?>
                                <button class="lux-action-btn lux-buy-btn lux-buy-btn--full" onclick="restock_request.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["restock_request_btn"]; ?>
                                </button>
                                <?php } elseif ($product["disablePurchase"]) { ?>
                                <button class="lux-action-btn lux-buy-btn lux-buy-btn--full" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["stock_status"]; ?>
                                </button>
                                <?php } else { ?>
                                <button class="lux-action-btn lux-buy-btn lux-buy-btn--full" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <span class="material-icons">shopping_cart</span>
                                    <span>Buy Now</span>
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </article>
                    <?php }
                    if (!$products) { ?>
                    <div class="lux-empty-state">
                        <div class="lux-empty-content">
                            <span class="lux-empty-icon"></span>
                            <h5 class="lux-empty-title">Sorry! No Products Found</h5>
                            <p class="lux-empty-text">Please try searching for something else</p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <footer id="lux-category-footer" class="lux-category-footer">
                    <div class="lux-footer-content">
                        <div class="lux-pagination-wrapper">
                            <?php echo $pagination; ?>
                        </div>
                        <div class="lux-results-info">
                            <p class="lux-results-text"><?php echo $results; ?></p>
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
/* Premium Category Page Styling - Unique lux-category Classes */
#lux-category-page.lux-category-section {
    background: #fff;
    padding: 30px 0 50px 0;
}

.lux-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.lux-category-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
}

/* Header */
.lux-category-header {
    margin-bottom: 30px;
}

.lux-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.lux-header-title-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.lux-category-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.lux-filter-btn {
    display: none;
    padding: 10px 20px;
    background: #ff6b9d;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    gap: 8px;
    align-items: center;
}

.lux-header-controls {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.lux-control-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.lux-control-label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.lux-select-wrapper {
    position: relative;
}

.lux-select {
    padding: 10px 35px 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23333" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    min-width: 150px;
}

.lux-select:hover {
    border-color: #ff6b9d;
}

/* Products Grid */
.lux-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.lux-product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
}

.lux-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.lux-product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 13px;
    z-index: 10;
    box-shadow: 0 3px 8px rgba(255,107,107,0.3);
}

.lux-product-image-wrapper {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.lux-product-image-link {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.lux-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.lux-product-card:hover .lux-product-image {
    transform: scale(1.1);
}

.lux-product-details {
    padding: 20px;
}

.lux-product-name {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 10px 0;
    line-height: 1.4;
    min-height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.lux-product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.lux-product-name a:hover {
    color: #ff6b9d;
}

.lux-product-pricing {
    margin-bottom: 15px;
}

.lux-price-current {
    font-size: 22px;
    font-weight: 700;
    color: #ff6b9d;
    margin-right: 8px;
}

.lux-price-original {
    font-size: 16px;
    color: #999;
    text-decoration: line-through;
}

.lux-product-actions {
    display: flex;
    gap: 10px;
}

.lux-action-btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.lux-wishlist-btn {
    background: #f0f0f0;
    color: #666;
}

.lux-wishlist-btn:hover {
    background: #ff6b9d;
    color: #fff;
}

.lux-buy-btn {
    background: linear-gradient(135deg, #ff6b9d 0%, #ff8c9f 100%);
    color: #fff;
}

.lux-buy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,107,157,0.3);
}

.lux-buy-btn--full {
    flex: 2;
}

/* Footer */
.lux-category-footer {
    padding: 30px 0;
    border-top: 1px solid #eee;
    margin-top: 40px;
}

.lux-footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.lux-results-text {
    color: #666;
    font-size: 14px;
    margin: 0;
}

/* Empty State */
.lux-empty-state {
    text-align: center;
    padding: 80px 20px;
}

.lux-empty-title {
    font-size: 24px;
    color: #666;
    margin: 20px 0 10px 0;
}

.lux-empty-text {
    color: #999;
    font-size: 16px;
}

/* Responsive */
@media (max-width: 992px) {
    .lux-category-layout {
        grid-template-columns: 1fr;
    }
    
    .lux-filter-btn {
        display: flex;
    }
    
    .lux-category-title {
        font-size: 26px;
    }
}

@media (max-width: 768px) {
    .lux-category-title {
        font-size: 22px;
    }
    
    .lux-header-top {
        flex-direction: column;
        align-items: stretch;
    }
    
    .lux-header-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .lux-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .lux-product-card:hover {
        transform: none;
    }
}

@media (max-width: 480px) {
    .lux-category-title {
        font-size: 20px;
    }
    
    .lux-control-group {
        flex: 1;
    }
    
    .lux-select {
        width: 100%;
        min-width: auto;
        font-size: 13px;
        padding: 8px 30px 8px 10px;
    }
    
    .lux-control-label {
        font-size: 12px;
    }
}
</style>

<?php echo $footer; ?>
