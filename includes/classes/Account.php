<?php


    class Account
    {

        private $connection;

        private $errorArray = array();

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function register($fn, $ln, $un, $em, $em2, $pas, $pas2)
        {
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateUsername($un);
            $this->validateEmail($em, $em2);
            $this->validatePassword($pas, $pas2);

            if (empty($this->errorArray)) {
              return $this->insertUserData($fn, $ln, $un, $em, $pas);
               }
             else {
                return false;
            }
        }

        public function insertUserData($fn, $ln, $un, $em, $pas){
            $pas = hash("sha512", $pas);
            $profilePic = "assets/images/profilePictures/default.png";

            $query = $this->connection->prepare("INSERT into users (firstName, lastName, username, email, password, profilePic)
                                                VALUES (:fn, :ln, :un, :em, :pas, :profilePic)");
            $query->bindParam(":fn", $fn);
            $query->bindParam(":ln", $ln);
            $query->bindParam(":un", $un);
            $query->bindParam(":em", $em);
            $query->bindParam(":pas", $pas);
            $query->bindParam(":profilePic", $profilePic);

            return $query->execute();

        }
        private function validateFirstName($fn)
        {
            if (strlen($fn) < 3 || strlen($fn) > 25) {
                array_push($this->errorArray, Constants::$firstNameCharecter);
            }
        }

        private function validateLastName($ln)
        {
            if (strlen($ln) > 25 || strlen($ln) < 3) {
                array_push($this->errorArray, Constants::$lastNameCharecter);
            }
        }

        private function validateUsername($un){
            if (strlen($un)> 25 || strlen($un) < 3) {
                array_push($this->errorArray, Constants::$usernameCharecter);
            }

            $query = $this->connection->prepare("SELECT * FROM users WHERE username=:un");
            $query->bindParam(":un", $un);
            $query->execute();

            if($query->rowCount() != 0){
                array_push($this->errorArray, Constants::$usernameExists);
            }

        }


        private function validateEmail($em, $em2){
            if ($em != $em2) {
                array_push($this->errorArray, Constants::$emailNotMatch);
            }

            if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
                array_push($this->errorArray, Constants::$emailValid);
            }

            $query = $this->connection->prepare("SELECT * FROM users WHERE email=:em");
            $query->bindParam(":em", $em);
            $query->execute();

            if($query->rowCount() != 0){
                array_push($this->errorArray, Constants::$emailExist);
            }

        }

        private function validatePassword($pas, $pas2){
            if ($pas != $pas2) {
                array_push($this->errorArray, Constants::$passwordNotMatch);
            }

            if(preg_match("/[^A-Za-z0-9]/", $pas)){
                array_push($this->errorArray, Constants::$passwordLetter);
            }

            if(strlen($pas) <6 ){
                array_push($this->errorArray, Constants::$passwordLength);
            }

        }

        public function getErrorMessage($error){
            if(in_array($error, $this->errorArray)){
                return "<span class='errorMessage'> $error </span>";
            }
        }

        public function logInUser($username, $password){
            $password = hash("sha512", $password);

            $query = $this->connection->prepare("SELECT * FROM `users` WHERE `username`=:username AND `password`=:password");
            $query->bindParam(":username", $username);
            $query->bindParam(":password", $password);

            $query->execute();

            if($query->rowCount() == 1){
                return true;
            }
            else {
                array_push($this->errorArray, Constants::$usernameIncorrect);
                return false;
            }
        }

    }