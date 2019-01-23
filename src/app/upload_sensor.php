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

if (isset($_POST["submit2"])){
    $file = $_FILES[csv][tmp_name];
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle);
        $rows_inserted = 0;
        $rows_total = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            foreach ($data as $key => $elem){
                if(is_null($elem) || $elem=='NULL' || $elem == NULL){
                    $data[$key] = '';
                }
            }
            $table = ["CowId", "FarmId", "Lactation_Num", "Age", "DateOfSample", "Yield",
                "Conductivity", "Fat", "Protein", "Lactose", "Milking_Time", "RestTime", "RestBout"];
            $sql2 = "INSERT INTO " . "SensorData" . "(" . implode(", ", $table) . ") VALUES ("
                . "'" . implode("', '", $data) . "')";
            $res = sqlsrv_query($conn, $sql2);
            if($res != False) {
                $rows_inserted = $rows_inserted + 1;
            }
            $rows_total += 1;

        }
        echo "Number of rows inserted is: ".$rows_inserted." from total ".$rows_total;
    }
        fclose($handle);
}
?>
</body>
</html>