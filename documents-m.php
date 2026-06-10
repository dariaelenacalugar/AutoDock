<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: welcome.html");
        exit();
    }

    include 'conexiune.php';

    $queryCars = "SELECT * FROM cars";
    $resultCars = mysqli_query($conn, $queryCars);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Documents - Autodock</title>
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
            <li><a href="cars-m.php">Cars</a></li>
            <li><a href="drivers.php">Drivers</a></li>
            <li><a href="services.php">Service</a></li>
            <li><a href="calendar.php">Calendar</a></li>
            <li><a href="documents-m.php" class="active">Documents</a></li>
        </ul>

        <div class="profile-dropdown-container">
            <div class="profile-trigger" id="profileTrigger">
                <div class="profile-avatar">AP</div>
                <div class="profile-info">
                    <?php echo $_SESSION['username']; ?>
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

        <header class="docs-header">
            <div>
                <h1 class="docs-title">Fleet Documents</h1>
                <p class="docs-subtitle">Provider & Series Management</p>
            </div>
            <button class="btn-add-doc" id="btnOpenDocModal">+ Add a new document</button>
        </header>

        <div class="accordion-list">
                <div class="acc-header-row">
                    <div class="acc-col-vehicle">Vehicle Details</div>
                    <div class="acc-col-status">Global Status</div>
                    <div class="acc-col-docs">Total Active Docs</div>
                    <div class="acc-col-arrow"></div>
                </div>
            <?php while($car = mysqli_fetch_assoc($resultCars)){ ?>
            <?php
                $carId = $car['id'];
                $queryDocs = "SELECT * FROM documents WHERE car_id = $carId";
                $resultDocs = mysqli_query($conn,$queryDocs);
                $totalDocs = mysqli_num_rows($resultDocs);
                $statusText = "All Valid";
                $statusClass = "text-green";
                mysqli_data_seek($resultDocs,0);
                while($doc = mysqli_fetch_assoc($resultDocs))
                {
                    if(strtotime($doc['expiry_date']) < time())
                    {
                        $statusText = "Document Expired";
                        $statusClass = "text-red";
                        break;
                    }
                }
                mysqli_data_seek($resultDocs,0);
            ?>
            <div class="acc-item">
                <div class="acc-main-row">
                    <div class="acc-col-vehicle">
                        <strong>
                            <?php echo $car['brand']." ".$car['model']; ?>
                        </strong>

                        <span class="plate-text">
                            <?php echo $car['license_plate']; ?>
                        </span>
                    </div>
                    <div class="acc-col-status <?php echo $statusClass; ?>">
                        <?php echo $statusText; ?>
                    </div>
                    <div class="acc-col-docs">
                        <?php echo $totalDocs; ?> Documents total
                    </div>
                    <div class="acc-col-arrow">
                        <span class="material-symbols-outlined expand-icon">
                            expand_more
                        </span>
                    </div>
                </div>
                <div class="acc-details-row">
                    <table class="nested-table">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Provider</th>
                                <th>Valid Until</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($doc = mysqli_fetch_assoc($resultDocs)){ ?>
                            <?php
                            $expired = strtotime($doc['expiry_date']) < time();
                            ?>
                            <tr>
                                <td>
                                    <?php echo $doc['doc_type']; ?>
                                </td>
                                <td>
                                    <?php echo $doc['provider']; ?>
                                </td>
                                <td>
                                    <?php echo $doc['expiry_date']; ?>
                                </td>
                                <td class="<?php echo $expired ? 'text-red' : 'text-green'; ?>">
                                    <?php echo $expired ? 'EXPIRED' : 'VALID'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
    </main>
    <div id="addDocModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="closeDocModal">&times;</span>

            <h2>ADD NEW DOCUMENT</h2>

            <form class="add-car-form">

                <h3 class="section-title">Document Details</h3>
                <div class="form-grid">
                    <div class="input-group">
                        <label>Select Vehicle</label>
                        <select>
                            <option>SB 07 DKY - Ford Focus</option>
                            <option>CJ 92 CBL - Renault Megane</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Expiration Date</label>
                        <input type="date" value="2026-09-06">
                    </div>
                    <div class="input-group">
                        <label>Document Type</label>
                        <select>
                            <option>ITP</option>
                            <option>RCA</option>
                            <option>Vinietă RO</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Provider / Issuer</label>
                        <input type="text" placeholder="e.g. Groupama">
                    </div>
                </div>

                <h3 class="section-title">Digital Copy</h3>
                <label for="docUploadInput" class="upload-area" id="dropZone">
                    <span class="material-symbols-outlined" id="removeFileBtn" style="display: none;"
                        title="Remove file">delete</span>

                    <input type="file" id="docUploadInput" accept=".pdf, image/png, image/jpeg" hidden>

                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                        stroke="#9C6D9D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path>
                        <path d="M12 12v9"></path>
                        <path d="m8 16 4-4 4 4"></path>
                    </svg>
                    <p id="uploadText">Click to upload or drag & drop PDF/Image of the document</p>
                </label>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCancelDoc">Cancel</button>
                    <button type="submit" class="btn-submit">Save document</button>
                </div>

            </form>
        </div>
    </div>

    <script>

        const docModal = document.getElementById("addDocModal");
        const btnOpenDoc = document.getElementById("btnOpenDocModal");
        const btnCloseDoc = document.getElementById("closeDocModal");
        const btnCancelDoc = document.getElementById("btnCancelDoc");

        if (btnOpenDoc) {
            btnOpenDoc.onclick = function () {
                docModal.style.display = "flex";
            }
        }
        if (btnCloseDoc) {
            btnCloseDoc.onclick = function () {
                docModal.style.display = "none";
            }
        }
        if (btnCancelDoc) {
            btnCancelDoc.onclick = function () {
                docModal.style.display = "none";
            }
        }
        window.onclick = function (event) {
            if (event.target == docModal) {
                docModal.style.display = "none";
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


        const accMainRows = document.querySelectorAll('.acc-main-row');

        accMainRows.forEach(row => {
            row.addEventListener('click', function () {
                const item = this.parentElement;
                item.classList.toggle('active');
            });
        });


        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('docUploadInput');
        const uploadText = document.getElementById('uploadText');
        const removeFileBtn = document.getElementById('removeFileBtn');

        if (dropZone && fileInput && uploadText) {


            function updateFileText(files) {
                if (files && files.length > 0) {
                    uploadText.innerHTML = `<strong>File selected:</strong> ${files[0].name}`;
                    uploadText.style.color = '#5CB85C'; // Verde
                    if (removeFileBtn) removeFileBtn.style.display = 'block'; // Arătăm coșul
                } else {
                    uploadText.innerHTML = 'Click to upload or drag & drop PDF/Image of the document';
                    uploadText.style.color = '';
                    if (removeFileBtn) removeFileBtn.style.display = 'none'; // Ascundem coșul
                }
            }


            if (removeFileBtn) {
                removeFileBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Oprește deschiderea ferestrei de Windows

                    fileInput.value = ''; // Ștergem fișierul
                    updateFileText(null); // Resetăm interfața
                });
            }

            fileInput.addEventListener('change', function () {
                updateFileText(this.files);
            });


            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }


            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
            });


            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
            });


            dropZone.addEventListener('drop', function (e) {
                let dt = e.dataTransfer;
                let files = dt.files;

                fileInput.files = files;
                updateFileText(files);
            });
        }
    </script>
</body>

</html>