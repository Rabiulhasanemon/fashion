<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="panel-body">
    <div id="chart-article" style="width: 100%; height: 400px;"></div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/chanvasjs/jquery.canvasjs.min.js"></script>
<script type="text/javascript"><!--
  $.ajax({
    type: 'get',
    url: 'index.php?route=article_dashboard/chart/chart&token=<?php echo $token; ?>&range=' + $(this).attr('href'),
    dataType: 'json',
    success: function(json) {
      var options = {
        animationEnabled: true,
        title: false,
        data: [{
          type: "doughnut",
          innerRadius: "40%",
          showInLegend: true,
          legendText: "{label}",
          indexLabel: "{label}: #percent%",
          dataPoints: json
        }]
      };
      var chart = new CanvasJS.Chart("chart-article", options);
      chart.render()
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });

$('#range .active a').trigger('click');
//--></script> 