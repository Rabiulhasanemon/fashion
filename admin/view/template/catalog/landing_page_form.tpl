<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-landing-page" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-landing-page" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
                        <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
                        <li><a href="#tab-faq" data-toggle="tab"><?php echo $tab_faq; ?></a></li>
                        <li><a href="#tab-module" data-toggle="tab"><?php echo $tab_module; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <ul class="nav nav-tabs" id="language">
                                <?php foreach ($languages as $language) { ?>
                                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php foreach ($languages as $language) { ?>
                                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="landing_page_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($landing_page_description[$language['language_id']]) ? $landing_page_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                                            <?php if (isset($error_title[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-summary<?php echo $language['language_id']; ?>"><?php echo $entry_summary; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="landing_page_description[<?php echo $language['language_id']; ?>][summary]" placeholder="<?php echo $entry_summary; ?>" id="input-summary<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($landing_page_description[$language['language_id']]) ? $landing_page_description[$language['language_id']]['summary'] : ''; ?></textarea>
                                            <?php if (isset($error_summary[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_summary[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="landing_page_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($landing_page_description[$language['language_id']]) ? $landing_page_description[$language['language_id']]['description'] : ''; ?></textarea>
                                            <?php if (isset($error_description[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="landing_page_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($landing_page_description[$language['language_id']]) ? $landing_page_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                                            <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="landing_page_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($landing_page_description[$language['language_id']]) ? $landing_page_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="landing_page_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($landing_page_description[$language['language_id']]) ? $landing_page_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array(0, $landing_page_store)) { ?>
                                                <input type="checkbox" name="landing_page_store[]" value="0" checked="checked" />
                                                <?php echo $text_default; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="landing_page_store[]" value="0" />
                                                <?php echo $text_default; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php foreach ($stores as $store) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($store['store_id'], $landing_page_store)) { ?>
                                                <input type="checkbox" name="landing_page_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                                <?php echo $store['name']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="landing_page_store[]" value="<?php echo $store['store_id']; ?>" />
                                                <?php echo $store['name']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-class"><?php echo $entry_class; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="class" value="<?php echo $class ?>" placeholder="<?php echo $entry_class; ?>" id="input-class" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-question-text"><?php echo $entry_question_text; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="question_text" value="<?php echo $question_text ?>" placeholder="<?php echo $entry_question_text; ?>" id="input-question-text" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-video-text"><?php echo $entry_video_text; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="video_text" value="<?php echo $video_text ?>" placeholder="<?php echo $entry_video_text; ?>" id="input-question-text" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-video-url"><?php echo $entry_video_url; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="video_url" value="<?php echo $video_url ?>" placeholder="<?php echo $entry_video_url; ?>" id="input-question-url" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-landing-page-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="landing_page_product" value="" placeholder="<?php echo $entry_product; ?>" id="input-landing-page-product" class="form-control" />
                                    <div id="landing-page-product" class="well well-sm" style="height: 150px; overflow: auto;">
                                        <?php foreach ($landing_page_products as $landing_page_product) { ?>
                                        <div id="landing-page-product<?php echo $landing_page_product['landing_page_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $landing_page_product['name']; ?>
                                            <input type="hidden" name="landing_page_product[]" value="<?php echo $landing_page_product['landing_page_id']; ?>" />
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_feature_image; ?></label>
                                <div class="col-sm-10">
                                    <a href="" id="feature-image-preview" data-toggle="image" class="img-thumbnail"><img src="<?php echo $featured_image_preview; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="featured_image" value="<?php echo $featured_image; ?>" id="input-feature-image" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                                <div class="col-sm-10"><a href="" id="image-preview" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image_preview; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                                    <?php if ($error_keyword) { ?>
                                    <div class="text-danger"><?php echo $error_keyword; ?></div>
                                    <?php } ?>
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
                        </div>
                        <div class="tab-pane" id="tab-image">
                            <div class="table-responsive">
                                <table id="images" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left"><?php echo $entry_image; ?></td>
                                        <td class="text-right"><?php echo $entry_sort_order; ?></td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $image_row = 0; ?>
                                    <?php foreach ($landing_page_images as $landing_page_image) { ?>
                                    <tr id="image-row<?php echo $image_row; ?>">
                                        <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $landing_page_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="landing_page_image[<?php echo $image_row; ?>][image]" value="<?php echo $landing_page_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                                        <td class="text-right"><input type="text" name="landing_page_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $landing_page_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                                        <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                    </tr>
                                    <?php $image_row++; ?>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-faq">
                            <table id="landing-page-faqs" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-left" width="30%"><?php echo $entry_question; ?></td>
                                    <td class="text-left"><?php echo $entry_answer; ?></td>
                                    <td class="text-right"><?php echo $entry_sort_order; ?></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $faq_row = 0; ?>
                                <?php foreach ($landing_page_faqs as $landing_page_faq) { ?>
                                <tr id="faq-row<?php echo $faq_row; ?>">
                                    <td class="text-left">
                                        <?php foreach ($languages as $language) { ?>
                                        <div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span>
                                            <input type="text" name="landing_page_faq[<?php echo $faq_row; ?>][landing_page_faq_description][<?php echo $language['language_id']; ?>][question]" value="<?php echo isset($landing_page_faq['landing_page_faq_description'][$language['language_id']]) ? $landing_page_faq['landing_page_faq_description'][$language['language_id']]['question'] : ''; ?>" placeholder="<?php echo $entry_question; ?>" class="form-control" />
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left"><?php foreach ($languages as $language) { ?>
                                        <div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span>
                                            <textarea id="input-faq-answer-<?php echo $faq_row; ?>-<?php echo $language['language_id']; ?>"
                                                    name="landing_page_faq[<?php echo $faq_row; ?>][landing_page_faq_description][<?php echo $language['language_id']; ?>][answer]"
                                                    placeholder="<?php echo $entry_answer; ?>"
                                                    class="form-control summernote">
                <?php echo isset($landing_page_faq['landing_page_faq_description'][$language['language_id']]) ? $landing_page_faq['landing_page_faq_description'][$language['language_id']]['answer'] : ''; ?>
            </textarea>
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><input type="text" name="landing_page_faq[<?php echo $faq_row; ?>][sort_order]" value="<?php echo $landing_page_faq['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                                    <td class="text-left"><button type="button" onclick="$('#faq-row<?php echo $faq_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                </tr>
                                <?php $faq_row++; ?>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-left"><button type="button" onclick="addFaq();" data-toggle="tooltip" title="<?php echo $button_faq_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab-module">
                            <div class="table-responsive">
                                <table id="module" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left"><?php echo $entry_module; ?></td>
                                        <td class="text-left"><?php echo $entry_position; ?></td>
                                        <td class="text-right"><?php echo $entry_sort_order; ?></td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $module_row = 0; ?>
                                    <?php foreach ($landing_page_modules as $landing_page_module) { ?>
                                    <tr id="module-row<?php echo $module_row; ?>">
                                        <td class="text-left"><select name="landing_page_module[<?php echo $module_row; ?>][code]" class="form-control">
                                                <?php foreach ($extensions as $extension) { ?>
                                                <?php if (!$extension['module']) { ?>
                                                <?php if ($extension['code'] == $landing_page_module['code']) { ?>
                                                <option value="<?php echo $extension['code']; ?>" selected="selected"><?php echo $extension['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $extension['code']; ?>"><?php echo $extension['name']; ?></option>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <optgroup label="<?php echo $extension['name']; ?>">
                                                    <?php foreach ($extension['module'] as $module) { ?>
                                                    <?php if ($module['code'] == $landing_page_module['code']) { ?>
                                                    <option value="<?php echo $module['code']; ?>" selected="selected"><?php echo $module['name']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $module['code']; ?>"><?php echo $module['name']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </optgroup>
                                                <?php } ?>
                                                <?php } ?>
                                            </select></td>
                                        <td class="text-left"><select name="landing_page_module[<?php echo $module_row; ?>][position]" class="form-control">
                                                <?php if ($landing_page_module['position'] == 'content_top') { ?>
                                                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                                                <?php } else { ?>
                                                <option value="content_top"><?php echo $text_content_top; ?></option>
                                                <?php } ?>
                                                <?php if ($landing_page_module['position'] == 'content_bottom') { ?>
                                                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                                                <?php } else { ?>
                                                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                                                <?php } ?>
                                                <?php if ($landing_page_module['position'] == 'column_left') { ?>
                                                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                                                <?php } else { ?>
                                                <option value="column_left"><?php echo $text_column_left; ?></option>
                                                <?php } ?>
                                                <?php if ($landing_page_module['position'] == 'column_right') { ?>
                                                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                                                <?php } else { ?>
                                                <option value="column_right"><?php echo $text_column_right; ?></option>
                                                <?php } ?>
                                                <?php if ($landing_page_module['position'] == 'after_header') { ?>
                                                <option value="after_header" selected="selected"><?php echo "After Header"; ?></option>
                                                <?php } else { ?>
                                                <option value="after_header"><?php echo "After Header"; ?></option>
                                                <?php } ?>
                                            </select></td>
                                        <td class="text-right"><input type="text" name="landing_page_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $landing_page_module['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                                        <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                                    </tr>
                                    <?php $module_row++; ?>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-left"><button type="button" onclick="addModule();" data-toggle="tooltip" title="<?php echo $button_module_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
        <?php foreach ($languages as $language) { ?>
            $('#input-description<?php echo $language['language_id']; ?>').summernote({
                height: 300
            });
            $('#input-summary<?php echo $language['language_id']; ?>').summernote({
                height: 200
            });
            <?php } ?>
        var module_row = <?php echo $module_row; ?>;
        function addModule() {
            html  = '<tr id="module-row' + module_row + '">';
            html += '  <td class="text-left"><select name="landing_page_module[' + module_row + '][code]" class="form-control">';
            <?php foreach ($extensions as $extension) { ?>
            <?php if (!$extension['module']) { ?>
                    html += '    <option value="<?php echo $extension['code']; ?>"><?php echo addslashes($extension['name']); ?></option>';
                    <?php } else { ?>
                    html += '    <optgroup label="<?php echo addslashes($extension['name']); ?>">';
                    <?php foreach ($extension['module'] as $module) { ?>
                        html += '      <option value="<?php echo $module['code']; ?>"><?php echo addslashes($module['name']); ?></option>';
                        <?php } ?>
                    html += '    </optgroup>';
                    <?php } ?>
            <?php } ?>
            html += '  </select></td>';
            html += '  <td class="text-left"><select name="landing_page_module[' + module_row + '][position]" class="form-control">';
            html += '    <option value="content_top"><?php echo $text_content_top; ?></option>';
            html += '    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
            html += '    <option value="column_left"><?php echo $text_column_left; ?></option>';
            html += '    <option value="column_right"><?php echo $text_column_right; ?></option>';
            html += '    <option value="after_header"><?php echo "After Header"; ?></option>';
            html += '  </select></td>';
            html += '  <td class="text-left"><input type="text" name="landing_page_module[' + module_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
            html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + module_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
            html += '</tr>';

            $('#module tbody').append(html);

            module_row++;
        }
        //--></script>
    <script type="text/javascript"><!--
        var image_row = <?php echo $image_row; ?>;

        function addImage() {
            html  = '<tr id="image-row' + image_row + '">';
            html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="landing_page_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
            html += '  <td class="text-right"><input type="text" name="landing_page_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
            html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
            html += '</tr>';

            $('#images tbody').append(html);

            image_row++;
        }
        //--></script>
    <script type="text/javascript"><!--
        $('#language a:first').tab('show');

        $('input[name=\'landing_page_product\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'landing_page_product\']').val('');

                $('#landing-page-product' + item['value']).remove();

                $('#landing-page-product').append('<div id="landing-page-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="landing_page_product[]" value="' + item['value'] + '" /></div>');
            }
        });

        $('#landing-page-product').delegate('.fa-minus-circle', 'click', function() {
            $(this).parent().remove();
        });
        //--></script></div>

<script type="text/javascript"><!--

    var faq_row = <?php echo $faq_row; ?>;

    // Initialize Summernote for existing rows
    $(document).ready(function () {
        <?php foreach ($languages as $language) { ?>
        <?php for ($i = 0; $i < $faq_row; $i++) { ?>
                $('#input-faq-answer-<?php echo $i; ?>-<?php echo $language['language_id']; ?>').summernote({
                    height: 100
                });
                <?php } ?>
        <?php } ?>
    });

    function addFaq() {
        html  = '<tr id="faq-row' + faq_row + '">';
        html += '  <td class="text-left">';
        <?php foreach ($languages as $language) { ?>
            html += '    <div class="input-group">';
            html += '      <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="landing_page_faq[' + faq_row + '][landing_page_faq_description][<?php echo $language['language_id']; ?>][question]" value="" placeholder="<?php echo $entry_question; ?>" class="form-control" />';
            html += '    </div>';
            <?php } ?>
        html += '  </td>';

        html += '  <td class="text-left">';
        <?php foreach ($languages as $language) { ?>
            html += '    <div class="input-group">';
            html += '      <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><textarea id="input-faq-answer-' + faq_row + '-<?php echo $language['language_id']; ?>" name="landing_page_faq[' + faq_row + '][landing_page_faq_description][<?php echo $language['language_id']; ?>][answer]" placeholder="<?php echo $entry_answer; ?>" class="form-control summernote"></textarea>';
            html += '    </div>';
            <?php } ?>
        html += '  </td>';

        html += '  <td class="text-right"><input type="text" name="landing_page_faq[' + faq_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#faq-row' + faq_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#landing-page-faqs tbody').append(html);

        <?php foreach ($languages as $language) { ?>
            // Initialize Summernote for newly added textarea
            $('textarea.summernote').summernote({
                height: 100
            });
            <?php } ?>

        faq_row++;

    }

    $('textarea.summernote').summernote({
        height: 100
    });
    //--></script>
<?php echo $footer; ?>