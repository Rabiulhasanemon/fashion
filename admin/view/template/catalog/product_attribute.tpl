<div class="table-responsive" id="attribute-table">
    <table id="attribute" class="table table-striped table-bordered table-hover">
        <tbody>
        <?php foreach ($attribute_groups as $attribute_group) { ?>
        <tr>
            <th colspan="2"><?php echo $attribute_group['name']; ?></th>
        </tr>
        <?php foreach ($attribute_group['product_attributes'] as $product_attribute) { ?>
        <tr id="attribute-row<?php echo $product_attribute['attribute_id'] ?>">
            <td class="text-left" style="width: 40%;"><?php echo $product_attribute['name']; ?></td>
            <td class="text-left">
                <input type="hidden" name="product_attribute[<?php echo $product_attribute['attribute_id'] ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" />
            <?php foreach ($languages as $language) { ?>
            <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="product_attribute[<?php echo $product_attribute['attribute_id'] ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
            </div>
            <?php } ?>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>