<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-slideshow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slideshow" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if (isset($error_name)) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-class"><?php echo $entry_class; ?></label>
            <div class="col-sm-10">
              <input type="text" name="class" value="<?php echo isset($class) ? $class : ''; ?>" placeholder="<?php echo $entry_class; ?>" id="input-class" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-banner"><?php echo $entry_banner; ?></label>
            <div class="col-sm-10">
              <select name="banner_id" id="input-banner" class="form-control">
                <option value=""><?php echo isset($text_select) ? $text_select : '-- Select --'; ?></option>
                <?php foreach ($banners as $banner) { ?>
                <?php if ($banner['banner_id'] == $banner_id) { ?>
                <option value="<?php echo $banner['banner_id']; ?>" selected="selected"><?php echo $banner['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if (isset($error_width)) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if (isset($error_height)) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
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
        </form>
      </div>
    </div>
    
    <!-- Banner Preview -->
    <?php if (!empty($banner_preview)) { ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-eye"></i> Preview</h3>
      </div>
      <div class="panel-body">
        <style>
        .main-home-banner{
            position: relative;
            max-width: 100%;
            overflow: hidden;
        }
        .slider-dot {
            position: absolute;
            z-index: 1;
            bottom: 5px;
            left: 0;
            text-align: center;
            margin: 0 auto;
            width: 100%;
            cursor: pointer;
        }

        .dot {
            height: 10px;
            width: 10px;
            margin: 0 5px;
            background-color: #fff;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 2s ease;
        }
        .dot.active {
            background: var(--secondaryColor);
        }
        .banner-slider {
            position: relative;
        }
        .banner-image {
            width: 100%;
        }
        .banner-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        </style>
        <div class="main-home-banner">
          <div class="banner-slider">
            <?php foreach ($banner_preview as $index => $banner) { ?>
            <div class="mySlides banner-image" style="<?php echo $index > 0 ? 'display: none;' : ''; ?>">
              <?php if (!empty($banner['link'])) { ?>
              <a href="<?php echo $banner['link']; ?>" target="_blank">
                <img src="<?php echo isset($banner['image']) ? $banner['image'] : ''; ?>" alt="<?php echo isset($banner['title']) ? htmlspecialchars($banner['title']) : ''; ?>" class="img-fluid"/>
              </a>
              <?php } else { ?>
              <img src="<?php echo isset($banner['image']) ? $banner['image'] : ''; ?>" alt="<?php echo isset($banner['title']) ? htmlspecialchars($banner['title']) : ''; ?>" class="img-fluid"/>
              <?php } ?>
            </div>
            <?php } ?>
          </div>
          <?php if (count($banner_preview) > 1) { ?>
          <div class="slider-dot">
            <?php foreach ($banner_preview as $index => $banner) { ?>
            <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
        
        <script>
        var slideIndex = 0;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        
        function showSlide(n) {
            if (slides.length === 0) return;
            if (n >= slides.length) { slideIndex = 0; }
            if (n < 0) { slideIndex = slides.length - 1; }
            
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (var i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            
            if (slides[slideIndex]) {
                slides[slideIndex].style.display = "block";
            }
            if (dots[slideIndex]) {
                dots[slideIndex].className += " active";
            }
        }
        
        function currentSlide(n) {
            slideIndex = n;
            showSlide(slideIndex);
        }
        
        // Auto-advance slides
        if (slides.length > 1) {
            setInterval(function() {
                slideIndex++;
                showSlide(slideIndex);
            }, 3000);
        }
        
        // Initialize
        showSlide(slideIndex);
        </script>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php echo $footer; ?>
