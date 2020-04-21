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
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
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
        </ul>
    </div>


  </div>
  </nav>

<!--- Database Connection --->
<?php include('data.php')?>

<!--- Scatter --->
<div class="graphrow">
	<div class="graphcolumn">
			<h5 class="graph1" align="center">Crime vs. Population Scatter</h5>
			<canvas id='ctx' width="200" height="100%" style="float: right;"></canvas>
	</div>
	<div class="graphcolumn">
			<h5 class="graph1" align="center">Crime vs. Population Comparative Line Graph</h5>
			<canvas id='linecomp' width="200" height="100%"></canvas>
 </div>
</div>
<div>
<button class="pcc" id="pccid" onclick="pcc()" style="color: #1a73e8;">Pearson's Correlation Coefficient: Click to compute...</button>
</div>
<script>
function pcc() {
	var correlationCoefficient = <?php echo json_encode($correlationresult); ?>;

	document.getElementById("pccid").innerHTML = "Applying Pearson's Correlation Coefficient Test (computed through SQL) we see that the correlation coefficient is: " + correlationCoefficient + "." + "\n This indicates that there is a very strong positive correlation between the population rate and crime rate, the higher the population the higher the crime rate.";
	document.getElementById("pccid").style.color = "purple";
}
</script>
<!--- Scatter script --->
<script>
function getScatterData() {
	var scatterCrime = <?php echo json_encode($crimecountsarr); ?>;
	var scatterPop = <?php echo json_encode($popcountarr); ?>;
			return { scatterCrime, scatterPop };
		}

var scatterData = getScatterData();
var plot = [];
for(var i=0;i<62;i++)
				{
						x = scatterData.scatterCrime[i];
						y = scatterData.scatterPop[i];
						var json = {x: x, y: y-1};
						plot.push(json);
				}
var scatterChart = new Chart(ctx, {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Crime vs. Population',
            data: plot,
						backgroundColor: 'rgba(160, 59, 59, 1)'
        }]
    },
    options: {
        scales: {
            xAxes: [{
                type: 'linear',
                position: 'bottom'
            }]
        }
    }
});
</script>
<!--- Comparative Line graph script -->
<script>
window.addEventListener('load', testsetup1);
async function getScatterData() {
			 //Descriptions
			 	var scatterCrime = <?php echo json_encode($crimecountsarr); ?>;
 		 		var scatterPop = <?php echo json_encode($popcountarr); ?>;
				const response = await fetch('res/Climate Data/NY Avg Temp.csv');
        const countydata = await response.text();
        const counties = [];
				const rows = countydata.split('\n').slice(1);
        rows.forEach(row => {
          const cols = row.split(',');
          counties.push(cols[1]);
        });
				return {counties, scatterCrime, scatterPop};
		}

async function testsetup1()
{
const scatData = await getScatterData();

dataObjects2 = [
  {
    label: "Crime Count",
    data: scatData.scatterCrime
  },
  {
    label: "Population Count",
    data: scatData.scatterPop
  }
]

labelObjects2 = [
	{
		labels: scatData.counties
	},
	{
		labels: scatData.counties
	}
]

/* data */
var data = {
  labels: labelObjects2[0].labels,
  datasets: [  {
    label:  dataObjects2[0].label,
    data: dataObjects2[0].data,
    /* global setting */
    backgroundColor: 'rgba(46, 84, 255, 0.65)',
    borderColor: 'rgba(46, 84, 255, 1)',
    borderWidth: 1
  },
	{
		label:  dataObjects2[1].label,
    data: dataObjects2[1].data,
    /* global setting */
    backgroundColor: 'rgba(160, 59, 59, 0.65)',
    borderColor: 'rgba(160, 59, 59, 1)',
    borderWidth: 1
	}]
};

var options = {

};

chart1 = new Chart('linecomp', {
  type: 'line',
  data: data,
  options: options
});
}
</script>


