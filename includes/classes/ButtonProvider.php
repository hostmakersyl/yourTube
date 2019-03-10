<?php 

class ButtonProvider {

    public static $signInfunction = "notSignedIn()";

    public static function createLink($link){
        return  User::isLoggedIn() ? $link : ButtonProvider::$signInfunction;
    }

    public static function createButton ($text, $imageSrc, $action, $class ) {
        $image = ($imageSrc == null) ?"" : "<img src='$imageSrc'>" ;

        // Change action if needed
        $action = ButtonProvider::createLink($action);

       return "<button class='$class' onclick='$action'> 
            $image
            <span class='text'> $text </span>
        </button>";
    }

    public static function createUserProfileButton($connection, $username) {
        $userObj = new User($connection, $username);
        $profilePic = $userObj->getUserProfilePic();
        $link = "profile.php?username=$username";

        return "<a href='$link'> 
                    <img src='$profilePic' class='profilePicture'>
        
                </a>";
    }

    
    public static function createHyperlinkButton ($text, $imageSrc, $href, $class ) {
        $image = ($imageSrc == null) ?"" : "<img src='$imageSrc'>" ;

 
       return "<a href='$href'><button class='$class'> 
            $image
            <span class='text'> $text </span>
        </button> </a>";
    }

    public static function createEditVideoButton($videoId){
        $href = "editVideo.php?videoId=$videoId";

        $button = ButtonProvider::createHyperlinkButton("Edit Video", null, $href, "edit button");
        return "<div class = 'editVideoButtonContainer'>
                    $button
                </div>";
    }

    public static function createSubscriberButton($connection, $userToObj, $userLoggedInObj){
        $userTo = $userToObj->getUsername();
        $userLoggedIn = $userLoggedInObj->getUsername();
        $isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);
        $buttonText = $isSubscribedTo ? "Subscribed" : "Subscribe";
        $buttonText .= " ". $userToObj->getSubscriberCount();

        $buttonClass = $isSubscribedTo ? "unSubscribe button" : "subscribe button";
        $action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

        $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

        return "
            <div class='subscribeButtonContainer'> $button </div> 
        ";

    }

}

?>


