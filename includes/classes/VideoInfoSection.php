<?php
require_once "VideoInfoControls.php";


 class VideoInfoSEction{

    private $connection, $video, $userLoggedInObj;
    public function __construct($connnection, $video, $userLoggedInObj) {
        $this->connection = $connnection;
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create(){
        return $this->createPrimaryInfo() . $this->createSecondaryInfo();
    }

    private function createPrimaryInfo(){
        $title = $this->video->getTitle();
        $views = $this->video->getViews();

        $videoInfoControls = new VideoInfoControls($this->video, $this->userLoggedInObj);
        $controls = $videoInfoControls->create();

        return "
         <div class='videoInfo'>
            <h1> $title </h1>
            <div class='bottomSection'>
                <span class='vidwCount'> $views Views</span>
                $controls

            </div>

         </div>
                ";
    }
    
    private function createSecondaryInfo(){

        $description = $this->video->getDescription();
        $uploadedDate = $this->video->getUploadDate();
        $uploadedBy = $this->video->getUploadedBy();
        $profileButton = ButtonProvider::createUserProfileButton($this->connection, $uploadedBy);

        if($uploadedBy == $this->userLoggedInObj->getUsername()){
                $actionButton = ButtonProvider::createEditVideoButton($this->video->getId());

        } else {
            $userToObject = new User($this->connection, $uploadedBy);
            $actionButton = ButtonProvider::createSubscriberButton($this->connection, $userToObject, $this->userLoggedInObj);
        }

        return "<div class='secodaryInfo'>
                        <div class='topRow'> 
                                    $profileButton
                                    <div class='uploadInfo'>
                                                <span class='owner'><a href='profile.php?username=$uploadedBy'>$uploadedBy</a></span>
                                                <span class='date'>Published on $uploadedDate </span>
                                    </div>
                      
                            <div>
                                $actionButton
                            </div>
                         </div>
                            <div class='descriptionContainer'>
                                $description
                            </div>
                       
                </div>";
        
    }
 }



?>