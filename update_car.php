<?php

include 'conexiune.php';

$id = $_POST['id'];

$query = "UPDATE cars SET
brand='".$_POST['brand']."',
model='".$_POST['model']."',
year='".$_POST['year']."',
license_plate='".$_POST['license_plate']."',
fuel_type='".$_POST['fuel_type']."',
capacity='".$_POST['capacity']."',
power='".$_POST['power']."',
consumption='".$_POST['consumption']."',
mileage='".$_POST['mileage']."',
status='".$_POST['status']."',
last_service_date='".$_POST['last_service_date']."',
next_service_date='".$_POST['next_service_date']."'
WHERE id=$id";

mysqli_query($conn,$query);

header("Location: cars-m.php");
exit();
?>