<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Heat Map
        Choose what to display on the map
    </title>
</head>
<body style="background-color:darkseagreen;">
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

<br>


<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">

    Choose a farm: <select name="FarmID" required="">
        <option value="">Choose a Farm</option>
        <?php
            $id_name = sqlsrv_query($conn, "SELECT FarmId, Name FROM Farm;");
            while($row = sqlsrv_fetch_array($id_name, SQLSRV_FETCH_ASSOC))
            {
                echo '<option value="'.$row['FarmId'].'">'.$row['Name'].', '.$row['FarmId'].'</option>';
            }
        ?>
    </select>
    Date: <input type="date" name="Date" id="Date" required="">
    Hour: <input type="text" name="Hour" id="Hour">
    <button name="submit" type="submit">Send</button>
</form>


<?php
if (isset($_POST["submit"])) {
    $farm = $_POST["FarmID"];
    $Hour = $_POST["Hour"];
    $Date = $_POST["Date"];


    if (is_numeric($Hour) and 0 <= $Hour and $Hour <= 23) {

        $sql = "SELECT COUNT(*) AS Num FROM SensorData
              WHERE FarmId = " . $farm . " 
              AND DATEPART(hour, DateOfSample) = " . $Hour .
            " AND CONVERT(date, DateOfSample) = '" . $Date . "';";
        $num = sqlsrv_fetch_array(sqlsrv_query($conn, $sql), SQLSRV_FETCH_ASSOC)["Num"];
        if (7 < $num){
            //red
            $color = 'rgba(255,0,0,0.2)';
        }
        else if (0 < $num and $num <= 3){
            //blue
            $color = 'rgba(0,0,255,0.2)';
        }
        else if (3 < $num and $num <= 7){
            //pink
            $color = 'rgba(255,0,255,0.2)';
        }
        else{
            //yellow
            $color = 'rgba(255,255,0,0.3)';
        }
        //get lon lat from farm selected
        $sql = "SELECT Longitude, Latitude FROM Farm WHERE FarmId = " . $farm . ";";
        $result = sqlsrv_fetch_array(sqlsrv_query($conn, $sql), SQLSRV_FETCH_ASSOC);
        $longi = $result["Longitude"];
        $lat = $result["Latitude"];
        echo
        "<script type='text/javascript'>
                        var map;
                        function GetMap(lat, long, color) {
                    ";
        echo 'color = \'' . $color . '\'; lat = ' . $lat . '; long = ' . $longi . ';';
        echo
        "        map = new Microsoft.Maps.Map('#myMap', {});
                            map.setView({
                                mapTypeId: Microsoft.Maps.MapTypeId.aerial,
                                center: new Microsoft.Maps.Location(lat, long),
                                zoom: 10});
                            Microsoft.Maps.loadModule('Microsoft.Maps.SpatialMath', function () {
                                var cent = map.getCenter();
                                var circ = createCircle(cent, 1, color);
                                map.entities.push(circ);});
                        }
                    
                        function createCircle(center, radius, color) {
                            var poly = Microsoft.Maps.SpatialMath.getRegularPolygon(center, radius, 36,
                                Microsoft.Maps.SpatialMath.DistanceUnits.Miles);
                            return new Microsoft.Maps.Polygon(poly, { fillColor: color, strokeThickness: 0 });
                        }
                    </script>
                    ";
    }
    else
        echo "<h2 align=\"center\">Error on hour entered.</h2>";
}
?>

<div id="myMap" style="width:1200px; height:600px; margin:0 auto; position:relative;"></div>


<script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AvJZzTmbwvMG
                                    XaZRbr3HrfyHDxYBVVFpkxnqpzkFg6d1P8lTk6vOAEnsYqSUYJB7'></script>
</body>