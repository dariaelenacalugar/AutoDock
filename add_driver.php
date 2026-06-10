<?php

include 'conexiune.php';

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$license_category = $_POST['license_category'];
$car_id = $_POST['car_id'];

$parts = explode(" ", $full_name, 2);

$first_name = $parts[0];
$last_name = isset($parts[1]) ? $parts[1] : '';

$query = "INSERT INTO drivers
(first_name,last_name,phone,email,license_category,car_id)
VALUES
('$first_name','$last_name','$phone','$email','$license_category','$car_id')";

mysqli_query($conn, $query);

header("Location: drivers.php");
exit();
?>