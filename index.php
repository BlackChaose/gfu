<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:25
 */
require_once('vendor/autoload.php');

use GUIHelper\UploadFile;

session_start();

if (isset($_SESSION['message']) && $_SESSION['message'])
{
    printf('<b>%s</b>', $_SESSION['message']);
    unset($_SESSION['message']);
}

$fUp = new UploadFile('./upload');
if (!empty($_POST)) {
    header('Content-Type: application/json');
    echo json_encode(["post: " => $_POST, "files: " => $_FILES], true);
    $fUp->validateFile();
    die;
}

?>

<!doctype html>
<html>
<head>
    <title>test</title>
    <meta charset="UTF-8"/>
</head>
<body>
<!--    <form name="loadFile" action="" method="post" enctype="multipart/form-data">-->
<form id="loadFile" name="loadFile" action="" method="post" enctype="multipart/form-data">
    <label>File to upload:</label>
    <input id="inputFile" type="file" name="upfile" required/>
    <input id="submit" type="submit" value="Upload!"/>
</form>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let sbmt = document.getElementById('submit');
    let form = document.getElementById('loadFile');
    sbmt.addEventListener('click', function (e) {
      e.preventDefault();
      var formData = new FormData(form);
      formData.append('uploadFile', form.submit);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/index.php');
      xhr.send(formData);
    });
  });
</script>
</body>
</html>