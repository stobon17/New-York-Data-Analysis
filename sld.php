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
        </ul>
    </div>


  </div>
  </nav>

	<!--- Calculate Score Section -->
	<div class="container-fluid padding">
	<div class="user inputs">
	      <div class="col-12">
	        <h1 align="center">Select a County to observe and desired weights (all weights must sum to <u>exactly</u> 10) for each profile to receive a score for that County!</h1>
	      </div>
	      <hr>
						<p align="left">Select County to be observed:
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
					<button onclick="fn1()" id="btn1" class="btn btn-outline-primary">Submit</button>
					<?php
					//Establish connection to database.
					$guser = getenv('DBUSER');
					$gpass = getenv('DBPASS');
					$gconn = getenv('DBCONN');
					$connection = oci_pconnect($username = $guser,
					                        $password = $gpass,
					                        $connection_string = $gconn);
					//Make sure connection was sucessful.
					if (!$connection) {
					    $e = oci_error();
					    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
					}

					function makeArraysld(&$query, &$arr1, &$arr2, &$arr3, &$arr4, &$arr5)
					{
					  while( ($row = oci_fetch_row($query)) != false)
					  {
					      $temp = $row[0];
								$temp2 = $row[1];
								$temp3 = $row[2];
								$temp4 = $row[3];
								//Housing $temp5 = $row[4];
					      array_push($arr1, $temp); //Educ
								array_push($arr2, $temp2); //econ
								array_push($arr3, $temp3); //climate
								array_push($arr4, $temp4); //crime
								//array_push($arr5, $temp5);
					  }
					}

					//Get all multipliers
					$sldcombined = oci_parse($connection, 'WITH educ AS
																								  (SELECT LEAST((HSDIPLOMA + BACHELORDEGREE) /
																								                 AVG(HSDIPLOMA + BACHELORDEGREE) OVER (), 1) AS educ_res, cid
																								   FROM AADAMES.EDUCATIONPROFILE
																								  ),
																								     econ AS
																								  (SELECT LEAST((EMPLOYED -  UNEMPLOYED) /
																								                AVG(EMPLOYED - UNEMPLOYED) OVER (), 1) AS econ_res, cid
																								   FROM AADAMES.ECONOMICPROFILE
																								  ),
																								     clim AS
																								  (SELECT ((MAXTEMP + MINTEMP)/ 2)/
																								           (SELECT MAX((MAXTEMP + MINTEMP)/ 2) FROM AADAMES.CLIMATEPROFILE) AS climate_res, cid
																								   FROM AADAMES.CLIMATEPROFILE
																								  ),
																								     crime AS
																								  (SELECT (crimecount) / (SELECT MAX(CRIMECOUNT)  FROM CRIMECOUNTS) AS crime_res, cid
																								   FROM CRIMECOUNTS
																								  )

																								SELECT educ.educ_res, econ.econ_res, clim.climate_res, crime.crime_res
																								FROM educ
																								JOIN econ
																								    ON educ.cid = econ.cid
																								JOIN clim
																								    ON clim.cid = educ.cid
																								JOIN crime
																								    ON crime.cid = educ.cid
																								ORDER BY educ.cid');
					oci_execute($sldcombined);

					//Arrays per Profile
					$sldedures = array();
					$sldeconres = array();
					$sldclimres = array();
					$sldcrimeres = array();
					$sldhousingres = array();
					makeArraysld($sldcombined, $sldedures, $sldeconres, $sldclimres, $sldcrimeres, $sldhousingres);
					oci_free_statement($sldcombined);
					?>
					<script type="text/javascript">
						var storedRes;
						var score = 0;
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

							if (tot != 10.0)
							{
								alert("ERROR: All weights must sum to 10");
							}
							else {
									getSelectText();
									//Initialize multipliers
									var crimeMul, educationMul, climateMul, economyMul, housingMul;

									//Multiplier Arrays
									crimeMulArr = <?php echo json_encode($sldcrimeres); ?>;
									eduMulArr = <?php echo json_encode($sldedures); ?>;
									climMulArr = <?php echo json_encode($sldclimres); ?>;
									econMulArr = <?php echo json_encode($sldeconres); ?>;
									housingMulArr = <?php echo json_encode($sldhousingres); ?>;

									//Get Index of Select list
									var x = document.getElementById("nycountieslist").selectedIndex;
  								var y = document.getElementById("nycountieslist").options;
									crimeMul = crimeMulArr[y[x].index];
									educationMul = eduMulArr[y[x].index];
									climateMul = climMulArr[y[x].index];
									economyMul = econMulArr[y[x].index];
									housingMul = housingMulArr[y[x].index];
									//For crime get MAX Count of cases per county, the county with highest count will be MAXCOUNT then per each county
									//Find the count for that county and divide it by the MAXCOUNT = 301348.
									housingMul = housingMul / 10;

									score = (-1)*(crimeNum * crimeMul) + (educationNum * educationMul) + (climateNum * climateMul) + (economyNum * economyMul) + (housingNum * 1);
									//Fix decimals
									score = score.toFixed(2);
									//Update text;


									document.getElementById("scoreIndicator").innerHTML = "We give " + storedRes + " a score of " + score + ".";
									//Change color - with color range
									if (score < 4)
									{
										document.getElementById("scoreIndicator").style.color = "red";
									}else if (score > 4 && score < 7)
									{
										document.getElementById("scoreIndicator").style.color = "DarkGoldenRod";
									}else
									{
											document.getElementById("scoreIndicator").style.color = "green";
									}
							}
						}
						function getSelectText()
						{
							var e = document.getElementById("nycountieslist");
							var result = e.options[e.selectedIndex].text;
							storedRes = result;
						}
				</script>
<p class="scoreI" id="scoreIndicator" align="right">Input weights and submit to calculate...</p>
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
