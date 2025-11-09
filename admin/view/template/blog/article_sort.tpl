<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-article" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-article" class="form-horizontal">
          <div class="sortable">
            <?php foreach($articles as $key => $article) { ?>
            <div draggable="true" class="box">
              <input type="hidden" name="article_id[]" value="<?php echo $article['article_id']; ?>">
              <span><?php echo $key + 1 ; ?></span>
              <span><?php echo $article['name']; ?></span>
              <i class="fa fa-trash remove"></i></div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
    document.addEventListener('DOMContentLoaded', (event) => {
      var sortable = $(".sortable");
      var dragSrcEl = null;

      function handleDragStart(e) {
        this.style.opacity = '0.4';
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
        sortable.addClass("drag")
      }

      function handleDragOver(e) {
        if (e.preventDefault) {
          e.preventDefault();
        }

        e.dataTransfer.dropEffect = 'move';

        return false;
      }

      function handleDragEnter(e) {
        this.classList.add('over');
      }

      function handleDragLeave(e) {
        this.classList.remove('over');
      }

      function handleDrop(e) {
        if (e.stopPropagation) {
          e.stopPropagation(); // stops the browser from redirecting.
        }

        if (dragSrcEl != this) {
          let parent = this.parentNode
          parent.insertBefore(dragSrcEl, this)
          // dragSrcEl.innerHTML = this.innerHTML;
          // this.innerHTML = e.dataTransfer.getData('text/html');
        }

        return false;
      }

      function handleDragEnd(e) {
        this.style.opacity = '1';
        sortable.removeClass("drag")
        items.forEach(function (item) {
          item.classList.remove('over');
        });
      }


      let items = document.querySelectorAll('.sortable .box');
      items.forEach(function(item) {
        item.addEventListener('dragstart', handleDragStart, false);
        item.addEventListener('dragenter', handleDragEnter, false);
        item.addEventListener('dragover', handleDragOver, false);
        item.addEventListener('dragleave', handleDragLeave, false);
        item.addEventListener('drop', handleDrop, false);
        item.addEventListener('dragend', handleDragEnd, false);
      });
    });
    $(".box .remove").on("click", function () {
      $(this).parents(".box").remove()
    })
//--></script>
</div>
<?php echo $footer; ?>