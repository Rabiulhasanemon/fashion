<?php if ($modules) { ?>
<column id="column-left" class="col-sm-3">
  <span class="lc-close"><i class="material-icons" aria-hidden="true">close</i></span>
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
</column>
<?php } ?>