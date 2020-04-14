<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="img/favicon.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CIS4301: Group 7 Semester Project</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
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
          <li class="nav-item active">
              <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
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

  </button>
</div>
</nav>

<!--- Image Slider -->
<div id="slides" class="carousel slide" data-ride="carousel">
<ul class="carousel-indicators">
  <li data-target="#slides" data-slide-to="0" class="active"></li>
  <li data-target="#slides" data-slide-to="1"></li>
  <li data-target="#slides" data-slide-to="2"></li>
</ul>
<div class="carousel-inner">
  <div class="carousel-item active">
      <img src="img/background.png">
      <div class="carousel-caption">
          <h1 class="dvtitle">Data Visualization</h1>
          <h3 class="dvdesc"> Provides several visualizations on data, including comparative line graphs, pie charts, and more.
              <br/> How has crime changed over time in an area? What has the weather been like historically?
          </h3>
        <!--  <button type="button" class="btn btn-outline-light btn-lg">GO TO</button> -->
          <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href='dv.php';">GO TO</button>
      </div>

  </div>
  <div class="carousel-item">
      <img src="img/background2.png">
    <div class="carousel-caption">
      <h1 class="display-2">Status of Living Diagnostic </h1>
      <h3> Enables a user to specify weights for data profiles, and renders a score for a chosen area, using magic.
          <br/> Where should I live?
       </h3>
      <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href='sld.php';">GO TO</button>
    </div>
  </div>
  <div class="carousel-item">
      <img src="img/background3.png">
      <div class="carousel-caption">
        <h1 class="display-2">Map Representation </h1>
        <h3> How is the data geographically distributed in New York? In which areas is crime most prominent? </h3>
        <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href='map.php';">GO TO</button>
      </div>
  </div>
</div>

<a class="carousel-control-prev" href="#slides" role="button" data-slide="prev">
   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
   <span class="sr-only">Previous</span>
 </a>
 <a class="carousel-control-next" href="#slides" role="button" data-slide="next">
   <span class="carousel-control-next-icon" aria-hidden="true"></span>
   <span class="sr-only">Next</span>
 </a>
</div>



<!-- Maybe not needed beyond this -->
<!--- Jumbotron -->
<div class="container-fluid">
<div class="row jumbotron">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
          <p class="lead">For DEMO: Tuple Count Function --></p>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
      <form action="" method="post">
        <input type="submit" name="sub" class="btn btn-outline-secondary" value="CLICK HERE TO GET TUPLE COUNT"/>
      </form>
<?php
function tupleCount($val)
{
  $guser = getenv('DBUSER');
  $gpass = getenv('DBPASS');
  $gconn = getenv('DBCONN');
  $connection = oci_connect($username = $guser,
                          $password = $gpass,
                          $connection_string = $gconn);
  $statement = oci_parse($connection, 'select ( select count(*) from AADAMES.CLIMATEPROFILE )+( select count(*) from AADAMES.CRIMEPROFILE )+( select count(*) from AADAMES.ECONOMICPROFILE )+( select count(*) from AADAMES.educationprofile )+( select count(*) from AADAMES.housingprofile ) as total_rows from dual');
  $result = oci_execute($statement);

  while ($rows = oci_fetch_array($statement))
  {
    $stringval = strval($rows[0]);
    echo "The tuple count for all tables used is: {$stringval}.";
  }
}

if(isset($_POST['sub']))
{
  echo tupleCount($_POST['sub']);
}
?>
</div>
</div>

<!--- Welcome Section/Project Overview -->
<div class="container-fluid padding">
<div class="row welcome text-center">
      <div class="col-12">
        <h1 class="display-4">Heres some insight on our data and project goals:</h1>
      </div>
      <hr>
      <div class="col-12">
          <p class="lead">The goal of our project is to provide users some greater insight on an area in
            the state of New York. This is done by providing data visualizations on crime, climate, housing, and economical factors
            within an area. With all this data in mind users can make weigh their options and make
            a decision on where they would like to live in New York. If a user is stuck between several places
            they can use the Status of Living Diagnostic to calculate scores for an area based on weights they enter, and
            come to a conclusion that way. <br/>
            Our data was collected from multiple sources such as, </p>
</div>
<hr>
</div>

<!--- Three Column Section -->
<div class="container-fluid padding">
<div class="row text-center padding">
    <div class="col-xs-12 col-sm-6 col-md-4">
        <i class="fab fa-html5"></i>
          <h3>HTML5</h3>
          <p>Built using HTML5.</p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <i class="fas fa-bold"></i>
          <h3>BOOTSTRAP</h3>
          <p>Built using the Bootstrap 4 framework.</p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <i class="fab fa-css3"></i>
          <h3>CSS3</h3>
          <p>Built using CSS3.</p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <i class="fas fa-database"></i>
          <h3>CISE Oracle</h3>
          <p>Built using the CISE Oracle DBMS.</p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <i class="fab fa-php"></i>
          <h3>PHP</h3>
          <p>Connected the CISE Oracle DB with PHP.</p>
    </div>
</div>
<hr class="my-4">
</div>

<!--- Fixed background -->
<figure>
    <div class="fixed-wrap">
        <div id="fixed">
        </div>
      </div>
</figure>

<!--- Footer -->
<footer>
<div class="col-12 text-center">

    <hr class="dark">
    <h5>&copy;  CIS4301 Group 7 - University of Florida</h5>
  </div>

</footer>

</body>
</html>
