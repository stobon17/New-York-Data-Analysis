<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="img/favicon.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Map Representation</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
	<link href="style.css" rel="stylesheet">

	<!-- Map stuff -->
	<link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css?dp-version=1578490236" />
	<script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
	<script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
	<script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
	<script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
	<script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-clustering.js"></script>
	<script type="text/javascript" src="res/crimeJson.js"></script>


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
            <li class="nav-item">
                <a class="nav-link" href="dv.php">Data Visualization</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sld.php">Status of Living Diagnostic</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="map.php">Map Representation</a>
            </li>
        </ul>
    </div>
  </div>
  </nav>

	<!-- Map Container -->
<div id="map" style="width: 100%; height: 820px; background: grey" />

  <script  type="text/javascript" charset="UTF-8" >
	function startClustering(map, data) {
	  // First we need to create an array of DataPoint objects,
	  // for the ClusterProvider
	  var dataPoints = data.map(function (item) {
	    return new H.clustering.DataPoint(item.Latitude, item.Longitude);
	  });

	  // Create a clustering provider with custom options for clusterizing the input
	  var clusteredDataProvider = new H.clustering.Provider(dataPoints, {
	    clusteringOptions: {
	      // Maximum radius of the neighbourhood
	      eps: 32,
	      // minimum weight of points required to form a cluster
	      minWeight: 1
	    }
	  });

	  // Create a layer tha will consume objects from our clustering provider
	  var clusteringLayer = new H.map.layer.ObjectLayer(clusteredDataProvider);

	  // To make objects from clustering provder visible,
	  // we need to add our layer to the map
	  map.addLayer(clusteringLayer);
	}


	/**
	 * Boilerplate map initialization code starts below:
	 */

	// Step 1: initialize communication with the platform
	var platform = new H.service.Platform({
	  app_id: 'LTPOes7v59wySI4HzyNc',
	  app_code: 'hb971n3Xnvlzd_RS5g3T2V-fzgaLuDpwPn-aJyvdKqA'

	});
	var pixelRatio = window.devicePixelRatio || 1;
	var defaultLayers = platform.createDefaultLayers();

	// Step 2: initialize a map, center on NY
	var map = new H.Map(document.getElementById('map'), defaultLayers.normal.map, {
	  center: new H.geo.Point(40.711874, -74.008026),
	  zoom: 11,
	  pixelRatio: pixelRatio
	});

	// Step 3: make the map interactive
	// MapEvents enables the event system
	// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
	var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));


	// Step 4: create the default UI component, for displaying bubbles
	var ui = H.ui.UI.createDefault(map, defaultLayers);

	// Step 5: request a data about airports's coordinates
	    //console.log(JSONobj);

	  startClustering(map, crimeJson);
</script>
</div>

<!--- Footer -->
<footer>
<div class="col-12 text-center">

      <hr class="dark">
      <h5>&copy;  CIS4301 Group 7 - University of Florida</h5>
    </div>

</footer>
</body>
