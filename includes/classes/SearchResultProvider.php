<?php 

class SearchResultProvider {
    private $connection, $userLogedInObj;

    public function __construct($connection, $userLogedInObj) {
        $this->connection = $connection;
        $this->userLogedInObj = $userLogedInObj;
    }


    public function getVideos($term, $orderBy) {
        $query = $this->connection->prepare("SELECT * FROM videos WHERE title LIKE CONCAT('%', :term , '%') OR uploadedBy LIKE  CONCAT('%', :term , '%') ORDER BY $orderBy DESC");
        $query->bindParam(":term", $term);
        $query->execute();

        $videos = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->connection, $row, $this->userLogedInObj);
            array_push($videos, $video);
        }

        return $videos;
    }



}


?>

