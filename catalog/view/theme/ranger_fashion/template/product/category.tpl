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
<?php echo $footer; ?>
