<?php


    class FormSanitaizer
    {
        public static function sanitizeFormString($inputText){
            $inputText = strip_tags($inputText);
            $inputText = str_replace(" ", "", $inputText);
            //$inputText = trim($inputText); // If I want to keep space between word than i have to use trim function rather than str_replace
            $inputText = strtolower($inputText);
            $inputText = ucfirst($inputText);
            return $inputText;
        }

        public static function sanitizeFormUsername($inputText){
            $inputText = strip_tags($inputText);
            $inputText = str_replace(" ", "", $inputText);

            return $inputText;
        }

        public static function sanitizeFormPassword($inputText){
            $inputText = strip_tags($inputText);

            return $inputText;
        }

        public static function sanitizeFormEmail($inputText){
            $inputText = strip_tags($inputText);
            $inputText = str_replace(" ", "", $inputText);

            return $inputText;
        }


    }