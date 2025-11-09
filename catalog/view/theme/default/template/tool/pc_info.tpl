<?php echo $header; ?>
<div class="container body">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if($error) { ?><div class="alert alert-warning alert-dismissable"><strong>Warning! </strong><?php echo $error; ?><button type="button" class="close" data-dismiss="alert">×</button></div><?php } ?>
  <?php if($success) { ?><div class="alert alert-success alert-dismissable"><strong>Success! </strong><?php echo $success; ?><button type="button" class="close" data-dismiss="alert">×</button></div><?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="button-link" style="height: 40px">
        <div class="left-button pull-left">
          <a class="pc-builder-button button-color-yellow" href="<?php echo $build; ?>"><?php echo $button_build; ?></a>
        </div>
        <div class="right-button pull-right ">
          <a class="pc-builder-button button-color-yellow" href="<?php echo $cart; ?>"><?php echo $button_cart; ?></a>
        </div>
        <div class="clearfix"></div>
      </div>
        <div class="main_content component-table-wrapper main-content-pc-builder">
          <table class="table component-table main-content-pc-builder">
            <tbody class="pc-builder-tbody">
            <tr class="component-title">
              <td class="component-col"><b><?php echo $column_component;?></b></td>
              <td class="image-col"><b><?php echo $column_image;?></b></td>
              <td class="name-col hidden-on-responsive"><b><?php echo $column_product_name;?></b></td>
              <td class="price-col"><b><?php echo $column_price;?></b></td>
              <td class="action-col"><b><?php echo $column_availability;?></b></td>
            </tr>
            <?php foreach ($components as $component) { ?>
            <?php if($component['product_id']) { ?>
            <tr class="component-detail">
              <td class="component-col component-td"><?php echo $component['name'];?></td>
              <td class="image-col image-td">
                <div class="image">
                  <a target="_blank" href="<?php echo $component['href'];?>"><img src="<?php echo $component['product_image'];?>" alt="" title="" class="img-responsive"></a>
                </div>
              </td>
              <td class="name-col name-td"><?php echo $component['product_name'];?></td>
              <td class="price-col price-td">
                <?php if($component['product_special']) { ?>
                <div class="price-old"><?php echo $component['product_price'];?></div>
                <div class="price-new"><?php echo $component['product_special'];?></div>
                <?php } else { ?>
                <div class="price"><?php echo $component['product_price'];?></div>
                <?php } ?>
              </td>
              <td class="action-col action-col-chose chose">
                <span><?php echo $component['stock_status']; ?></span>
              </td>
            </tr>
            <?php } else { ?>
              <tr class="w-100p">
                <td class="component-col"><b><?php echo $component['name'];?></b></td>
                <td class="name-col hidden-on-responsive"></td>
                <td class="image-col hidden-on-responsive"></td>
                <td class="price-col hidden-on-responsive"></td>
                <td class="action-col action-col-chose">
                </td>
              </tr>
            <?php } ?>

            <?php } ?>
            <tr class="total-amount">
              <td colspan="3" class="amount-label"><b><?php echo $text_total ?>:</b></td>
              <td class="amount"><?php echo $total; ?></td>
              <td></td>
            </tr>
            </tbody>

          </table>
          <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>