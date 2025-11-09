<?php echo $header; ?>
<div class="container body">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if($error) { ?><div class="alert alert-warning alert-dismissable"><strong>Warning! </strong><?php echo $error; ?><button type="button" class="close" data-dismiss="alert">×</button></div><?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
        <div class="button-link">
        <div class="left-search pull-left">
          <div class="back-button-icon">
            <a class="back-button" href="<?php echo $back; ?>"><span><img src="image/back-icon.png" alt="back-button" class="img-responsive"></span></a>
          </div>
          <div class="input-group pull-right">
            <div class="search">
              <select name="filter_category" id="select-category" class="form-control form-control-search" onchange="location = this.value;">
                <?php foreach ($categories as $category) { ?>
                <?php if ($category['value'] == $filter_category) { ?>
                <option value="<?php echo $category['href']; ?>" selected="selected"><?php echo $category['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $category['href']; ?>"><?php echo $category['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <div class="input-group input-group-search">
                <input type="text" name="search"  value="<?php echo $filter_name ?>" id="input-search"  placeholder="Search" class="form-control input-lg" autocomplete="off">
                <span class="input-group-btn">
                  <button type="button" id="button-search" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="right-short-by pull-right">
          <div class="sort_by_wrap padding-right">
            <label class="control-label short-by" for="input-sort">Sort By:</label>
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
        <div class="clearfix"></div>
      </div>
       <div class="main_content main-content-pc-builder">
          <table class="table component-table">
            <tbody class="pc-builder-tbody">
            <tr class="component-title">
              <td class="image-col"><b><?php echo $column_image;?></b></td>
              <td class="name-col"><b><?php echo $column_product_name;?></b></td>
              <td class="model-col"><b><?php echo $column_model;?></b></td>
              <td class="price-col"><b><?php echo $column_price;?></b></td>
              <td class="action-col"><b><?php echo $column_action;?></b></td>
            </tr>
            <?php foreach ($products as $product) { ?>
            <tr class="component-detail">
              <td class="image-col image-col-ada">
                <div class="image">
                  <a target="_blank" href="<?php echo $product['href'];?>"><img src="<?php echo $product['thumb'];?>" alt="<?php echo $product['name'];?>" title="<?php echo $product['name'];?>" class="img-responsive"/></a>
                </div>
              </td>
              <td class="name-col name-col-ada"><?php echo $product['name'];?></td>
              <td class="model-col model-col-ada"><?php echo $product['model'];?></td>
              <td class="price-col price-col-ada">
                <?php if($product['special']) { ?>
                <div class="price-old"><?php echo $product['price'];?></div>
                <div class="price-new"><?php echo $product['special'];?></div>
                <?php } else { ?>
                <div class="price"><?php echo $product['price'];?></div>
                <?php } ?>
              </td>
              <td class="action-col  action-col-ada"><a href="<?php echo $product['add'];?>"><?php echo $button_add;?></a></td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
  <script>
      $('#button-search').bind('click', function() {
          var url = 'tool/pc_builder/choose?component_id=<?php echo $component_id; ?>';
          var search = $('#input-search').prop('value');
          if (search) {
              url += '&filter_name=' + encodeURIComponent(search);
          }
          var category = '<?php echo $filter_category; ?>';

          if (category) {
              url += '&filter_category=' + encodeURIComponent(category);
          }
          location = url;
      });

      $('#input-search').bind('keydown', function(e) {
          if (e.keyCode == 13) {
              $('#button-search').trigger('click');
          }
      });
  </script>
</div>
<?php echo $footer; ?>