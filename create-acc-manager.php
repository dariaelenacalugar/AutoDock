<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <title>AutoDock</title>


</head>

<body>
    <div class="container">
        <div class="left-panel">
            <div class="logo">
                <a href="index.html">
                    <img src="images/logo.png" alt="Autodock Logo" class="logo-img">
                </a>
            </div>
            <div class="tagline">Smart, efficient and centralized<br>fleet management</div>
            <hr class="divider">
            <ul class="features">
                <li>
                    <span class="material-symbols-outlined list-icon">bar_chart</span>
                    <div>Real-time dashboard and<br>statistics</div>
                </li>
                <li>
                    <span class="material-symbols-outlined list-icon">notifications_active</span>
                    <div>Alerts for expiring<br>documents</div>
                </li>
                <li>
                    <span class="material-symbols-outlined list-icon">calendar_month</span>
                    <div>Integrated service<br>calendar</div>
                </li>
                <li>
                    <span class="material-symbols-outlined list-icon">shield</span>
                    <div>Insurance and vignette<br>management</div>
                </li>
            </ul>
        </div>

        <div class="right-panel">
            <h1>Create account</h1>

            <div class="role-selector">
                <div id="btn-manager" class="role-btn active">
                    <span>Manager</span>
                </div>
                <a href="create-acc-user.php" id="btn-user" class="role-btn" style="text-decoration: none;">
                    <span>User</span>
                </a>
            </div>

            <form class="register-form" action="register-manager.php" method="POST" >

                <div class="form-row">
                    <div class="input-group">
                        <label>First name</label>
                        <input type="text" name="first_name">
                    </div>
                    <div class="input-group">
                        <label>Last name</label>
                        <input type="text" name="last_name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username">
                    </div>
                </div>

                <div class="input-group">
                    <label>Company name</label>
                    <input type="text" name="company_name">
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password">
                    </div>
                    <div class="input-group">
                        <label>Confirm password</label>
                        <input type="password" name="confirm_password">
                    </div>
                </div>

                <div class="terms-group">
                    <label class="remember-me">
                        <input type="checkbox" name="terms" required> I agree with Terms & Conditions and Privacy Policy
                    </label>
                </div>

                <button type="submit" class="signup-btn">Sign up</button>

                <div class="redirect-link">
                    <span>Already have an account?</span>
                    <a href="welcome.html">Sign in</a>
                </div>

            </form>
        </div>



    </div>
    </div>

</body>

</html>