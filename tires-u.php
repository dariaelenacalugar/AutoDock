<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: welcome.html");
    exit();
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user'){
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

$queryTires = "
SELECT tires.*,
       cars.brand AS car_brand,
       cars.model AS car_model,
       cars.license_plate
FROM tires
JOIN cars ON tires.car_id = cars.id
WHERE cars.assigned_user_id = '$userId'
";

$resultTires = mysqli_query($conn, $queryTires);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Tires - Autodock</title>
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
            <li><a href="cars-u.php">Cars</a></li>
            <li><a href="documents-u.php">Documents</a></li>
            <li><a href="tires-u.php" class="active">Tires</a></li> </ul>

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
                        <?php echo $userData['first_name']." ".$userData['last_name']; ?>
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

        <header class="page-header" style="margin-bottom: 30px; flex-direction: column; align-items: flex-start;">
            <h1 class="docs-title" style="margin-bottom: 8px;">Tire Status & Wear</h1>
            <p class="user-subtitle" style="margin-top: 0;">Tire condition monitoring for your safety</p>
        </header>

        <div class="tires-grid">

           <?php while($tire = mysqli_fetch_assoc($resultTires)){ ?>

        <div class="tire-card">

                <div class="tire-card-header">
                    <span class="tire-plate">
                        <?php echo $tire['license_plate']; ?>
                    </span>

                    <span class="tire-season">
                        <?php echo ucfirst($tire['tire_type']); ?>
                    </span>
                </div>

                <div class="tire-model">
                    <?php echo $tire['car_brand']." ".$tire['car_model']; ?>
                </div>

                <div class="tire-condition-box">
                    <div class="tire-label">Condition</div>

                    <div class="tire-status">
                        <?php echo strtoupper($tire['condition_status']); ?>
                    </div>
                </div>

                <div class="tire-progress-wrapper">

                    <div class="tire-progress-track">
                        <div class="tire-progress-fill"
                            style="width: <?php echo $tire['wear_level']; ?>%;">
                        </div>
                    </div>

                    <div class="tire-wear-text">
                        Wear level: <?php echo $tire['wear_level']; ?>%
                    </div>

                </div>

            </div>

            <?php } ?>

        </div>

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