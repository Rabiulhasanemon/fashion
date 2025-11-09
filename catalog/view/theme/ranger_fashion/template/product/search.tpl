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
<section class="gray-bg">
    <div class="container search-criteria">
        <div id="content" class="content">
            <?php echo $content_top; ?>
            <?php if ($products) { ?>

            <div class="featured-product-wrapper">
                <?php foreach ($products as $product) { ?>
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
                            <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" width="300" height="300" />
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
                    </div>
                </div>
                <?php } if(!$products) { ?>
                <div class="empty-content">
                    <span class="icon"></span>
                    <h5>Sorry! No Product Founds</h5>
                    <p>Please try searching for something else</p>
                </div>
                <?php } ?>
            </div>
            <div class="bottom-bar">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                            <?php echo $pagination; ?>
                    </div>
                    <div class="col-md-6 show-item-no">
                        <p class="pull-right"><?php echo $results; ?></p>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <p><?php echo $text_empty; ?></p>
            <?php } ?>
            <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $column_right; ?>
    </div>
</section>
<script type="text/javascript"><!--
    app.onReady(window, "$", function () {
        $("#search [name=search]").val(decodeURIComponent(getURLVar("search")))
    }, 20);
    --></script>
<?php echo $footer; ?>
<?php echo $footer; ?>