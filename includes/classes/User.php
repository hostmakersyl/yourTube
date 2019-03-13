<?php


    class User
    {

        private $connection, $sqlData;

        public function __construct($connection, $username){
            $this->connection = $connection;

            $query = $this->connection->prepare("SELECT * FROM users WHERE username=:un");
            $query->bindParam(":un", $username);
            $query->execute();
            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public static function isLoggedIn() {
            return isset($_SESSION["userLogedIn"]);
        }


        public function getUsername(){
            return $this->sqlData["username"];
        }
        public function getUserFullName(){
            return $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
        }

        public function getUserFirstName(){
            return $this->sqlData["firstName"];
        }

        public function getUserLastName(){
            return $this->sqlData["lastName"];
        }
        public function getUserEmail(){
            return $this->sqlData["email"];
        }

        public function getUserProfilePic(){
            return $this->sqlData["profilePic"];
        }

        public function getUserRegisterDate(){
            return $this->sqlData["signUpDate"];
        }

        public function isSubscribedTo($userTo){
            $username = $this->getUsername();
            $query = $this->connection->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
            $query->bindParam(":userTo", $userTo);
            $query->bindParam(":userFrom", $username);

            
            $query->execute();

            return $query->rowCount() > 0;

        }

        
        public function getSubscriberCount(){
            $username = $this->getUsername();

            $query = $this->connection->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
            $query->bindParam(":userTo", $username);
            
         
            $query->execute();

            return $query->rowCount();

        }

        public function getSubscriptions() {
            $query = $this->connection->prepare("SELECT userTo FROM subscribers WHERE userFrom=:userFrom");
            $username = $this->getUsername();
            $query->bindParam(":userFrom", $username);
            $query->execute();

            $subs = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $user = new User($this->connection, $row['userTo']);
                array_push($subs, $user);
            }

            return $subs;
        }


    }