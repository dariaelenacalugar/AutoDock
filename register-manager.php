<?php
    include 'conexiune.php';

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $company_name = $_POST['company_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password){
        die("Passwords do not match!");
    }

    $checkUser = mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE username='$username'
        OR email='$email'"
    );

    if(mysqli_num_rows($checkUser) > 0){
        die("Username or email already exists!");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "
    INSERT INTO users
    (
    first_name,
    last_name,
    username,
    email,
    company_name,
    password,
    role
    )
    VALUES
    (
    '$first_name',
    '$last_name',
    '$username',
    '$email',
    '$company_name',
    '$passwordHash',
    'manager'
    )
    ";

    if(mysqli_query($conn,$sql)){
        header("Location: welcome.html");
        exit();
    }
    else{
        echo "Error: ".mysqli_error($conn);
    }
?>