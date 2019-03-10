<?php require_once "includes/connection.php";
require_once "includes/classes/Account.php";
require_once "includes/classes/Constants.php";
require_once "includes/classes/FormSanitaizer.php";

$account = new Account($connection);

if(isset($_POST['submit'])){
    $firstName = FormSanitaizer::sanitizeFormString($_POST['firstName']);
    $lastName = FormSanitaizer::sanitizeFormString($_POST['lastName']);
    $userName = FormSanitaizer::sanitizeFormUsername($_POST['userName']);
    $email = FormSanitaizer::sanitizeFormEmail($_POST['email']);
    $email2 = FormSanitaizer::sanitizeFormEmail($_POST['email2']);
    $password = FormSanitaizer::sanitizeFormPassword($_POST['password']);
    $password2 = FormSanitaizer::sanitizeFormPassword($_POST['password2']);

     $wasSuccessful = $account->register($firstName, $lastName, $userName, $email, $email2, $password, $password2);

     if($wasSuccessful){
         // Success message
         $_SESSION['userLogedIn'] = $userName;
         // Redirect To index page
         header("Location: index.php");

     }
}


function getInputValueOfForm($name){
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
            <h3>Sign Up</h3>
            <span>To Continue To VideoTube</span>
        </div>

        <div class="signUpForm">
            <form action="signUp.php" method="post" >

                <?php echo $account->getErrorMessage(Constants::$userAccountConfirmation) ;?>

                <?php echo $account->getErrorMessage(Constants::$firstNameCharecter); ?>
                <input type="text" name="firstName" value="<?php getInputValueOfForm('firstName') ;?>" placeholder="First Name" autocomplete="Off" required>
                <?php echo $account->getErrorMessage(Constants::$lastNameCharecter); ?>
                <input type="text" name="lastName"  value="<?php getInputValueOfForm('lastName') ;?>"  placeholder="last Name" autocomplete="Off" required>
                <?php echo $account->getErrorMessage(Constants::$usernameCharecter); ?>
                <?php echo $account->getErrorMessage(Constants::$usernameExists); ?>
                <input type="text" name="userName"  value="<?php getInputValueOfForm('userName') ;?>"  placeholder="Username" autocomplete="Off" required>

                <?php echo $account->getErrorMessage(Constants::$emailExist); ?>
                <?php echo $account->getErrorMessage(Constants::$emailValid); ?>
                <input type="email" name="email" value="<?php getInputValueOfForm('email') ;?>"  placeholder="Email" required>
                <?php echo $account->getErrorMessage(Constants::$emailNotMatch); ?>
                <input type="email" name="email2"  value="<?php getInputValueOfForm('email2') ;?>"  placeholder="Confirm Email" required>

                <?php echo $account->getErrorMessage(Constants::$passwordLetter); ?>
                <?php echo $account->getErrorMessage(Constants::$passwordLength); ?>
                <input type="password" name="password"  value="<?php getInputValueOfForm('password') ;?>"  placeholder="Password" required>
                <?php echo $account->getErrorMessage(Constants::$passwordNotMatch); ?>
                <input type="password" name="password2"  value="<?php getInputValueOfForm('password2') ;?>"  placeholder="Confirm Password" required>

                <input type="submit" name="submit" value="SUBMIT">


            </form>

        </div>

        <span>Already Have An Acoount.<a class="signInMessage" href="signIn.php"> Sign In </a> Hear.</span>

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