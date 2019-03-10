<?php 

    class VideoGrid {

        private $connection, $userLogedInObj;
        private $largeMode = false;
        private $gridClass = "videoGrid";
        public function __construct($connection, $userLogedInObj){
            $this->connection = $connection;
            $this->userLogedInObj = $userLogedInObj;
        }

        public function create($videos, $title, $showFilter){
            if($videos == null){
                $gridItems = $this->generateItems();
            } else {
                $gridItems = $this->generateItemsFromVideos($videos);
            }

            $header = "";

            if($title != null){
                $header = $this->createGridHeader($title, $showFilter);
            } else {

            }
            return " $header
                    <div class='$this->gridClass'>
                        $gridItems
                    </div>";
            
        }

        public function generateItems(){
            $query = $this->connection->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
            $query->execute();

            $elementsHTML = "";
            while ($row = $query->fetch(PDO::FETCH_ASSOC)){
                $video = new Video($this->connection, $row, $this->userLogedInObj);

                $item = new VideoGridItems($video, $this->largeMode);
                $elementsHTML .= $item->create();
            }

            return $elementsHTML;
        }

        public function generateItemsFromVideos($videos){

        }

        public function createGridHeader($title, $showFilter){
            return "";
        }
    }

?>