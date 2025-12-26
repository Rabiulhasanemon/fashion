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
<div class="container">
    <section class="pb-choose pb-choose-product">
      <div id="content">
        <div class="top">
        <div class="back-button-icon">
            <a class="back-button" href="<?php echo $back; ?>"><i class="fa fa-angle-left"></i> <span>BACK TO PC BUILD</span></a>
        </div>
        <div class="filter-by-brand">
            <select>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="opel">Opel</option>
                <option value="audi">Audi</option>
            </select>
        </div>
        </div>
        <div class="right-short-by pull-right">
            <div class="sort_by_wrap padding-right">
                <div class="custom_select_design custom_select_design-selection">
                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                        <?php foreach ($sorts as $sorts) { ?>
                        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="item-listing">
          <?php foreach ($products as $product) { ?>
            <div class="item">
                <div class="img-wrap">
                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"></a>
                </div>
                <div class="item-info">
                    <h3 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
                    <div class="price-wrap">
                        <?php if ($product['special']) { ?>
                        <span class="price-new price"><span class="symbol">৳</span><span><?php echo $product['special']; ?></span></span><span class="price-old price"><span class="symbol">৳</span><span><?php echo $product['price']; ?></span></span>
                        <?php } else { ?>
                        <span class="price"><span class="symbol">৳</span><span><?php echo $product['price']; ?></span></span>
                        <?php } ?>
                    </div>
                    <div class="actions">
                        <a class="btn-add" href="<?php echo $product['add'];?>"><?php echo $button_add;?></a>
                    </div>
                </div>
            </div>
          <?php } if(!$products) { ?>
            <div class="empty-content bg-white">
                <span class="icon"></span>
                <h5>Sorry! No Product Founds</h5>
                <p>Please try searching for something else</p>
            </div>
          <?php } ?>
        </div>
        <?php if($products) { ?>
         <div class="bottom-bar">
            <div class="row ">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 show-item-no">
                  <p class="pull-right"><?php echo $results; ?></p>
              </div>
            </div>
        </div>
        <?php } ?>
    </section>
</div>
  <script>
  app.onReady(window, "$", function () {
      $('#button-search').on('click', function() {
          var url = 'tool/pc_builder/choose?component_id=<?php echo $component_id; ?>';
          var search = $('#input-search').val();
          if (search) {
              url += '&filter_name=' + encodeURIComponent(search);
          }
          var category = '<?php echo $filter_category; ?>';

          if (category) {
              url += '&filter_category=' + encodeURIComponent(category);
          }
          location = url;
      });

      $('#input-search').on('keydown', function(e) {
          if (e.keyCode == 13) {
              $('#button-search').trigger('click');
          }
      });
  }, 20);

  </script>
</div>
<?php echo $footer; ?>