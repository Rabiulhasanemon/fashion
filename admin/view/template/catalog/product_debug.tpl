<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <li><a href="<?php echo $this->url->link('common/dashboard', 'token=' . $token, 'SSL'); ?>">Home</a></li>
        <li><a href="<?php echo $this->url->link('catalog/product', 'token=' . $token, 'SSL'); ?>">Products</a></li>
        <li><?php echo $heading_title; ?></li>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    
    <?php if (isset($cleanup_result) && $cleanup_result) { ?>
    <div class="alert alert-<?php echo $cleanup_result['success'] ? 'success' : 'warning'; ?>">
      <h4>Cleanup Result</h4>
      <p>Cleaned <?php echo $cleanup_result['cleaned_tables']; ?> tables</p>
      <ul>
        <?php foreach ($cleanup_result['messages'] as $msg) { ?>
        <li><?php echo $msg; ?></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    
    <?php if (isset($error)) { ?>
    <div class="alert alert-danger">
      <strong>Error:</strong> <?php echo $error; ?>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bug"></i> Product Debug Information</h3>
      </div>
      <div class="panel-body">
        
        <?php if (!isset($debug_info) || empty($debug_info)) { ?>
        <div class="alert alert-warning">
          <strong>Warning:</strong> Debug information is not available. Please check the error logs.
          <br><small>Debug Info Status: <?php echo isset($debug_info) ? 'Set' : 'Not Set'; ?></small>
        </div>
        <?php } ?>
        
        <!-- Debug Test Output -->
        <div class="alert alert-info">
          <strong>Debug Test:</strong> 
          <br>Debug Info exists: <?php echo isset($debug_info) ? 'Yes' : 'No'; ?>
          <br>Zero Records count: <?php echo isset($debug_info['zero_records']) ? count($debug_info['zero_records']) : 'N/A'; ?>
          <br>Current Product ID: <?php echo isset($current_product_id) ? $current_product_id : 'Not Set'; ?>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <h4>Quick Actions</h4>
            <div class="form-inline" style="margin-bottom: 15px;">
              <form method="get" action="" style="display: inline-block;">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="form-group">
                  <label>Check Product ID:</label>
                  <input type="number" name="product_id" class="form-control" placeholder="Enter Product ID" value="<?php echo isset($current_product_id) && $current_product_id > 0 ? $current_product_id : ''; ?>" style="width: 150px;">
                </div>
                <button type="submit" class="btn btn-info">
                  <i class="fa fa-search"></i> Check Product
                </button>
              </form>
            </div>
            <div>
              <a href="<?php echo $this->url->link('catalog/product_debug', 'token=' . $token . '&action=cleanup', 'SSL'); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to clean up all product_id = 0 records?');">
                <i class="fa fa-trash"></i> Cleanup product_id = 0 Records
              </a>
              <a href="<?php echo $this->url->link('catalog/product_debug', 'token=' . $token, 'SSL'); ?>" class="btn btn-primary">
                <i class="fa fa-refresh"></i> Refresh Debug Info
              </a>
            </div>
          </div>
        </div>
        
        <hr>
        
        <!-- Product ID = 0 Records -->
        <div class="row">
          <div class="col-md-12">
            <h4><i class="fa fa-exclamation-triangle text-danger"></i> Product ID = 0 Records</h4>
            <?php if (!empty($debug_info['zero_records'])) { ?>
            <div class="alert alert-danger">
              <strong>Warning:</strong> Found product_id = 0 records in the following tables:
              <table class="table table-bordered table-striped" style="margin-top: 10px;">
                <thead>
                  <tr>
                    <th>Table</th>
                    <th>Count</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($debug_info['zero_records'] as $table => $count) { ?>
                  <tr>
                    <td><?php echo $table; ?></td>
                    <td><span class="label label-danger"><?php echo $count; ?></span></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } else { ?>
            <div class="alert alert-success">
              <i class="fa fa-check"></i> No product_id = 0 records found. Database is clean.
            </div>
            <?php } ?>
          </div>
        </div>
        
        <hr>
        
        <!-- Duplicate Models -->
        <div class="row">
          <div class="col-md-12">
            <h4><i class="fa fa-copy"></i> Duplicate Models</h4>
            <?php if (!empty($debug_info['duplicate_models'])) { ?>
            <div class="alert alert-warning">
              <strong>Warning:</strong> Found duplicate models:
              <table class="table table-bordered table-striped" style="margin-top: 10px;">
                <thead>
                  <tr>
                    <th>Model</th>
                    <th>Count</th>
                    <th>Product IDs</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($debug_info['duplicate_models'] as $dup) { ?>
                  <tr>
                    <td><?php echo htmlspecialchars($dup['model']); ?></td>
                    <td><span class="label label-warning"><?php echo $dup['count']; ?></span></td>
                    <td><?php echo $dup['product_ids']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } else { ?>
            <div class="alert alert-success">
              <i class="fa fa-check"></i> No duplicate models found.
            </div>
            <?php } ?>
          </div>
        </div>
        
        <hr>
        
        <!-- Duplicate SKUs -->
        <div class="row">
          <div class="col-md-12">
            <h4><i class="fa fa-barcode"></i> Duplicate SKUs</h4>
            <?php if (!empty($debug_info['duplicate_skus'])) { ?>
            <div class="alert alert-warning">
              <strong>Warning:</strong> Found duplicate SKUs:
              <table class="table table-bordered table-striped" style="margin-top: 10px;">
                <thead>
                  <tr>
                    <th>SKU</th>
                    <th>Count</th>
                    <th>Product IDs</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($debug_info['duplicate_skus'] as $dup) { ?>
                  <tr>
                    <td><?php echo htmlspecialchars($dup['sku']); ?></td>
                    <td><span class="label label-warning"><?php echo $dup['count']; ?></span></td>
                    <td><?php echo $dup['product_ids']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } else { ?>
            <div class="alert alert-success">
              <i class="fa fa-check"></i> No duplicate SKUs found.
            </div>
            <?php } ?>
          </div>
        </div>
        
        <hr>
        
        <!-- Product Information (if product_id provided) -->
        <?php if (isset($debug_info['product'])) { ?>
        <div class="row">
          <div class="col-md-12">
            <h4><i class="fa fa-cube"></i> Product Information (ID: <?php echo $debug_info['product']['product_id']; ?>)</h4>
            <?php if ($debug_info['product']) { ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <tr>
                  <th>Field</th>
                  <th>Value</th>
                </tr>
                <?php foreach ($debug_info['product'] as $key => $value) { ?>
                <tr>
                  <td><strong><?php echo $key; ?></strong></td>
                  <td><?php echo htmlspecialchars(is_array($value) ? print_r($value, true) : $value); ?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
            
            <?php if (!empty($debug_info['product_relations'])) { ?>
            <h5>Related Records</h5>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Table</th>
                  <th>Record Count</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($debug_info['product_relations'] as $table => $count) { ?>
                <tr>
                  <td><?php echo $table; ?></td>
                  <td><span class="label label-info"><?php echo $count; ?></span></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } ?>
            <?php } else { ?>
            <div class="alert alert-danger">
              <?php echo $debug_info['product_error']; ?>
            </div>
            <?php } ?>
          </div>
        </div>
        <hr>
        <?php } ?>
        
        <!-- Auto Increment Info -->
        <?php if (isset($debug_info['auto_increment'])) { ?>
        <div class="row">
          <div class="col-md-12">
            <h4><i class="fa fa-database"></i> Database Auto Increment Info</h4>
            <table class="table table-bordered">
              <tr>
                <th>Next Auto Increment Value</th>
                <td><?php echo $debug_info['auto_increment']['auto_increment']; ?></td>
              </tr>
              <tr>
                <th>Total Rows</th>
                <td><?php echo $debug_info['auto_increment']['rows']; ?></td>
              </tr>
            </table>
          </div>
        </div>
        <hr>
        <?php } ?>
        
        <!-- Recent Errors -->
        <?php if (!empty($debug_info['recent_errors'])) { ?>
        <div class="row">
          <div class="col-md-12">
            <h4><i class="fa fa-file-text"></i> Recent Error Logs (Last 50 lines)</h4>
            <div style="max-height: 400px; overflow-y: auto; background: #f5f5f5; padding: 10px; border: 1px solid #ddd; font-family: monospace; font-size: 12px;">
              <?php foreach (array_reverse($debug_info['recent_errors']) as $line) { ?>
              <div><?php echo htmlspecialchars($line); ?></div>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
        
      </div>
    </div>
    
  </div>
</div>
<?php echo $footer; ?>