<script>
//!!!!!!!!!!!!!!!!!!!!CRIME AND EDUCATION!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	var CrimeRate = <?php echo json_encode($crimeratearr); ?>;
	var EducationRate = <?php echo json_encode($educationrate); ?>;
  var Counties = <?php echo json_encode($counties); ?>;
 
var lineCrimeRate = {
  x: Counties,
  y: CrimeRate,
  type: 'scatter',
  name: 'Crime Rate'
};

var lineEducationRate = {
  x: Counties,
  y: EducationRat,
  type: 'scatter',
  name: 'Education Rate'
};

var data = [lineCrimeRate, lineEducationRate];

var layout = {
  title: 'Comparision of Crime Rate with Education',
  xaxis: {
    title: 'Rate of Crime by Population'
  },
  yaxis: {
    title: 'Rate of Highschool Education and Higher by Population'
  }
};

//CHANGE MY DIV TO CURRENT DIV CONTAINER
Plotly.newPlot('myDiv', data)

</script> 


<script>
//!!!!!!!!!!!!!!!!!!!!AVG TEMP AND INCOME OVER THE YEARS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
var trace1 = {
  x: <?php echo json_encode($avgtemp); ?>,
  y: [40, 45, 50, 55, 60],
  name: 'Average Temperature',
  type: 'scatter'
};

var trace2 = {
  x: [2, 3, 4],
  y: [4, 5, 6],
  name: 'Income',
  yaxis: 'y2',
  type: 'scatter'
};

var data = [trace1, trace2];

var layout = {
  title: 'Comparision of Climate and Income',
  yaxis: {title: 'Average Temperature'},
  yaxis2: {
    title: 'Median Household Income',
    titlefont: {color: 'rgb(148, 103, 189)'},
    tickfont: {color: 'rgb(148, 103, 189)'},
    overlaying: 'y',
    side: 'right'
  }
};
//CHANGE MY DIV TO CURRENT DIV CONTAINER
Plotly.newPlot('myDiv', data, layout);

</script>


<!--- Drop Down Crime Bar Graph -->
<hr class="hrgraph1">
<h5 class="graph1" align="center" style="padding-bottom: 0.8rem;"> Top 3 Crimes in New York from 2014-2017</h5>
<div id="myDIV" align="center">
  <button class="btngraph active" onclick="changeData(0)">Top 3: 2014</button>
  <button class="btngraph" onclick="changeData(1)">Top 3: 2015</button>
  <button class="btngraph" onclick="changeData(2)">Top 3: 2016</button>
	<button class="btngraph" onclick="changeData(3)">Top 3: 2017</button>
</div>
<canvas id="chart-0" width="400" height="100"></canvas>
</div>

<!--- Line Graph --->
<hr class="hrgraph1">
<div class="container-fluid padding">
<h5 class="graph1" align="center">Weather Statistics for all New York Counties 2014-2017</h5>
<div id="myDIV1" align="center">
  <button class="btngraph active" onclick="changeData1(0)">Average Temperature</button>
  <button class="btngraph" onclick="changeData1(1)">Average Precipitation Level</button>
</div>
<canvas id="multiline" width="800" height="200"></canvas>
<script>
var chart1;
var dataObjects1;
var labelObjects1;
window.addEventListener('load', testsetup);
async function getClimData() {
			 //Descriptions
				var tempavg = <?php echo json_encode($data); ?>;
				var lvlprecip = <?php echo json_encode($precipdata); ?>;
				const response = await fetch('res/Climate Data/NY Avg Temp.csv');
        const countydata = await response.text();
        const counties = [];
				const rows = countydata.split('\n').slice(1);
        rows.forEach(row => {
          const cols = row.split(',');
          counties.push(cols[1]);
        });
				return {counties, tempavg, lvlprecip};
		}

