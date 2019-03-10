<?php

require_once "ButtonProvider.php";
require_once "CommentControls.php";
require_once "User.php";
class Comment {

    private $connection, $userLogedInObj, $sqlData, $videoId;

    public function __construct($connection, $userLogedInObj, $input, $videoId) {
  

        if(!is_array($input)){
            $query = $connection->prepare("SELECT * FROM comments WHERE id=:id");
            $query->bindParam(":id", $input);
            $query->execute();

            $input = $query->fetch(PDO::FETCH_ASSOC);
        }

        $this->sqlData = $input;
        $this->connection = $connection;
        $this->userLogedInObj = $userLogedInObj;
        $this->videoId = $videoId;
        
    }

    public function create(){
        $id = $this->sqlData["id"];
        $videoId = $this->getVideoId();
        $body = $this->sqlData['body'];
        $postedBy = $this->sqlData['postedBy'];
        $profileButton = ButtonProvider::createUserProfileButton($this->connection, $postedBy);
        $timeSpan = $this->time_elapsed_string($this->sqlData['datePosted']);

        $commentControlsObj = new CommentControls($this->connection, $this,  $this->userLogedInObj);
        $commentControls = $commentControlsObj->create();

       $numResponse = $this->getNumberOfReplies();
    //    $viewRepliesText = "" ;

       if($numResponse > 0 ) {
           $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies($id, this, $videoId )'>
              View all $numResponse replies
           </span>";
       } else {
           $viewRepliesText = "<div class='repliesSection'></div>";
       }

        return "<div class='itemContainer'> 
                    <div class='comment'>
                        $profileButton
                        <div class='mainContainer'>
                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timeSpan'>$timeSpan</span>
                            </div>
                                <div class='body'>
                                    $body
                                </div>
                        </div>
                    </div>
                    $commentControls
                    $viewRepliesText
                </div> ";

    }


    public function getNumberOfReplies() {
        $id = $this->sqlData["id"];
        $query = $this->connection->prepare("SELECT Count(*) as 'count' FROM comments WHERE responseTo=:responseTo");
        $query->bindParam(":responseTo", $id);
        $query->execute();

        return $query->fetchColumn();
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    

    public function getId(){
        return $this->sqlData['id'];
    }
    public function getVideoId(){
        return $this->videoId;
    }
    public function getLikes(){
        $commentId = $this->getId();
        $query = $this->connection->prepare("SELECT count(*) as 'count' FROM likes WHERE commentId=:commentId");
        $query->bindParam(":commentId", $commentId);
        
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numLikes = $data["count"];


        $query = $this->connection->prepare("SELECT count(*) as 'count' FROM dislikes WHERE commentId=:commentId");
        $query->bindParam(":commentId", $commentId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numDisLikes = $data["count"];

        return $numLikes - $numDisLikes;

    }


    public function wasLikedBy() {
        $username = $this->userLogedInObj->getUsername();
        $id = $this->getId();            
        $query = $this->connection->prepare("SELECT * FROM likes WHERE username = :username AND commentId=:commentId");
        $query->bindParam(":username", $username);
        $query->bindParam(":commentId", $id);

        
        $query->execute();

        return $query->rowCount();
    }


    public function wasDisLikedBy() {
        $username = $this->userLogedInObj->getUsername();
        $id = $this->getId();            
        $query = $this->connection->prepare("SELECT * FROM Dislikes WHERE username = :username AND commentId=:commentId");
        $query->bindParam(":username", $username);
        $query->bindParam(":commentId", $id);

       
        $query->execute();

        return $query->rowCount();
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
       $query = $this->connection->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
       $query->bindParam(":username", $username);
       $query->bindParam(":commentId", $id);

       $query->execute();    

       return -1;
       
      } else {
       $query = $this->connection->prepare("DELETE FROM disLikes WHERE username=:username AND commentId=:commentId");
       $query->bindParam(":username", $username);
       $query->bindParam(":commentId", $id);
       $query->execute();  
       $count = $query->rowCount()  ;
       $query = $this->connection->prepare("INSERT INTO likes (username, commentId) VALUES (:username, :commentId)");
       $query->bindParam(":username", $username);
       $query->bindParam(":commentId", $id);

       $query->execute();
       

       return 1 + $count;

      }
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
   $query = $this->connection->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
   $query->bindParam(":username", $username);
   $query->bindParam(":commentId", $id);

   $query->execute();    

   return 1;
   
  } else {
   $query = $this->connection->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
   $query->bindParam(":username", $username);
   $query->bindParam(":commentId", $id);
   $query->execute();  
   $count = $query->rowCount()  ;
   $query = $this->connection->prepare("INSERT INTO dislikes (username, commentId) VALUES (:username, :commentId)");
   $query->bindParam(":username", $username);
   $query->bindParam(":commentId", $id);

   $query->execute();
   

   return -1 - $count;

  }
}

public function getReplies(){
    $id = $this->getId();
    $query = $this->connection->prepare("SELECT * FROM comments WHERE responseTo=:commentId ORDER BY datePosted ASC");
    $query->bindParam(":commentId", $id);
    
    $query->execute();

    $comments = "";

    $videoId = $this->getVideoId();

    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $comment = new Comment($this->connection, $this->userLogedInObj, $row,  $id) ;
        $comments .= $comment->create();
        
    }
    return $comments;
}
  


}


?>