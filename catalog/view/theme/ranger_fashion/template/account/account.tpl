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

<?php if ($success) { ?>
<div class="container">
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
</div>
<?php } ?>

<div class="container account-modern-page">
    <?php echo $column_left; ?>
    <div id="content" class="content account-modern-content">
        <!-- My Account Section -->
        <div class="account-modern-section">
            <h2 class="account-modern-heading"><?php echo $text_my_account; ?></h2>
            <div class="account-modern-grid account-modern-grid-2x2">
                <a href="<?php echo $edit; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_edit; ?></div>
                </a>
                
                <a href="<?php echo $password; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_password; ?></div>
                </a>
                
                <a href="<?php echo $address; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_address; ?></div>
                </a>
                
                <a href="<?php echo $wishlist; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_wishlist; ?></div>
                </a>
            </div>
        </div>

        <!-- My Orders Section -->
        <div class="account-modern-section">
            <h2 class="account-modern-heading"><?php echo $text_my_orders; ?></h2>
            <div class="account-modern-grid account-modern-grid-row">
                <a href="<?php echo $order; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_order; ?></div>
                </a>
                
                <a href="<?php echo $download; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_download; ?></div>
                </a>
                
                <?php if ($reward) { ?>
                <a href="<?php echo $reward; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_reward; ?></div>
                </a>
                <?php } ?>
                
                <?php /* Hidden per request
                <a href="<?php echo $return; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_return; ?></div>
                </a>
                
                <a href="<?php echo $transaction; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_transaction; ?></div>
                </a>
                */ ?>
            </div>
        </div>

        <?php /* Hidden per request - Newsletter Section
        <div class="account-modern-section">
            <h2 class="account-modern-heading"><?php echo $text_my_newsletter; ?></h2>
            <div class="account-modern-grid account-modern-grid-row">
                <a href="<?php echo $newsletter; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_newsletter; ?></div>
                </a>
            </div>
        </div>
        */ ?>
        
        <?php if (isset($is_vendor) && $is_vendor) { ?>
        <!-- Vendor Section -->
        <div class="account-modern-section">
            <h2 class="account-modern-heading">Vendor Dashboard</h2>
            <div class="account-modern-grid account-modern-grid-row">
                <a href="<?php echo $vendor_dashboard; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="account-modern-text">Vendor Dashboard</div>
                    <?php if (isset($vendor_status) && $vendor_status == 'pending') { ?>
                    <small style="color: orange;">Pending Approval</small>
                    <?php } elseif (isset($vendor_status) && $vendor_status == 'approved') { ?>
                    <small style="color: green;">Active</small>
                    <?php } ?>
                </a>
            </div>
        </div>
        <?php } elseif (isset($vendor_register)) { ?>
        <div class="account-modern-section">
            <h2 class="account-modern-heading">Become a Vendor</h2>
            <div class="account-modern-grid account-modern-grid-row">
                <a href="<?php echo $vendor_register; ?>" class="account-modern-card">
                    <div class="account-modern-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="account-modern-text">Register as Vendor</div>
                </a>
            </div>
        </div>
        <?php } ?>
        
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>
