<?php

    class VideoDetailsFormProvider
    {
        private $connection;

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function createUploadForm()
        {
            $fileInput = $this->createFileInpute();
            $titleInput = $this->createTitleInpute();
            $descriptionInput = $this->createDescriptionInpute();
            $privacyInput = $this->createPrivacyInput();
            $categoryInput = $this->createCategorysInput();
            $uploadButton = $this->createUploadButton();
            return "
            <form action=\"processing.php\" method=\"POST\" enctype=\"multipart/form-data\">
                {$fileInput}
                {$titleInput}
                {$descriptionInput}
                {$privacyInput}
                {$categoryInput}
                {$uploadButton}
            </form>
         ";
        }

        private function createFileInpute()
        {
            return "  <div class=\"form-group\">
                        <label for=\"exampleFormControlFile1\">Your File</label>
                        <input type=\"file\" class=\"form-control-file\" id=\"exampleFormControlFile1\" name='fileInput' required>
                      </div>";
        }

        private function createTitleInpute()
        {
            return "<div class=\"form-group\">
                    <input class=\"form-control\" type=\"text\" placeholder=\"Title\" name='titleInput'>
                 </div>";
        }


        private function createDescriptionInpute()
        {
            return "<div class=\"form-group\">
                        <textarea class=\"form-control\"  placeholder=\"Description\" name='descriptionInput' rows='3'>               
                        </textarea>
                     </div>";
        }

        private function createPrivacyInput()
        {
            return "
                <div class=\"form-group\">
                    <select class=\"form-control\" id=\"exampleFormControlSelect1\" name='privacyInput'>
                      <option value='0'>Public</option>
                      <option value='1'>Private</option>              
                    </select>
                  </div>";
        }

        private function createCategorysInput()
        {
            $query = $this->connection->prepare("SELECT * FROM categorys");
            $query->execute();
            $html = "<div class=\"form-group\"> 
                    <select class=\"form-control\" id=\"exampleFormControlSelect1\" name='categoryInput'>";

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $name = $row['name'];
                $id = $row['id'];
                $html .= "<option value='{$id}'> {$name}</option>";
            }

            $html .= "</select>
                  </div>";
            return $html;
        }


        public function createUploadButton()
        {
            return "<button type=\"submit\" name='uploadButton' class=\"btn btn-primary\">Upload</button>";
        }


    }

?>