<?php


    class VideoProcessor
    {

        private $connection;
        private $validSizeLimit = 500000000;
        private $alowedType = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");
        private $ffmpegPath;
        private $ffprobeThunmbnailPath;

        public function __construct($connection)
        {
            $this->connection = $connection;
            $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
            $this->ffprobeThunmbnailPath = realpath("ffmpeg/bin/ffprobe.exe");
        }

        public function upload($videoUploadData)
        {
            $targetDir = "uploads/videos/";
            $videoData = $videoUploadData->videoArray;

            $tmpFilePath = $targetDir . uniqid() . basename($videoData["name"]);
            $tmpFilePath = str_replace(" ", "_", $tmpFilePath);
            $isValidData = $this->processData($videoData, $tmpFilePath);

            if (!$isValidData) {
                return false;
            }

            if (move_uploaded_file($videoData["tmp_name"], $tmpFilePath)) {
                $finalFilePath = $targetDir . uniqid() . ".mp4";

                if ($this->insertVideoData($videoUploadData, $finalFilePath)) {
                    echo "Something is wrong to insert your Data into Database";

                }
                if (!$this->convertVideoToMp4($tmpFilePath, $finalFilePath)) {
                    echo "Upload Failed";
                    return false;
                }
                if ($this->deleteFile($tmpFilePath)) {
                    echo "File has been deleted.";
                    return false;
                }

                if (!$this->genarateThumbnail($finalFilePath)) {
                    echo "Upload Failed. Could not generate thumbnails. \n..";
                    return false;
                }
                return true;
            }
        }

        private function processData($videoData, $filePath)
        {
            $videoType = pathinfo($filePath, PATHINFO_EXTENSION);

            if (!$this->isValidSize($videoData)) {
                echo " Maximum Video Upload Size is 500 MB";
                return false;
            } elseif (!$this->isValidType($videoType)) {
                echo "Invalid Video Type";
                return false;
            } elseif ($this->hasError($videoData)) {
                echo "Video File Upload Error " . $videoData['error'];
                return false;
            }

            return true;
        }

        public function isValidSize($videoData)
        {
            return $videoData["size"] <= $this->validSizeLimit;
        }

        public function isValidType($type)
        {
            $lowerCase = strtolower($type);
            return in_array($lowerCase, $this->alowedType);
        }

        public function hasError($videoData)
        {
            return $videoData['error'] != 0;
        }

        public function insertVideoData($uploadData, $filePath)
        {

            $query = $this->connection->prepare("INSERT INTO `videos`(`uploadedBy`, `title`, `description`, `privacy`,`filePath`, `category`)
                    VALUES (:uploadedBy, :title, :description, :privacy,:filePath, :category)");

            $query->bindParam(":uploadedBy", $uploadData->uploadedBy);
            $query->bindParam(":title", $uploadData->titleInput);
            $query->bindParam(":description", $uploadData->descriptionInput);
            $query->bindParam(":privacy", $uploadData->privacyInput);
            $query->bindParam(":filePath", $filePath);
            $query->bindParam(":category", $uploadData->categoryInput);


            $query->execute();

        }

        public function convertVideoToMp4($tmpFilePath, $finalFilePath)
        {
            $cmd = "$this->ffmpegPath -i $tmpFilePath $finalFilePath 2>&1";

            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);

            if ($returnCode != 0) {
                // Command Failed
                foreach ($outputLog as $line)
                    {
                        echo $line . "<br>";
                    }
                    return false;



            }
            return true;
        }

        public function deleteFile($tmpFilePath)
        {
            if (!unlink($tmpFilePath)) {
                echo "Not delete file";
                return false;
            };
        }

        public function genarateThumbnail($filePath)
        {
            $thumbnailSize = "210x118";
            $numThumbnails = 3;
            $pathToThumbnails = "uploads/videos/thumbnails";

            $duration = $this->getVideoDuration($filePath);
            $videoId = $this->connection->lastInsertId();
            $this->updateDuration($duration, $videoId);

            for ($num = 1; $num <= $numThumbnails; $num++) {
                $imageName = uniqid() . ".jpg";
                $interval = ($duration * 0.8) / $numThumbnails * $num;
                $fullThumbnailsPath = "$pathToThumbnails/$videoId-$imageName";

                  $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailsPath 2>&1";
                //$cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailsPath 2>&1";

            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);

            if ($returnCode != 0) {
                // Command Failed
                foreach ($outputLog as $line)
                    {
                        echo $line . "<br>";
                    }
              }

            $query = $this->connection->prepare("INSERT INTO `thumbnail` (`videoId`, `filePath`, `selected`) 
                                                VALUES (:videoId, :filePath, :selected)");
            $query->bindParam(":videoId", $videoId);
            $query->bindParam(":filePath", $fullThumbnailsPath);
            $query->bindParam(":selected", $selected);

            $selected = $num == 1 ? 1 : 0;
            $success = $query->execute();
            if(!$success){
                echo "Error inserting thumbnail\n";
                return false;
            }

            }
            return true;
        }

        private function getVideoDuration($filePath)
        {
            return (int)shell_exec("$this->ffprobeThunmbnailPath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
        }

        private function updateDuration($duration, $videoId)
        {

            $hours = floor($duration / 3600);
            $mins = floor(($duration - ($hours * 3600)) / 60);
            $secs = floor($duration % 60);

            $hours = ($hours < 1) ? "" : $hours . ":";
            $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
            $secs = ($secs < 10) ? "0" . $secs : $secs . ":";

            $duration = $hours . $mins . $secs;

            $query = $this->connection->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId ");

            $query->bindParam(":duration", $duration);
            $query->bindParam(":videoId", $videoId);

            $query->execute();


        }


    }