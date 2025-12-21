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
    <div class="alert alert-success premium-alert">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
    </div>
</div>
<?php } ?>

<div class="container account-page my-account premium-account">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="premium-account-wrapper">
            <!-- My Account Section -->
            <div class="premium-account-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle"></i>
                        <?php echo $text_my_account; ?>
                    </h2>
                </div>
                <div class="premium-cards-grid">
                    <a href="<?php echo $edit; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_edit; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="<?php echo $password; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_password; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="<?php echo $address; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_address; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="<?php echo $wishlist; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_wishlist; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- My Orders Section -->
            <div class="premium-account-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-shopping-bag"></i>
                        <?php echo $text_my_orders; ?>
                    </h2>
                </div>
                <div class="premium-cards-grid">
                    <a href="<?php echo $order; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_order; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="<?php echo $download; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_download; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <?php if ($reward) { ?>
                    <a href="<?php echo $reward; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_reward; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    <?php } ?>
                    
                    <a href="<?php echo $return; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_return; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="<?php echo $transaction; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_transaction; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="premium-account-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-envelope"></i>
                        <?php echo $text_my_newsletter; ?>
                    </h2>
                </div>
                <div class="premium-cards-grid">
                    <a href="<?php echo $newsletter; ?>" class="premium-card">
                        <div class="card-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $text_newsletter; ?></h3>
                        </div>
                        <div class="card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>
