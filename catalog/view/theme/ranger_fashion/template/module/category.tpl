<div class="list-group category_nav">
    <div class="title">Categories</div>
  <?php foreach ($categories as $category) { ?>
  <?php if ($category['category_id'] == $category_id) { ?>
  <a href="<?php echo $category['href']; ?>" class="list-group-item active root"><?php echo $category['name']; ?></a>
  <?php if ($category['children']) { ?>
  <?php foreach ($category['children'] as $child) { ?>
  <?php if (isset($child['category_id']) && $child['category_id'] == $child_id) { ?>
  <?php if($child['children']) { ?>
  <a href="<?php echo $child['href']; ?>" class="list-group-item child highlighted">&nbsp;&nbsp;<?php echo $child['name']; ?></a>
  <?php foreach ($child['children'] as $child2) { ?>
  <?php if (isset($child2['category_id']) && $child2['category_id'] == $child_id_2) { ?>
  <a href="<?php echo $child2['href']; ?>" class="list-group-item child2 child active">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child2['name']; ?></a>
  <?php } else { ?>
  <a href="<?php echo $child2['href']; ?>" class="list-group-item child2 child">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child2['name']; ?></a>
  <?php } ?>
  <?php } ?>
  <?php } else { ?>
  <a href="<?php echo $child['href']; ?>" class="list-group-item active child">&nbsp;&nbsp;<?php echo $child['name']; ?></a>
  <?php } ?>
  <?php } else { ?>
  <a href="<?php echo $child['href']; ?>" class="list-group-item child">&nbsp;&nbsp;&nbsp;<?php echo $child['name']; ?></a>
  <?php } ?>
  <?php } ?>
  <?php } ?>
  <?php } else { ?>
  <a href="<?php echo $category['href']; ?>" class="list-group-item root"><?php echo $category['name']; ?></a>
  <?php } ?>
  <?php } ?>
</div>
