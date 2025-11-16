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
                    <div class="images product-images">
                        <?php if ($thumb) { ?>
                        <div class="featured-image">
                            <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>">
                                <img class="main-image main-img" src="<?php echo $thumb; ?>" width="645" height="645" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
                            </a>
                            <meta itemprop="image"  content="<?php echo $thumb; ?>"/>
                        </div>
                        <?php } ?>
                        <?php if ($images) { ?>
                        <div class="thumbnails">
                            <?php foreach ($images as $image) { ?>
                            <a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>">
                                <img class="thumb-image" src="<?php echo $image['thumb']; ?>" width="70" height="70" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
                            </a>
                            <meta itemprop="image"  content="<?php echo $image['thumb']; ?>"/>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="product-info-all">
                        <div class="product-head-info">
                            <h1 itemprop="name" class="name"><?php echo $heading_title; ?></h1>

                            <div class="short-info">
                                <ul>
                                    <li> <b>Status:</b>  <span><?php echo $stock; ?></span></li>
                                    <li><b>Rating:</b>  <span><?php echo $rating; ?>/5.0 </span> <?php echo $reviews; ?></li>
                                    <?php if ($sku) { ?>
                                    <li> <b>SKU: </b><?php echo $sku; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <?php if ($options) { ?>
                        <div class="p-opt-wrap">
                            <?php foreach ($options as $option) { ?>
                            <?php if($option['type'] === 'select'){ ?>
                            <div class="p-opt color required">
                                <div class="p-opt-lbl" id="input-option<?php echo $option['product_option_id']; ?>">  <?php echo $option['name']; ?>:  <b></b></div>
                                <div class="p-opt-vals">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <label><input class="hide" type="radio" value="<?php echo $option_value['option_value_id']; ?>"  name="option[<?php echo $option['product_option_id']; ?>]" title="<?php echo $option_value['name']; ?>"><span><?php echo $option_value['name']; ?></span></label>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="p-opt required">
                                <div class="p-opt-lbl" id="input-option<?php echo $option['product_option_id']; ?>">Select <?php echo $option['name']; ?>:  <b></b></div>
                                <div class="p-opt-vals">
                                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                                    <label><input class="hide" type="radio" value="<?php echo $option_value['option_value_id']; ?>"  name="option[<?php echo $option['product_option_id']; ?>]" title="<?php echo $option_value['name']; ?>"><span><?php echo $option_value['name']; ?></span></label>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php }  ?>

                            <?php } ?>
                        </div>
                        <?php } ?>

                        <div class="cart-option" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                            <link itemprop="availability" href="http://schema.org/<?php echo $stock_meta; ?>"/><link itemprop="itemCondition" href="http://schema.org/NewCondition">
                            <meta itemprop="priceCurrency" content="BDT" /><meta itemprop="price" content="<?php echo $raw_price; ?>" />

                            <div class="price-wrap">
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
                            </div>
                            <div class="control-options">
                                <div class="quantity">
                                    <span><i class="material-icons">remove</i></span>
                                    <span class="qty"><input type="text" name="quantity" id="input-quantity" value="<?php echo $minimum; ?>" size="2"></span>
                                    <span  class="increment"><i class="material-icons">add</i></span>
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                                </div>
                                <button id="button-cart" class="btn" <?php echo $disablePurchase ? "disabled" : ""; ?>> Buy Now</button>
                            </div>

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
                        <li class="nav-tab" onclick="openTab(event, 'product-video')">Product Video</li>
                        <li class="nav-tab" onclick="openTab(event, 'write-review')"><?php echo $tab_review; ?></li>
                    </ul>

                    <div class="single-tab-details product-description" id="description" style="display: block;">
                        <div itemprop="description" class="seo-description"><?php echo $description ?></div>
                    </div>

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
                        <div id="question"><?php echo $question; ?></div>
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
                        <div id="review" class="mb-3"><?php echo $review; ?></div>
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
                    <?php
                    if ($products) {
                    ?>

                   <div class="related-products-head mb-3" style="display: flex; align-items: center; justify-content: space-between;">
                       <h4 class="related-product-title" style="margin: 0;">More Products</h4>
                       <div class="more-products-nav" style="display: flex; gap: 8px;">
                           <button type="button" class="more-products-prev" style="background: transparent; border: 1px solid #ddd; padding: 5px 10px; cursor: pointer; border-radius: 3px;"><i class="fa fa-chevron-left"></i></button>
                           <button type="button" class="more-products-next" style="background: transparent; border: 1px solid #ddd; padding: 5px 10px; cursor: pointer; border-radius: 3px;"><i class="fa fa-chevron-right"></i></button>
                       </div>
                   </div>
                    <div class="related-product-list-wrapper">
                        <?php foreach ($products as $product) { ?>
                        <a href="<?php echo $product['href']; ?>" class="special-product-item">
                            <div class="img">
                                <img
                                        src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"
                                        width="120"
                                        height="120"
                                />
                            </div>
                            <div class="info">
                                <?php if ($product['special']) { ?>
                                <?php
                                  $price = floatval(str_replace(['৳', ','], '', $product['price']));
                                  $special = floatval(str_replace(['৳', ','], '', $product['special']));
                                  $discountAmount = $price - $special;
                                  $mark = ($discountAmount / $price) * 100;
                                ?>
                                <div class="mark"><?php echo round($mark, 1); ?>% OFF </div>
                                <?php } ?>
                                <h5 class="name"><?php echo $product['name']; ?></h5>
                                <div class="product-price-wrap">
                                    <?php if ($product['special']) { ?>
                                    <span class="price"><?php echo $product['special']; ?></span>
                                    <span class="price old"><?php echo $product['price']; ?></span>
                                    <?php } else { ?>
                                    <span class="price"><?php echo $product['price']; ?></span>
                                    <?php } ?>

                                </div>
                            </div>
                        </a>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>
<section class="content-bottom">
    <div class="container">
        <?php echo $content_bottom; ?>
    </div>
</section>
<?php echo $footer; ?>
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

</script>