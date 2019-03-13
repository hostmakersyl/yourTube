<?php 

require_once "includes/header.php";
require_once "includes/classes/SearchResultProvider.php";

if(!isset($_GET['term']) || $_GET['term'] == ""){
    echo "You must be specified your search";
    exit();
}

$term = $_GET['term'];

if(!isset($_GET['orderBy']) || $_GET['orderBy'] == "views") {
    $orderBy = "views";
} else {
    $orderBy = "uploadDate";
}

$searchResultProvider = new SearchResultProvider($connection, $userLogedInObj);

$videos = $searchResultProvider->getVideos($term, $orderBy);

$videoGrid = new VideoGrid($connection, $userLogedInObj);

?>

<div class="largeVideoGridContainer"> 
    <?php 
        if(sizeof($videos) > 0) {
            echo $videoGrid->createLarge($videos, sizeof($videos) . " result found", true );
        } else {
            echo "No result match with you search";
        }
    ?>

</div>








<?php 
require_once "includes/fotter.php";
?>