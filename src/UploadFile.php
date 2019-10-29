<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:38
 */

namespace GUIHelper;

use GUIHelper\Logger;
use Exception;

class UploadFile
{
    private $path_to;
    private $target_file;
    private $fileName;
    private $uploadOk;
    private $fileType;
    private $resultMessage;
    private $nameField; //name field filt to upload
    private $nameSubmit; //name submit button
    private $overwrite;

    public $log;

    public function sanitize($path_to)
    {

        return [$path_to];
    }

    public function __construct($nameField, $nameSubmit, $path_to, $overwrite = false)
    {
        $this->nameField = $nameField;
        $this->nameSubmit = $nameSubmit;
        $res = $this->sanitize($path_to);
        $this->path_to = $res[0];
        $this->fileName = basename($_FILES[$this->nameField]["name"]);
        $this->target_file = $this->path_to . DIRECTORY_SEPARATOR . basename($_FILES[$this->nameField]["name"]);
        $this->uploadOk = 1;
        $this->fileType = strtolower(pathinfo($this->target_file, PATHINFO_EXTENSION));
        $this->log = new Logger(__DIR__ . '/../logs/logger0x.log');
        $this->overwrite = $overwrite;
        session_start();
    }

    public function handlerQuery()
    {

    }

    public function writeLog($str)
    {
        $text = "message: ";
        $text .= $str;
        $text .= "(( path to: ";
        $text .= $this->path_to;
        $text .= " target file: ";
        $text .= $this->target_file;
        $text .= " type of file ";
        $text .= $this->fileType;
        $text .= " result of message: ";
        $text .= $this->resultMessage;
        $text .= " ))";

        $this->log->log($text);
    }

    public function validateFile()
    {
        try {
            if (isset($_POST["submit"])) {

                if (file_exists($this->target_file)) {
                    if (!$this->overwrite) {
                        $this->uploadOk = 0;
                        throw new Exception("Sorry, file already exists.");
                    } else {
                        chmod($this->target_file, 0755); //Change the file permissions if allowed
                        unlink($this->target_file); //remove the file
                        $this->writeLog('File ' . $this->target_file . " has been deleted for overwrite!");
                    }
                }

                if ($_FILES[$this->nameField]["size"] > 500000) {
                    throw new Exception("Sorry, your file is too large.");
                    $this->uploadOk = 0;
                }

                if ($this->fileType != "odt") {
                    throw new Exception("Sorry, only odt files are allowed.");
                    $this->uploadOk = 0;
                }

                /** attention  -----------------------------------------*/
                /** todo: slice & chunk files & index & hash - for show upload files in realtime! */
                /** todo: add test & ci */
                if (!isset($_SERVER['HTTP_X_FILE_NAME'])) {
                    $this->writeLog('file\'s name required');
                    throw new Exception('file\'s name required');
                }

                if ($_SERVER['HTTP_X_FILE_NAME'] !== $this->fileName) {
                    $this->writeLog('file name Error '.$_SERVER['HTTP_X_FILE_NAME']." ".$this->fileName);
                    throw new Exception('file name error');
                }
                //$index = intval($_SERVER['HTTP_X_INDEX']);
                //$total = intval($_SERVER['HTTP_X_TOTAL']);

                if(md5(file_get_contents($_FILES[$this->nameField]["tmp_name"])) !== $_SERVER['HTTP_X_HASH']){
                    $this->writeLog('file\'s hash error');
                    throw new Exception('Hash error');
                }
                /** attention  -----------------------------------------*/

                if ($this->uploadOk == 0) {
                    throw new Exception("Sorry, your file was not uploaded.");

                } else {
                    if (move_uploaded_file($_FILES[$this->nameField]["tmp_name"], $this->target_file)) {
                        $this->resultMessage = "The file " . basename($_FILES[$this->nameField]["name"]) . " has been uploaded.";
                        $this->writeLog($this->resultMessage);
                    } else {
                        throw new Exception("Sorry, there was an error uploading your file.");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->writeLog($e->getMessage());
            $this->resultMessage = 'Result Message: Error! ' . $e->getMessage();
            return ["status"=>"Error!","result"=>$this->resultMessage];
        }
        return ["status"=>"Ok!","result"=>$this->resultMessage];
    }

    public function sendRequest($data)
    {
        try {
//            header("Access-Control-Allow-Origin: *");
//            header("Access-Control-Allow-Methods: POST");
//            header("Access-Control-Max-Age: 1000");
//            header("Access-Control-Allow-Headers: x-requested-with, x-file-name, x-index, x-total, x-hash, Content-Type, origin, authorization, accept, client-security-token");
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            $this->writeLog($e->getMessage());
        }
    }
}