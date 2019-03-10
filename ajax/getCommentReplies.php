<?php 
require_once "../includes/connection.php";
require_once "../includes/classes/User.php";
require_once "../includes/classes/Comment.php";

$username = $_SESSION["userLogedIn"];
$videoId = $_POST["videoId"];
$commentId = $_POST["commentId"];

$userLogedInObj = new User($connection, $username);


$comment = new Comment($connection,$userLogedInObj, $commentId, $videoId);

echo $comment->getReplies();

//echo ;

?>