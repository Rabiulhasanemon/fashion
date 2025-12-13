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
<section id="content-top">
    <div class="container"><?php echo $content_top; ?></div>
</section>
<?php if ($success) { ?>
<div class="container alert-container">
    <div class="alert alert-success"><?php echo $success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
</div>
<?php } ?>
<div class="product-details" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="productID" content="<?php echo $product_id; ?>">
    <meta itemprop="sku" content="<?php echo $product_id; ?>">
    <section class="basic">
        <div class="container"  id="product">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="lux-product-media" id="product-media-gallery">
                        <div class="lux-media-grid">
                            <?php 
                            // DEBUG: Check what we have
                            $debug_output = '';
                            if (isset($_GET['debug_images'])) {
                                $debug_output .= "<!-- DEBUG INFO:\n";
                                $debug_output .= "thumb: " . (isset($thumb) ? $thumb : 'NOT SET') . "\n";
                                $debug_output .= "popup: " . (isset($popup) ? $popup : 'NOT SET') . "\n";
                                $debug_output .= "images count: " . (isset($images) && is_array($images) ? count($images) : 'NOT ARRAY') . "\n";
                                if (isset($images) && is_array($images)) {
                                    foreach ($images as $idx => $img) {
                                        $debug_output .= "  images[" . $idx . "]: " . print_r($img, true) . "\n";
                                    }
                                }
                                $debug_output .= "featured_image: " . (isset($featured_image) ? $featured_image : 'NOT SET') . "\n";
                                $debug_output .= "-->\n";
                            }
                            echo $debug_output;
                            
                            // Get all images for thumbnails
                            $all_thumbnails = array();
                            
                            // Add main thumbnail first if exists
                            if (!empty($thumb)) {
                                $all_thumbnails[] = array(
                                    'thumb' => $thumb,
                                    'popup' => !empty($popup) ? $popup : $thumb,
                                    'original' => $thumb
                                );
                            }
                            
                            // Add additional images
                            if (!empty($images) && is_array($images)) {
                                foreach ($images as $img) {
                                    $thumb_src = '';
                                    if (!empty($img['thumb'])) {
                                        $thumb_src = $img['thumb'];
                                    } elseif (!empty($img['original'])) {
                                        $thumb_src = $img['original'];
                                    }
                                    
                                    if (!empty($thumb_src)) {
                                        $popup_src = !empty($img['popup']) ? $img['popup'] : $thumb_src;
                                        $all_thumbnails[] = array(
                                            'thumb' => $thumb_src,
                                            'popup' => $popup_src,
                                            'original' => !empty($img['original']) ? $img['original'] : $thumb_src
                                        );
                                    }
                                }
                            }
                            
                            // Set main image - use thumb if available, otherwise first additional, otherwise placeholder
                            $main_img_src = '';
                            $main_img_popup = '';
                            
                            if (!empty($thumb)) {
                                $main_img_src = $thumb;
                                $main_img_popup = !empty($popup) ? $popup : $thumb;
                            } elseif (!empty($images) && is_array($images) && !empty($images[0]['thumb'])) {
                                $main_img_src = $images[0]['thumb'];
                                $main_img_popup = !empty($images[0]['popup']) ? $images[0]['popup'] : $images[0]['thumb'];
                            } else {
                                // Placeholder fallback
                                $this->load->model('tool/image');
                                $main_img_src = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
                                $main_img_popup = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                            }
                            ?>
                            <div class="lux-media-grid__thumbs">
                                <?php if (count($all_thumbnails) > 0) { ?>
                                    <div class="lux-thumb-track">
                                        <?php foreach ($all_thumbnails as $index => $thumb_data) { 
                                            $thumb_src = isset($thumb_data['thumb']) && $thumb_data['thumb'] ? $thumb_data['thumb'] : (isset($thumb_data['original']) ? $thumb_data['original'] : '');
                                            $popup_src = isset($thumb_data['popup']) && $thumb_data['popup'] ? $thumb_data['popup'] : $thumb_src;
                                            if (!$thumb_src) { continue; }
                                        ?>
                                        <button class="lux-thumb <?php echo ($index === 0) ? 'is-active' : ''; ?>"
                                                type="button"
                                                data-image="<?php echo htmlspecialchars($thumb_src); ?>"
                                                data-popup="<?php echo htmlspecialchars($popup_src); ?>"
                                                aria-label="Preview <?php echo htmlspecialchars($heading_title); ?>">
                                            <span class="lux-thumb__inner">
                                                <img src="<?php echo htmlspecialchars($thumb_src); ?>" alt="<?php echo htmlspecialchars($heading_title); ?>" onerror="this.src='image/placeholder.png';">
                                            </span>
                                        </button>
                                        <meta itemprop="image" content="<?php echo htmlspecialchars($thumb_src); ?>"/>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="lux-media-grid__viewer">
                                <div class="lux-stage" data-media-stage>
                                    <a class="lux-stage__link"
                                       href="<?php echo htmlspecialchars($main_img_popup); ?>"
                                       title="<?php echo htmlspecialchars($heading_title); ?>"
                                       data-fancybox="product-gallery"
                                       id="main-image-link">
                                        <img class="lux-stage__image"
                                             id="main-product-image"
                                             src="<?php echo htmlspecialchars($main_img_src); ?>"
                                             alt="<?php echo htmlspecialchars($heading_title); ?>"
                                             onerror="this.src='image/placeholder.png';">
                                        <span class="lux-stage__zoom">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M10.5 3A7.5 7.5 0 0 1 18 10.5c0 1.73-.6 3.32-1.6 4.57l4.76 4.76-1.41 1.41-4.76-4.76A7.47 7.47 0 0 1 10.5 18 7.5 7.5 0 1 1 10.5 3zm0 2A5.5 5.5 0 1 0 10.5 16 5.5 5.5 0 0 0 10.5 5z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </a>
                                    <meta itemprop="image" content="<?php echo htmlspecialchars($main_img_src); ?>"/>
                                </div>
                                <?php if (!empty($images) && is_array($images)) { ?>
                                    <div class="lux-stage__hidden">
                                        <?php foreach ($images as $img) { ?>
                                            <?php if (!empty($img['popup'])) { ?>
                                            <a href="<?php echo htmlspecialchars($img['popup']); ?>" data-fancybox="product-gallery"></a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="product-info-all">
                        <div class="product-head-info">
                            <h1 itemprop="name" class="product-name-premium"><?php echo $heading_title; ?></h1>

                            <?php 
                            // Handle short_description - it might be an array or string
                            $display_short_description = '';
                            if (isset($short_description) && $short_description) {
                                if (is_array($short_description)) {
                                    $display_short_description = implode(' ', array_filter($short_description));
                                } else {
                                    $display_short_description = trim($short_description);
                                }
                            }
                            if ($display_short_description) { 
                            ?>
                            <div class="product-short-description-premium">
                                <span class="short-desc-text"><?php echo html_entity_decode($display_short_description, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <?php } ?>

                            <div class="short-info">
                                <ul>
                                    <?php if (isset($manufacturer) && $manufacturer) { ?>
                                    <li> <b>Brand:</b>  <span><a href="<?php echo isset($manufacturers) ? $manufacturers : '#'; ?>" style="color: #10503D; text-decoration: none;"><?php echo $manufacturer; ?></a></span></li>
                                    <?php } ?>
                                    <?php if (isset($price) && $price) { ?>
                                    <li> <b>Regular Price:</b>  <span><?php echo $price; ?></span></li>
                                    <?php } ?>
                                    <?php if (isset($special) && $special) { ?>
                                    <li> <b>Price:</b>  <span style="color: #FF6A00; font-weight: 600;"><?php echo $special; ?></span></li>
                                    <?php } elseif (isset($price) && $price) { ?>
                                    <li> <b>Price:</b>  <span style="color: #FF6A00; font-weight: 600;"><?php echo $price; ?></span></li>
                                    <?php } ?>
                                    <li> <b>Status:</b>  <span><?php echo $stock; ?></span></li>
                                    <li><b>Rating:</b>  <span><?php echo $rating; ?>/5.0 </span> <?php echo $reviews; ?></li>
                                    <?php if ($sku) { ?>
                                    <li> <b>SKU: </b><?php echo $sku; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <?php if ($options) { ?>
                        <div class="p-opt-wrap prx-option-wrap">
                            <?php foreach ($options as $option) { ?>
                            <div class="prx-option-field <?php echo $option['type']; ?>">
                                <div class="prx-option-head">
                                    <div class="prx-option-name"><?php echo $option['name']; ?></div>
                                    <div class="prx-option-selection" id="input-option<?php echo $option['product_option_id']; ?>"><b></b></div>
                                </div>
                                <div class="prx-option-values">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <label class="prx-option-pill">
                                        <input class="prx-option-input" type="radio" value="<?php echo $option_value['option_value_id']; ?>"  name="option[<?php echo $option['product_option_id']; ?>]" title="<?php echo $option_value['name']; ?>">
                                        <span class="prx-option-pill__label"><?php echo $option_value['name']; ?></span>
                                    </label>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>

                        <div class="cart-option prx-cart-option" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                            <link itemprop="availability" href="http://schema.org/<?php echo $stock_meta; ?>"/><link itemprop="itemCondition" href="http://schema.org/NewCondition">
                            <meta itemprop="priceCurrency" content="BDT" /><meta itemprop="price" content="<?php echo $raw_price; ?>" />

                            <div class="price-wrap prx-price-wrap">
                                <input type="hidden" name="enable_emi" checked value="0"/>

                                <?php if ($disablePurchase || !$special) { ?>
                                <span class="price product-price"><span><?php echo $price; ?></span></span>
                                <?php } else { ?>
                                <span class="price product-price"><span> <ins style="text-decoration: none;"><?php echo $special; ?></ins> </span></span>
                                <span class="price-old product-price"><span> <del><?php echo $price; ?></del> </span></span>
                                <?php
                                  $p = floatval(str_replace(['৳', ','], '', $price));
                                  $s = floatval(str_replace(['৳', ','], '', $special));
                                  $discountAmount = $p - $s;
                                ?>
                                <span class="save">Save <?php echo $discountAmount; ?> TK.</span>
                                <?php } ?>
                                <?php if (!empty($product_reward_points)) { ?>
                                <div class="prx-reward-pill">
                                    <i class="fa fa-gift"></i>
                                    <span>Earn <?php echo $product_reward_points; ?> reward points</span>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="prx-action-grid">
                                <div class="prx-qty-control" data-min-qty="<?php echo (int)$minimum; ?>">
                                    <span class="prx-qty-btn prx-qty-btn--minus"><i class="material-icons">remove</i></span>
                                    <span class="qty"><input type="text" name="quantity" id="input-quantity" value="<?php echo $minimum; ?>" size="2"></span>
                                    <span class="prx-qty-btn prx-qty-btn--plus"><i class="material-icons">add</i></span>
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                                </div>
                                <div class="prx-cta-buttons">
                                    <button id="button-cart" class="prx-btn prx-btn--cart" <?php echo $disablePurchase ? "disabled" : ""; ?>><i class="fa fa-shopping-cart"></i><span>Add to Cart</span></button>
                                    <button id="buy-now" class="prx-btn prx-btn--buy" <?php echo $disablePurchase ? "disabled" : ""; ?>><i class="fa fa-bolt"></i><span>Buy Now</span></button>
                                    <button type="button" class="prx-btn prx-btn--compare" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i><span>Compare</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="prx-contact-grid">
                            <?php if (!empty($whatsapp_link)) { ?>
                            <a class="prx-contact-card prx-contact-card--whatsapp" href="<?php echo $whatsapp_link; ?>" target="_blank" rel="noopener" title="Order on WhatsApp">
                                <div class="prx-contact-icon prx-icon-animated">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                            </a>
                            <?php } ?>
                            <?php if (!empty($primary_contact_tel)) { ?>
                            <a class="prx-contact-card prx-contact-card--call" href="<?php echo $primary_contact_tel; ?>" title="Call for Order">
                                <div class="prx-contact-icon prx-icon-animated">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </a>
                            <?php } ?>
                        </div>

                        <div class="save-and-share-wrap">
                            <button class="btn btn-outline" onclick="wishlist.add('<?php echo $product_id; ?>');" type="button"><span class="material-icons">favorite_border</span> Add To Wishlist </button>

                            <div class="social-share">
                                <div class="share-on">
                                    <span class="share" >Share:</span>
                                    <span class="share-ico" data-type="facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                          <path d="M8.00016 1.35999C4.3335 1.35999 1.3335 4.35332 1.3335 8.03999C1.3335 11.3733 3.7735 14.14 6.96016 14.64V9.97332H5.26683V8.03999H6.96016V6.56665C6.96016 4.89332 7.9535 3.97332 9.48016 3.97332C10.2068 3.97332 10.9668 4.09999 10.9668 4.09999V5.74665H10.1268C9.30016 5.74665 9.04016 6.25999 9.04016 6.78665V8.03999H10.8935L10.5935 9.97332H9.04016V14.64C10.6111 14.3919 12.0416 13.5903 13.0734 12.38C14.1053 11.1697 14.6704 9.63041 14.6668 8.03999C14.6668 4.35332 11.6668 1.35999 8.00016 1.35999Z" fill="#5E5E5E"/>
                                        </svg>
                                    </span>
                                    <span class="share-ico" data-type="whatsapp">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                          <path d="M8.00066 1.33331C11.6827 1.33331 14.6673 4.31798 14.6673 7.99998C14.6673 11.682 11.6827 14.6666 8.00066 14.6666C6.8225 14.6687 5.66505 14.3569 4.64733 13.7633L1.33666 14.6666L2.23799 11.3546C1.64397 10.3366 1.33193 9.17866 1.33399 7.99998C1.33399 4.31798 4.31866 1.33331 8.00066 1.33331ZM5.72866 4.86665L5.59533 4.87198C5.50912 4.87792 5.42489 4.90056 5.34733 4.93865C5.27504 4.97965 5.20904 5.03084 5.15133 5.09065C5.07133 5.16598 5.02599 5.23131 4.97733 5.29465C4.73074 5.61525 4.59798 6.00886 4.59999 6.41331C4.60133 6.73998 4.68666 7.05798 4.81999 7.35531C5.09266 7.95665 5.54133 8.59331 6.13333 9.18331C6.27599 9.32531 6.416 9.46798 6.56666 9.60065C7.30228 10.2482 8.17885 10.7153 9.12666 10.9646L9.50533 11.0226C9.62866 11.0293 9.752 11.02 9.876 11.014C10.0701 11.0037 10.2597 10.9512 10.4313 10.86C10.5186 10.8149 10.6038 10.7659 10.6867 10.7133C10.6867 10.7133 10.7149 10.6942 10.77 10.6533C10.86 10.5866 10.9153 10.5393 10.99 10.4613C11.046 10.4035 11.0927 10.3364 11.13 10.26C11.182 10.1513 11.234 9.94398 11.2553 9.77131C11.2713 9.63931 11.2667 9.56731 11.2647 9.52265C11.262 9.45131 11.2027 9.37731 11.138 9.34598L10.75 9.17198C10.75 9.17198 10.17 8.91931 9.81533 8.75798C9.7782 8.74182 9.73844 8.73256 9.698 8.73065C9.65238 8.72587 9.60627 8.73096 9.56279 8.74557C9.51931 8.76018 9.47948 8.78396 9.446 8.81531C9.44266 8.81398 9.39799 8.85198 8.91599 9.43598C8.88833 9.47315 8.85022 9.50125 8.80653 9.51668C8.76284 9.53212 8.71554 9.53419 8.67066 9.52265C8.62721 9.51107 8.58466 9.49636 8.54333 9.47865C8.46066 9.44398 8.43199 9.43065 8.37533 9.40665C7.99258 9.23992 7.63829 9.0143 7.32533 8.73798C7.24133 8.66465 7.16333 8.58465 7.08333 8.50731C6.82107 8.25612 6.5925 7.97197 6.40333 7.66198L6.36399 7.59865C6.33617 7.55584 6.31335 7.50999 6.29599 7.46198C6.27066 7.36398 6.33666 7.28531 6.33666 7.28531C6.33666 7.28531 6.49866 7.10798 6.57399 7.01198C6.64733 6.91865 6.70933 6.82798 6.74933 6.76331C6.82799 6.63665 6.85266 6.50665 6.81133 6.40598C6.62466 5.94998 6.43177 5.49642 6.23266 5.04531C6.19333 4.95598 6.07666 4.89198 5.97066 4.87931C5.93466 4.87487 5.89866 4.87131 5.86266 4.86865C5.77315 4.86351 5.68339 4.8644 5.59399 4.87131L5.72866 4.86665Z" fill="#5E5E5E"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="copy-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M12.6667 1.33331C13.0203 1.33331 13.3594 1.47379 13.6095 1.72384C13.8595 1.97389 14 2.31302 14 2.66665V10.6666C14 11.0203 13.8595 11.3594 13.6095 11.6095C13.3594 11.8595 13.0203 12 12.6667 12H11.3333V13.3333C11.3333 13.6869 11.1929 14.0261 10.9428 14.2761C10.6928 14.5262 10.3536 14.6666 10 14.6666H3.33333C2.97971 14.6666 2.64057 14.5262 2.39052 14.2761C2.14048 14.0261 2 13.6869 2 13.3333V5.33331C2 4.97969 2.14048 4.64055 2.39052 4.3905C2.64057 4.14046 2.97971 3.99998 3.33333 3.99998H4.66667V2.66665C4.66667 2.31302 4.80714 1.97389 5.05719 1.72384C5.30724 1.47379 5.64638 1.33331 6 1.33331H12.6667ZM6.66667 9.99998H5.33333C5.16341 10.0002 4.99998 10.0652 4.87642 10.1819C4.75286 10.2985 4.67851 10.4579 4.66855 10.6276C4.65859 10.7972 4.71378 10.9642 4.82284 11.0945C4.9319 11.2248 5.0866 11.3086 5.25533 11.3286L5.33333 11.3333H6.66667C6.83659 11.3331 7.00002 11.2681 7.12358 11.1514C7.24714 11.0348 7.32149 10.8753 7.33145 10.7057C7.34141 10.5361 7.28622 10.3691 7.17716 10.2388C7.0681 10.1085 6.9134 10.0247 6.74467 10.0046L6.66667 9.99998ZM12.6667 2.66665H6V3.99998H10C10.3536 3.99998 10.6928 4.14046 10.9428 4.3905C11.1929 4.64055 11.3333 4.97969 11.3333 5.33331V10.6666H12.6667V2.66665ZM8 7.33331H5.33333C5.15652 7.33331 4.98695 7.40355 4.86193 7.52858C4.7369 7.6536 4.66667 7.82317 4.66667 7.99998C4.66667 8.17679 4.7369 8.34636 4.86193 8.47138C4.98695 8.59641 5.15652 8.66665 5.33333 8.66665H8C8.17681 8.66665 8.34638 8.59641 8.4714 8.47138C8.59643 8.34636 8.66667 8.17679 8.66667 7.99998C8.66667 7.82317 8.59643 7.6536 8.4714 7.52858C8.34638 7.40355 8.17681 7.33331 8 7.33331Z" fill="#5E5E5E"/>
                                    </svg>
                                     Copy Link
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="product-info-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <ul class="nav-tab-bars mb-3">
                        <li class="nav-tab active" onclick="openTab(event, 'description')">Description</li>
                        <?php if (isset($attribute_groups) && !empty($attribute_groups)) { ?>
                        <li class="nav-tab" onclick="openTab(event, 'product-attributes')"><?php echo isset($tab_attribute) ? $tab_attribute : 'Specifications'; ?></li>
                        <?php } ?>
                        <li class="nav-tab" onclick="openTab(event, 'product-video')">Product Video</li>
                        <li class="nav-tab" onclick="openTab(event, 'write-review')"><?php echo $tab_review; ?></li>
                    </ul>

                    <div class="single-tab-details product-description" id="description" style="display: block;">
                        <div itemprop="description" class="seo-description"><?php echo $description ?></div>
                    </div>

                    <?php if (isset($attribute_groups) && !empty($attribute_groups)) { ?>
                    <div class="single-tab-details product-attributes" id="product-attributes" style="display: none;">
                        <div class="modern-attributes-wrapper">
                            <?php foreach ($attribute_groups as $attribute_group) { ?>
                            <div class="attribute-group-card">
                                <div class="attribute-group-header">
                                    <h3 class="attribute-group-title">
                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                        <?php echo htmlspecialchars($attribute_group['name']); ?>
                                    </h3>
                                </div>
                                <div class="attribute-group-body">
                                    <div class="attribute-list">
                                        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                                        <div class="attribute-item">
                                            <div class="attribute-name">
                                                <span class="attribute-label"><?php echo htmlspecialchars($attribute['name']); ?></span>
                                            </div>
                                            <div class="attribute-value">
                                                <span class="attribute-text"><?php echo htmlspecialchars($attribute['text']); ?></span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="single-tab-details product-video" id="product-video" style="display: none;">
                        <?php if (isset($video_url) && !empty($video_url)) { ?>
                        <div class="product-video-wrapper" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; background: #000;">
                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="<?php echo htmlspecialchars($video_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <?php } else { ?>
                        <div class="product-video-placeholder" style="background: #000; width: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px;">
                            <div style="text-align: center;">
                                <i class="fa fa-youtube-play" style="font-size: 64px; margin-bottom: 20px; color: #ff0000;"></i>
                                <p>No video available for this product</p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="single-tab-details ask-question" id="ask-question" style="display: none;">
                        <div class="section-head">
                            <div class="title-n-action">
                                <p class="section-blurb"><?php echo $text_question_help; ?></p>
                            </div>
                        </div>
                        <div id="question"><?php echo isset($question) ? $question : ''; ?></div>
                    </div>
                    <div class="single-tab-details review" id="write-review" style="display: none;">
                        <?php if ($no_of_review) { ?>
                        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                            <meta itemprop="ratingValue" content="<?php echo $rating ; ?>"/>
                            <meta itemprop="reviewCount" content="<?php echo $no_of_review ; ?>"/>
                        </div>
                        <?php } ?>
                        <div class="section-head">
                            <div class="title-n-action">
                                <p class="section-blurb"><?php echo $text_review_help; ?></p>
                                <?php if ($no_of_review) { ?>
                                <div class="average-rating">
                                    <span class="count"><b><?php echo $rating; ?></b><span> out of 5</span></span>
                                    <span class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <?php if ($rating < $i) { ?>
                                        <i class="fa-regular fa-star"></i>
                                        <?php } else { ?>
                                        <i class="fa-solid fa-star"></i>
                                        <?php } ?>
                                        <?php } ?>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div id="review" class="mb-3"><?php echo isset($review) ? $review : ''; ?></div>
                        <form method="post" enctype="multipart/form-data" id="form-review" action="index.php?route=product/product/write&product_id=<?php echo $product_id; ?>">

                            <h4 class="write-reivew-title mb-3"><?php echo $text_write_review; ?></h4>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 form-group">
                                    <input name="name" placeholder="<?php echo $entry_name; ?>*" type="text" class="form-input">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 form-group">
                                    <input name="email" placeholder="<?php echo $entry_email; ?>" type="text" class="form-input">
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 form-group">
                                    <textarea name="text" rows="5" placeholder="<?php echo $entry_review; ?>*" class="form-input"></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 form-group">
                                    <label class="control-label"><?php echo $entry_rating; ?>:</label>
                                    &nbsp;<?php echo $entry_bad; ?> &nbsp; <input type="radio" name="rating" value="1" /> &nbsp; <input type="radio" name="rating" value="2" /> &nbsp; <input type="radio" name="rating" value="3" /> &nbsp; <input type="radio" name="rating" value="4" /> &nbsp; <input type="radio" name="rating" value="5" /> <?php echo $entry_good; ?>
                                    <br><br>
                                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn submit-btn"><?php echo $button_continue; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="content-bottom">
                        <?php echo $content_bottom; ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php if (isset($products) && is_array($products) && count($products) > 0) { ?>
                    <div class="rpmini24_widget">
                        <div class="rpmini24_head">
                            <div>
                                <p class="rpmini24_kicker">You may also like</p>
                                <h4 class="rpmini24_title">Related Products</h4>
                            </div>
                            <?php if (!empty($view_all_products_link)) { ?>
                            <a class="rpmini24_viewall" href="<?php echo $view_all_products_link; ?>">View All</a>
                            <?php } ?>
                        </div>
                        <div class="rpmini24_list">
                            <?php foreach ($products as $product) { ?>
                            <a href="<?php echo $product['href']; ?>" class="rpmini24_card">
                                <div class="rpmini24_media">
                                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" onerror="this.src='image/placeholder.png';" />
                                </div>
                                <div class="rpmini24_info">
                                    <h5><?php echo $product['name']; ?></h5>
                                    <div class="rpmini24_price">
                                        <?php if ($product['special']) { ?>
                                        <span class="rpmini24_price-new"><?php echo $product['special']; ?></span>
                                        <span class="rpmini24_price-old"><?php echo $product['price']; ?></span>
                                        <?php } else { ?>
                                        <span class="rpmini24_price-new"><?php echo $product['price']; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Related Products Section -->

    <!-- Compatible Products Section -->
    <?php 
    if (isset($compatible_products) && is_array($compatible_products) && count($compatible_products) > 0) { ?>
    <section class="newproduct-section popular-category-sec compatible-products-showcase" style="padding: 50px 0; background-color: #f3f5f6;">
        <div class="container">
            <div class="section-title">
                <h2 class="h3">Compatible Products</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="popular-category-slider owl-carousel compatible-products-slider">
                        <?php foreach ($compatible_products as $product) { ?>
                        <div class="slider-item">
                            <div class="product-card">
                                <div class="product-thumb">
                                    <?php if ($product['special']) { ?>
                                    <?php
                                    $price = floatval(str_replace(['৳', ','], '', $product['price']));
                                    $special = floatval(str_replace(['৳', ','], '', $product['special']));
                                    $discount = 0;
                                    if ($price > 0) {
                                        $discountAmount = $price - $special;
                                        $discount = round(($discountAmount / $price) * 100, 0);
                                    }
                                    if ($discount > 0) {
                                    ?>
                                    <div class="product-badge product-badge2 bg-info">-<?php echo $discount; ?>%</div>
                                    <?php } ?>
                                    <?php } ?>
                                    
                                    <?php if ($product['featured_image']) { ?>
                                    <img class="lazy" alt="<?php echo $product['name']; ?>" src="<?php echo $product['featured_image']; ?>" />
                                    <?php } else { ?>
                                    <img class="lazy" alt="<?php echo $product['name']; ?>" src="<?php echo $product['thumb']; ?>" />
                                    <?php } ?>
                                    
                                    <div class="product-button-group">
                                        <a class="product-button wishlist_store" onclick="wishlist.add('<?php echo $product['product_id']; ?>');" href="javascript:;" title="Wishlist"><i class="icon-heart"></i></a>
                                        <a class="product-button product_compare" onclick="compare.add('<?php echo $product['product_id']; ?>');" href="javascript:;" title="Compare"><i class="icon-repeat"></i></a>
                                        <?php if (!$product['disablePurchase']) { ?>
                                        <a class="product-button add_to_single_cart" onclick="cart.add('<?php echo $product['product_id']; ?>');" href="javascript:;" title="To Cart"><i class="icon-shopping-cart"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="product-card-body">
                                    <h3 class="product-title"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
                                    
                                    <?php if (isset($product['rating']) && $product['rating'] > 0) { ?>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <i class="fas fa-star<?php echo $i <= $product['rating'] ? ' filled' : ''; ?>"></i>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                    
                                    <h4 class="product-price">
                                        <?php if ($product['special']) { ?>
                                        <del><?php echo $product['price']; ?></del> <?php echo $product['special']; ?>
                                        <?php } else { ?>
                                        <?php echo $product['price']; ?>
                                        <?php } ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
</div>
<section class="content-bottom">
    <div class="container">
        <?php echo $content_bottom; ?>
    </div>
</section>
<?php echo $footer; ?>

<style>
/* =================================================
   PREMIUM PRODUCT NAME & SHORT DESCRIPTION
   ================================================= */
.product-name-premium {
    font-size: 42px;
    font-weight: 700;
    line-height: 1.2;
    color: #1a1a1a;
    margin: 0 0 24px 0;
    padding: 0;
    letter-spacing: -0.5px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
    text-transform: none;
    word-wrap: break-word;
    overflow-wrap: break-word;
    position: relative;
    display: block;
}

.product-name-premium::after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 0;
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #FF6A00 0%, #FF8533 100%);
    border-radius: 2px;
}

.product-short-description-premium {
    margin: 32px 0 28px 0;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-left: 4px solid #FF6A00;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
}

.product-short-description-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, #FF6A00 0%, transparent 100%);
}

.short-desc-text {
    display: block;
    color: #4a4a4a;
    font-size: 16px;
    line-height: 1.75;
    font-weight: 400;
    letter-spacing: 0.2px;
    text-align: left;
    margin: 0;
    padding: 0;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .product-name-premium {
        font-size: 38px;
        margin-bottom: 22px;
    }
    
    .product-short-description-premium {
        margin: 28px 0 24px 0;
        padding: 18px 22px;
    }
    
    .short-desc-text {
        font-size: 15px;
        line-height: 1.7;
    }
}

@media (max-width: 991px) {
    .product-name-premium {
        font-size: 34px;
        margin-bottom: 20px;
        letter-spacing: -0.3px;
    }
    
    .product-name-premium::after {
        width: 70px;
        height: 3px;
        bottom: -10px;
    }
    
    .product-short-description-premium {
        margin: 24px 0 20px 0;
        padding: 16px 20px;
        border-left-width: 3px;
    }
    
    .short-desc-text {
        font-size: 15px;
        line-height: 1.65;
    }
}

@media (max-width: 768px) {
    .product-name-premium {
        font-size: 28px;
        margin-bottom: 18px;
        letter-spacing: -0.2px;
    }
    
    .product-name-premium::after {
        width: 60px;
        height: 3px;
        bottom: -8px;
    }
    
    .product-short-description-premium {
        margin: 20px 0 18px 0;
        padding: 14px 18px;
        border-radius: 10px;
    }
    
    .short-desc-text {
        font-size: 14px;
        line-height: 1.6;
    }
}

@media (max-width: 480px) {
    .product-name-premium {
        font-size: 24px;
        margin-bottom: 16px;
        line-height: 1.3;
    }
    
    .product-name-premium::after {
        width: 50px;
        height: 2px;
        bottom: -6px;
    }
    
    .product-short-description-premium {
        margin: 18px 0 16px 0;
        padding: 12px 16px;
        border-left-width: 3px;
    }
    
    .short-desc-text {
        font-size: 13px;
        line-height: 1.55;
    }
}

.prx-cart-option {
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    margin-bottom: 18px;
    background: #fff;
}
.prx-price-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.prx-reward-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    background: #fff7e6;
    color: #b37400;
    font-size: 12px;
    font-weight: 600;
}
.prx-reward-pill i {
    color: #ff9800;
}
.prx-action-grid {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.prx-qty-control {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    background: #f9f9f9;
    border-radius: 999px;
    padding: 6px 16px;
    border: 1px solid #ededed;
}
.prx-qty-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #858585;
}
.prx-qty-btn.is-disabled {
    opacity: 0.35;
    pointer-events: none;
}
.prx-qty-control .qty input {
    width: 50px;
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 600;
}
.prx-cta-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}
.prx-btn {
    border: none;
    border-radius: 999px;
    padding: 12px 18px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.prx-btn--cart {
    background: #fff2e7;
    color: #ff6a00;
}
.prx-btn--buy {
    background: #000;
    color: #fff;
}
.prx-btn--compare {
    background: #f4f4f4;
    color: #333;
}
.prx-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.prx-btn:not(:disabled):hover {
    transform: translateY(-2px);
}
.prx-contact-grid {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    justify-content: flex-start;
}
.prx-contact-card {
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    width: 80px;
    height: 80px;
    position: relative;
    overflow: hidden;
}
.prx-contact-card:hover {
    border-color: #ff6a00;
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
.prx-contact-card--whatsapp {
    background: #f0fff5;
    border-color: rgba(37,211,102,0.3);
}
.prx-contact-card--whatsapp:hover {
    border-color: #25d366;
    box-shadow: 0 8px 20px rgba(37,211,102,0.3);
}
.prx-contact-card--call {
    background: #fff8f0;
    border-color: rgba(255,106,0,0.3);
}
.prx-contact-card--call:hover {
    border-color: #ff6a00;
    box-shadow: 0 8px 20px rgba(255,106,0,0.3);
}
.prx-contact-icon {
    width: 100%;
    height: 100%;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    position: relative;
    z-index: 1;
}
.prx-contact-icon i {
    display: block;
    line-height: 1;
}
.prx-contact-card--whatsapp .prx-contact-icon {
    background: rgba(37,211,102,0.15);
    color: #25d366;
}
.prx-contact-card--call .prx-contact-icon {
    background: rgba(255,106,0,0.15);
    color: #ff6a00;
}
/* Icon Animations */
.prx-icon-animated {
    animation: iconPulse 2s ease-in-out infinite;
}
.prx-contact-card:hover .prx-icon-animated {
    animation: iconBounce 0.6s ease-in-out;
}
@keyframes iconPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}
@keyframes iconBounce {
    0%, 100% {
        transform: scale(1) translateY(0);
    }
    25% {
        transform: scale(1.15) translateY(-5px);
    }
    50% {
        transform: scale(1.1) translateY(-2px);
    }
    75% {
        transform: scale(1.15) translateY(-5px);
    }
}
.prx-contact-label {
    display: none;
}
.prx-contact-value {
    display: none;
}
.prx-option-wrap {
    margin: 20px 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.prx-option-field {
    border: 1px solid #f1f1f1;
    border-radius: 12px;
    padding: 16px;
}
.prx-option-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.prx-option-name {
    font-weight: 600;
    font-size: 15px;
}
.prx-option-selection {
    font-size: 13px;
    color: #888;
}
.prx-option-values {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.prx-option-pill {
    border: 1px solid #e1e1e1;
    border-radius: 999px;
    padding: 6px 16px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}
.prx-option-pill input {
    display: none;
}
.prx-option-pill:hover {
    border-color: #ff6a00;
    color: #ff6a00;
}
.prx-option-pill.is-selected {
    border-color: #ff6a00;
    background: #fff5ed;
    color: #ff6a00;
}
.prx-option-pill.is-selected .prx-option-pill__label {
    font-weight: 600;
}
.prx-option-pill__label {
    display: inline-block;
}
.rpmini24_widget {
    border: 1px solid #f0f0f0;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.04);
    background: #fff;
}
.rpmini24_head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 16px;
}
.rpmini24_kicker {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #999;
    margin: 0 0 4px;
}
.rpmini24_title {
    margin: 0;
    font-size: 20px;
}
.rpmini24_viewall {
    font-size: 13px;
    color: #ff6a00;
    text-decoration: none;
    font-weight: 600;
}
.rpmini24_list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.rpmini24_card {
    display: flex;
    gap: 12px;
    text-decoration: none;
    color: inherit;
    padding: 10px;
    border-radius: 12px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}
.rpmini24_card:hover {
    border-color: #ffe1cf;
    background: #fff8f3;
}
.rpmini24_media {
    width: 70px;
    height: 70px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}
.rpmini24_media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.rpmini24_info h5 {
    margin: 0 0 6px;
    font-size: 14px;
    line-height: 1.3;
}
.rpmini24_price {
    display: flex;
    gap: 6px;
    align-items: baseline;
    font-weight: 600;
    color: #ff6a00;
}
.rpmini24_price-old {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
}

/* Compatible products */
.compatible-products-showcase {
    padding: 50px 0;
    background: #f9f9fb;
}
.compatible-products-showcase .product-card {
    border-radius: 12px;
    border: 1px solid #f0f0f0;
    overflow: hidden;
    background: #fff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.compatible-products-showcase .product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 32px rgba(16,24,40,0.08);
}
.compatible-products-showcase .product-thumb img {
    height: 190px;
    object-fit: cover;
}
.compatible-products-showcase .product-button-group .product-button {
    background: #111 !important;
}
.compatible-products-showcase .product-card-body {
    padding: 16px;
}
.compatible-products-showcase .product-price del {
    color: #9e9e9e;
    font-size: 13px;
}

@media (max-width: 768px) {
    .prx-cart-option {
        padding: 18px;
    }
    .prx-cta-buttons {
        grid-template-columns: 1fr;
    }
    .prx-contact-grid {
        gap: 10px;
    }
    .prx-contact-card {
        width: 70px;
        height: 70px;
    }
    .prx-contact-icon {
        font-size: 32px;
    }
}

/* Modern Attributes Styles */
.modern-attributes-wrapper {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.attribute-group-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.attribute-group-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.attribute-group-header {
    background: linear-gradient(135deg, #10503D 0%, #1a6b52 100%);
    padding: 18px 24px;
    border-bottom: 2px solid #0d3f2f;
}

.attribute-group-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.attribute-group-title i {
    font-size: 20px;
    opacity: 0.9;
}

.attribute-group-body {
    padding: 0;
}

.attribute-list {
    display: flex;
    flex-direction: column;
}

.attribute-item {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 16px;
    padding: 16px 24px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
    align-items: center;
}

.attribute-item:last-child {
    border-bottom: none;
}

.attribute-item:hover {
    background-color: #f8f9fa;
}

.attribute-item:nth-child(even) {
    background-color: #fafbfc;
}

.attribute-item:nth-child(even):hover {
    background-color: #f0f2f5;
}

.attribute-name {
    display: flex;
    align-items: center;
}

.attribute-label {
    font-weight: 600;
    font-size: 14px;
    color: #2c3e50;
    line-height: 1.5;
    position: relative;
    padding-left: 12px;
}

.attribute-label::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 4px;
    background: #A68A6A;
    border-radius: 50%;
}

.attribute-value {
    display: flex;
    align-items: center;
}

.attribute-text {
    font-size: 14px;
    color: #555;
    line-height: 1.6;
    word-break: break-word;
}

/* Responsive Design for Attributes */
@media (max-width: 768px) {
    .attribute-item {
        grid-template-columns: 1fr;
        gap: 8px;
        padding: 14px 18px;
    }
    
    .attribute-group-header {
        padding: 14px 18px;
    }
    
    .attribute-group-title {
        font-size: 16px;
    }
    
    .attribute-label {
        font-size: 13px;
        margin-bottom: 4px;
    }
    
    .attribute-text {
        font-size: 13px;
    }
}
</style>

<script>
var product_id = <?php echo $product_id; ?>;
fbq && fbq('track', 'ViewContent', {
    content_ids: ['<?php echo $product_id; ?>'],
    content_type: 'product',
    value: <?php echo $raw_price; ?>,
    currency: 'BDT'
});
</script>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("single-tab-details");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("nav-tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Set default active tab (Description)
    var defaultTab = document.querySelector('.nav-tab.active');
    if (defaultTab) {
        var tabName = defaultTab.getAttribute('onclick').match(/'([^']+)'/)[1];
        document.getElementById(tabName).style.display = "block";
    }


    const copyLinkElement = document.querySelector('.copy-link');

    if (copyLinkElement) {
        copyLinkElement.addEventListener('click', () => {
            const currentUrl = window.location.href;
            navigator.clipboard.writeText(currentUrl)
                .then(() => {
                    const copyMessage = document.createElement('div');
                    copyMessage.classList.add('copy-message');
                    copyMessage.textContent = 'Copied!';
                    copyLinkElement.appendChild(copyMessage);

                    setTimeout(() => {
                        copyLinkElement.removeChild(copyMessage);
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy:', err);
                });
        });
    }

    // Product Image Thumbnail Click Handler
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnailItems = document.querySelectorAll('.lux-thumb');
        const mainImage = document.getElementById('main-product-image');
        const mainImageLink = document.getElementById('main-image-link');
        
        thumbnailItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all thumbnails
                thumbnailItems.forEach(function(thumb) {
                    thumb.classList.remove('is-active');
                });
                
                // Add active class to clicked thumbnail
                this.classList.add('is-active');
                
                // Update main image
                const newImageSrc = this.getAttribute('data-image');
                const newPopupSrc = this.getAttribute('data-popup');
                
                if (mainImage && newImageSrc) {
                    mainImage.src = newImageSrc;
                }
                
                if (mainImageLink && newPopupSrc) {
                    mainImageLink.href = newPopupSrc;
                }
            });
        });

        const optionInputs = document.querySelectorAll('.prx-option-pill input');
        optionInputs.forEach(function(input) {
            const parentLabel = input.parentElement;
            if (input.checked) {
                parentLabel.classList.add('is-selected');
            }

            input.addEventListener('change', function() {
                const valuesWrapper = parentLabel.closest('.prx-option-values');
                if (valuesWrapper) {
                    valuesWrapper.querySelectorAll('.prx-option-pill').forEach(function(pill) {
                        pill.classList.remove('is-selected');
                    });
                }
                parentLabel.classList.add('is-selected');
            });
        });

        const qtyWrapper = document.querySelector('.prx-qty-control');
        const qtyInput = document.getElementById('input-quantity');

        if (qtyWrapper && qtyInput) {
            const minusBtn = qtyWrapper.querySelector('.prx-qty-btn--minus');
            const plusBtn = qtyWrapper.querySelector('.prx-qty-btn--plus');
            const minQty = parseInt(qtyWrapper.dataset.minQty, 10) || 1;

            const sanitizeValue = (val) => {
                const parsed = parseInt(val, 10);
                if (isNaN(parsed) || parsed < minQty) {
                    return minQty;
                }
                return parsed;
            };

            const syncButtonState = () => {
                const current = sanitizeValue(qtyInput.value);
                qtyInput.value = current;
                if (minusBtn) {
                    minusBtn.classList.toggle('is-disabled', current <= minQty);
                }
            };

            const updateQty = (delta) => {
                const current = sanitizeValue(qtyInput.value);
                const next = Math.max(minQty, current + delta);
                qtyInput.value = next;
                qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
                syncButtonState();
            };

            if (minusBtn) {
                minusBtn.addEventListener('click', function () {
                    updateQty(-1);
                });
            }

            if (plusBtn) {
                plusBtn.addEventListener('click', function () {
                    updateQty(1);
                });
            }

            qtyInput.addEventListener('input', function () {
                const safeValue = sanitizeValue(qtyInput.value);
                qtyInput.value = safeValue;
                syncButtonState();
            });

            syncButtonState();
        }

        // Initialize Owl Carousel for Related Products
        if (typeof jQuery !== 'undefined' && jQuery.fn.owlCarousel) {
            // Initialize Owl Carousel for Compatible Products
            var $compatibleSlider = jQuery('.compatible-products-slider');
            if ($compatibleSlider.length && $compatibleSlider.find('.slider-item').length > 0) {
                if ($compatibleSlider.data('owl.carousel')) {
                    $compatibleSlider.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
                }
                $compatibleSlider.addClass('owl-carousel').owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: false,
                    dots: false,
                    autoplay: false,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
                    responsive: {
                        0: {
                            items: 2,
                            margin: 6,
                            slideBy: 2
                        },
                        576: {
                            items: 3,
                            margin: 8,
                            slideBy: 2
                        },
                        768: {
                            items: 4,
                            margin: 10,
                            slideBy: 2
                        },
                        992: {
                            items: 5,
                            margin: 10
                        },
                        1200: {
                            items: 6,
                            margin: 10
                        }
                    }
                });
            }

        }
    });

</script>