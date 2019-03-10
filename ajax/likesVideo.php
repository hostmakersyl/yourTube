<?php 
require_once "../includes/connection.php";
require_once "../includes/classes/Video.php";
require_once "../includes/classes/User.php";

$username = $_SESSION["userLogedIn"];
$videoId = $_POST["videoId"];

$userLogedInObj = new User($connection, $username);

$video = new Video($connection, $videoId, $userLogedInObj);

echo $video->like();

?>