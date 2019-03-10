<?php
    require_once "includes/connection.php";
    require_once "includes/classes/Account.php";
    require_once "includes/classes/Constants.php";
    require_once "includes/classes/FormSanitaizer.php";

    $account = new Account($connection);
    if(isset($_POST['submitButton'])){
        $logInUser = FormSanitaizer::sanitizeFormString($_POST['username']);
        $logInpassword = FormSanitaizer::sanitizeFormString($_POST['password']);

        $wasSuccessful = $account->logInUser($logInUser, $logInpassword);


        if($wasSuccessful){
            // Success message
            $_SESSION['userLogedIn'] = $logInUser;
            // Redirect To index page
            header("Location: index.php");

        }
    }
    function getInputValueOfLogIn($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }

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
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <img class = "logoContainer" src = "assets/images/icons/VideoTubeLogo.png" title = "logo" alt = "Your Tube Logo">
                <h3>Log In</h3>
                <span>To Continue To VideoTube</span>
            </div>

            <div class="loginForm">
                <form action="signIn.php" method="post">
                    <?php echo $account->getErrorMessage(Constants::$usernameIncorrect); ?>


                    <input type="text" name="username" value="<?php getInputValueOfLogIn('username') ;?>"  placeholder="Username" required >
                    <input type="password" name="password" value="<?php getInputValueOfLogIn('password') ;?>"   placeholder="Password" required >

                    <input type="submit" name="submitButton" value="Log In">

                </form>

            </div>

            <span>No Account. You can <a class="signInMessage" href="signUp.php"> Register </a>Hear.</span>

        </div>

    </div>



<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity = "sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin = "anonymous"></script>
<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity = "sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin = "anonymous"></script>
<script src = "assets/js/commonAction.js" type = "text/javascript"></script>
</body>
</html>