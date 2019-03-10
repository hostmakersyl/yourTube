<?php require_once "includes/header.php"; ?>


    <?php
//    session_destroy(); // It will destroy all session variable. We can do ohter way
    //unset($_SESSION['userLogedIn']); // It only destroy this variable only
        if(isset($_SESSION['userLogedIn'])){
            echo "User Loged in as "  . $userLogedInObj->getUserFullName() . "<br>";
            echo "User Loged in as "  . $userLogedInObj->getUserFirstName(). "<br>";
            echo "User Loged in as "  . $userLogedInObj->getUserLastName() . "<br>";
            echo "User Loged in as "  . $userLogedInObj->getUserEmail() . "<br>";
            echo "User Loged in as "  . $userLogedInObj->getUserProfilePic() . "<br>";
            echo "User Register in as "  . $userLogedInObj->getUserRegisterDate() . "<br>";

        } else {
            echo "User not logged in";
        }
    ?>

<?php require_once "includes/fotter.php" ;?>