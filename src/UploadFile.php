<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:38
 */

namespace GUIHelper;

class UploadFile
{
    private $path_to;
    private $path_out;
    private $target_file;
    private $uploadOk;
    private $fileType;


    public function sanitize($path_to, $path_out)
    {

        return [$path_to, $path_out];
    }

    public function __construct($path_to, $path_out = './out')
    {
        $res = $this->sanitize($path_to, $path_out);
        $this->path_to = $res[0];
        $this->path_out = $res[1];
        $this->target_file = $this->path_to.basename($_FILES["fileToUpload"]["name"]);
        $this->uploadOk = 1;
        $this->fileType = strtolower(pathinfo($this->target_file,PATHINFO_EXTENSION));

    }

    public function handlerQuery()
    {

    }

    public function validateFile()
    {

        if (isset($_POST["submit"])) {

// Check if file already exists
            if (file_exists($this->target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
// Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
// Allow certain file formats
            if ($this->target_file != "odt") {
                echo "Sorry, only odt files are allowed.";
                $uploadOk = 0;
            }
// Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $this->target_file)) {
                    echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
}