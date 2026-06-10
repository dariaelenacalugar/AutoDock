<?php

include 'conexiune.php';

$id = $_GET['id'];

$query = "DELETE FROM service_orders WHERE id = $id";

mysqli_query($conn, $query);

header("Location: services.php");
exit();

?>