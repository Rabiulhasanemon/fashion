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
<div class="container account-page my-account">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="my-info">
            <h2><?php echo $text_my_account; ?></h2>
            <ul class="list-unstyled">
                <li><a href="<?php echo $edit; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/account-info.png"/></span><?php echo $text_edit; ?></a></li>
                <li><a href="<?php echo $password; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/password.png"/></span><?php echo $text_password; ?></a></li>
                <li><a href="<?php echo $address; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/address.png"/></span><?php echo $text_address; ?></a></li>
                <li><a href="<?php echo $wishlist; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/wishlist.png"/></span><?php echo $text_wishlist; ?></a></li>
            </ul>
            <h2><?php echo $text_my_orders; ?></h2>
            <ul class="list-unstyled">
                <li><a href="<?php echo $order; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/order-history.png"/></span><?php echo $text_order; ?></a></li>
                <li><a href="<?php echo $download; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/downloads.png"/></span><?php echo $text_download; ?></a></li>
                <?php if ($reward) { ?>
                <li><a href="<?php echo $reward; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/reward-point.png"/></span><?php echo $text_reward; ?></a></li>
                <?php } ?>
                <li><a href="<?php echo $return; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/return.png"/></span><?php echo $text_return; ?></a></li>
                <li><a href="<?php echo $transaction; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/transaction.png"/></span><?php echo $text_transaction; ?></a></li>
            </ul>
            <h2><?php echo $text_my_newsletter; ?></h2>
            <ul class="list-unstyled">
                <li><a href="<?php echo $newsletter; ?>"><span><img class="c-icon" src="catalog/view/theme/ribana/icons/account/subscribe.png"/></span><?php echo $text_newsletter; ?></a></li>
            </ul>
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>
<?php echo $footer; ?>