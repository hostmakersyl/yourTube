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
            $elementsHTML = "";

            foreach ($videos as $video){
                $item = new VideoGridItems($video, $this->largeMode);
                $elementsHTML .= $item->create();
            }

            return $elementsHTML;
        }

        public function createGridHeader($title, $showFilter){
            $filter = "";

            if($showFilter) {
                $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              // echo $link;
              $urlArray = parse_url($link);
              //var_dump($urlArray);
              $query = $urlArray["query"];

              parse_str($query, $params);
              //var_dump($params);
              unset($params["orderBy"]);
              //var_dump($params);
              $newQuery = http_build_query($params);
              $newUrl = basename($_SERVER["PHP_SELF"]) . "?". $newQuery;
              //echo $newUrl;
              $filter = "<div class='videoGridHeader'>
                            <span> Order by:</span>
                            <a href='$newUrl&orderBy=uploadDate'>Upload Date </a>
                            <a href='$newUrl&orderBy=views'>Most Views </a>
                        </div>";
                
            }

            return "<div class='videoGridHeader'>
                            <div class='left'>
                                $title
                            </div>
                                $filter
                        </div>";
        }


        public function createLarge($video, $title, $showFilter){
            $this->gridClass .= "large";
            $this->largeMode = true;
            return $this->create($video, $title, $showFilter);
        }
    }

?>