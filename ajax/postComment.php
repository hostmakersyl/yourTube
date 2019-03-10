<?php 

require_once ("../includes/connection.php");
require_once ("../includes/classes/User.php");
require_once ("../includes/classes/Comment.php");

if(isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoId'])){
    $userLoggedInObj = new User($connection, $_SESSION['userLogedIn']);


    $commentText = $_POST['commentText'];
    $postedBy = $_POST['postedBy'];
    $videoId = $_POST['videoId'];
    $responseTo  = isset($_POST['responseTo']) ? $_POST['responseTo'] : 0;
    $query = $connection->prepare("INSERT INTO comments (postedBy,videoId,responseTo,body) VALUES (:postedBy, :videoId, :responseTo, :body)");
    $query->bindParam(":postedBy", $postedBy);
    $query->bindParam(":videoId", $videoId);
    $query->bindParam(":responseTo", $responseTo);
    $query->bindParam(":body", $commentText);
    $query->execute();
    //Return new HTML comment


    $newComment = new Comment($connection, $userLoggedInObj, $connection->lastInsertId(), $videoId);

    echo $newComment->create();

   

  
    
   
} else {
    echo " You should log in to post";
}



?>s