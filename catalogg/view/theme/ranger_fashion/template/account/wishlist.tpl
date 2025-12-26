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
<div class="container alert-container">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
</div>
<div class="container account-page account-wishlist">
    <?php echo $column_left; ?>
    <div id="content" class="content left-wrapper">
        <div class="my-info">
        <h2><?php echo $heading_title; ?></h2>
        <?php if ($products) { ?>
        <div class="item-listing">
            <?php foreach ($products as $product) { ?>
            <div class="item">
                <div class="img-wrap">
                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                </div>
                <div class="item-info">
                    <h3 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
                    <div class="price-wrap">
                        <?php if ($product['price']) { ?>
                        <div class="price">
                            <?php if (!$product['special']) { ?>
                            <?php echo $product['price']; ?>
                            <?php } else { ?>
                            <b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="actions">
                        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_cart; ?>"><i class="material-icons">shopping_cart</i></button>
                        <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>"><i class="material-icons">delete</i></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <p><?php echo $text_empty; ?></p>
        <?php } ?>
        <div class="buttons clearfix">
            <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>