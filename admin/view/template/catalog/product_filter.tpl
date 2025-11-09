<div id="filter-wrap">
    <?php foreach ($filter_groups as $filter_group) { ?>
    <div class="form-group">
        <label class="control-label col-sm-2"><?php echo $filter_group['name']; ?></label>
        <div class="col-sm-10">
            <?php foreach ($filter_group['product_filters'] as $product_filter) { ?>
            <label class="checkbox-inline">
                <input type="checkbox" name="product_filter[]" value="<?php echo $product_filter['filter_id'] ?>" <?php echo $product_filter['checked'] ? "checked" : "" ?>> <?php echo $product_filter['name']; ?>
            </label>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>