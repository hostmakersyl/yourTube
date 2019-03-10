<?php

    class CommentSection {
        private $connection, $video, $userLoggedInObj;
        public function __construct($connnection, $video, $userLoggedInObj) {
            $this->connection = $connnection;
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }
    
        public function create(){
            return $this->createCommentSection();
        }

        private function createCommentSection (){
            $numComments = $this->video->getNumberOfComments();
            
            $postedBy = $this->userLoggedInObj->getUsername();
            $videoId = $this->video->getId();

            $profileButton = ButtonProvider::createUserProfileButton($this->connection, $postedBy);
            $commentAction = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";
            $commentButton = ButtonProvider::createButton("COMMENT",null,$commentAction,"postComment");


            //Get Comments HTML
            $comments = $this->video->getComments();
            $commentItems = "";
            foreach ($comments as $comment){
                $commentItems .= $comment->create();
            }

            return "
                <div class='commentSection'>
                    <div class='header'>
                        <span class='commentCount'>$numComments Comments</span>
                        <div class='commentForm'>
                            $profileButton
                            <textarea class='commentBody' placeholder='Add your comment'></textarea>
                            $commentButton
                        </div>
                    </div>
                    <div class='comments'>
                        $commentItems
                    </div>
                </div>
            
            ";
        }


    }


?>