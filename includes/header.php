<?php
    require_once "includes/connection.php";
    require_once "includes/classes/ButtonProvider.php";
    require_once "includes/classes/User.php";
    require_once "includes/classes/Video.php";
    require_once "includes/classes/VideoGrid.php";
    require_once "includes/classes/VideoGridItems.php";
    require_once "includes/classes/SubscriptionProvider.php";

    //session_destroy();
    $usernameLogedIn = User::isLoggedIn() ? $_SESSION["userLogedIn"] : "";

    $userLogedInObj = new user($connection, $usernameLogedIn);


?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Tube</title>
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity = "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin = "anonymous">
    <link type = "text/css" rel = "stylesheet" href = "assets/css/style.css"/>


</head>
<body>
<div id = "pageContainer">
    <div id = "mastHeadContainer">
        <button class = "navShowHide menuButton"><img class = "menuImage" src = "assets/images/menu.png"/></button>
        <a href = "index.php">
            <img class = "logoContainer" src = "assets/images/icons/VideoTubeLogo.png" title = "logo" alt = "Your Tube Logo">
        </a>
        <div class="searchBarContainer">
            <form class="searchBarForm" action="search.php" method="GET">
                <input class="searchBar searchButton" type="text" name="term" placeholder="Search">
                <button class="searchButton ">
                    <img src="assets/images/icons/search.png" alt="Search" title="Search">
                </button>
            </form>
        </div>
        <div class="rightIcon">
            <a href="upload.php"><img class="upload" src="assets/images/icons/upload.png" alt="Upload"></a>
            <a href="#"><img class="profile" src="assets/images/profilePictures/default-male.png" alt="Profile"></a>
        </div>
    </div>
    <div id = "sideNavContainer" style = "display: none;">
        Hello 3
    </div>

    <div id = "mainSectionContainer">
        <div id = "mainContentContainer">