<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:25
 */
require_once('vendor/autoload.php');

use GUIHelper\UploadFile;

ini_set('display_errors', '1');

session_start();

if (isset($_SESSION['message']) && $_SESSION['message'])
{
    printf('<b>%s</b>', $_SESSION['message']);
    unset($_SESSION['message']);
}


if (!empty($_POST)) {
    $fUp = new UploadFile('upfile', 'submit','./upload', true);
    $fUp->sendRequest( $fUp->validateFile());
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
    <input id="submit" type="submit" name="submit" value="Upload!"/>
</form>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let sbmt = document.getElementById('submit');
    let form = document.getElementById('loadFile');
    sbmt.addEventListener('click', function (e) {
      e.preventDefault();
      var formData = new FormData(form);
      formData.append('upfile', form.submit);
      formData.append('submit',sbmt.value);
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            console.log('!!!');
        }
      };
      xhr.open('POST', '/index.php');
      xhr.setRequestHeader("X-File-Name", form.submit.name);
      xhr.setRequestHeader("X-File-Index", 0);
      xhr.send(formData);
    });
  });
</script>
</body>
</html>