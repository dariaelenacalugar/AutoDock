<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: welcome.html");
    exit();
}

include 'conexiune.php';
$queryUsers = "SELECT id, username FROM users WHERE role='user'";
$resultUsers = mysqli_query($conn, $queryUsers);

$query="SELECT * FROM cars";
$result=mysqli_query($conn, $query);

$query_tires = "
SELECT tires.*, cars.brand AS car_brand, cars.model AS car_model
FROM tires
LEFT JOIN cars ON tires.car_id = cars.id
";

$result_tires = mysqli_query($conn, $query_tires);

$queryCars = "SELECT id, brand, model, license_plate, year FROM cars";
$resultCars = mysqli_query($conn, $queryCars);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars Inventory - Autodock</title>
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
            <li><a href="dashboard_manager.php">Dashboard</a></li>
            <li><a href="cars-m.php" class="active">Cars</a></li>
            <li><a href="drivers.php">Drivers</a></li>
            <li><a href="services.php">Service</a></li>
            <li><a href="calendar.php">Calendar</a></li>
            <li><a href="documents-m.php">Documents</a></li>
        </ul>


        <div class="profile-dropdown-container">
            <div class="profile-trigger" id="profileTrigger">
                <div class="profile-avatar">AP</div>
                <div class="profile-info">
                    <span class="profile-name">
                        <?php echo $_SESSION['username']; ?>
                    </span>
                    <span class="profile-role">Manager</span>
                </div>
                <span class="material-symbols-outlined arrow-icon">expand_more</span>
            </div>

            <div class="profile-menu" id="profileMenu">
                <div class="profile-menu-header">
                    <strong>Manager Central</strong>
                    <span>manager@autodock.ro</span>
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

        <header class="page-header">
            <h1>Cars inventory</h1>
            <button id="btnOpenModal" class="btn-add-car">+ Add a new car</button>
        </header>

        <div class="filters-row">
            <input type="text" placeholder="Search by ID, brand or driver...." class="search-wide">
            <select class="status-dropdown">
                <option value="all">All statuses</option>
                <option value="active">Active</option>
                <option value="warning">Warning</option>
                <option value="service">In service</option>
            </select>
        </div>

        <div class="panel inventory-panel">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Vehicle Details</th>
                        <th class="text-center">Specs (Fuel/Eng/Power)</th>
                        <th class="text-center">Mileage</th>
                        <th class="text-center">Assigned Driver</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Service (Last/Next)</th>
                        <th></th> 
                    </tr>
                </thead>
            <tbody>

            <?php while($car = mysqli_fetch_assoc($result)){ ?>

            <tr>

                <td>
                    <strong>
                        <?php echo $car['brand']." ".$car['model']." (".$car['year'].")"; ?>
                    </strong>
                    <br>
                    <span class="license-plate">
                        <?php echo $car['license_plate']; ?>
                    </span>
                </td>

                <td class="text-center">
                    <?php echo $car['fuel_type']; ?> |
                    <?php echo $car['capacity']; ?>L |
                    <?php echo $car['power']; ?> HP
                    <br>
                    <span style="color: var(--text-muted); font-size:12px;">
                        <?php echo $car['consumption']; ?> L/100km
                    </span>
                </td>

                <td class="text-center">
                    <?php echo number_format($car['mileage']); ?> km
                </td>

                <td class="text-center">
                    Unassigned
                </td>

                <td class="text-center">
                    <?php echo $car['status']; ?>
                </td>

                <td class="text-center">
                    Last:
                    <?php echo $car['last_service_date'] ?: 'N/A'; ?>
                    <br>
                    Next:
                    <?php echo $car['next_service_date'] ?: 'N/A'; ?>
                </td>

                <td class="table-actions">

                   <a href="#"
                        class="btnEditCar"

                        data-id="<?php echo $car['id']; ?>"
                        data-brand="<?php echo $car['brand']; ?>"
                        data-model="<?php echo $car['model']; ?>"
                        data-year="<?php echo $car['year']; ?>"
                        data-license="<?php echo $car['license_plate']; ?>"
                        data-fuel="<?php echo $car['fuel_type']; ?>"
                        data-capacity="<?php echo $car['capacity']; ?>"
                        data-power="<?php echo $car['power']; ?>"
                        data-consumption="<?php echo $car['consumption']; ?>"
                        data-mileage="<?php echo $car['mileage']; ?>"
                        data-status="<?php echo $car['status']; ?>"
                        data-lastservice="<?php echo $car['last_service_date']; ?>"
                        data-nextservice="<?php echo $car['next_service_date']; ?>">

                        <span class="material-symbols-outlined action-icon edit-icon">
                            edit
                        </span>

                    </a>

                    <a href="delete_car.php?id=<?php echo $car['id']; ?>"
                    onclick="return confirm('Delete this car?');">
                    <span class="material-symbols-outlined action-icon delete-icon">delete</span>
                    </a>

                </td>

            </tr>

            <?php } ?>

            </tbody>
            </table>
        </div>

        <div class="page-header" style="margin-top: 50px; margin-bottom: 20px;">
            <h1 style="margin: 0;">Tires inventory</h1>
            <button class="btn-add-car" id="btnOpenTireModal">+ Add new tires</button>
        </div>

        <div class="panel inventory-panel">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Vehicle Details</th>
                        <th class="text-center">Tire Type</th>
                        <th class="text-center">Brand/Dimension</th>
                        <th class="text-center">Wear level</th>
                        <th class="text-center">Condition Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                   <?php while($tire = mysqli_fetch_assoc($result_tires)){ ?>
                    <tr>
                        <td>
                            <strong>
                            <?php echo $tire['car_brand']." ".$tire['car_model']; ?>
                            </strong><br>
                            ID: <?php echo $tire['car_id']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $tire['tire_type']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $tire['brand']; ?><br>
                            <?php echo $tire['size']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $tire['wear_level']; ?>%
                        </td>
                        <td class="text-center">
                            <?php echo $tire['condition_status']; ?>
                        </td>
                        <td class="table-actions">

                                <a href="#"
                                class="btnEditTire"

                                data-id="<?php echo $tire['id']; ?>"
                                data-brand="<?php echo $tire['brand']; ?>"
                                data-size="<?php echo $tire['size']; ?>"
                                data-type="<?php echo $tire['tire_type']; ?>"
                                data-wear="<?php echo $tire['wear_level']; ?>"
                                data-condition="<?php echo $tire['condition_status']; ?>">
                                    <span class="material-symbols-outlined action-icon edit-icon">
                                        edit
                                    </span>

                                </a>
                                <a href="delete_tire.php?id=<?php echo $tire['id']; ?>"
                                onclick="return confirm('Delete this tire?');">

                                    <span class="material-symbols-outlined action-icon delete-icon">
                                        delete
                                    </span>
                                </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody> 
            </table>
        </div>

    </main>

    <div id="addCarModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">&times;</span>

            <h2>ADD NEW VEHICLE</h2>

            <form class="add-car-form" action="add_car.php" method="POST">

                <h3 class="section-title">General information</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Brand & Model</label>
                        <input type="text" name="brand" placeholder="e.g. Dacia">
                        <input type="text" name="model" placeholder="e.g. Logan">
                    </div>
                    <div class="input-group">
                        <label>License Plate</label>
                        <input type="text" name="license_plate" placeholder="e.g. SB 12 AAA">
                    </div>
                    <div class="input-group">
                        <label>Year</label>
                        <input type="number" name="year" value="2026">
                    </div>
                    <div class="input-group">
                        <label>VIN (Chassis Number)</label>
                        <input type="text" name="vin" placeholder="17 characters">
                    </div>
                </div>

                <h3 class="section-title">Document status</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>RCA Expiration Date</label>
                        <input type="date" name="rca_expiry_date">
                    </div>
                    <div class="input-group">
                        <label>ITP Expiration Date</label>
                        <input type="date" name="itp_expiry_date">
                    </div>
                </div>

                <h3 class="section-title">Assignment</h3>
                <div class="input-group full-width">
                    
                    <label>Assign User</label>
                    <select name="assigned_user_id">
                        <option value="">Unassigned</option>
                        <?php while($user = mysqli_fetch_assoc($resultUsers)){ ?>
                            <option value="<?php echo $user['id']; ?>">
                                <?php echo $user['username']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Engine Capacity (L)</label>
                    <input type="number" step="0.1" name="capacity">
                </div>

                <div class="input-group">
                    <label>Power (HP)</label>
                    <input type="number" name="power">
                </div>

                <div class="input-group">
                    <label>Consumption (L/100km)</label>
                    <input type="number" step="0.1" name="consumption">
                </div>

                <div class="input-group">
                    <label>Mileage</label>
                    <input type="number" name="mileage">
                </div>
                <div class="input-group">
                    <label>Last Service Date</label>
                    <input type="date" name="last_service_date">
                </div>

                <div class="input-group">
                    <label>Next Service Date</label>
                    <input type="date" name="next_service_date">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancel">Cancel</button>
                    <button type="submit" class="btn-submit">Add to fleet</button>
                </div>

            </form>
        </div>
    </div>
    <div id="editCarModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-modal" id="closeEditCarModal">&times;</span>
        <h2>EDIT VEHICLE</h2>
        <form action="update_car.php" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-grid">
                <div class="input-group">
                    <label>Brand</label>
                    <input type="text" name="brand" id="edit_brand">
                </div>
                <div class="input-group">
                    <label>Model</label>
                    <input type="text" name="model" id="edit_model">
                </div>
                <div class="input-group">
                    <label>Year</label>
                    <input type="number" name="year" id="edit_year">
                </div>
                <div class="input-group">
                    <label>License Plate</label>
                    <input type="text" name="license_plate" id="edit_license">
                </div>
                <div class="input-group">
                    <label>Fuel Type</label>
                    <input type="text" name="fuel_type" id="edit_fuel">
                </div>
                <div class="input-group">
                    <label>Capacity</label>
                    <input type="number" step="0.1"
                           name="capacity"
                           id="edit_capacity">
                </div>
                <div class="input-group">
                    <label>Power</label>
                    <input type="number" name="power" id="edit_power">
                </div>
                <div class="input-group">
                    <label>Consumption</label>
                    <input type="number"
                           step="0.1"
                           name="consumption"
                           id="edit_consumption">
                </div>
                <div class="input-group">
                    <label>Mileage</label>
                    <input type="number"
                           name="mileage"
                           id="edit_mileage">
                </div>
                <div class="input-group">
                    <label>Status</label>
                    <select name="status" id="edit_status">
                        <option value="active">Active</option>
                        <option value="in_service">In Service</option>
                        <option value="reserve">Reserve</option>
                        <option value="decommissioned">Decommissioned</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Last Service</label>
                    <input type="date"
                           name="last_service_date"
                           id="edit_lastservice">
                </div>
                <div class="input-group">
                    <label>Next Service</label>
                    <input type="date"
                           name="next_service_date"
                           id="edit_nextservice">
                </div>
            </div>
            <div class="modal-actions">
                <button type="button"
                        class="btn-cancel"
                        id="cancelEditCar">
                    Cancel
                </button>
                <button type="submit"
                        class="btn-submit">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
    <div id="addTireModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeTireModal">&times;</span>

            <h2>ADD NEW TIRES</h2>

            <form class="add-car-form" action="add_tire.php" method="POST">

                <h3 class="section-title">General information</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Brand & Dimension</label>
                            <input type="text" name="brand" placeholder="e.g Michelin">
                            <input type="text" name="size" placeholder="e.g 205/60 R16">
                    </div>
                    <div class="input-group">
                        <label>Tire Type</label>
                        <select name="tire_type">
                            <option value="winter">Winter Tires</option>
                            <option value="summer">Summer Tires</option>
                            <option value="all-season">All-Season Tires</option>
                        </select>
                    </div>
                </div>

                <h3 class="section-title">Condition & Status</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Wear Level (%)</label>
                        <input type="text" name="wear_level" placeholder="e.g. 15%">
                    </div>
                    <div class="input-group">
                        <label>Condition Status</label>
                        <select name="condition_status">
                            <option value="new">Good Condition</option>
                            <option value="used">Normal Wear</option>
                            <option value="worn">Needs Replacement</option>
                        </select>
                    </div>
                </div>

                <h3 class="section-title">Assignment</h3>
                <div class="input-group">
                    <label>Assign to Vehicle</label>
                    <select name="car_id">
                        <option value="">Unassigned</option>
                        <?php while($carOption = mysqli_fetch_assoc($resultCars)){ ?>
                            <option value="<?php echo $carOption['id']; ?>">
                                <?php echo $carOption['brand']." ".$carOption['model']." (".$carOption['year'].") - ".$carOption['license_plate']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancelTire">Cancel</button>
                    <button type="submit" class="btn-submit">Add to fleet</button>
                </div>
            </form>
        </div>
    </div>
    <div id="editTireModal" class="modal-overlay">
        <div class="modal-content">

            <span class="close-modal" id="closeEditTireModal">&times;</span>

            <h2>EDIT TIRE</h2>

            <form action="update_tire.php" method="POST">

                <input type="hidden" name="id" id="edit_tire_id">

                <div class="form-grid">

                    <div class="input-group">
                        <label>Brand</label>
                        <input type="text" name="brand" id="edit_tire_brand">
                    </div>

                    <div class="input-group">
                        <label>Size</label>
                        <input type="text" name="size" id="edit_tire_size">
                    </div>

                    <div class="input-group">
                        <label>Tire Type</label>

                        <select name="tire_type" id="edit_tire_type">
                            <option value="winter">Winter</option>
                            <option value="summer">Summer</option>
                            <option value="all-season">All Season</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Wear Level</label>
                        <input type="number" name="wear_level" id="edit_tire_wear">
                    </div>

                    <div class="input-group">
                        <label>Condition</label>

                        <select name="condition_status" id="edit_tire_condition">
                            <option value="new">Good Condition</option>
                            <option value="used">Normal Wear</option>
                            <option value="worn">Needs Replacement</option>
                        </select>
                    </div>

                </div>

                <div class="modal-actions">
                    <button type="button"
                            class="btn-cancel"
                            id="cancelEditTire">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn-submit">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
        
    <script>

        const modal = document.getElementById("addCarModal");
        const btnOpen = document.getElementById("btnOpenModal");
        const btnClose = document.getElementById("closeModal");
        const btnCancel = document.getElementById("btnCancel");


        btnOpen.onclick = function () {
            modal.style.display = "flex";
        }


        btnClose.onclick = function () {
            modal.style.display = "none";
        }


        btnCancel.onclick = function () {
            modal.style.display = "none";
        }

        const tireModal = document.getElementById("addTireModal");
        const btnOpenTire = document.getElementById("btnOpenTireModal");
        const btnCloseTire = document.getElementById("closeTireModal");
        const btnCancelTire = document.getElementById("btnCancelTire");

        btnOpenTire.onclick = function () {
            tireModal.style.display = "flex";
        }

        btnCloseTire.onclick = function () {
            tireModal.style.display = "none";
        }

        btnCancelTire.onclick = function () {
            tireModal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }

            if (event.target == tireModal) {
                tireModal.style.display = "none";
            }
             if (event.target == editCarModal) {
                editCarModal.style.display = "none";
            }
            if (event.target == editTireModal) {
                editTireModal.style.display = "none";
            }
        }

        const profileTrigger = document.getElementById('profileTrigger');
        const profileMenu = document.getElementById('profileMenu');

        profileTrigger.addEventListener('click', function (event) {
            profileMenu.classList.toggle('show');
            event.stopPropagation();
        });

        document.addEventListener('click', function (event) {
            if (!profileMenu.contains(event.target) && !profileTrigger.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
        });

        
        document.addEventListener('DOMContentLoaded', () => {
            const tableRows = document.querySelectorAll('.inventory-table tbody tr');

            tableRows.forEach(row => {
                row.addEventListener('click', function (e) {
                    
                    if (e.target.closest('.action-icon')) return;

                   
                    tableRows.forEach(r => {
                        if (r !== this) {
                            r.classList.remove('show-actions');
                        }
                    });

                   
                    this.classList.toggle('show-actions');
                });
            });
        });

        const editCarModal =
        document.getElementById("editCarModal");

        document.querySelectorAll(".btnEditCar")
        .forEach(button => {

            button.addEventListener("click", function(e){

                e.preventDefault();
                e.stopPropagation();

                document.getElementById("edit_id").value =
                    this.dataset.id;

                document.getElementById("edit_brand").value =
                    this.dataset.brand;

                document.getElementById("edit_model").value =
                    this.dataset.model;

                document.getElementById("edit_year").value =
                    this.dataset.year;

                document.getElementById("edit_license").value =
                    this.dataset.license;

                document.getElementById("edit_fuel").value =
                    this.dataset.fuel;

                document.getElementById("edit_capacity").value =
                    this.dataset.capacity;

                document.getElementById("edit_power").value =
                    this.dataset.power;

                document.getElementById("edit_consumption").value =
                    this.dataset.consumption;

                document.getElementById("edit_mileage").value =
                    this.dataset.mileage;

                document.getElementById("edit_status").value =
                    this.dataset.status;

                document.getElementById("edit_lastservice").value =
                    this.dataset.lastservice;

                document.getElementById("edit_nextservice").value =
                    this.dataset.nextservice;

                editCarModal.style.display = "flex";

            });

        });

        document.getElementById("closeEditCarModal")
        .onclick = () => editCarModal.style.display = "none";

        document.getElementById("cancelEditCar")
        .onclick = () => editCarModal.style.display = "none";

        const editTireModal =
        document.getElementById("editTireModal");

        document.querySelectorAll(".btnEditTire")
        .forEach(button => {

            button.addEventListener("click", function(e){

                e.preventDefault();
                e.stopPropagation();

                document.getElementById("edit_tire_id").value =
                    this.dataset.id;

                document.getElementById("edit_tire_brand").value =
                    this.dataset.brand;

                document.getElementById("edit_tire_size").value =
                    this.dataset.size;

                document.getElementById("edit_tire_type").value =
                    this.dataset.type;

                document.getElementById("edit_tire_wear").value =
                    this.dataset.wear;

                document.getElementById("edit_tire_condition").value =
                    this.dataset.condition;

                editTireModal.style.display = "flex";

            });

        });

        document.getElementById("closeEditTireModal")
        .onclick = () => editTireModal.style.display = "none";

        document.getElementById("cancelEditTire")
        .onclick = () => editTireModal.style.display = "none";
    </script>
</body>

</html>