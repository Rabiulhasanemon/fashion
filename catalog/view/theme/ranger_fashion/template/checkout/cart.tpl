<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
</section>

<!-- New Cart Page Container -->
<div class="ctp-new-container">
    <!-- Alert Messages -->
    <div class="ctp-new-alert-wrapper">
        <?php if ($attention) { ?>
        <div class="ctp-new-alert ctp-new-alert-info">
            <i class="fa fa-info-circle"></i> 
            <span><?php echo $attention; ?></span>
            <button type="button" class="ctp-new-alert-close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (isset($success) && $success) { ?>
        <div class="ctp-new-alert ctp-new-alert-success">
            <i class="fa fa-check-circle"></i> 
            <span><?php echo $success; ?></span>
            <button type="button" class="ctp-new-alert-close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($error_warning) { ?>
        <div class="ctp-new-alert ctp-new-alert-danger">
            <i class="fa fa-exclamation-circle"></i> 
            <span><?php echo $error_warning; ?></span>
            <button type="button" class="ctp-new-alert-close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
    </div>

    <div class="container">
        <div class="row">
            <?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            
            <div id="content" class="<?php echo $class; ?>">
                <?php echo $content_top; ?>
                
                <!-- Page Title -->
                <div class="ctp-new-header">
                    <h1 class="ctp-new-title">
                        <?php echo $heading_title; ?>
                        <?php if ($weight) { ?>
                        <span class="ctp-new-weight">(<?php echo $weight; ?>)</span>
                        <?php } ?>
                    </h1>
                </div>

                <!-- Cart Items -->
                <form action="<?php echo isset($action) ? $action : ''; ?>" method="post" enctype="multipart/form-data">
                    <div class="ctp-new-cart-wrapper">
                        <!-- Desktop Table View -->
                        <div class="ctp-new-table-wrapper">
                            <table class="ctp-new-table">
                                <thead>
                                    <tr>
                                        <th class="ctp-new-th-image"><?php echo $column_image; ?></th>
                                        <th class="ctp-new-th-name"><?php echo $column_name; ?></th>
                                        <th class="ctp-new-th-qty"><?php echo $column_quantity; ?></th>
                                        <th class="ctp-new-th-price"><?php echo $column_price; ?></th>
                                        <th class="ctp-new-th-total"><?php echo $column_total; ?></th>
                                        <th class="ctp-new-th-action"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product) { ?>
                                    <tr class="ctp-new-item-row">
                                        <td class="ctp-new-td-image">
                                            <?php if ($product['thumb']) { ?>
                                            <a href="<?php echo $product['href']; ?>" class="ctp-new-image-link">
                                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="ctp-new-product-image" />
                                            </a>
                                            <?php } ?>
                                        </td>
                                        <td class="ctp-new-td-name">
                                            <a href="<?php echo $product['href']; ?>" class="ctp-new-product-name"><?php echo $product['name']; ?></a>
                                            <?php if (!$product['stock']) { ?>
                                            <span class="ctp-new-stock-warning">*** <?php echo $error_stock ? $error_stock : 'Out of Stock'; ?></span>
                                            <?php } ?>
                                            <?php if ($product['option']) { ?>
                                            <div class="ctp-new-options">
                                                <?php foreach ($product['option'] as $option) { ?>
                                                <div class="ctp-new-option-item">
                                                    <span class="ctp-new-option-name"><?php echo $option['name']; ?>:</span>
                                                    <span class="ctp-new-option-value"><?php echo $option['value']; ?></span>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                            <?php if ($product['reward']) { ?>
                                            <div class="ctp-new-reward">
                                                <i class="fa fa-gift"></i> <?php echo $product['reward']; ?>
                                            </div>
                                            <?php } ?>
                                        </td>
                                        <td class="ctp-new-td-qty">
                                            <div class="ctp-new-qty-wrapper">
                                                <input type="number" 
                                                       name="quantity[<?php echo $product['key']; ?>]" 
                                                       value="<?php echo $product['quantity']; ?>" 
                                                       min="1" 
                                                       class="ctp-new-qty-input" />
                                                <button type="submit" 
                                                        data-toggle="tooltip" 
                                                        title="<?php echo $button_update; ?>" 
                                                        class="ctp-new-btn-update">
                                                    <i class="fa fa-refresh"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="ctp-new-td-price">
                                            <?php 
                                            // Calculate discount for cart display
                                            $cart_discount_percent = 0;
                                            $cart_discount_amount = 0;
                                            $cart_discount_amount_formatted = '';
                                            if (isset($product['special']) && $product['special']) {
                                                $p = floatval(str_replace(['৳', ',', ' ', 'TK', 'tk'], '', $product['price']));
                                                $s = floatval(str_replace(['৳', ',', ' ', 'TK', 'tk'], '', $product['special']));
                                                $cart_discount_amount = $p - $s;
                                                if ($p > 0) {
                                                    $cart_discount_percent = round(($cart_discount_amount / $p) * 100);
                                                }
                                                $cart_discount_amount_formatted = '৳' . number_format($cart_discount_amount, 2, '.', '');
                                            }
                                            ?>
                                            <?php if (isset($product['special']) && $product['special']) { ?>
                                            <div class="ctp-new-price-wrapper">
                                                <div class="ctp-new-price-current"><?php echo $product['special']; ?></div>
                                                <div class="ctp-new-price-old"><?php echo $product['price']; ?></div>
                                                <?php if ($cart_discount_percent > 0) { ?>
                                                <div class="ctp-new-discount-badge"><?php echo $cart_discount_percent; ?>% OFF</div>
                                                <?php } ?>
                                            </div>
                                            <?php } else { ?>
                                            <div class="ctp-new-price-wrapper">
                                                <div class="ctp-new-price-current"><?php echo $product['price']; ?></div>
                                            </div>
                                            <?php } ?>
                                        </td>
                                        <td class="ctp-new-td-total">
                                            <div class="ctp-new-total-price"><?php echo $product['total']; ?></div>
                                        </td>
                                        <td class="ctp-new-td-action">
                                            <button type="button" 
                                                    data-toggle="tooltip" 
                                                    title="<?php echo $button_remove; ?>" 
                                                    class="ctp-new-btn-remove" 
                                                    onclick="cart.remove('<?php echo $product['key']; ?>', true);">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php foreach ($vouchers as $voucher) { ?>
                                    <tr class="ctp-new-item-row">
                                        <td class="ctp-new-td-image"></td>
                                        <td class="ctp-new-td-name">
                                            <div class="ctp-new-product-name"><?php echo $voucher['description']; ?></div>
                                        </td>
                                        <td class="ctp-new-td-qty">
                                            <div class="ctp-new-qty-wrapper">
                                                <input type="text" value="1" disabled="disabled" class="ctp-new-qty-input" />
                                            </div>
                                        </td>
                                        <td class="ctp-new-td-price">
                                            <div class="ctp-new-price-wrapper">
                                                <div class="ctp-new-price-current"><?php echo $voucher['amount']; ?></div>
                                            </div>
                                        </td>
                                        <td class="ctp-new-td-total">
                                            <div class="ctp-new-total-price"><?php echo $voucher['amount']; ?></div>
                                        </td>
                                        <td class="ctp-new-td-action">
                                            <button type="button" 
                                                    data-toggle="tooltip" 
                                                    title="<?php echo $button_remove; ?>" 
                                                    class="ctp-new-btn-remove" 
                                                    onclick="voucher.remove('<?php echo $voucher['key']; ?>');">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="ctp-new-mobile-cards">
                            <?php foreach ($products as $product) { ?>
                            <div class="ctp-new-mobile-card">
                                <div class="ctp-new-mobile-card-header">
                                    <a href="<?php echo $product['href']; ?>" class="ctp-new-mobile-image-link">
                                        <?php if ($product['thumb']) { ?>
                                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="ctp-new-mobile-image" />
                                        <?php } ?>
                                    </a>
                                    <button type="button" 
                                            class="ctp-new-mobile-remove" 
                                            onclick="cart.remove('<?php echo $product['key']; ?>', true);">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="ctp-new-mobile-card-body">
                                    <a href="<?php echo $product['href']; ?>" class="ctp-new-mobile-name"><?php echo $product['name']; ?></a>
                                    <?php if (!$product['stock']) { ?>
                                    <div class="ctp-new-mobile-stock-warning">*** <?php echo $error_stock ? $error_stock : 'Out of Stock'; ?></div>
                                    <?php } ?>
                                    <?php if ($product['option']) { ?>
                                    <div class="ctp-new-mobile-options">
                                        <?php foreach ($product['option'] as $option) { ?>
                                        <div class="ctp-new-mobile-option">
                                            <span><?php echo $option['name']; ?>: <?php echo $option['value']; ?></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                    <div class="ctp-new-mobile-price-section">
                                        <?php if (isset($product['special']) && $product['special']) { ?>
                                        <div class="ctp-new-mobile-price-wrapper">
                                            <div class="ctp-new-mobile-price-current"><?php echo $product['special']; ?></div>
                                            <div class="ctp-new-mobile-price-old"><?php echo $product['price']; ?></div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="ctp-new-mobile-price-wrapper">
                                            <div class="ctp-new-mobile-price-current"><?php echo $product['price']; ?></div>
                                        </div>
                                        <?php } ?>
                                        <div class="ctp-new-mobile-total">
                                            <span class="ctp-new-mobile-total-label">Total:</span>
                                            <span class="ctp-new-mobile-total-value"><?php echo $product['total']; ?></span>
                                        </div>
                                    </div>
                                    <div class="ctp-new-mobile-qty-section">
                                        <label class="ctp-new-mobile-qty-label">Quantity:</label>
                                        <div class="ctp-new-mobile-qty-wrapper">
                                            <input type="number" 
                                                   name="quantity[<?php echo $product['key']; ?>]" 
                                                   value="<?php echo $product['quantity']; ?>" 
                                                   min="1" 
                                                   class="ctp-new-mobile-qty-input" />
                                            <button type="submit" class="ctp-new-mobile-btn-update">
                                                <i class="fa fa-refresh"></i> Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>

                <!-- Order Summary -->
                <div class="ctp-new-summary-wrapper">
                    <div class="ctp-new-summary-card">
                        <h3 class="ctp-new-summary-title">Order Summary</h3>
                        <div class="ctp-new-summary-table">
                            <?php foreach ($totals as $total) { ?>
                            <div class="ctp-new-summary-row <?php echo (isset($text_total) && $total['title'] == $text_total) ? 'ctp-new-summary-total' : ''; ?>">
                                <span class="ctp-new-summary-label"><?php echo $total['title']; ?>:</span>
                                <span class="ctp-new-summary-value"><?php echo $total['text']; ?></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Coupon & Voucher Section -->
                <?php if ($coupon || $voucher || $reward || $shipping) { ?>
                <div class="ctp-new-promo-wrapper">
                    <div class="row">
                        <?php if ($coupon) { ?>
                        <div class="col-md-6 col-sm-12 ctp-new-promo-item">
                            <?php echo $coupon; ?>
                        </div>
                        <?php } ?>
                        <?php if ($voucher) { ?>
                        <div class="col-md-6 col-sm-12 ctp-new-promo-item">
                            <?php echo $voucher; ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

                <!-- Action Buttons -->
                <div class="ctp-new-actions">
                    <a href="<?php echo $continue; ?>" class="ctp-new-btn ctp-new-btn-continue">
                        <i class="fa fa-arrow-left"></i> <?php echo $button_shopping; ?>
                    </a>
                    <a href="<?php echo $checkout; ?>" class="ctp-new-btn ctp-new-btn-checkout">
                        <?php echo $button_checkout; ?> <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <?php echo $content_bottom; ?>
            </div>
            <?php echo $column_right; ?>
        </div>
    </div>
</div>

<?php echo $footer; ?>

<style>
/* =================================================
   NEW CART PAGE STYLES - NO CONFLICTS
   All classes prefixed with ctp-new-
   ================================================= */

/* Container */
.ctp-new-container {
    background: #f8f9fa;
    min-height: 60vh;
    padding: 20px 0 40px;
}

/* Alert Messages */
.ctp-new-alert-wrapper {
    max-width: 1200px;
    margin: 0 auto 20px;
    padding: 0 15px;
}

.ctp-new-alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.ctp-new-alert i {
    font-size: 18px;
    flex-shrink: 0;
}

.ctp-new-alert span {
    flex: 1;
}

.ctp-new-alert-close {
    background: none;
    border: none;
    font-size: 24px;
    color: inherit;
    opacity: 0.6;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    flex-shrink: 0;
}

.ctp-new-alert-close:hover {
    opacity: 1;
}

.ctp-new-alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.ctp-new-alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.ctp-new-alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Page Header */
.ctp-new-header {
    margin-bottom: 30px;
    padding: 0 15px;
}

.ctp-new-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.ctp-new-weight {
    font-size: 20px;
    font-weight: 400;
    color: #666;
}

/* Cart Wrapper */
.ctp-new-cart-wrapper {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

/* Desktop Table */
.ctp-new-table-wrapper {
    display: block;
    overflow-x: auto;
}

.ctp-new-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.ctp-new-table thead {
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
}

.ctp-new-th-image,
.ctp-new-th-name,
.ctp-new-th-qty,
.ctp-new-th-price,
.ctp-new-th-total,
.ctp-new-th-action {
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ctp-new-th-image {
    width: 120px;
    text-align: center;
}

.ctp-new-th-name {
    width: auto;
    min-width: 200px;
}

.ctp-new-th-qty {
    width: 150px;
    text-align: center;
}

.ctp-new-th-price {
    width: 180px;
    text-align: right;
}

.ctp-new-th-total {
    width: 120px;
    text-align: right;
}

.ctp-new-th-action {
    width: 60px;
    text-align: center;
}

.ctp-new-item-row {
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s ease;
}

.ctp-new-item-row:hover {
    background: #f8f9fa;
}

.ctp-new-item-row:last-child {
    border-bottom: none;
}

.ctp-new-td-image,
.ctp-new-td-name,
.ctp-new-td-qty,
.ctp-new-td-price,
.ctp-new-td-total,
.ctp-new-td-action {
    padding: 20px 12px;
    vertical-align: middle;
}

.ctp-new-image-link {
    display: block;
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    margin: 0 auto;
}

.ctp-new-product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 8px;
}

.ctp-new-product-name {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    text-decoration: none;
    display: block;
    margin-bottom: 8px;
    line-height: 1.4;
    transition: color 0.2s ease;
}

.ctp-new-product-name:hover {
    color: #A68A6A;
}

.ctp-new-stock-warning {
    display: inline-block;
    color: #dc3545;
    font-size: 13px;
    font-weight: 500;
    margin-top: 4px;
}

.ctp-new-options {
    margin-top: 8px;
}

.ctp-new-option-item {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.ctp-new-option-name {
    font-weight: 500;
}

.ctp-new-option-value {
    color: #333;
}

.ctp-new-reward {
    font-size: 13px;
    color: #28a745;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.ctp-new-qty-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}

.ctp-new-qty-input {
    width: 80px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-align: center;
    font-size: 15px;
    font-weight: 500;
}

.ctp-new-qty-input:focus {
    outline: none;
    border-color: #A68A6A;
    box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.1);
}

.ctp-new-btn-update {
    padding: 10px 14px;
    background: #FF6A00;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ctp-new-btn-update:hover {
    background: #ff8533;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 106, 0, 0.3);
}

.ctp-new-price-wrapper {
    text-align: right;
}

.ctp-new-price-current {
    font-size: 18px;
    font-weight: 700;
    color: #A68A6A;
    margin-bottom: 4px;
}

.ctp-new-price-old {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
    margin-bottom: 6px;
}

.ctp-new-discount-badge {
    display: inline-block;
    background: #9c27b0;
    color: #ffffff;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
}

.ctp-new-total-price {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    text-align: right;
}

.ctp-new-btn-remove {
    padding: 10px 14px;
    background: #dc3545;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.ctp-new-btn-remove:hover {
    background: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

/* Mobile Cards */
.ctp-new-mobile-cards {
    display: none;
}

.ctp-new-mobile-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.ctp-new-mobile-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 12px;
}

.ctp-new-mobile-image-link {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    flex-shrink: 0;
}

.ctp-new-mobile-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 8px;
}

.ctp-new-mobile-remove {
    padding: 8px;
    background: #dc3545;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ctp-new-mobile-card-body {
    flex: 1;
}

.ctp-new-mobile-name {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    text-decoration: none;
    display: block;
    margin-bottom: 8px;
    line-height: 1.4;
}

.ctp-new-mobile-stock-warning {
    color: #dc3545;
    font-size: 13px;
    margin-bottom: 8px;
}

.ctp-new-mobile-options {
    margin-bottom: 12px;
}

.ctp-new-mobile-option {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.ctp-new-mobile-price-section {
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e9ecef;
}

.ctp-new-mobile-price-wrapper {
    margin-bottom: 8px;
}

.ctp-new-mobile-price-current {
    font-size: 18px;
    font-weight: 700;
    color: #A68A6A;
    margin-bottom: 4px;
}

.ctp-new-mobile-price-old {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
}

.ctp-new-mobile-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
}

.ctp-new-mobile-total-label {
    font-size: 14px;
    font-weight: 600;
    color: #666;
}

.ctp-new-mobile-total-value {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
}

.ctp-new-mobile-qty-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.ctp-new-mobile-qty-label {
    font-size: 14px;
    font-weight: 600;
    color: #666;
}

.ctp-new-mobile-qty-wrapper {
    display: flex;
    gap: 8px;
}

.ctp-new-mobile-qty-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-align: center;
    font-size: 15px;
    font-weight: 500;
}

.ctp-new-mobile-btn-update {
    padding: 10px 16px;
    background: #A68A6A;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.ctp-new-mobile-btn-update:hover {
    background: #ff8533;
}

/* Order Summary */
.ctp-new-summary-wrapper {
    margin-bottom: 30px;
}

.ctp-new-summary-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.ctp-new-summary-title {
    font-size: 22px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 20px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #e9ecef;
}

.ctp-new-summary-table {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.ctp-new-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.ctp-new-summary-row:last-child {
    border-bottom: none;
}

.ctp-new-summary-total {
    border-top: 2px solid #e9ecef;
    border-bottom: none !important;
    padding-top: 16px;
    margin-top: 8px;
}

.ctp-new-summary-label {
    font-size: 16px;
    color: #666;
    font-weight: 500;
}

.ctp-new-summary-total .ctp-new-summary-label {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
}

.ctp-new-summary-value {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
}

.ctp-new-summary-total .ctp-new-summary-value {
    font-size: 24px;
    font-weight: 700;
    color: #A68A6A;
}

/* Promo Section */
.ctp-new-promo-wrapper {
    margin-bottom: 30px;
}

.ctp-new-promo-item {
    margin-bottom: 20px;
}

/* Action Buttons */
.ctp-new-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    flex-wrap: wrap;
    padding: 0 15px;
}

.ctp-new-btn {
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    min-width: 160px;
    justify-content: center;
}

.ctp-new-btn-continue {
    background: #ffffff;
    color: #666;
    border: 2px solid #ddd;
}

.ctp-new-btn-continue:hover {
    background: #f8f9fa;
    border-color: #bbb;
    color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.ctp-new-btn-checkout {
    background: linear-gradient(135deg, #A68A6A 0%, #c4a882 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.3);
}

.ctp-new-btn-checkout:hover {
    background: linear-gradient(135deg, #c4a882 0%, #A68A6A 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 106, 0, 0.4);
}

/* Responsive Design */
@media (max-width: 991px) {
    .ctp-new-title {
        font-size: 28px;
    }
    
    .ctp-new-table-wrapper {
        display: none;
    }
    
    .ctp-new-mobile-cards {
        display: block;
        padding: 16px;
    }
    
    .ctp-new-actions {
        flex-direction: column;
    }
    
    .ctp-new-btn {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .ctp-new-container {
        padding: 15px 0 30px;
    }
    
    .ctp-new-title {
        font-size: 24px;
    }
    
    .ctp-new-summary-card {
        padding: 20px;
    }
    
    .ctp-new-summary-title {
        font-size: 20px;
    }
    
    .ctp-new-summary-total .ctp-new-summary-value {
        font-size: 22px;
    }
}

@media (max-width: 480px) {
    .ctp-new-title {
        font-size: 20px;
    }
    
    .ctp-new-mobile-card {
        padding: 12px;
    }
    
    .ctp-new-mobile-image-link {
        width: 80px;
        height: 80px;
    }
    
    .ctp-new-mobile-name {
        font-size: 14px;
    }
    
    .ctp-new-summary-card {
        padding: 16px;
    }
}
</style>
