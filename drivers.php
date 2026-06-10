<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header("Location: welcome.html");
    exit();
    }

    include 'conexiune.php';
    $query="SELECT * FROM drivers";
    $result=mysqli_query($conn,$query);
    $query_cars = "SELECT id, brand, model FROM cars";
    $result_cars = mysqli_query($conn, $query_cars);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivers - Autodock</title>
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
            <li><a href="dashboard-manager.php">Dashboard</a></li>
            <li><a href="cars-m.php">Cars</a></li>
            <li><a href="drivers.php" class="active">Drivers</a></li>
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

                <a href="index.html" class="profile-menu-item sign-out">
                    <span class="material-symbols-outlined">logout</span> Sign Out
                </a>
            </div>
        </div>
    </nav>

    <main class="dashboard-container">


        <header class="page-header header-right">
            <button id="btnOpenDriverModal" class="btn-add-car">+ Add a new driver</button>
        </header>


        <div class="drivers-list">


            <div class="drivers-grid drivers-header-row">
                <div>Driver</div>
                <div>Assigned Vehicle</div>
                <div>License Type</div>
                <div>Phone</div>

            </div>


            <?php while($driver = mysqli_fetch_assoc($result)){ ?>
            <div class="drivers-grid driver-card">
                <div class="driver-profile">
                    <div class="avatar avatar-blue">
                        <?php echo strtoupper(substr($driver['first_name'],0,1).substr($driver['last_name'],0,1)); ?>
                    </div>
                    <div class="driver-info">
                        <strong>
                            <?php echo $driver['first_name']." ".$driver['last_name']; ?>
                        </strong>

                        <span class="driver-email">
                            <?php echo $driver['email']; ?>
                        </span>
                    </div>
                </div>
                <div class="vehicle-info">
                    Car ID:
                    <?php echo $driver['car_id'] ? $driver['car_id'] : 'Unassigned'; ?>
                </div>
                <div>
                    <?php echo $driver['license_category']; ?>
                </div>
                <div>
                    <?php echo $driver['phone']; ?>
                </div>
                <div class="table-actions"
                    style="display:flex;gap:12px;border:none;align-items:center;justify-content:flex-end;">

                    <a href="#"
                    class="btnEditDriver"

                    data-id="<?php echo $driver['id']; ?>"
                    data-firstname="<?php echo $driver['first_name']; ?>"
                    data-lastname="<?php echo $driver['last_name']; ?>"
                    data-phone="<?php echo $driver['phone']; ?>"
                    data-email="<?php echo $driver['email']; ?>"
                    data-license="<?php echo $driver['license_category']; ?>">

                        <span class="material-symbols-outlined action-icon edit-icon">
                            edit
                        </span>

                    </a>
                    <a href="delete_driver.php?id=<?php echo $driver['id']; ?>"
                    onclick="return confirm('Delete this driver?');">
                        <span class="material-symbols-outlined action-icon delete-icon">
                            delete
                        </span>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>
    <div id="addDriverModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeDriverModal">&times;</span>

            <h2>ADD NEW DRIVER</h2>

            <form id="driverForm" class="add-car-form" action="add_driver.php" method="POST">

                <h3 class="section-title">Personal information</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Full name</label>
                        <input type="text" name="full_name">
                    </div>
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>
                    <div class="input-group">
                        <label>Phone number</label>
                        <input type="text" name="phone">
                    </div>
                </div>

                <h3 class="section-title">License Details</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>License Category</label>
                        <input type="text" name="license_category">
                    </div>
                </div>

                <h3 class="section-title">Vehicle Assignment</h3>
                <div class="input-group full-width">
                    <label>Select Vehicle to Assign</label>
                    <select name="car_id">
                        <option value="">Unassigned</option>
                        <?php while($car = mysqli_fetch_assoc($result_cars)){ ?>
                            <option value="<?php echo $car['id']; ?>">
                                <?php echo $car['brand']." ".$car['model']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancelDriver">Cancel</button>
                    <input type="submit" value="Register Driver" class="btn-submit">
                </div>

            </form>
        </div>
    </div>
    <div id="editDriverModal" class="modal-overlay">
        <div class="modal-content" >
            <span class="close-modal" id="closeEditModal">&times;</span>

            <h2>EDIT DRIVER</h2>

            <form action="update_driver.php" method="POST">
                <input type="hidden" name="id" id="edit_driver_id">
                <h3 class="section-title">Personal information</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" id="edit_first_name">
                    </div>
                    <div class="input-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" id="edit_last_name">
                    <div class="input-group">
                        <label>Email</label>
                       <input type="email" name="email" id="edit_email">
                    </div>
                    <div class="input-group">
                        <label>Phone number</label>
                        <input type="text" name="phone" id="edit_phone">
                    </div>
                </div>

                <h3 class="section-title">License Details</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>License Category</label>
                        <input type="text"
                            name="license_category"
                            id="edit_license_category">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancelEdit">Cancel</button>
                    <button type="submit" class="btn-submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 420px; text-align: center;">
            <span class="close-modal" id="closeDeleteModal">&times;</span>

            <h2 style="color: var(--status-red); margin-bottom: 15px;">DELETE RECORD</h2>
            
            <p style="font-family: sans-serif; font-size: 15px; color: var(--text-gray-medium); margin-bottom: 30px; line-height: 1.5;">
                Are you sure you want to delete this driver? <br>
                <strong>This action cannot be undone.</strong>
            </p>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="btnCancelDelete">Cancel</button>
                <button type="button" class="btn-delete" id="btnConfirmDelete">Delete</button>
            </div>
        </div>
    </div>
    <script>
   
    const driverModal = document.getElementById("addDriverModal");
    const btnOpenDriver = document.getElementById("btnOpenDriverModal");
    const btnCloseDriver = document.getElementById("closeDriverModal");
    const btnCancelDriver = document.getElementById("btnCancelDriver");

    if (btnOpenDriver) {
        btnOpenDriver.onclick = function () {
            driverModal.style.display = "flex";
        }
    }
    if (btnCloseDriver) {
        btnCloseDriver.onclick = function () {
            driverModal.style.display = "none";
        }
    }
    if (btnCancelDriver) {
        btnCancelDriver.onclick = function () {
            driverModal.style.display = "none";
        }
    }

   
    const editModal = document.getElementById("editDriverModal");
    const closeEditBtn = document.getElementById("closeEditModal");
    const cancelEditBtn = document.getElementById("btnCancelEdit");
    document.querySelectorAll(".btnEditDriver")
      .forEach(button => {

         button.addEventListener("click", function(e){

             e.preventDefault();
             e.stopPropagation();

            document.getElementById("edit_driver_id").value =
                this.dataset.id;

            document.getElementById("edit_first_name").value =
                this.dataset.firstname;

            document.getElementById("edit_last_name").value =
                this.dataset.lastname;

            document.getElementById("edit_phone").value =
                this.dataset.phone;

            document.getElementById("edit_email").value =
                this.dataset.email;

            document.getElementById("edit_license_category").value =
                this.dataset.license;

            editModal.style.display = "flex";

        });

    });

    if (closeEditBtn) {
        closeEditBtn.onclick = function() {
            editModal.style.display = "none";
        }
    }
    if (cancelEditBtn) {
        cancelEditBtn.onclick = function() {
            editModal.style.display = "none";
        }
    }

    
    window.onclick = function (event) {
        if (event.target == driverModal) {
            driverModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }

  
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


    document.addEventListener('DOMContentLoaded', () => {
        const driverCards = document.querySelectorAll('.driver-card');

        driverCards.forEach(card => {
            card.addEventListener('click', function (e) {
               
                if (e.target.closest('.action-icon')) return;

              
                driverCards.forEach(c => {
                    if (c !== this) {
                        c.classList.remove('show-actions');
                    }
                });

               
                this.classList.toggle('show-actions');
            });
        });
    });
    
</script>
</body>

</html>