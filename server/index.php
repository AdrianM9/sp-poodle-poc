<?php
/*
* A simple PHP site with login actions for a bank.
* @author: Mihai Iamandei, Teodor-Adrian Mirea
* @original: RootDev4 https://github.com/RootDev4/poodle-PoC
*/
header("Access-Control-Allow-Origin: http://".$_SERVER["REMOTE_ADDR"]);
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Default user credentials
define("USERNAME", "sp-usr");
define("PASSWORD", "sp-pwd");

// Variables
$username = "";
$password = "";
$wrong_credentials = false;

function generate_session_cookie($username) {
    return base64_encode($username);
}

function get_user_by_session_cookie($session_cookie) {
    return base64_decode($session_cookie);
}

// User logged in
if (isset($_COOKIE["SESSIONID"])) {
    $_SESSION["user"] = get_user_by_session_cookie($_COOKIE["SESSIONID"]);
}

// User login
if (isset($_POST["login"])) {
    $username = @$_POST["username"];
    $password = @$_POST["password"];

    if ($username == USERNAME && $password == PASSWORD) {
        // Set cookie for 24 hours
        setcookie("SESSIONID", generate_session_cookie($username), time()+60*60*24);
        $_SESSION["user"] = USERNAME;
        header("Location: .");
    } else {
        $wrong_credentials = true;
    }
}

// User logout
if (isset($_GET["logout"]) && isset($_SESSION["user"])) {
    setcookie("SESSIONID", null);
    header("Location: .");
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCR Romania</title>
    <style type="text/css">
        body {
            font-family: "Open Sans", sans-serif;
            margin: 0;
        }

        header {
            background-color: rgba(188, 228, 250, 0.9);
            display: flex;
            align-items: center;
            padding: 1rem;
        }

        .menus {
            align-items: center;
            display: flex;
            margin-left: 1.5rem;
            width: -moz-available;
        }

        .menu {
            color: rgb(0, 73, 123);
            cursor: pointer;
            margin-left: 1.5rem;
            max-width: -moz-max-content;
            width: -moz-available;
        }

        .menu:hover {
            color: rgb(0, 115, 187);
        }

        .account {
            align-items: flex-end;
            display: flex;
            flex-direction: column;
            margin-left: 1.5rem;
            width: -moz-max-content;
        }

        .user {
            color: rgb(0, 73, 123);
            width: -moz-max-content;
        }

        .username {
            font-weight: bold;
        }

        .logout-button {
            color: rgb(0, 73, 123);
            font-size: 14px;
            font-style: italic;
            text-decoration: none;
        }

        .logout-button:hover {
            color: rgb(0, 115, 187);
        }

        .login-button {
            color: rgb(0, 73, 123);
            font-weight: bold;
            text-decoration: none;
            width: -moz-max-content;
        }

        .main-view {
            align-items: center;
            background-position: 50% 0;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .main-view.home {
            background-image: url("images/home_bg.png");
        }

        .ad-container {
            color: white;
            padding-left: 20%;
            width: 50%;
        }

        .ad-title {
            font-size: 55px;
        }

        .ad-details {
            font-size: 18px;
        }

        .ad-more-button {
            background-color: rgb(219, 106, 0);
            border: rgb(255, 121, 0) 1px solid;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            width: -moz-fit-content;
        }

        .ad-more-button:hover {
            background-color: rgb(201, 94, 0);
        }

        .main-view.login {
            background-image: url("images/login_bg.png");
            justify-content: center;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 20rem;
        }

        .login-form .login-form-title{
            color: white;
            font-size: 29px;
            margin-bottom: 2rem;
        }

        .login-form input {
            background-color: rgba(0, 0, 0, 0.3);
            border: none;
            color: white;
            line-height: 2rem;
            margin-bottom: 1rem;
            width: 100%;
        }

        .login-form input::-moz-selection{
            background-color: white;
            color: black;
        }

        .login-form .error-message {
            color: rgb(255, 155, 155);
            font-size: 14px;
            font-style: italic;
            line-height: 0;
        }

        .login-form button {
            background-color: rgb(0, 73, 123);
            border: rgb(0, 112, 187) 1px solid;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-top: 2rem;
            padding: 0.5rem 1rem;
            width: 100%;
        }
        
        .login-form button:hover {
            background-color: rgb(0, 94, 153);
        }
    </style>
</head>
<body>
    <header>
        <a href="/">
            <img src="images/logo.svg" alt="Logo BCR Banca Comerciala Romana" height="38" width="102">
        </a>
        <div class="menus">
            <div class="menu">Want George account</div>
            <div class="menu">Digital Banking</div>
            <div class="menu">Accounts and debit cards</div>
            <div class="menu">Loans</div>
            <div class="menu">Savings and investment</div>
            <div class="menu">Insurance</div>
            <div class="menu">Others</div>
            <div class="menu"></div>
        </div>
        <div class="account">
            <?php if (isset($_SESSION["user"])) { ?>
                <div class="user">Hi, <span class="username">sp-usr</span></div>
                <a href="?logout" class="logout-button">Log out</a>
            <?php } elseif ($_SERVER["REQUEST_URI"] != "/login") { ?>
                <a href="login" class="login-button">Log in</a>
            <?php } ?>
        </div>
        
    </header>

    <?php if ($_SERVER["REQUEST_URI"] == "/") { ?>
        <div class="main-view home">
            <div class="ad-container">
                <h1 class="ad-title">More shopping with Credit Card 100% online</h1>
                <h2 class="ad-details">Get your Credit Card 100% online and you are free to shop anytime and anywhere with zero fees and equal interest-free installments.</h2>
                <div class="ad-more-button">
                    Find out more
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($_SERVER["REQUEST_URI"] == "/login") { ?>
        <div class="main-view login">
            <form class="login-form" action="" method="POST">
                <div class="login-form-title">Log into your account</div>
                <input type="text" name="username" placeholder="Your username" required value="<?php echo $username; ?>">
                <input type="password" name="password" placeholder="Your password" required>
                <?php if ($wrong_credentials) { ?>
                    <span class="error-message">Username or password is incorrect</span>
                <?php } ?>
                <button type="submit" name="login">Log in</button>
            </form>
        </div>
    <?php } ?>
</body>
