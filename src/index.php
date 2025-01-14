<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Ctrl Alt Elite</title>
    <script src="https://kit.fontawesome.com/24be3f9595.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav>
        <ul class="left-nav">
            <li><img src="assets/ctrlaltelite.png"></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="discover.php">Discover</a></li>
            <li><a href="dashboard/dashboard.php">Dashboard</a></li>
        </ul>
        <ul class="right-nav">
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Sign up</a></li>
            <!-- <li><i class="fa-solid fa-globe"></i>
                <select name="language" id="language">
                    <option value="english">EN</option>
                    <option value="dutch">NL</option>
                </select>
            </li> -->
        </ul>
    </nav>
    <div class="main">
        <span style="color: var(--secondary)"><b>LETS START THE QUIZ!</b></span>
        <h2>Step into a Realm of <br>Infinite Quiz Excitement</h2>
        <h3>Ready to test your knowledge and have fun? Challenge <br> friends, learn new things, and Kahoot your way to
            victory!</h3>

        <div class="opaImage">
            <img src="assets/opa.png" alt="opa">
        </div>

        <button type="submit" class="hostnow" onclick="sendToDashboard()">Host Now!</button> <a class="underline"
            href="discover.php">Create Your Quiz</a>
            
        <h4>Get our app now on:</h4>
        <div class="appStore">
            <button>
                <img src="assets/play_store_grey.png" alt="Google Play">
                <span>Play Store</span>
            </button>
            <button>
                <img src="assets/app_store_grey.png" alt="App Store">
                <span>App Store</span>
            </button>
        </div>
    </div>
    
    <script>
        function sendToDashboard() {
            window.location.href = "dashboard/dashboard.php";
        }
    </script>
</body>

</html>