<h3><?php echo $name; ?></h3>
<div class="row">
  <?php foreach ($locations as $location) { ?>
  <div class="location-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image">
          <img src="<?php echo $location['image']; ?>" alt="<?php echo $location['name']; ?>" title="<?php echo $location['name']; ?>" class="img-responsive" />
      </div>
      <div class="caption">
        <h4><?php echo $location['name']; ?></h4>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
