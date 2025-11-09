<?php
// Heading
$_['heading_title']            = 'Shopping Cart';

// Text
$_['text_success']             =  'You have added <a href="%s">%s</a> to your shopping cart!';
$_['text_success_all']             =  'You have added %s to your shopping cart!';

$_['text_success_popup']             =  '<div class="msg-wrapper">' .
    '<div class="msg-details">' .
    '<i class="material-icons" aria-hidden="true">check_circle_outline</i>' .
    '<div class="success-msg">You have added <a href="%s">%s</a> to your shopping cart!</div>' .
    '<div class="cart-info">' .
    '<span class="cart-qty">Cart Quantity: <span class="value">%s</span></span>'.
    '<span class="cart-total">Cart Total: <span class="value">%s</span></span>'.
    '</div>'.
    '</div>'.
    '<div class="btn-wrapper">' .
    '<a href="%s"><button class="btn">View Cart</button></a>'.
    '<a class="checkout-btn" href="%s"><button class="btn st-outline">Confirm Order</button></a>'.
    '</div>'.
    '</div>';
$_['text_success_popup_all']             =  '<div class="msg-wrapper">' .
    '<div class="msg-details">' .
    '<i class="material-icons" aria-hidden="true">check_circle_outline</i>' .
    '<div class="success-msg">You have added %s to your shopping cart!</div>' .
    '<div class="cart-info">' .
    '<span class="cart-qty">Cart Quantity: <span class="value">%s</span></span>'.
    '<span class="cart-total">Cart Total: <span class="value">%s</span></span>'.
    '</div>'.
    '</div>'.
    '<div class="btn-wrapper">' .
    '<a href="%s"><button class="btn">View Cart</button></a>'.
    '<a class="checkout-btn" href="%s"><button class="btn st-outline">Confirm Order</button></a>'.
    '</div>'.
    '</div>';

$_['text_remove']              = 'Success: You have modified your shopping cart!';
$_['text_login']               = 'Attention: You must <a href="%s">login</a> or <a href="%s">create an account</a> to view prices!';
$_['text_items']               = '%s item(s)';
$_['text_points']              = 'Reward Points: %s';
$_['text_next']                = 'What would you like to do next?';
$_['text_next_choice']         = 'Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.';
$_['text_empty']               = 'Your shopping cart is empty!';
$_['text_day']                 = 'day';
$_['text_week']                = 'week';
$_['text_semi_month']          = 'half-month';
$_['text_month']               = 'month';
$_['text_year']                = 'year';
$_['text_trial']               = '%s every %s %s for %s payments then ';
$_['text_length']              = ' for %s payments';
$_['text_until_cancelled']     = 'until cancelled';
$_['text_trial_description']   = '%s every %d %s(s) for %d payment(s) then';
$_['text_payment_description'] = '%s every %d %s(s) for %d payment(s)';
$_['text_payment_cancel']      = '%s every %d %s(s) until canceled';

// Column
$_['column_image']             = 'Image';
$_['column_name']              = 'Product Name';
$_['column_model']             = 'Model';
$_['column_quantity']          = 'Quantity';
$_['column_price']             = 'Unit Price';
$_['column_total']             = 'Total';

// Error
$_['error_stock']              = 'Products marked with *** are not available in the desired quantity or not in stock!';
$_['error_variation']          = 'The selected variation is currently unavailable!';
$_['error_product_stock']             = 'Products not available in the desired quantity or not in stock!';
$_['error_minimum']            = 'Minimum order amount for %s is %s!';
$_['error_maximum']    = 'Maximum order quantity for %s is %s!';
$_['error_required']           = '%s required!';
$_['error_product']            = 'Warning: There are no products in your cart!';
$_['error_emi_1']              = 'Error! Some non EMI product on cart. Please complete the current cart.';
$_['error_emi_2']              = 'Error! Some EMI product on cart. Please complete the current cart.';