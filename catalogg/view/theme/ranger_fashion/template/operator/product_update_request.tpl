<form method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
    <div class="form-group">
        <label for="new-price"><?php echo $entry_new_price?>:</label>
        <input type="number" class="form-control" id="new-price" name="new_price" min="0" placeholder="Current Price: <?php echo $price; ?>">
    </div>
    <div class="form-group">
        <label for="new-price"><?php echo $entry_new_regular_price; ?>:</label>
        <input type="number" class="form-control" id="new-price" name="new_regular_price" min="0" placeholder="Current Regular Price: <?php echo $regular_price; ?>">
    </div>
    <div class="form-group">
        <label for="new-status"><?php echo $entry_new_status?>:</label>
        <select name="new_status" id="new-status" class="form-control">
            <?php foreach($stock_statuses as $stock_status) { ?>
                <option value="<?php echo $stock_status['id'] ?>"><?php echo $stock_status['name'] ?></option>
            <?php }?>
        </select>
    </div>
    <div class="form-group">
        <label for="new-price"><?php echo $entry_new_sort_order; ?>:</label>
        <input type="number" class="form-control" id="new-price" name="new_sort_order" min="0" placeholder="Current Sort Order: <?php echo $sort_order; ?>">
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>