<?php
include 'conexiune.php';

$id = intval($_GET['id']);

mysqli_query(
    $conn,
    "DELETE FROM service_orders WHERE id=$id"
);

header("Location: calendar.php");
exit;
?>