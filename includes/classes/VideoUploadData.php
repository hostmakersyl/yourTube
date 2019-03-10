<?php
class VideoUploadData {

    public $videoArray, $titleInput, $descriptionInput, $privacyInput, $categoryInput, $uploadedBy;
//$uploadButton
    public function __construct($videoArray, $titleInput, $descriptionInput, $privacyInput, $categoryInput,$uploadedBy) {
        $this->videoArray = $videoArray;
        $this->titleInput = $titleInput;
        $this->descriptionInput = $descriptionInput;
        $this->privacyInput = $privacyInput;
        $this->categoryInput = $categoryInput;
        $this->uploadedBy  = $uploadedBy;
    }

//    public function getTitle(){ //We can call the title and other data by this way. if we keep variable private.
//        return $this->titleInput;
//    }
}

?>