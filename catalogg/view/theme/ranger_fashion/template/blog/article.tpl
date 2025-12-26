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
<section class="bg-gray p-tb-15">
<div class="container">
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-5'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-md-8 col-sm-7'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div class="<?php echo $class;?>" itemscope itemtype="http://schema.org/Article"><?php echo $content_top; ?>
            <meta itemprop="datePublished" content="<?php echo $date_published; ?>">
            <meta itemprop="dateModified" content="<?php echo $date_modified; ?>">
            <meta itemprop="mainEntityOfPage" content="<?php echo $article_url; ?>">
            <div class="info-page">
                <div id="content" class="blog-left">
                    <div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                        <meta itemprop="height" content="740">
                        <meta itemprop="width" content="350">
                        <meta itemprop="url" content="<?php echo $image; ?>">
                        <?php if($video_id) { ?>
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                        </div>
                        <?php } else { ?>
                        <img src="<?php echo $image; ?>" alt="<?php echo $heading_title; ?>">
                        <?php } ?>
                    </div>
                    <div class="article-title">
                        <h1 itemprop="headline"><?php echo $heading_title; ?></h1>
                    </div>
                    <div class="meta">
                        <span class="author"><i class="fa fa-user-circle"></i><span itemprop="author">Ribana Team</span></span>
                        <span class="date"><i class="fa fa-calendar"></i><span><?php echo $date_published; ?></span></span>
                    </div>
                    <div class="share-on">
                        <span class="share">Share:</span>
                        <span class="share-ico fa fa-facebook" data-type="facebook"></span>
                        <span class="share-ico fa fa-twitter" data-type="twitter"></span>
                        <span class="share-ico fa fa-google-plus" data-type="google-plus"></span>
                        <span class="share-ico fa fa-pinterest" data-type="pinterest"></span>
                    </div>

                    <div class="article-description" itemprop="articleBody"><?php echo $description; ?></div>

                    <div itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
                        <div itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
                            <meta itemprop="url" content="https://www.startech.com.bd/image/catalog/logo.png">
                        </div>
                        <span itemprop="name">Ribana Bangladesh</span>
                    </div>
                </div>
                <div class="blog-comments">
                    <div class="comments">
                        <h2><?php echo $text_comments ?></h2>
                        <?php if($comments) { ?>
                        <?php foreach($comments as $comment) { ?>
                        <div class="comment-info rounded-shp">
                            <h4 class="author"><?php echo $comment['author'] ?></h4>
                            <p class="text"><?php echo $comment['text'] ?></p>
                        </div>
                        <?php } ?>
                        <?php echo $pagination ?>
                        <?php } else { ?>
                        <div id="no-comment">
                            <p><?php echo $text_no_comment ?></p>
                        </div>
                        <?php } ?>
                    </div>
                    <form id="form-comment">
                        <h2><?php echo $text_write ?></h2>
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <input type="text" name="name" value="" id="input-name" class="form-control" placeholder="<?php echo $entry_name ?>">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <input type="text" name="email" value="" id="input-email" class="form-control" placeholder="Your Email">
                            </div>
                            <div class="form-group col-sm-12">
                                <textarea name="text" id="input-text" class="form-control" cols="30" rows="10" placeholder="<?php echo $entry_comment ?>"></textarea>
                            </div>
                        </div>
                        <?php echo $captcha ?>
                        <div class="buttons clearfix">
                            <button type="button" id="button-comment" data-loading-text="Loading..." class="btn submit-btn"><?php echo $text_submit ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if($column_right) { ?>
        <div class="col-md-4 col-sm-5">
            <div class="ppl-blog-list">
                <h2>Popular Post</h2>
                <?php echo $column_right; ?>
            </div>
        </div>
        <?php } ?><?php echo $content_bottom; ?>
    </div>
</div>
</section>
<?php echo $footer; ?>
<script type="text/javascript">
    $('#button-comment').on('click', function () {
        $.ajax({
            url: 'blog/article/write?article_id=<?php echo $article_id; ?>',
            method: 'post',
            dataType: 'json',
            contentType: null,
            data: new FormData($("#form-comment").get(0)),
            beforeSend: function () {
                $('#button-comment').button('loading');
            },
            complete: function () {
                $('#button-comment').button('reset');
            },
            success: function (json) {
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

    $(".share-ico").on("click", function () {
        shareOnSocialMedea($(this).data("type"), location.href, $(".article-titl h1").text(), $(".main-img").attr("src"))
    });

</script>