<?php foreach ($filter_groups as $filter_group) { ?>
<div class="filter-group" data-group-id="<?php echo $filter_group['filter_group_id']; ?>">
  <div class="label">
    <span><?php echo $filter_group['name']; ?></span>
  </div>
  <div class="items">
    <?php foreach ($filter_group['filter'] as $filter) { ?>
    <label class="filter">
      <input type="checkbox" name="filter" value="<?php echo $filter['filter_id']; ?>" />
      <span><?php echo $filter['name']; ?></span>
    </label>
    <?php } ?>
  </div>
</div>
<?php } ?>