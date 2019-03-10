<?php require_once "includes/header.php";
    require_once "includes/classes/VideoUploadData.php";
    require_once "includes/classes/VideoProcessor.php";
?>


<?php
    if (!isset($_POST['uploadButton'])) {
        echo "Nothing found in the page..";
        exit();
    }

    // 1. Create file upload Data
    $videoArray = $_FILES['fileInput'];
    $titleInput = $_POST['titleInput'];
    $descriptionInput = $_POST['descriptionInput'];
    $privacyInput = $_POST['privacyInput'];
    $categoryInput = $_POST['categoryInput'];
    $uploadedBy = $userLogedInObj->getUsername();
    $videoUploadData = new VideoUploadData($videoArray, $titleInput, $descriptionInput, $privacyInput, $categoryInput, $uploadedBy);
    //echo $videoUploadData->titleInput;

    //2. Process Video Data Upload
    $videoProcessor = new VideoProcessor($connection);
    $wasSuccessful = $videoProcessor->upload($videoUploadData);


    //3. Check if upload was successful

    if($wasSuccessful){
        echo "Upload Successful";
    }


?>