<?php

//setting header

$guser = getenv('DBUSER');
$gpass = getenv('DBPASS');
$gconn = getenv('DBCONN');
$connection = oci_connect($username = $guser,
                        $password = $gpass,
                        $connection_string = $gconn);
//Make sure connection was sucessfull.
if (!$connection) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

//Weather Avg
$avgtemp = oci_parse($connection, 'SELECT AVGTEMP FROM AADAMES.CLIMATEPROFILE');
$result = oci_execute($avgtemp);

$data = array();

while( ($row = oci_fetch_row($avgtemp)) != false)
{
    $temp = $row[0];
    array_push($data, $temp);
}

oci_free_statement($avgtemp);
oci_close($connection)
?>
