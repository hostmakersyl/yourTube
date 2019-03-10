<?php 
require_once "ButtonProvider.php";
    class VideoInfoControls{

        private  $video, $userLoggedInObj;

        public function __construct($video, $userLoggedInObj) {
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }
    
        public function create(){
            $likeButton =$this->createLikeButton();
            $disLikeButton = $this->createDislikeButton();
            return "
           
            <div class='controls'>
                    $likeButton
                    $disLikeButton
             </div>
            
            ";
        }

        private function createLikeButton () {
            $text = $this->video->getLikes();
            $videoId = $this->video->getId();
            $action = "likeVideo(this, $videoId)";
            $class = "likeButton";
            $imageSrc = "assets/images/icons/thumb-up.png";

            //Change button img if video has been liked.
            if($this->video->wasLikedBy()) {
                $imageSrc = "assets/images/icons/thumb-up-active.png";
            }


            return ButtonProvider::createButton($text,$imageSrc,$action,$class);
        }

        private function createDislikeButton() {
            $text = $this->video->getDisLikes();
            $videoId = $this->video->getId();
            $action = "disLikeVideo(this, $videoId)";
            $class = "disLikeButton";
            $imageSrc = "assets/images/icons/thumb-down.png";

            //Change button img if video has been liked.
            if($this->video->wasDisLikedBy()) {
            $imageSrc = "assets/images/icons/thumb-down-active.png";
        }


            return ButtonProvider::createButton($text,$imageSrc,$action,$class);
        }

   
    }



?>