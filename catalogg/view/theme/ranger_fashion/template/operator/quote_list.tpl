<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="col-sm-4">
                <h6 class="page-heading"><?php echo $heading_title; ?></h6>
            </div>
        </div>
    </div>
</section>
<div class="container body">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="main_content">
          <div class="row">
              <div class="col-md-6">
                  <h4><?php echo $heading_title; ?></h4>
              </div>
              <div class="col-md-6 form-inline">
                  <div class="form-group pull-right">
                      <label>Show: </label>
                      <select class="form-control" onchange="location = this.value;">
                        <?php foreach($filters as $filter) { ?>
                            <option value="<?php echo $filter['href']; ?>" <?php echo $filter_status == $filter['value'] ? "selected" : "" ?> ><?php echo $filter['text']; ?></option>
                          <?php } ?>
                      </select>
                  </div>
              </div>
          </div>
          <?php if ($quotes) { ?>
          <div class="table-responsive">
              <table class="table table-quote table-hover">
                  <thead>
                  <tr>
                      <td class="text-right"><?php echo $column_quote_id; ?></td>
                      <td class="text-right"><?php echo $column_quote_by; ?></td>
                      <td class="text-left"><?php echo $column_customer; ?></td>
                      <td class="text-left"><?php echo $column_telephone; ?></td>
                      <td class="text-right"><?php echo $column_products; ?></td>
                      <td class="text-left"><?php echo $column_date_added; ?></td>
                      <td style="width: 170px"></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($quotes as $quote) { ?>
                  <tr>
                      <td class="text-right">#<?php echo $quote['quote_id']; ?></td>
                      <td class="text-right"><?php echo $quote['operator_name']; ?></td>
                      <td class="text-left"><?php echo $quote['name']; ?></td>
                      <td class="text-left"><?php echo $quote['telephone']; ?></td>
                      <td class="text-right"><?php echo $quote['products']; ?></td>
                      <td class="text-left"><?php echo $quote['date_added']; ?></td>
                      <td class="text-right">
                          <a href="<?php echo $quote['href']; ?>" data-toggle="tooltip" title="<?php echo $button_quote; ?>" class="btn btn-info"><i class="fa fa-reply"></i></a>&nbsp;
                          <a href="<?php echo $quote['duplicate']; ?>" data-toggle="tooltip"  title="<?php echo $button_duplicate; ?>" class="btn btn-danger remove"><i class="fa fa-remove"></i></a>
                      </td>
                  </tr>
                  <?php } ?>
                  </tbody>
              </table>
          </div>
          <div class="text-right"><?php echo $pagination; ?></div>
          <?php } else { ?>
          <p><?php echo $text_empty; ?></p>
          <?php } ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<style>.table>tbody>tr>td {padding: 8px;}</style>
<?php echo $footer; ?>
<script>
    $('.btn.remove').on("click", function (e) {
        return confirm("Are your sure about this")
    })
</script>