<?php
include 'conexiune.php';

$id = $_POST['id'];

$date = $_POST['appointment_date'];
$type = $_POST['intervention_type'];
$cost = $_POST['estimated_cost'];
$description = $_POST['description'];
$center = $_POST['service_center'];

$sql = "
UPDATE service_orders
SET
appointment_date='$date',
intervention_type='$type',
estimated_cost='$cost',
description='$description',
service_center='$center'
WHERE id=$id
";

mysqli_query($conn,$sql);

header("Location: calendar.php");
exit;
?>