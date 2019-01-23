<html lang="en">

<body style="background-color:deepskyblue;">

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

if (isset($_POST["submit"])){
    $file = $_FILES[csv][tmp_name];
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle);
        $rows_inserted = 0;
        $rows_total = 0;
        $table = ["FarmId", "FarmSize", "Longitude", "Latitude", "Name"];
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            foreach ($data as $key => $elem){
                if(is_null($elem) || $elem=='NULL' || $elem == NULL){
                    $data[$key] = '';
                }
            }

            $sql2 = "INSERT INTO " . "Farm" . "(" . implode(", ", $table) . ") VALUES ("
                    . "'" . implode("', '", $data) . "')";
            //echo $sql2;
            $res = sqlsrv_query($conn, $sql2);
            if($res != False){
                $rows_inserted = $rows_inserted + 1;
            }
            $rows_total += 1;
        }

        echo "Number of rows inserted is: ".$rows_inserted." from total ".$rows_total;
        fclose($handle);
    }
}
?>
</body >
</html >
