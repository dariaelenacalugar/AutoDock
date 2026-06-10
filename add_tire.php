<?php
include 'conexiune.php';

$brand = $_POST['brand'];
$size = $_POST['size'];
$tire_type = $_POST['tire_type'];
$condition_status = $_POST['condition_status'];
$wear_level = $_POST['wear_level'];
$car_id = $_POST['car_id'];

$query = "INSERT INTO tires
(car_id, brand, size, tire_type, condition_status, wear_level)
VALUES
('$car_id', '$brand', '$size', '$tire_type', '$condition_status', '$wear_level')";

mysqli_query($conn, $query);

header("Location: cars-m.php");
exit();
?>