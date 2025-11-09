<div class="panel">
  <div class="filters">
    <div class="price-filter">
      <div class="label">
        <span>Price Range</span>
      </div>
      <div id="rang-slider" data-from="<?php echo $price_from; ?>" data-to="<?php echo $price_to; ?>" data-min="<?php echo $min_price; ?>" data-max="<?php echo $max_price; ?>"></div>
      <div class="range-label from"><input type="text" id="range-to" name="from"></div>
      <div  class="range-label to"><input type="text" id="range-from" name="to"></div>
    </div>
    <?php if(isset($categories)) { ?>
    <div class="filter-group show" data-group-type="category">
      <div class="label">
        <span>Category</span>
        <i class="toggler fa"></i>
      </div>
      <div class="items">
        <?php foreach ($categories as $category) { ?>
        <label class="filter category">
          <input type="checkbox" name="category" value="<?php echo $category['category_id']; ?>" />
          <span><?php echo $category['name']; ?></span>
        </label>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
    <?php echo $filter_groups; ?>
  </div>
</div>

<?php if(!$filter_groups) { ?>
<div class="clear ads ads-pos-2" data-position="2"></div>
<?php } ?>