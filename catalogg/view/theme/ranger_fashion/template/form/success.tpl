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
      <div class="main_content thank-u">
        <h2>Thank you for registering to the event.!</h2>
        <p>Your registration Number: <b><?php echo $event_participant_id; ?></b> . Please show this registration Number to collect your participation ID from your university</p>
        <p>IUB: 12 NOV, 2018</p>
        <p>NSU: 14 NOV, 2018</p>
        <p>EWU: 18 NOV, 2018</p>
        <br/>
        <p><a href="https://www.facebook.com/star.tech.ltd" target="_blank"><img src="catalog/view/theme/startech/image/find-us-on-facebook.png"/></a></p>
        <br/>
        <p style="background-color: yellow; font-size: 16px; font-weight: bold">Please Note: Seats are limited, we will provide and confirm the participation ID on first come first serve basis.</p>
        <p>Show this message to "Star Tech Pragati Sharani Branch" while buying your ASUS / ROG laptop to get a free Bluetooth speaker [Validity: 15 December,2018]</p>
        <div class="buttons">
          <div class=""><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        </div>
      </div>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>