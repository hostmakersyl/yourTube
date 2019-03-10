<?php
    require_once "includes/header.php";
    require_once "includes/classes/VideoPlayer.php";
    require_once "includes/classes/VideoInfoSection.php";
    require_once "includes/classes/Comment.php";
    require_once "includes/classes/CommentSection.php";

    //require_once "ajax/postComment.php";

    
    ?>

    <?php

    if(!isset($_GET['id'])){
        echo "No video has been selected";
        exit();
    }
    $videos = new Video($connection, $_GET['id'], $userLogedInObj);

    $videos->increamentViews();

    ?>
<script src = "assets/js/commentAction.js" type = "text/javascript"></script>
<script src = "assets/js/videoPlayerAction.js" type = "text/javascript"></script>

    <div class="watchLeftColumn">
        <?php
            $videoPlayer = new VideoPlayer($videos);
            echo $videoPlayer->create(true);


            $videoSection = new VideoInfoSection($connection, $videos, $userLogedInObj);
            echo $videoSection->create();
            ?>
            
<?php
            $commentSection = new CommentSection($connection, $videos, $userLogedInObj);
            echo $commentSection->create();
        ?>
        <!-- <div class="comments"></div> -->
    </div>

    <div class="suggestions">
        <?php 
            $videoGrid = new VideoGrid($connection, $userLogedInObj);
            echo $videoGrid->create(null, null, false);

        ?>
    </div>



<?php require_once "includes/fotter.php" ;?>