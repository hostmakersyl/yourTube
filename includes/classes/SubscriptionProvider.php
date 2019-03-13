<?php

    class SubscriptionProvider {

        private $connection, $userLogedInObj;

        public function __construct($connection, $userLogedInObj) {
            $this->connection = $connection;
            $this->userLogedInObj = $userLogedInObj;
        }


        public function getVideos(){
            $videos = array();
            $subscription = $this->userLogedInObj->getSubscriptions();

            if(sizeof($subscription) > 0 ) {
                // user1, user2, user3
                // SELECT * FROM videos WHERE uploadeBy=? OR uploadedBy = ? OR uploadedBy=?
                // $query->bindParam(1,"user1");
                // $query->bindParam(2,"user2");
                // $query->bindParam(3,"user3");

                $condition = "";
                $i = 0;

                while($i < sizeof($subscription)){
                    if($i == 0){
                        $condition .= "WHERE uploadedBy=?";
                    } else {
                        $condition .= " OR uploadedBy=?";
                    }
                    $i++;
                }

                $videoSql = "SELECT * FROM videos $condition ORDER BY uploadedBy DESC";
                $videoQuery = $this->connection->prepare($videoSql);

                $i = 1;

                foreach($subscription as $sub ){
                    $subUsername = $sub->getUsername();
                    $videoQuery->bindValue($i, $subUsername); 
                    $i++;
                }

                $videoQuery->execute();
                while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)){
                    $video = new Video($this->connection, $row, $this->userLogedInObj);
                    array_push($videos, $video);
                }


            }



            return $videos;

        }
 
    }



?>