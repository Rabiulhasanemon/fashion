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
      <div class="main_content thank-u">
        <h2>Thank you for your order!</h2>
        <?php echo $text_message; ?>
        <br/>
        <p><a href="https://www.facebook.com/star.tech.ltd" target="_blank"><img src="catalog/view/theme/startech/image/find-us-on-facebook.png"/></a></p>
        <br/>
        <div class="buttons">
          <div class=""><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        </div>
      </div>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<!-- Google Code for Order Conversion Page -->
<script type="text/javascript">
  /* <![CDATA[ */
  var google_conversion_id = 853148242;
  var google_conversion_language = "en";
  var google_conversion_format = "3";
  var google_conversion_color = "ffffff";
  var google_conversion_label = "5c5tCKfat3EQ0oTolgM";
  var google_remarketing_only = false;
  /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
  <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/853148242/?label=5c5tCKfat3EQ0oTolgM&amp;guid=ON&amp;script=0"/>
  </div>
</noscript>
<?php echo $footer; ?>