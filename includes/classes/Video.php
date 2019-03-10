<?php


    class Video
    {

        private $connection, $sqlData, $userLogedInObj;

        public function __construct($connection, $input, $userLogedInObj){
            $this->connection = $connection;
            $this->userLogedInObj = $userLogedInObj;

            if(is_array($input)){
                $this->sqlData = $input;
            } else {
                $query = $this->connection->prepare("SELECT * FROM videos WHERE id=:id");
                $query->bindParam(":id", $input);
                $query->execute();
                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }

        public function getId(){
            return $this->sqlData["id"];
        }

        public function getUploadedBy(){
            return $this->sqlData["uploadedBy"];
        }

        public function getTitle(){
            return $this->sqlData["title"];
        }
        public function getDescription(){
            return $this->sqlData["description"];
        }

        public function getPrivacy(){
            return $this->sqlData["privacy"];
        }

        public function getFilePath(){
            return $this->sqlData["filePath"];
        }

        public function getCategory(){
            return $this->sqlData["category"];
        }

        public function getUploadDate(){
            $date = $this->sqlData["uploadDate"];
            return date("M j Y", strtotime($date));
        }

        public function getTimeStamp(){
            $date = $this->sqlData["uploadDate"];
            return date("M jS Y", strtotime($date));
        }

        public function getViews(){
            return $this->sqlData["views"];
        }

        public function getDuration(){
            return $this->sqlData["duration"];
        }

        public function increamentViews(){
            $videoId = $this->getId();
            $query = $this->connection->prepare("UPDATE videos SET views = views + 1 WHERE id=:id");
            $query->bindParam(":id", $videoId);
           
            $query->execute();
            $this->sqlData["views"] = $this->sqlData["views"] +1;
        }


        public function getLikes(){
            $videoId = $this->getId();
            $query = $this->connection->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId = :videoId");
            $query->bindParam(":videoId", $videoId);
            
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data['count'];
        }

        public function getDisLikes() {
            $videoId = $this->getId();
            $query = $this->connection->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId = :videoId");
            $query->bindParam(":videoId", $videoId);
            
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data['count'];
        }

        public function like(){
             $id = $this->getId();
             $username = $this->userLogedInObj->getUsername();
            // $query = $this->connection->prepare("SELECT * FROM likes WHERE username = :username AND videoId=:videoId");
            // $query->bindParam(":username", $username);
            // $query->bindParam(":videoId", $id);
            
            // $query->execute();
// Above code has been replace by the function.... wasLiked..


           if($this->wasLikedBy()) {
            $query = $this->connection->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);

            $query->execute();    

            $result = array(
                "likes" => -1,
                "disLikes" => 0
            );

            return json_encode($result);
            
           } else {
            $query = $this->connection->prepare("DELETE FROM disLikes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();  
            $count = $query->rowCount()  ;
            $query = $this->connection->prepare("INSERT INTO likes (username, videoId) VALUES (:username, :videoId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);

            $query->execute();
            

            $result = array(
                "likes" => 1,
                "disLikes" => 0 - $count
            );

            return json_encode($result);

           }
        }

        public function wasLikedBy() {
            $username = $this->userLogedInObj->getUsername();
            $id = $this->getId();            
            $query = $this->connection->prepare("SELECT * FROM likes WHERE username = :username AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);

           
            $query->execute();

            return $query->rowCount();
        }


        public function disLike(){
            $id = $this->getId();
            $username = $this->userLogedInObj->getUsername();
           // $query = $this->connection->prepare("SELECT * FROM likes WHERE username = :username AND videoId=:videoId");
           // $query->bindParam(":username", $username);
           // $query->bindParam(":videoId", $id);
           
           // $query->execute();
// Above code has been replace by the function.... wasLiked..


          if($this->wasDisLikedBy()) {
           $query = $this->connection->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
           $query->bindParam(":username", $username);
           $query->bindParam(":videoId", $id);

           $query->execute();    

           $result = array(
               "likes" => 0,
               "disLikes" => -1
           );

           return json_encode($result);
           
          } else {
           $query = $this->connection->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
           $query->bindParam(":username", $username);
           $query->bindParam(":videoId", $id);
           $query->execute();  
           $count = $query->rowCount()  ;
           $query = $this->connection->prepare("INSERT INTO dislikes (username, videoId) VALUES (:username, :videoId)");
           $query->bindParam(":username", $username);
           $query->bindParam(":videoId", $id);

           $query->execute();
           

           $result = array(
               "likes" => 0 - $count,
               "disLikes" => 1
           );

           return json_encode($result);

          }
       }

        public function wasDisLikedBy() {
            $username = $this->userLogedInObj->getUsername();
            $id = $this->getId();            
            $query = $this->connection->prepare("SELECT * FROM Dislikes WHERE username = :username AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);

            
            $query->execute();

            return $query->rowCount();
        }

        public function getNumberOfComments(){
            $id = $this->getId();
            $query = $this->connection->prepare("SELECT * FROM comments WHERE videoId=:videoId");
            $query->bindParam(":videoId", $id);
            
            $query->execute();

            return $query->rowCount();
        }

        public function getComments(){
            $id = $this->getId();
            $query = $this->connection->prepare("SELECT * FROM comments WHERE videoId=:videoId AND responseTo=0 ORDER BY datePosted DESC");
            $query->bindParam(":videoId", $id);
            
            $query->execute();

            $comments = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $comment = new Comment($this->connection, $this->userLogedInObj, $row,  $id) ;
                array_push($comments, $comment);
                
            }
            return $comments;
        }


        public function getThumbnails(){
            $videoId = $this->getId();
            $query = $this->connection->prepare("SELECT filePath FROM thumbnail WHERE videoId=:videoId AND selected=1");
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            return $query->fetchColumn();


        }

    }