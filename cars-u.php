<?php
    session_start();
    if($_SESSION['role'] != 'user'){
    header("Location: dashboard_manager.php");
    exit();
}

    if(!isset($_SESSION['user_id'])){
        header("Location: welcome.html");
        exit();
    }

    include 'conexiune.php';

    $userId = $_SESSION['user_id'];
    $userQuery = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$userId'"
    );

    $userData = mysqli_fetch_assoc($userQuery);
    $queryCars = "
    SELECT *
    FROM cars
    WHERE assigned_user_id = '$userId'
    ";
    $queryTires = "
    SELECT tires.*,
       cars.brand AS car_brand,
       cars.model AS car_model
    FROM tires
    JOIN cars ON tires.car_id = cars.id
    WHERE cars.assigned_user_id = '$userId'";
    $resultCars = mysqli_query($conn, $queryCars);
    $resultTires = mysqli_query($conn, $queryTires);
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Autodock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Calistoga&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="manager.css">
</head>

<body>

    <nav class="dash-navbar">
        <a href="index.html">
            <img src="images/logo.png" alt="Autodock Logo" class="dash-logo">
        </a>
        <ul class="dash-nav-links">
            <li><a href="cars-u.php" class="active">Cars</a></li>
            <li><a href="documents-u.php">Documents</a></li>
            <li><a href="tires-u.php">Tires</a></li>
        </ul>

        <div class="profile-dropdown-container">
            <div class="profile-trigger" id="profileTrigger">
                <div class="profile-avatar">
                    <?php
                    echo strtoupper(
                        substr($userData['first_name'], 0, 1) .
                        substr($userData['last_name'], 0, 1)
                    );
                    ?>
                </div>
                <div class="profile-info">
                    <span class="profile-name">
                        <?php echo $_SESSION['username']; ?>
                    </span>
                    <span class="profile-role">User</span>
                </div>
                <span class="material-symbols-outlined arrow-icon">expand_more</span>
            </div>

           <div class="profile-menu" id="profileMenu">
                <div class="profile-menu-header">
                    <strong>
                        <?php echo $userData['first_name']." ".$userData['last_name']; ?>
                    </strong>

                    <span>
                        <?php echo $userData['email']; ?>
                    </span>
                </div>
                
                <hr class="dropdown-divider">
                
                <a href="#" class="profile-menu-item">
                    <span class="material-symbols-outlined">account_circle</span> My Profile
                </a>
                <a href="#" class="profile-menu-item">
                    <span class="material-symbols-outlined">settings</span> Account Settings
                </a>
                
                <hr class="dropdown-divider">
                
                <a href="logout.php" class="profile-menu-item sign-out">
                    <span class="material-symbols-outlined">logout</span> Sign Out
                </a>
            </div>
        </div>
    </nav>

    <main class="dashboard-container">

        <header class="page-header" style="margin-bottom: 20px;">
            <h1>Welcome back, <?php echo $_SESSION['username']; ?>!</h1>
        </header>

        <section class="user-dashboard-grid">

            <div class="user-panel">
                <h2>Current Fleet Status</h2>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th class="align-left">VEHICLE</th>
                            <th>DRIVER</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($car = mysqli_fetch_assoc($resultCars)){ ?>
                        <tr>
                            <td class="align-left">
                                <?php echo $car['brand']." ".$car['model']." (".$car['license_plate'].")"; ?>
                            </td>
                            <td>
                                Assigned
                            </td>
                            <td class="text-green">
                                <?php echo strtoupper($car['status']); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="user-panel">
                <h2>Documents Status</h2>

                <table class="user-table">
                    <thead>
                        <tr>
                            <th class="align-left">VEHICLE</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $resultDocs = mysqli_query(
                            $conn,
                            "SELECT * FROM cars WHERE assigned_user_id='$userId'"
                        );

                        while($car = mysqli_fetch_assoc($resultDocs)){
                        ?>
                        <tr>
                            <td class="align-left">
                                <?php echo $car['brand']." ".$car['model']; ?>
                            </td>

                            <td class="text-green">
                                VALID
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="user-panel">
                <h2>Tire Setup</h2>
                <table class="user-table">
                    <tbody>
                        <?php while($tire = mysqli_fetch_assoc($resultTires)){ ?>
                        <tr>
                            <td class="align-left">
                                <?php echo $tire['car_brand']." ".$tire['car_model']; ?>
                            </td>
                            <td>
                                <?php echo $tire['tire_type']; ?>
                            </td>
                            <td>
                                <?php echo $tire['condition_status']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="support-panel">
                <div class="support-header">
                    <span class="material-symbols-outlined support-icon">help</span>
                    <h2>Support & Issues</h2>
                </div>
                <p class="support-text">For service appointments or date changes, please contact the Fleet Manager.</p>
                <p class="support-email">Email: <strong>management@autodock.ro</strong></p>
            </div>

        </section>

    </main>

    <script>
        
        const profileTrigger = document.getElementById('profileTrigger');
        const profileMenu = document.getElementById('profileMenu');

        if (profileTrigger && profileMenu) {
            profileTrigger.addEventListener('click', function (event) {
                profileMenu.classList.toggle('show');
                event.stopPropagation();
            });

            document.addEventListener('click', function (event) {
                if (!profileMenu.contains(event.target) && !profileTrigger.contains(event.target)) {
                    profileMenu.classList.remove('show');
                }
            });
        }
    </script>
</body>

</html>