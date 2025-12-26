<div class="module-manufacturer">
  <div class="container">
    <h2 class="home-content-section-title"><?php echo $name; ?></h2>
    <div class="brand-item-wrapper">
      <ul class="brand-items">
        <?php foreach ($manufacturers as $manufacturer) { ?>
        <li class="each-brand"><a href="<?php echo $manufacturer['href']; ?>"><img src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" ></a> </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>