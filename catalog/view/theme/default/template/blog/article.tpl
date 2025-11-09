<?php echo $header; ?>
<div class="container">
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
      <div class="row">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
            <div class="article-title">
                <h3><?php echo $heading_title; ?></h3>
            </div>
            <div class="article-subtitle">
                <span class="article-date"><?php echo $date; ?></span>
                <div class="addthis_toolbox addthis_default_style">
                    <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                    <a class="addthis_button_tweet"></a>
                    <a class="addthis_button_pinterest_pinit"></a>
                    <a class="addthis_counter addthis_pill_style"></a>
                </div>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
            </div>
            <div class="article-description">
                <p><?php echo $description; ?></p>
            </div>
            <div class="blog-comments" >
                <div class="comments">
                    <?php if($comments) { ?>
                        <?php foreach($comments as $comment) { ?>
                            <div class="comment">
                                <div class="date"><?php echo $comment['date_added'] ?></div>
                                <div class="author"><?php echo $comment['author'] ?></div>
                                <div class="text"><?php echo $comment['text'] ?></div>
                            </div>
                        <?php } ?>
                        <?php echo $pagination ?>
                    <?php } else { ?>
                        <div id="no-comment">
                            <p><?php echo $text_no_comment ?></p>
                        </div>
                    <?php } ?>
                </div>
                <form class="form-horizontal" id="form-comment">
                    <h2><?php echo $text_write ?></h2>
                    <div class="form-group required">
                        <div class="col-sm-12">
                            <label class="control-label" for="input-name"><?php echo $entry_name ?></label>
                            <input type="text" name="name" value="" id="input-name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="col-sm-12">
                            <label class="control-label" for="input-comment"><?php echo $entry_comment ?></label>
                            <textarea name="text" rows="5" id="input-comment" class="form-control"></textarea>
                        </div>
                    </div>
                    <?php echo $captcha ?>
                    <div class="buttons clearfix">
                        <div class="pull-right">
                            <button type="button" id="button-comment" data-loading-text="Loading..." class="btn btn-primary"><?php echo $text_submit ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
    $('#button-comment').on('click', function() {
        $.ajax({
            url: 'blog/article/write?article_id=<?php echo $article_id; ?>',
            type: 'post',
            dataType: 'json',
            data: $("#form-comment").serialize(),
            beforeSend: function() {
                $('#button-comment').button('loading');
            },
            complete: function() {
                $('#button-comment').button('reset');
            },
            success: function(json) {
                $('.alert-success, .alert-danger').remove();
                if (json['error']) {
                    $('#form-comment').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }
                if (json['success']) {
                    $('#form-comment').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                }
            }
        });
    });
</script>