<?php echo $header; ?>
<section class="after-header">
    <div class="container">
        <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $i => $breadcrumb) { if($i < 1) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } else { ?>
            <li  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a><meta itemprop="position" content="<?php echo $i; ?>" /></li>
            <?php }} ?>
        </ul>
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
          <div class="row info_form">
              <div class="col-md-4 col-pull-4">
                  <div class="main_content contact-info">
                      <h3>Contact Address</h3>
                      <div class="address contact">
                          <div class="icon">
                              <a href=""><i class="fa fa-location-arrow" aria-hidden="true"></i></a>
                          </div>
                          <div class="info-text">
                              <P><?php echo $address; ?></P>
                          </div>
                      </div>
                      <div class="phone contact">
                          <div class="icon">
                              <a href=""><i class="fa fa-phone" aria-hidden="true"></i></a>
                          </div>
                          <div class="info-text">
                              <P><?php echo $telephone; ?></P>
                          </div>
                      </div>
                      <div class="email contact">
                          <div class="icon">
                              <a href="mailto:info@ribana.com"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                          </div>
                          <div class="info-text">
                              <P><?php echo $config_email; ?></P>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-8 col-pull-8">
                  <div class="main_content">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="contact_form">
                      <fieldset>
                          <h3><?php echo $text_contact; ?></h3>
                          <div class="row">
                              <div class="col-sm-6">
                                  <div class="form-group required">
                                      <input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
                                      <?php if ($error_name) { ?>
                                      <div class="text-danger"><?php echo $error_name; ?></div>
                                      <?php } ?>

                                  </div>
                              </div>
                             <div class="col-sm-6">
                                 <div class="form-group required email">
                                     <input type="text" name="email" placeholder="Email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
                                     <?php if ($error_email) { ?>
                                     <div class="text-danger"><?php echo $error_email; ?></div>
                                     <?php } ?>
                                 </div>
                             </div>
                         </div>
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="form-group required">
                                      <textarea name="enquiry" placeholder="Message" rows="10" style="height: 200px" id="input-enquiry" class="form-control"><?php echo $enquiry; ?></textarea>
                                      <?php if ($error_enquiry) { ?>
                                      <div class="text-danger"><?php echo $error_enquiry; ?></div>
                                      <?php } ?>
                                  </div>
                              </div>
                          </div>
                          <?php if ($site_key) { ?>
                          <div class="form-group">
                              <div class="col-sm-offset-2 col-sm-10">
                                  <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                                  <?php if ($error_captcha) { ?>
                                  <div class="text-danger"><?php echo $error_captcha; ?></div>
                                  <?php } ?>
                              </div>
                          </div>
                          <?php } ?>
                      </fieldset>
                      <div class="buttons">
                          <input class="btn btn-primary" type="submit" value="<?php echo $button_submit; ?>" />
                      </div>
                  </form>
                  </div>
              </div>
          </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