async function testsetup()
{
const climData = await getClimData();

dataObjects1 = [
  {
    label: "Average Temperature (°F) in New York for the Years 2014-2017",
    data: climData.tempavg
  },
  {
    label: "Average Precipitation Level (inches) in New York for the Years 2014-2017",
    data: climData.lvlprecip
  }
]

labelObjects1 = [
	{
		labels: climData.counties
	},
	{
		labels: climData.counties
	}
]

/* data */
var data = {
  labels: labelObjects1[0].labels,
  datasets: [  {
    label:  dataObjects1[0].label,
    data: dataObjects1[0].data,
    /* global setting */
    backgroundColor: 'rgba(46, 84, 255, 0.65)',
    borderColor: 'rgba(46, 84, 255, 1)',
    borderWidth: 1
  }]
};

var options = {

};

chart1 = new Chart('multiline', {
  type: 'line',
  data: data,
  options: options
});

}
function changeData1(index) {
  chart1.data.datasets.forEach(function(dataset) {
    dataset.label = dataObjects1[index].label;
    dataset.data = dataObjects1[index].data;

    //dataset.backgroundColor = dataObjects[index].backgroundColor;
  });
	chart1.data.labels = labelObjects1[index].labels;
  chart1.update();
}
</script>




<hr class="hrgraph1">

<!--- Pie Chart for Renter Occupied Houses in NY --->
<div id="piecontainer">
<div id="pie1">
<h5 class="graph1" align="center" style="padding-bottom: 0.8rem;">Renter Occupied Housing in New York Counties</h5>
<canvas id="chartcanvas2" width="400" height="100"></canvas>
<hr class="hrgraph1">
</div>
<div id="pie2">
<h5 class="graph1" align="center" style="padding-bottom: 0.8rem;">Owner Occupied Housing in New York Counties</h5>
<canvas id="chartcanvas3" width="400" height="100"></canvas>
<hr class="hrgraph1">
</div>
</div>


<script>
window.addEventListener('load', setup2);

async function setup2() {
	var ctx2 = document.getElementById('chartcanvas2').getContext('2d');
	const nyHousing = await getData2();

	var colors = [];
	var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
         };
				 for (let i = 0; i < 62; i++)
				 {
					 colors.push(dynamicColors());
				 }

				         const myChart2 = new Chart(ctx2, {
				           type: 'doughnut',
				           data: {
				             labels: nyHousing.counties,
				             datasets: [
				               {
				                 data: nyHousing.renterOccupied,
				                 fill: true,
				                 backgroundColor: colors,
				                 borderWidth: 1
				               }
				             ]
				           },
				           options: {}
				         });
				 }
				 async function getData2() {
				 			 const response = await fetch('res/Housing Data/Housing_data.csv');
				 			 const data = await response.text();
				 			 const counties = [];
				 			 const ownerOccupied = [];
				 			 const renterOccupied = [];
				 			 const rows = data.split('\n').slice(1);
				 			 rows.forEach(row => {
				 				 const cols = row.split(',');
				 				 counties.push(cols[1]);
				 				 renterOccupied.push(parseFloat(cols[7]));
				 			 });
				 			 return { counties, renterOccupied };
				 		 }
</script>

<script>
window.addEventListener('load', setup3);

async function setup3() {
	var ctx3 = document.getElementById('chartcanvas3').getContext('2d');
	const nyHousing2 = await getData3();

	var colors = [];
	var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
         };
				 for (let i = 0; i < 62; i++)
				 {
					 colors.push(dynamicColors());
				 }

				         const myChart3 = new Chart(ctx3, {
				           type: 'doughnut',
				           data: {
				             labels: nyHousing2.counties,
				             datasets: [
				               {
				                 data: nyHousing2.ownerOccupied,
				                 fill: true,
				                 backgroundColor: colors,
				                 borderWidth: 1
				               }
				             ]
				           },
				           options: {

				 					}
				         });
				 }
				 async function getData3() {
				 			 const response = await fetch('res/Housing Data/Housing_data.csv');
				 			 const data = await response.text();
				 			 const counties = [];
				 			 const ownerOccupied = [];
				 			 const renterOccupied = [];
				 			 const rows = data.split('\n').slice(1);
				 			 rows.forEach(row => {
				 				 const cols = row.split(',');
				 				 counties.push(cols[1]);
				 				 ownerOccupied.push(parseFloat(cols[6]));
				 				 renterOccupied.push(parseFloat(cols[7]));
				 			 });
				 			 return { counties, ownerOccupied, renterOccupied };
				 		 }
