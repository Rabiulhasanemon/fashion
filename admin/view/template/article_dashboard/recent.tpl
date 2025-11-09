<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <td ><?php echo $column_article_id; ?></td>
          <td><?php echo $column_title; ?></td>
          <td><?php echo $column_status; ?></td>
          <td><?php echo $column_date_added; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($articles) { ?>
        <?php foreach ($articles as $article) { ?>
        <tr>
          <td ><?php echo $article['article_id']; ?></td>
          <td><?php echo $article['name']; ?></td>
          <td><?php echo $article['status']; ?></td>
          <td><?php echo $article['date_added']; ?></td>
          <td class="text-right"><a href="<?php echo $article['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-edit"></i></a></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
