<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Add new Farm</title>
    <title>Fill this form to add new farm</title>
</head>

<body style="background-color:green;">

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

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
    Farm ID   <input type="number" name="id" id="id"  required=""><br>
    Farm Name <input type="text" name="name" id="name" size="40"><br>
    Farm Size <input type="number" name="size" id="size"><br>
    LAT <input type="number" name="lat" id="lat" step=    "0.00000000001"><br>
    LONG <input type="number" name="long" id="long" step= "0.00000000001"><br>
    <button name="submit" type="submit">Add Farm</button>
    <button name="reset" type="reset">Reset Page</button>
</form>

<?php
if (isset($_POST["submit"])) {


    $id = $_POST["id"];
    $name = "'". $_POST["name"] ."'";
    $size = $_POST["size"];
    $lat = $_POST["lat"];
    $long = $_POST["long"];

    $sql = "INSERT INTO Farm (FarmId, Name, FarmSize, Latitude, Longitude)
                VALUES ( $id , $name, $size, $lat, $long);";
    echo "<script>console.log($sql);</script>";
    sqlsrv_query($conn, $sql);
}
?>

</body>
</html>