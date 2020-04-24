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

//Weather Avg
$avgtemp = oci_parse($connection, 'SELECT (MAXTEMP + MINTEMP) / 2 FROM AADAMES.CLIMATEPROFILE'); //*** QUERY TREND 1 ***
$result = oci_execute($avgtemp);

$data = array();

while( ($row = oci_fetch_row($avgtemp)) != false)
{
    $temp = $row[0];
    array_push($data, $temp);
}

oci_free_statement($avgtemp);

//Precipitation
$precip = oci_parse($connection, 'SELECT PRECIPLVL FROM AADAMES.CLIMATEPROFILE ORDER BY cid');
$precipres = oci_execute($precip);

$precipdata = array();

while ( ($row = oci_fetch_row($precip)) != false)
{
  $temp = $row[0];
  array_push($precipdata, $temp);
}

//Getting Max Count, Median, and Min of Crime per Year
function makeArray(&$query, &$arr1, &$arr2)
{
  while( ($row = oci_fetch_row($query)) != false)
  {
      $temp = $row[0];
      $temp2 = $row[1];
      array_push($arr1, $temp);
      array_push($arr2, $temp2);
  }
}

//2014
$crime2014 = oci_parse($connection, "SELECT * FROM (SELECT OFFENSEDESC, COUNT(*) AS num FROM AADAMES.CRIMEPROFILE WHERE ARRESTDATE LIKE '%-14' GROUP BY OFFENSEDESC ORDER BY num DESC) WHERE ROWNUM <= 3"); //*** TREND QUERY 2
$result = oci_execute($crime2014);
$crime2014_desc = array();
$crime2014_count = array();
makeArray($crime2014, $crime2014_desc, $crime2014_count);
oci_free_statement($crime2014);

//2015
$crime2015 = oci_parse($connection, "SELECT * FROM (SELECT OFFENSEDESC, COUNT(*) AS num FROM AADAMES.CRIMEPROFILE WHERE ARRESTDATE LIKE '%-15' GROUP BY OFFENSEDESC ORDER BY num DESC) WHERE ROWNUM <= 3");
$result = oci_execute($crime2015);
$crime2015_desc = array();
$crime2015_count = array();
makeArray($crime2015, $crime2015_desc, $crime2015_count);
oci_free_statement($crime2015);

//2016
$crime2016 = oci_parse($connection, "SELECT * FROM (SELECT OFFENSEDESC, COUNT(*) AS num FROM AADAMES.CRIMEPROFILE WHERE ARRESTDATE LIKE '%-16' GROUP BY OFFENSEDESC ORDER BY num DESC) WHERE ROWNUM <= 3");
$result = oci_execute($crime2016);
$crime2016_desc = array();
$crime2016_count = array();
makeArray($crime2016, $crime2016_desc, $crime2016_count);
oci_free_statement($crime2016);

//2017
$crime2017 = oci_parse($connection, "SELECT * FROM (SELECT OFFENSEDESC, COUNT(*) AS num FROM AADAMES.CRIMEPROFILE WHERE ARRESTDATE LIKE '%-17' GROUP BY OFFENSEDESC ORDER BY num DESC) WHERE ROWNUM <= 3");
$result = oci_execute($crime2017);
$crime2017_desc = array();
$crime2017_count = array();
makeArray($crime2017, $crime2017_desc, $crime2017_count);
oci_free_statement($crime2017);


//CRIMECOUNTS
$crimecounts = oci_parse($connection, 'SELECT YEAR2016+YEAR2017+YEAR2018 FROM AADAMES.CRIMECOUNT ORDER BY countyname');
$result = oci_execute($crimecounts);
$crimecountsarr = array();
while( ($row = oci_fetch_row($crimecounts)) != false)
{
    $temp = $row[0];
    array_push($crimecountsarr, $temp);
}
oci_free_statement($crimecounts);
$popcounts = oci_parse($connection, 'SELECT POPULATION FROM AADAMES.CRIMECOUNT ORDER BY countyname');
$result = oci_execute($popcounts);

$popcountarr = array();
while( ($row = oci_fetch_row($popcounts)) != false)
{
    $temp = $row[0];
    array_push($popcountarr, $temp);
}
oci_free_statement($popcounts);

//CRIMERATE FROM CRIMECOUNTS 2016
$crimerate = oci_parse($connection, 'SELECT YEAR2016/POPULATION AS CRIMERATE FROM AADAMES.CRIMECOUNT ORDER BY countyname'); //*** TREND QUERY 3 ***
$result = oci_execute($crimerate);
$crimeratearr = array();
while( ($row = oci_fetch_row($crimerate)) != false)
{
    $temp = $row[0];
    array_push($crimeratearr, $temp);
}

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//EDUCATION RATE FROM 2016

$education = oci_parse($connection, 'SELECT (HSDIPLOMA+ASDEGREE+BACHELORDEGREE+GRADUATE) AS EDU FROM AADAMES.EDUCATIONPROFILE ORDER BY CID');
$result = oci_execute($education);
$educationarr = array();
while( ($row = oci_fetch_row($education)) != false)
{
    $temp = $row[0];
    array_push($educationarr, $temp);
}


//COUNTIES (FOR X AXIS ON LINE GRAPH)

$counties = oci_parse($connection, 'SELECT countyname FROM AADAMES.CRIMECOUNT ORDER BY countyname');
$result = oci_execute($counties);
$countiesarr = array();
while( ($row = oci_fetch_row($counties)) != false)
{
    $temp = $row[0];
    array_push($countiesarr, $temp);
}

//MEDIAN INCOME OF HOUSING  TABLE IS NOT MADE YET BUT DATA IS HERE
$income = oci_parse($connection, 'SELECT  MEDIANINCOME FROM AADAMES.HOUSING ORDER BY countyname');
$result = oci_execute($income);
$incomearr  = array();
while( ($row = oci_fetch_row($income )) != false)
{
    $temp = $row[0];
    array_push($incomearr , $temp);
}

//POPULATION SIZE
$popsize = oci_parse($connection, 'SELECT POPULATIONSIZE  FROM AADAMES.POPULATIONPROFILE ORDER BY countyname');
$result = oci_execute($popsize);
$popsizearr = array();
while( ($row = oci_fetch_row($popsize)) != false)
{
    $temp = $row[0];
    array_push($popsizearr, $temp);
}


//Computing Correlation between Crime and Population   // **TEND QUERY 5
$correlation = oci_parse($connection, 'WITH pop AS
  (SELECT POPULATIONSIZE, countyid FROM AADAMES.POPULATIONPROFILE
  ),
     crime AS
  (SELECT CRIMECOUNT, cid FROM STOBON.CRIMECOUNTS
  )

SELECT ROUND(CORR(crime.CRIMECOUNT, pop.POPULATIONSIZE),3) Correlation
FROM pop
JOIN crime
    ON pop.countyid = crime.cid
ORDER BY crime.cid');
$result = oci_execute($correlation);
$correlationresult = oci_fetch_row($correlation);

oci_close($connection)

?>
