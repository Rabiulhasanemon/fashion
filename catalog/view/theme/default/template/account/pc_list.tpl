<?php echo $header; ?>
<div class="container body">
  <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
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
          <h1><?php echo $heading_title; ?></h1>
          <?php if ($pcs) { ?>
          <div class="table-responsive">
              <table class="table table-bpced table-hover">
                  <thead>
                  <tr>
                      <td class="text-right"><?php echo $column_pc_id; ?></td>
                      <td class="text-right"><?php echo $column_name; ?></td>
                      <td class="text-right"><?php echo $column_description; ?></td>
                      <td class="text-right"><?php echo $column_date_added; ?></td>
                      <td></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($pcs as $pc) { ?>
                  <tr>
                      <td class="text-right">#<?php echo $pc['pc_id']; ?></td>
                      <td class="text-right"><?php echo $pc['name']; ?></td>
                      <td class="text-right"><?php echo $pc['description']; ?></td>
                      <td class="text-right"><?php echo $pc['date_added']; ?></td>
                      <td class="text-right">
                          <a href="<?php echo $pc['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
                          <a href="<?php echo $pc['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-info delete"><i class="fa fa-remove"></i></a>
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
<script>
    $('.btn.delete').on("click", function (e) {
        return confirm("Are your sure about this")
    })
</script>
<?php echo $footer; ?>