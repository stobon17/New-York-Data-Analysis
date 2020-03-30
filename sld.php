<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="img/favicon.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Status of Living Diagnostic</title>
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
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dv.php">Data Visualization</a>
            </li>
            <li class="nav-item active">
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

	<!--- Welcome Section/Project Overview -->
	<div class="container-fluid padding">
	<div class="user inputs">
	      <div class="col-12">
	        <h1 class>Select a County to observe and desired weights for each profile to receive a score for that County!</h1>
	      </div>
	      <hr>
				Select County to be observed:
				    <select id="nycountieslist">
				  <?php
								$filename = 'res/nycounties.txt';
								$eachlines = file($filename, FILE_IGNORE_NEW_LINES);//create an array

									foreach($eachlines as $lines){
										echo "<option>{$lines}</option>";
									}

					?>
				</select>
					<br>
					Crime:		<input id="text1" type="number" placeholder="Enter weight for crime." size="23"><br>
					Education:		<input id="text2" type="number" placeholder="Enter weight for education." size="27"><br>
					Climate:		<input id="text3" type="number" placeholder="Enter weight for climate." size="25"><br>
					Economy:		<input id="text4" type="number" placeholder="Enter weight for economy." size="25"><br>
					Housing:		<input id="text5" type="number" placeholder="Enter weight for housing." size="25">
					<br>
					<button onclick="fn1()" id="btn1">Submit</button>

					<script type="text/javascript">
						var storedRes;
						function fn1()
						{
							//Take inputs
							var crimeInput = document.getElementById("text1").value;
							var educationInput = document.getElementById("text2").value;
							var climateInput = document.getElementById("text3").value;
							var economyInput = document.getElementById("text4").value;
							var housingInput = document.getElementById("text5").value;

							//Convert to ints
							var crimeNum = parseInt(crimeInput);
							var educationNum = parseInt(educationInput);
							var climateNum = parseInt(climateInput);
							var economyNum = parseInt(economyInput);
							var housingNum = parseInt(housingInput);

							var tot = crimeNum + educationNum + climateNum + economyNum + housingNum;

							if (tot != 10)
							{
								alert("ERROR: All weights must sum to 10");
							}
							else {
									getSelectText();
									var score = 0;
									score = (crimeNum * 0.5) + (educationNum * 0.5) + (climateNum * 0.50) + (economyNum * 0.50) + (housingNum * 0.50)
									alert("The score for " + storedRes +  " is " + score + ".");
							}
						}
						function getSelectText()
						{
							var e = document.getElementById("nycountieslist");
							var result = e.options[e.selectedIndex].text;
							storedRes = result;
						}
				</script>

	</div>
	</div>


<!--- Footer -->
<footer>
<div class="col-12 text-center">

      <hr class="dark">
      <h5>&copy;  CIS4301 Group 7 - University of Florida</h5>
    </div>

</footer>
</body>
