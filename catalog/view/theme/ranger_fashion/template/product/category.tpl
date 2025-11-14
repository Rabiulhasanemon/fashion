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
<section class="product-categories">
    <div class="container">

        <div class="row"><?php echo $column_left; ?><?php if ($column_left && $column_right) { ?><?php $class = 'col-sm-12 col-md-6 product-listing'; ?><?php } elseif ($column_left || $column_right) { ?><?php $class = 'col-sm-12 col-md-9 product-listing'; ?><?php } else { ?><?php $class = 'col-sm-12'; ?><?php } ?>
            <div id="content" class="<?php echo $class; ?>">

                <div class="top-bar">
                    <div class="row align-center">
                        <div class="col-md-6 col-sm-12 title-toggle-wrap">
                            <h3 class="title"><?php echo $heading_title; ?></h3>
                            <button id="lc-toggle"><i class="material-icons">filter_list</i> <span>Filter</span></button>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="top-bar-filter">
                                <div class="form-group show">
                                    <label><?php echo $text_limit; ?></label>
                                    <div class="custom-selects">
                                        <select id="input-limit" onchange="location = this.value;">
                                            <?php foreach ($limits as $limits) { ?><?php if ($limits['value'] == $limit) { ?>
                                            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                                            <?php } ?><?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $text_sort; ?></label>
                                    <div class="custom-selects">
                                        <select id="input-limit" onchange="location = this.value;">
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
                    </div>
                </div>
                
                <?php if (isset($category_modules) && !empty($category_modules)) { ?>
                <div class="category-modules" style="margin-bottom: 30px;">
                    <?php foreach ($category_modules as $module) { ?>
                    <?php if (isset($module['output'])) { ?>
                    <div class="category-module-item" style="margin-bottom: 20px;">
                        <?php if (!empty($module['description'])) { ?>
                        <div class="category-module-description" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo html_entity_decode($module['description'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <?php } ?>
                        <?php if (!empty($module['output'])) { ?>
                        <?php echo $module['output']; ?>
                        <?php } else { ?>
                        <!-- Module <?php echo isset($module['code']) ? htmlspecialchars($module['code']) : 'unknown'; ?> loaded but produced no output -->
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                <?php } ?>
                
                <div class="row main-content">
                    <?php foreach ($products as $product) { ?>

                    <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="product-item">

                            <?php if ($product['special']) { ?>
                            <?php
                              $price = floatval(str_replace(['৳', ','], '', $product['price']));
                              $special = floatval(str_replace(['৳', ','], '', $product['special']));
                              $discountAmount = $price - $special;
                              $mark = ($discountAmount / $price) * 100;
                            ?>
                            <div class="mark"><?php echo round($mark, 1); ?>% OFF </div>
                            <?php } ?>

                            <a href="<?php echo $product['href']; ?>">
                                <div class="product-img">
                                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                                </div>
                            </a>

                            <div class="product-info">
                                <a href="<?php echo $product['href']; ?>">
                                    <h4 class="name"><?php echo $product['name']; ?></h4>
                                </a>
                                <div class="product-price-wrap">
                                    <?php if ($product['special']) { ?>
                                    <span class="price"><?php echo $product['special']; ?></span>
                                    <span class="price old"><?php echo $product['price']; ?></span>
                                    <?php } else { ?>
                                    <span class="price"><?php echo $product['price']; ?></span>
                                    <?php } ?>

                                </div>
                                <div class="product-btn-wrap">
                                    <button  class="btn btn-outline wishlist" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 21L10.55 19.7C8.86667 18.1834 7.475 16.875 6.375 15.775C5.275 14.675 4.4 13.6917 3.75 12.825C3.1 11.9417 2.64167 11.1334 2.375 10.4C2.125 9.66669 2 8.91669 2 8.15002C2 6.58336 2.525 5.27502 3.575 4.22502C4.625 3.17502 5.93333 2.65002 7.5 2.65002C8.36667 2.65002 9.19167 2.83336 9.975 3.20002C10.7583 3.56669 11.4333 4.08336 12 4.75003C12.5667 4.08336 13.2417 3.56669 14.025 3.20002C14.8083 2.83336 15.6333 2.65002 16.5 2.65002C18.0667 2.65002 19.375 3.17502 20.425 4.22502C21.475 5.27502 22 6.58336 22 8.15002C22 8.91669 21.8667 9.66669 21.6 10.4C21.35 11.1334 20.9 11.9417 20.25 12.825C19.6 13.6917 18.725 14.675 17.625 15.775C16.525 16.875 15.1333 18.1834 13.45 19.7L12 21ZM12 18.3C13.6 16.8667 14.9167 15.6417 15.95 14.625C16.9833 13.5917 17.8 12.7 18.4 11.95C19 11.1834 19.4167 10.5084 19.65 9.92503C19.8833 9.32503 20 8.73336 20 8.15002C20 7.15002 19.6667 6.31669 19 5.65003C18.3333 4.98336 17.5 4.65003 16.5 4.65003C15.7167 4.65003 14.9917 4.87503 14.325 5.32503C13.6583 5.75836 13.2 6.31669 12.95 7.00003H11.05C10.8 6.31669 10.3417 5.75836 9.675 5.32503C9.00833 4.87503 8.28333 4.65003 7.5 4.65003C6.5 4.65003 5.66667 4.98336 5 5.65003C4.33333 6.31669 4 7.15002 4 8.15002C4 8.73336 4.11667 9.32503 4.35 9.92503C4.58333 10.5084 5 11.1834 5.6 11.95C6.2 12.7 7.01667 13.5917 8.05 14.625C9.08333 15.6417 10.4 16.8667 12 18.3Z" fill="#C4C4C4"/>
                                        </svg>
                                    </button>
                                    <?php if($product["disablePurchase"] && $product["restock_request_btn"]) { ?>
                                    <button  class="btn btn-outline buy" onclick="restock_request.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["restock_request_btn"]; ?>
                                    </button>
                                    <?php } elseif ($product["disablePurchase"]) { ?>
                                    <button  class="btn btn-outline buy" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                    <?php echo $product["stock_status"]; ?>
                                    </button>
                                    <?php } else { ?>
                                    <button  class="btn btn-outline buy" <?php echo $product["disablePurchase"] ? "disabled" : ""; ?> onclick="cart.add('<?php echo $product['product_id']; ?>');">
                                        <span class="material-icons">shopping_cart</span> Buy Now
                                    </button>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                    if (!$products) { ?>
                   <div class="col-lg-8 offset-lg-2">
                       <div class="empty-content txt-center">
                           <span class="icon"></span>
                           <h5>Sorry! No Product Founds</h5>
                           <p>Please try searching for something else</p>
                       </div>
                   </div>
                    <?php } ?>
                </div>
                <div class="bottom-bar">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <?php echo $pagination; ?>
                        </div>
                        <div class="col-md-6 rs-none text-right">
                            <p><?php echo $results; ?></p>
                        </div>
                    </div>
                </div>
                <?php echo $content_bottom; ?>
            </div>
            <?php echo $column_right; ?>
        </div>
    </div>
</section>
<?php echo $footer; ?>
