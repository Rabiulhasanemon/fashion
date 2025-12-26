<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo $home; ?>">Home</a></li>
            <li><?php echo $vendor['store_name']; ?></li>
        </ul>
    </div>
</section>

<div class="container">
    <div class="row">
        <?php echo $column_left; ?>
        <div id="content" class="col-sm-9">
            <div class="vendor-store-header" style="background: #f5f5f5; padding: 30px; margin-bottom: 30px; border-radius: 5px;">
                <div class="row">
                    <div class="col-sm-3">
                        <?php if ($vendor['logo']) { ?>
                        <img src="<?php echo $vendor['logo']; ?>" alt="<?php echo $vendor['store_name']; ?>" class="img-responsive" style="max-height: 150px;" />
                        <?php } ?>
                    </div>
                    <div class="col-sm-9">
                        <h1><?php echo $vendor['store_name']; ?></h1>
                        <?php if ($vendor['store_description']) { ?>
                        <p><?php echo $vendor['store_description']; ?></p>
                        <?php } ?>
                        <div class="vendor-stats">
                            <span><i class="fa fa-star"></i> <?php echo number_format($vendor['rating'], 1); ?> (<?php echo $vendor['review_count']; ?> reviews)</span>
                            <span style="margin-left: 20px;"><i class="fa fa-box"></i> <?php echo $vendor['total_products']; ?> Products</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <h2><?php echo $text_products; ?></h2>
            
            <?php if ($products) { ?>
            <div class="row">
                <?php foreach ($products as $product) { ?>
                <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="product-thumb">
                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                        <div>
                            <div class="caption">
                                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                                <p><?php echo $product['description']; ?></p>
                                <?php if ($product['price']) { ?>
                                <p class="price">
                                    <?php if (!$product['special']) { ?>
                                    <?php echo $product['price']; ?>
                                    <?php } else { ?>
                                    <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                                    <?php } ?>
                                </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            </div>
            <?php } else { ?>
            <p><?php echo $text_no_products; ?></p>
            <?php } ?>
        </div>
    </div>
</div>
<?php echo $footer; ?>


