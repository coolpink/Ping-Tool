<div class="row-fluid">

  <div class="span12">
    <div class="hero-unit">
      <h2>I have now checked <?php echo $projects->count(); ?> websites a total of <span class="check-count"><?php echo $check_count ?></span> times!</h2>
      <p>That's pretty cool, considering since this tools creation we have had <?php echo $upTime; ?>% up time across all our sites!</p>
    </div>
  </div>

</div>
<div class="row-fluid">
  <div class="span8">
    <div class="stats">
      <div class="last-updated"><small>(last updated <?php echo date("Y-m-d H:i:s"); ?>)</small></div>
      <div id="uptime_chart"></div>
    </div>
  </div>

  <div class="span4">
    <div class="well sidebar-nav">
      <ul class="nav nav-list clients">
        <?php foreach ($clients as $client): ?>
          <li class="nav nav-header"><?php echo $client->getTitle(); ?></li>
          <?php foreach ($client->getProjects() as $project): ?>
            <li class="client"><strong><?php echo $project->getDomain(); ?></strong> <span class="status"></span></li>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>


<script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge, corechart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Label');
        data.addColumn('number', 'Value');
        data.addRows([
          ['Uptime (%)', <?php echo $upTime; ?>]
        ]);

        var options = {
          width: 150, height: 150,
          greenFrom: 90, greenTo: 100,
          yellowFrom:75, yellowTo: 90,
          redFrom:0, redTo: 20,
          minorTicks: 10,
          'animation.easing': 'in'
        };

        var chart = new google.visualization.Gauge(document.getElementById("uptime_chart"));
        chart.draw(data, options);
      }

      setTimeout(function() {
        
      })
    </script>