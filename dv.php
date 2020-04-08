<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="img/favicon.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Data Visualization</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
	<script
				src="https://code.jquery.com/jquery-3.4.1.min.js"
				integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
				crossorigin="anonymous"></script>
	<link href="style.css" rel="stylesheet">
</head>
<body>
  <!-- Navigation Bar -->

  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="img/logo.png"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="dv.php">Data Visualization</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sld.php">Status of Living Diagnostic</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="map.php">Map Representation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="search.php">Filter/Search</a>
            </li>
        </ul>
    </div>


  </div>
  </nav>
<!--- Chart Container --->
<body>
<canvas id="chartcanvas" width="800" height="200"></canvas>

	<script>
window.addEventListener('load', setup);

async function setup() {
	var ctx = document.getElementById('chartcanvas').getContext('2d');
	const globalTemps = await getData();
        const myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: globalTemps.counties,
            datasets: [
              {
                label: 'Average Temperature in °F For The Years 2014-2019',
                data: globalTemps.temps,
                fill: true,
                borderColor: 'rgba(46, 84, 255, 1)',
                backgroundColor: 'rgba(46, 84, 255, 0.65)',
                borderWidth: 1
              }
            ]
          },
          options: {
						scales: {
							yAxes: [
								{
									ticks: {
										callback: function(value, index, values) {
											return value + '°';
										}
									}
								}
							]
						}
					}
        });
}


	async function getData() {
        const response = await fetch('res/Climate Data/NY Avg Temp.csv');
        const data = await response.text();
        const counties = [];
        const temps = [];
        const rows = data.split('\n').slice(1);
        rows.forEach(row => {
          const cols = row.split(',');
          counties.push(cols[1]);
          temps.push(parseFloat(cols[2]));
        });
        return { counties, temps };
      }

	</script>
</body>


<!--- Footer -->
<footer>
<div class="col-12 text-center">

      <hr class="dark">
      <h5>&copy;  CIS4301 Group 7 - University of Florida</h5>
    </div>

</footer>
</body>
