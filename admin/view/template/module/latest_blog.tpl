<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-latest" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                                <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-class"><?php echo $entry_class; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="class" value="<?php echo $class; ?>" placeholder="<?php echo $entry_class; ?>" id="input-class" class="form-control" />
                            <?php if ($error_class) { ?>
                                <div class="text-danger"><?php echo $error_class; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="category" value="<?php echo $category; ?>" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-featured-width"><?php echo $entry_featured_image; ?></label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="featured_width" value="<?php echo $featured_width; ?>" placeholder="<?php echo $entry_featured_width; ?>" id="input-featured-width" class="form-control" />
                                    <?php if ($error_featured_width) { ?>
                                        <div class="text-danger"><?php echo $error_featured_width; ?></div>
                                    <?php } ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="featured_height" value="<?php echo $featured_height; ?>" placeholder="<?php echo $entry_featured_height; ?>" id="input-height" class="form-control" />
                                    <?php if ($error_featured_height) { ?>
                                        <div class="text-danger"><?php echo $error_featured_height; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_image; ?></label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                                    <?php if ($error_width) { ?>
                                        <div class="text-danger"><?php echo $error_width; ?></div>
                                    <?php } ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                                    <?php if ($error_height) { ?>
                                        <div class="text-danger"><?php echo $error_height; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-view"><?php echo $entry_view; ?></label>
                        <div class="col-sm-10">
                            <select name="view" id="input-view" class="form-control">
                                <?php if ($view == 'tab') { ?>
                                    <option value="tab" selected="selected"><?php echo $text_tab; ?></option>
                                    <option value="list"><?php echo $text_list; ?></option>
                                <?php } else { ?>
                                    <option value="tab"><?php echo $text_tab; ?></option>
                                    <option value="list" selected="selected"><?php echo $text_list; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-popular"><?php echo $entry_popular; ?></label>
                        <div class="col-sm-10">
                            <select name="popular" id="input-popular" class="form-control">
                                <?php if ($popular) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="status" id="input-status" class="form-control">
                                <?php if ($status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('input[name=\'category\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=blog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        category_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });

                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['category_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'category\']').val(item['label']);
            $('input[name=\'category_id\']').val(item['value']);
        }
    });
    //--></script>
<?php echo $footer; ?>
