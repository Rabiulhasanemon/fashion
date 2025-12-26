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

<?php if (isset($error_warning) && $error_warning) { ?>
<div class="container">
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
</div>
<?php } ?>

<div class="container account-modern-page">
    <?php echo $column_left; ?>
    <div id="content" class="content account-modern-content">
        <div class="account-modern-section">
            <h2 class="account-modern-heading"><?php echo $heading_title; ?></h2>
            
            <?php if (isset($vendor) && $vendor['status'] != 'approved') { ?>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> <?php echo $text_pending_review; ?>
            </div>
            <?php } ?>
            
            <div class="account-modern-grid account-modern-grid-2x2" style="margin-bottom: 30px;">
                <div class="account-modern-card" style="text-align: center;">
                    <div class="account-modern-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_products; ?></div>
                    <div class="h2" style="margin-top: 10px;"><?php echo $total_products; ?></div>
                </div>
                
                <div class="account-modern-card" style="text-align: center;">
                    <div class="account-modern-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_orders; ?></div>
                    <div class="h2" style="margin-top: 10px;"><?php echo $total_orders; ?></div>
                </div>
                
                <div class="account-modern-card" style="text-align: center;">
                    <div class="account-modern-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="account-modern-text"><?php echo $text_balance; ?></div>
                    <div class="h2" style="margin-top: 10px;"><?php echo $pending_balance; ?></div>
                </div>
                
                <div class="account-modern-card" style="text-align: center;">
                    <div class="account-modern-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="account-modern-text">Total Earned</div>
                    <div class="h2" style="margin-top: 10px;"><?php echo $total_earnings; ?></div>
                </div>
            </div>
            
            <div class="account-modern-section">
                <h3 class="account-modern-heading">Quick Actions</h3>
                <div class="account-modern-grid account-modern-grid-row">
                    <a href="<?php echo $link_products; ?>" class="account-modern-card">
                        <div class="account-modern-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="account-modern-text">Manage Products</div>
                    </a>
                    
                    <a href="<?php echo $link_orders; ?>" class="account-modern-card">
                        <div class="account-modern-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="account-modern-text">View Orders</div>
                    </a>
                    
                    <a href="<?php echo $link_withdrawal; ?>" class="account-modern-card">
                        <div class="account-modern-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="account-modern-text">Withdraw Funds</div>
                    </a>
                </div>
            </div>
        </div>
        
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>

