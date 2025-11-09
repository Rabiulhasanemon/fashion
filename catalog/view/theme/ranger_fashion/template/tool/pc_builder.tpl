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
<div class="container pc-builder">
  <?php if($error) { ?><div class="alert alert-warning alert-dismissable"><strong>Warning! </strong><?php echo $error; ?><button type="button" class="close" data-dismiss="alert">×</button></div><?php } ?>
  <?php if($success) { ?><div class="alert alert-success alert-dismissable"><strong>Success! </strong><?php echo $success; ?><button type="button" class="close" data-dismiss="alert">×</button></div><?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="component-table-wrapper main-content-pc-builder 2">
        <table class="table component-table">
          <tbody class="pc-builder-tbody">
          <tr class="component-title">
            <td class="component-col"><b><?php echo $column_component;?></b></td>
            <td class="image-col"><b><?php echo $column_image;?></b></td>
            <td class="name-col hidden-on-responsive"><b><?php echo $column_product_name;?></b></td>
            <td class="price-col"><b><?php echo $column_price;?></b></td>
            <td class="action-col"><b><?php echo $column_action;?></b></td>
          </tr>
          <?php foreach ($components as $component) { ?>
          <?php if($component['product_id']) { ?>
          <tr class="component-detail selected">
            <td class="component-col component-td">
              <span class="img-ico <?php echo str_replace(" ", "-", strtolower($component['name'])); ?>"></span>
              <span class="name"><?php echo $component['name'];?></span>
            </td>
            <td class="image-col image-td image-td-choses">
              <div class="image chose-images">
                <a target="_blank" href="<?php echo $component['href'];?>"><img src="<?php echo $component['product_image'];?>" alt="" title="" class="img-responsive"></a>
              </div>
            </td>
            <td class="name-col name-td name-td-choses"><?php echo $component['product_name'];?></td>
            <td class="price-col price-td price-td-choses">
              <?php if($component['product_special']) { ?>
              <div class="price-wrap">
                <div class="price price-new"><span class="symbol">৳</span><?php echo $component['product_special'];?></div>
                <div class="price price-old"><span class="symbol">৳</span><?php echo $component['product_price'];?></div>
              </div>
              <?php } else { ?>
              <div class="price"><span class="symbol">৳</span><?php echo $component['product_price'];?></div>
              <?php } ?>
            </td>
            <td class="action-col action-col-chose chose">
              <span><a class="remove-td" href="<?php echo $component['remove'];?>"><?php echo $button_remove;?></a></span>
              <span><a class="change-td" href="<?php echo $component['choose'];?>"><?php echo $button_choose;?></a></span>
            </td>
          </tr>
          <?php } else { ?>
            <tr class="name-value w-100p">
              <td class="component-col">
                <span class="img-ico <?php echo str_replace(" ", "-", strtolower($component['name'])); ?>"></span>
                <span class="name"><?php echo $component['name'];?></span>
              </td>
              <td class="name-col hidden-on-responsive"></td>
              <td class="image-col hidden-on-responsive"></td>
              <td class="price-col hidden-on-responsive"></td>
              <td class="action-col action-col-chose">
                <a href="<?php echo $component['choose'];?>"><?php echo $button_choose;?></a>
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
<img id="aaaaaaaaa" src="" alt="">
<script type="text/javascript">
    app.onReady(window, ["html2canvas"], function () {
        var region = document.querySelector(".main_content");
        region.querySelector(".pb-info-head").className += ' ' + 'show'
        html2canvas(region, {
            async: true
        }).then(function(canvas) {
            var pngUrl = canvas.toDataURL();
            window.ca = canvas
            document.getElementById("input-base64-image").value = canvas.toDataURL();
            $(".pb-info-head").removeClass("show");
        }).catch(function (reason) {
            console.log(reason)
        });
    }, 30, 100);

    var form = document.getElementById("form-base64-image");
    form.onsubmit = function (ev) {
        var input = document.getElementById("input-base64-image");
        if(!input.value) {
            alert("Screenshot isn't prepared yet. Please clink again")
        }
        if(!input.value) {
            ev.preventDefault()
        }
    }
    function draw() {
       document.getElementById("aaaaaaaaa").src = ca.toDataURL()
    }
</script>
<?php echo $footer; ?>