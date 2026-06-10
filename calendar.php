<?php
session_start();
include 'conexiune.php';
$query_cars = "SELECT id, brand, model, license_plate FROM cars";
$result_cars = mysqli_query($conn, $query_cars);

$query = "
SELECT service_orders.*, cars.license_plate
FROM service_orders
LEFT JOIN cars ON service_orders.car_id = cars.id
ORDER BY appointment_date
";

$result = mysqli_query($conn, $query);
$events = [];

while($row = mysqli_fetch_assoc($result)){
    $day = date('j', strtotime($row['appointment_date']));
    $events[$day][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Calendar - Autodock</title>
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
            <li><a href="dashboard_manager.php">Dashboard</a></li>
            <li><a href="cars-m.php">Cars</a></li>
            <li><a href="drivers.php">Drivers</a></li>
            <li><a href="services.php">Service</a></li>
            <li><a href="calendar.php" class="active">Calendar</a></li>
            <li><a href="documents-m.php">Documents</a></li>
        </ul>

      
        <div class="profile-dropdown-container">
            <div class="profile-trigger" id="profileTrigger">
                <div class="profile-avatar">AP</div>
                <div class="profile-info">
                    <span class="profile-name"><?php echo $_SESSION['username']; ?></span>
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
            <h1>Fleet Calendar - June 2026</h1>
            <button id="btnOpenEventModal" class="btn-add-car">+ Add a new event</button>
        </header>

        <div class="calendar-wrapper">

            <div class="calendar-header-row">
                <div>MON</div>
                <div>TUE</div>
                <div>WED</div>
                <div>THU</div>
                <div>FRI</div>
                <div>SAT</div>
                <div>SUN</div>
            </div>
            <div class="calendar-grid">

                <?php for($day=1; $day<=30; $day++){ ?>

                <div class="cal-day">
                    <span class="day-number"><?php echo $day; ?></span>

                    <?php
                    if(isset($events[$day])){
                        foreach($events[$day] as $event){

                            $class = "event-grey";

                            if($event['intervention_type'] == "revision"){
                                $class = "event-green";
                            }

                            if($event['intervention_type'] == "repair"){
                                $class = "event-red";
                            }

                            if($event['intervention_type'] == "inspection"){
                                $class = "event-yellow";
                            }

                            echo "<div class='event-pill ".$class."'>";
                            echo "<div class='event-content'>";
                            echo "<strong>".$event['license_plate']."</strong><br>";
                            echo ucfirst($event['intervention_type']);
                            echo "</div>";
                            echo "<div class='event-actions'>";
                            echo "<a href='#'
                                    class='btnEditEvent'
                                    data-id='".$event['id']."'
                                    data-date='".$event['appointment_date']."'
                                    data-type='".$event['intervention_type']."'
                                    data-cost='".$event['estimated_cost']."'
                                    data-description='".$event['description']."'
                                    data-center='".$event['service_center']."'>
                                    <span class='material-symbols-outlined action-icon edit-icon'>
                                        edit
                                    </span>
                                </a>";
                            echo "<a href='delete_event.php?id=".$event['id']."'
                                    onclick=\"return confirm('Delete this event?');\">
                                    <span class='material-symbols-outlined action-icon delete-icon'>
                                        delete
                                    </span>
                                </a>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

                <?php } ?>

                <div class="cal-day"><span class="day-number inactive">1</span></div>
                <div class="cal-day"><span class="day-number inactive">2</span></div>
                <div class="cal-day"><span class="day-number inactive">3</span></div>
                <div class="cal-day"><span class="day-number inactive">4</span></div>
                <div class="cal-day"><span class="day-number inactive">5</span></div>

            </div>
        </div>
    </main>
    <div id="addEventModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeEventModal">&times;</span>

            <h2>ADD NEW EVENT</h2>

            <form class="add-car-form" action="add_services.php" method="POST">

                <h3 class="section-title">Event type</h3>
                <div class="event-type-options">
                    <button type="button" class="event-type-btn active">Service</button>
                    <button type="button" class="event-type-btn">Legal(ITP/RCA)</button>
                    <button type="button" class="event-type-btn">Other</button>
                </div>

                <h3 class="section-title">Select vehicle</h3>
                <div class="input-group full-width">
                    <select name="car_id">
                    <?php while($car = mysqli_fetch_assoc($result_cars)){ ?>
                        <option value="<?php echo $car['id']; ?>">
                            <?php echo $car['license_plate']." - ".$car['brand']." ".$car['model']; ?>
                        </option>
                    <?php } ?>
                    </select>
                </div>

                <h3 class="section-title">Start date</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <input type="date" name="appointment_date">
                    </div>
                    <div class="input-group">
                        <input type="text" name="estimated_cost" placeholder="e.g. 500 RON">
                    </div>
                </div>

                <h3 class="section-title">Description/Notes</h3>
                <div class="input-group full-width">
                    <textarea name="description" rows="3"></textarea>
                </div>
                <input type="text"
                       name="service_center"
                       placeholder="Service center">
                <select name="intervention_type">
                    <option value="revision">Revision</option>
                    <option value="repair">Repair</option>
                    <option value="inspection">Inspection</option>
                    <option value="other">Other</option>
                </select>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancelEvent">Cancel</button>
                    <button type="submit" class="btn-submit">Create event</button>
                </div>

            </form>
        </div>
    </div>
    <div id="editEventModal" class="modal-overlay">
        <div class="modal-content">

            <span class="close-modal" id="closeEditModal">&times;</span>

            <h2>EDIT EVENT</h2>

            <form action="update_event.php" method="POST">

                <input type="hidden" name="id" id="edit_id">

                <div class="input-group">
                    <label>Date</label>
                    <input type="date" name="appointment_date" id="edit_date">
                </div>

                <div class="input-group">
                    <label>Cost</label>
                    <input type="text" name="estimated_cost" id="edit_cost">
                </div>

                <div class="input-group">
                    <label>Service Center</label>
                    <input type="text" name="service_center" id="edit_center">
                </div>

                <div class="input-group">
                    <label>Type</label>
                    <select name="intervention_type" id="edit_type">
                        <option value="revision">Revision</option>
                        <option value="repair">Repair</option>
                        <option value="inspection">Inspection</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Description</label>
                    <textarea name="description"
                            id="edit_description"
                            rows="4"></textarea>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn-submit">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const eventModal = document.getElementById("addEventModal");
        const btnOpenEvent = document.getElementById("btnOpenEventModal");
        const btnCloseEvent = document.getElementById("closeEventModal");
        const btnCancelEvent = document.getElementById("btnCancelEvent");

       
        btnOpenEvent.onclick = function () {
            eventModal.style.display = "flex";
        }
        btnCloseEvent.onclick = function () {
            eventModal.style.display = "none";
        }
        btnCancelEvent.onclick = function () {
            eventModal.style.display = "none";
        }
        window.onclick = function (event) {
            if (event.target == eventModal) {
                eventModal.style.display = "none";
            }
        }

       
        const typeButtons = document.querySelectorAll('.event-type-btn');
        typeButtons.forEach(button => {
            button.addEventListener('click', function () {
                
                typeButtons.forEach(btn => btn.classList.remove('active'));
                
                this.classList.add('active');
            });
        });

      
        const profileTrigger = document.getElementById('profileTrigger');
        const profileMenu = document.getElementById('profileMenu');

        profileTrigger.addEventListener('click', function(event) {
            profileMenu.classList.toggle('show');
            event.stopPropagation(); 
        });

        document.addEventListener('click', function(event) {
            if (!profileMenu.contains(event.target) && !profileTrigger.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
        });
        const editModal = document.getElementById('editEventModal');

        document.querySelectorAll('.btnEditEvent').forEach(btn => {

            btn.addEventListener('click', function(e){

                e.preventDefault();

                document.getElementById('edit_id').value =
                    this.dataset.id;

                document.getElementById('edit_date').value =
                    this.dataset.date;

                document.getElementById('edit_cost').value =
                    this.dataset.cost;

                document.getElementById('edit_center').value =
                    this.dataset.center;

                document.getElementById('edit_type').value =
                    this.dataset.type;

                document.getElementById('edit_description').value =
                    this.dataset.description;

                editModal.style.display = "flex";
            });

        });

        document.getElementById('closeEditModal').onclick = function(){
            editModal.style.display = "none";
        };
    </script>
</body>

</html>