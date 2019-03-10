<?php 
class VideoGridItems {

    private $largeMode, $video;

    public function __construct($video, $largeMode)
    {
        $this->video = $video;
        $this->largeMode = $largeMode;
    }

    public function create(){
        $thumbnail = $this->createThumbnails();
        $details = $this->createDetails();

        $url = "watch.php?id=". $this->video->getId();

        return "<a href='$url'>
                    <div class='videoGridItem'>
                        $thumbnail
                        $details
                    </div>
                </a>";
    }

    private function createThumbnails(){
        $thumbnail = $this->video->getThumbnails();
        $duration = $this->video->getDuration();

        return "<div class='thumbnail'>
                    <img src='$thumbnail'>
                    <div class='duration'>
                        <span> $duration </span>
                    </div>
                </div>";
    }

    private function createDetails(){
        $title = $this->video->getTitle();
        $username = $this->video->getUploadedBy();
        $view = $this->video->getViews();
        $description = $this->createDescription();
        $timeStamp = $this->video->getTimeStamp();

        return "<div class='details'>
                        <h3 class='title'>$title</h3>
                        <span class='username'>$username</span>
                        <div class='stats'>
                            <span class='viewCount'>$view views</span>
                            <span class='timeStamp'>$timeStamp</span>
                        </div>
                        $description
                </div>";

    }

    private function createDescription(){
        if(!$this->largeMode) {
            return "";
        } else {
            $description = $this->video->getDescription();
            $description = (strlen($description) > 350 ) ? substr($description, 0 , 347) . "..." : $description;
            return "<span class='description'> $description</span>";

        }
    }



}

?>