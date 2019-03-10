<?php 

require_once ("../includes/connection.php");

if(isset($_POST['userTo']) && isset($_POST['userFrom'])){

    $userTo = $_POST['userTo'] ;
    $userFrom = $_POST['userFrom'] ;

    $query = $connection->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
    $query->bindParam(":userTo", $userTo);
    $query->bindParam(":userFrom", $userFrom);
    $query->execute();

    if($query->rowCount() == 0 ){
        //Insert
        $query = $connection->prepare("INSERT INTO subscribers (userTo, userFrom) VALUES (:userTo, :userFrom)");
        $query->bindParam(":userTo", $userTo);
        $query->bindParam(":userFrom", $userFrom);
        $query->execute();
    } else {
        // Delete
        $query = $connection->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindParam(":userTo", $userTo);
        $query->bindParam(":userFrom", $userFrom);
        $query->execute();
    }
    // check if the user is subscribed

    // if subscribed - delete

    // if not subscribed - insert

    // return new number of subscribed

    $query = $connection->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
    $query->bindParam(":userTo", $userTo);
    $query->execute();

    echo $query->rowCount();


} else {
    echo " You should log in to subscribe";
}



?>