</script>

<!--- Drop Down Bar Chart -->
<script>
function getTop3Data() {
			 //Descriptions
			 var d2014 = <?php echo json_encode($crime2014_desc); ?>;
			 var d2015 = <?php echo json_encode($crime2015_desc); ?>;
			 var d2016 = <?php echo json_encode($crime2016_desc); ?>;
			 var d2017 = <?php echo json_encode($crime2017_desc); ?>;
			 //Counts
			 var c2014 = <?php echo json_encode($crime2014_count); ?>;
			 var c2015 = <?php echo json_encode($crime2015_count); ?>;
			 var c2016 = <?php echo json_encode($crime2016_count); ?>;
			 var c2017 = <?php echo json_encode($crime2017_count); ?>;
			return { d2014, d2015, d2016, d2017, c2014, c2015, c2016, c2017 };
		}

const crimeData = getTop3Data();

var dataObjects = [
  {
    label: "Top 3 Crimes 2014",
    data: [crimeData.c2014[0], crimeData.c2014[1], crimeData.c2014[2]]
  },
  {
    label: "Top 3 Crimes 2015",
    data: [crimeData.c2015[0], crimeData.c2015[1], crimeData.c2015[2]]
  },
  {
    label: "Top 3 Crimes 2016",
    data: [crimeData.c2016[0], crimeData.c2016[1], crimeData.c2016[2]]
  },
	{
		label: "Top 3 Crimes 2017",
		data: [crimeData.c2017[0], crimeData.c2017[1], crimeData.c2017[2]]
	}
]

var labelObjects = [
	{
		labels: [crimeData.d2014[0], crimeData.d2014[1], crimeData.d2014[2]]
	},
	{
		labels: [crimeData.d2015[0], crimeData.d2015[1], crimeData.d2015[2]]
	},
	{
		labels: [crimeData.d2016[0], crimeData.d2016[1], crimeData.d2016[2]]
	},
	{
		labels: [crimeData.d2017[0], crimeData.d2017[1], crimeData.d2017[2]]
	}
]

/* data */
var data = {
  labels: labelObjects[0].labels,
  datasets: [  {
    label:  dataObjects[0].label,
    data: dataObjects[0].data,
    /* global setting */
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(255, 206, 86, 0.2)'
    ],
    borderColor: [
      'rgba(255, 99, 132, 1)',
      'rgba(54, 162, 235, 1)',
      'rgba(255, 206, 86, 1)'
    ],
    borderWidth: 1
  }]
};

var options = {
  legend: {
    display: true,
    fillStyle: "red",

    labels: {
      boxWidth: 0,
      fontSize: 24,
      fontColor: "black",
    }
  },
  scales: {
    xAxes: [{
      stacked: false,
      scaleLabel: {
        display: true,
        labelString: 'Offense Description'
      },
    }],
    yAxes: [{
      stacked: true,
      scaleLabel: {
        display: true,
        labelString: 'Crime Count'
      },
      ticks: {
        suggestedMin: 0,
        suggestedMax: 10
      }
    }]
  },/*end scales */
  plugins: {
    datalabels: {
      color: 'black',
      font:{
        size: 25
      }
    }
  }
};

var chart = new Chart('chart-0', {
  type: 'bar',
  data: data,
  options: options
});

function changeData(index) {
  chart.data.datasets.forEach(function(dataset) {
    dataset.label = dataObjects[index].label;
    dataset.data = dataObjects[index].data;

    //dataset.backgroundColor = dataObjects[index].backgroundColor;
  });
	chart.data.labels = labelObjects[index].labels;
  chart.update();
}

/* add active class on click */
// Add active class to the current button (highlight it)
var header = document.getElementById("myDIV");
var btns = header.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>


<!--- Footer -->
<footer>
<div class="col-12 text-center">

      <hr class="dark">
      <h5>&copy;  CIS4301 - Group 7 - University of Florida</h5>
    </div>

</footer>
</body>
