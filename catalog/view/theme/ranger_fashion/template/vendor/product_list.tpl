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

<?php if ($success) { ?>
<div class="container">
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
</div>
<?php } ?>

<div class="container">
    <div class="row">
        <?php echo $column_left; ?>
        <div id="content" class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><?php echo $heading_title; ?></h2>
                    <div class="pull-right">
                        <a href="<?php echo $add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Product</a>
                        <button type="button" id="button-delete" class="btn btn-danger" onclick="confirm('Are you sure?') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i> Delete</button>
                    </div>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                        <td class="text-left"><?php echo $column_name; ?></td>
                                        <td class="text-left"><?php echo $column_model; ?></td>
                                        <td class="text-right"><?php echo $column_price; ?></td>
                                        <td class="text-right"><?php echo $column_quantity; ?></td>
                                        <td class="text-center"><?php echo $column_status; ?></td>
                                        <td class="text-right"><?php echo $column_action; ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($products) { ?>
                                    <?php foreach ($products as $product) { ?>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" /></td>
                                        <td class="text-left"><?php echo $product['name']; ?></td>
                                        <td class="text-left"><?php echo $product['model']; ?></td>
                                        <td class="text-right"><?php echo $product['price']; ?></td>
                                        <td class="text-right"><?php echo $product['quantity']; ?></td>
                                        <td class="text-center"><span class="label label-<?php echo $product['status_class']; ?>"><?php echo $product['status']; ?></span></td>
                                        <td class="text-right"><a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>


