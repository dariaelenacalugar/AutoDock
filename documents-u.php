<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user'){
    header("Location: welcome.html");
    exit();
}
if(!isset($_SESSION['user_id'])){
    header("Location: welcome.html");
    exit();
}

include 'conexiune.php';

$userId = $_SESSION['user_id'];

$queryDocuments = "
SELECT documents.*,
       cars.brand,
       cars.model,
       cars.license_plate
FROM documents
JOIN cars ON documents.car_id = cars.id
WHERE cars.assigned_user_id = '$userId'
";
$userQuery = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$userId'"
);

$resultDocuments = mysqli_query($conn, $queryDocuments);
$userData = mysqli_fetch_assoc($userQuery);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Documents - Autodock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Calistoga&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="manager.css">
</head>

<body>

    <nav class="dash-navbar">
        <a href="index.html">
            <img src="images/logo.png" alt="Autodock Logo" class="dash-logo">
        </a>
        <ul class="dash-nav-links">
            <li><a href="cars-u.php">Cars</a></li>
            <li><a href="documents-u.php" class="active">Documents</a></li>
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

        <p class="user-subtitle">Check the status of insurance and road taxes</p>

        <div class="user-panel">
            <h2>Documents & Compliance</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th class="align-left">VEHICLE</th>
                        <th>Document Type</th>
                        <th>Provider</th>
                        <th>Issue Date</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($doc = mysqli_fetch_assoc($resultDocuments)){ ?>
                    <tr>
                        <td class="align-left">
                            <?php echo $doc['brand']." ".$doc['model']." (".$doc['license_plate'].")"; ?>
                        </td>
                        <td>
                            <?php echo $doc['doc_type']; ?>
                        </td>
                        <td>
                            <?php echo $doc['provider']; ?>
                        </td>
                        <td>
                            <?php echo $doc['issue_date']; ?>
                        </td>
                        <td>
                            <?php echo $doc['expiry_date']; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="user-info-box">
            <span class="material-symbols-outlined">info</span>
            <p>As a User, you can view deadlines, but you cannot upload new documents. Contact your administrator for
                updates.</p>
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