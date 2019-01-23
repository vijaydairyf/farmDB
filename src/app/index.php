<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
</head>

<body style="background-color:lightgrey;">

<?php
$server = "tcp:techniondbcourse01.database.windows.net,1433";
$user = "andrey0s";
$pass = "Qwerty12!";
$database = "andrey0s";
$c = array("Database" => $database, "UID" => $user, "PWD" => $pass);
sqlsrv_configure('WarningsReturnAsErrors', 0);
$conn = sqlsrv_connect($server, $c);
if($conn === false)
{
    echo "error";
    die(print_r(sqlsrv_errors(), true));
}
?>

<h1 align="middle">Welcome to AfiMilk DB</h1>

<img src = "afimilk.jpg" alt = "afimilk logo" style="width:50%; height:50%; margin-left: auto;
 margin-right: auto; display: block">

<p align="middle">This website will show data about our farm and cows</p>

<center> <a style="text-align:center" href="heatmap.php">Click here to see heatmap</a> </center>
<br>
<center> <a style="text-align:center" href="upload_file.php">Click here to upload csv data files</a> </center>
<br>
<center> <a style="text-align:center" href="register_farm_form.php">Click here to register a farm by filling in a form</a> </center>
<br>

<?php
$sql1="SELECT SD.CowId
FROM SensorData SD
WHERE SD.DateOfSample = (select max(SD2.DateOfSample)
                       from SensorData SD2)";
$last_cow_id = sqlsrv_query($conn, $sql1);
$print_last_cow_id = sqlsrv_fetch_array($last_cow_id);



$sql2="SELECT SD.Fat
FROM SensorData SD
WHERE SD.DateOfSample = (select max(SD2.DateOfSample)
                       from SensorData SD2)";
$last_cow_weight = sqlsrv_query($conn, $sql2);
$print_last_cow_weight =sqlsrv_fetch_array($last_cow_weight);

$sql3="SELECT SD.Fat
FROM SensorData SD
WHERE SD.DateOfSample=(select max(SD2.DateOfSample)
                       from SensorData SD2
                      WHERE SD2.DateOfSample <> (select max(SD3.DateOfSample)
                                                 from SensorData SD3
                                                WHERE SD3.CowId=SD2.CowId))";
$last_cow_weight_before = sqlsrv_query($conn, $sql3);
$print_last_cow_weight_before=sqlsrv_fetch_array($last_cow_weight_before);

$weight_difference= $print_last_cow_weight['Fat']-$print_last_cow_weight_before['Fat'];

$sql4 = "SELECT avg(SD.Fat) as weight_average
FROM SensorData SD
WHERE SD.CowId=(select SD2.CowId
                from SensorData SD2
                where SD2.DateOfSample=(select max(SD3.DateOfSample)
                                        from SensorData SD3))";
$last_cow_weight_average = sqlsrv_query($conn, $sql4);
$print_last_cow_weight_average = sqlsrv_fetch_array($last_cow_weight_average);

$sql5 = "SELECT count(*) as counter
FROM SensorData SD
WHERE SD.CowId=(select SD2.CowId
                from SensorData SD2
                where SD2.DateOfSample=(select max(SD3.DateOfSample)
                                        from SensorData SD3))";
$last_cow_weights_count = sqlsrv_query($conn, $sql5);
$num_last_cow_weights_count=sqlsrv_fetch_array($last_cow_weights_count);

$sql6 ="SELECT avg(Conductivity)as average
FROM SensorData SD
WHERE (SD.CowId % 2 = 1)";
$Conductivity_average_odd = sqlsrv_query($conn, $sql6);
$print_Conductivity_average_odd=sqlsrv_fetch_array($Conductivity_average_odd);

$sql7 ="SELECT avg(Conductivity) as average
FROM SensorData SD
WHERE (SD.CowId % 2 = 0)";
$Conductivity_average_even = sqlsrv_query($conn, $sql7);
$print_Conductivity_average_even=sqlsrv_fetch_array($Conductivity_average_even);

$avg_last_cow = ($num_last_cow_weights_count['counter']>10) ? $print_last_cow_weight_average['weight_average'] : "INSUFFICIENT DATA";

$sql8 = "SELECT Conductivity FROM SensorData";
$result = sqlsrv_query($conn, $sql8);
$con_array = [];
$i = 0;
while($row = sqlsrv_fetch_array($result))
{
    $con_array[$i] = $row["Conductivity"];
    $i++;

}
sort($con_array);
$pos = ($i+1) * 0.75;
$base = floor($pos);
$third_q = $con_array[$base];







echo "<table align='center' border = \"1\">";
echo "<tr><th colspan=\"3\">AfiWeight Data</th></tr>
 <tr>
 <th>cow ID</th>
 <th>difference in weight</th>
 <th>Average weight</th>
 </tr>
 <tr>
 <th>".$print_last_cow_id['CowId']."</th> 
 <th>".$weight_difference."</th>
 <th>".$avg_last_cow."</th>
 </tr>";
echo "</table>";

echo "<table align='center' border = \"1\">";
echo "
 <tr> <th colspan=\"3\">AfiLab Data</th> </tr>
 <tr>
 <th>Even average</th>
 <th>Odd average</th>
 <th>3rd quantile</th>
 </tr>
 <tr>
 <th>".$print_Conductivity_average_even['average']."</th> 
 <th>".$print_Conductivity_average_odd['average']."</th>
 <th>$third_q</th>
 </tr>";
echo "</table>"


?>
</body>
</html>
