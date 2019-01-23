<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Upload CSV Grades</title>
</head>

<body style="background-color:deepskyblue;">
<h2>Upload Farm</h2>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
    <input name="csv" type="file" id="csv" />
    <input type="submit" value="submit farm" name="submit" formaction="/upload_farm.php"/>
</form>



<h2>Upload Sensor</h2>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
    <input name="csv" type="file" id="csv" />
    <input type="submit" value="submit sensor" name="submit2" formaction="/upload_sensor.php"/>
</form>

</body></html>