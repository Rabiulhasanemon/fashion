<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-product" data-toggle="tooltip_" title="<?php echo $button_save; ?>"
                        class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip_" title="<?php echo $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-search-suggestion" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="search_suggestion_status" id="input-status" class="form-control">
                                <?php if ($search_suggestion_status) { ?>
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
                        <label class="col-sm-2 control-label" for="input-search-order"><span data-toggle="tooltip_" title="<?php echo $help_search_order; ?>"><?php echo $search_order; ?></span></label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <select name="search_suggestion_options[search_order]" id="input-search-order" class="form-control">
                                        <option value="name"
                                        <?php echo (isset($options['search_order']) && $options['search_order'] == 'name') ? 'selected="selected"' : "" ;?>
                                        ><?php echo $search_order_name; ?></option>
                                        <option value="rating"
                                        <?php echo (isset($options['search_order']) && $options['search_order'] == 'rating') ? 'selected="selected"' : "" ;?>
                                        ><?php echo $search_order_rating; ?></option>
                                        <option value="relevance"
                                        <?php echo (isset($options['search_order']) && $options['search_order'] == 'relevance') ? 'selected="selected"' : "" ;?>
                                        ><?php echo $search_order_relevance; ?></option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="search_suggestion_options[search_order_dir]" id="input-search-order-dir" class="form-control">
                                        <option value="asc"<?php echo (isset($options['search_order_dir']) && $options['search_order_dir'] == 'asc') ? 'selected="selected"' : "" ;?>><?php echo $search_order_dir_asc; ?></option>
                                        <option value="desc"<?php echo (isset($options['search_order_dir']) && $options['search_order_dir'] == 'desc') ? 'selected="selected"' : "" ;?>><?php echo $search_order_dir_desc; ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-search-limit"><?php echo $search_limit; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="search_suggestion_options[search_limit]" value="<?php echo isset($options['search_limit']) ? $options['search_limit'] : 0;?>" id="input-search-limit" class="form-control">
                        </div>
                    </div>
                    <input type="hidden" name="search_suggestion_module[0][search_suggestion]" value="1"/>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>