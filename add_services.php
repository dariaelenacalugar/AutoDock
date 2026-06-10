<?php

include 'conexiune.php';

$car_id = $_POST['car_id'];
$appointment_date = $_POST['appointment_date'];
$intervention_type = $_POST['intervention_type'];
$service_center = $_POST['service_center'];
$estimated_cost = $_POST['estimated_cost'];
$description = $_POST['description'];

$query = "INSERT INTO service_orders
(
    car_id,
    appointment_date,
    intervention_type,
    service_center,
    estimated_cost,
    description,
    status
)
VALUES
(
    '$car_id',
    '$appointment_date',
    '$intervention_type',
    '$service_center',
    '$estimated_cost',
    '$description',
    'scheduled'
)";

mysqli_query($conn, $query);

header('Location: calendar.php');
exit();