<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="col-sm-4">
                <h6 class="page-heading"><?php echo $heading_title; ?></h6>
            </div>
        </div>
    </div>
</section>
<section id="content" class="p-tb-15">
    <div class="container">
        <div class="row">
            <?php foreach ($offers as $i => $offer) { ?>
            <div class="col-lg-6 col-md-12 m-b-30 offer">
                <a href="<?php echo $offer['href'] ?>"><img src="<?php echo $offer['image'] ?>" alt="laptop-hp"></a>
                <div class="details">
                    <div class="row">
                        <div class="col-md-8 col-sm-12 left-items">
                            <a href="<?php echo $offer['href'] ?>"><h4 class="title"><?php echo $offer['title'] ?></h4></a>
                            <p class="short-desc"><?php echo $offer['short_description'] ?></p>
                            <p class="validation"><span>Validation :</span> <?php echo $offer['date_start'] ?> to <?php echo $offer['date_end'] ?></p>
                        </div>
                        <div class="col-md-4 col-sm-12 right-items">
                            <h4 class="brunch-name"><?php echo $offer['branch'] ?></h4>
                            <a href="<?php echo $offer['href'] ?>" class="view-details">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(($i + 1) % 2 == 0) { ?><div class="clearfix"></div><?php } ?>
            <?php } ?>
        </div>

    </div>
</section>
<?php echo $footer; ?>