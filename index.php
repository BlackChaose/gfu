<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:25
 */
require_once('vendor/autoload.php');

use GUIHelper\UploadFile;

$a = new UploadFile('./upload');
echo "<pre>";
print_r($_POST);
print_r($_FILES);
echo "</pre>"
//$a->handlerQuery();
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
    <input id="inputFile" type="file" name="upfile" required />
    <input id="submit" type="submit" value="Upload!" />
</form>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let sbmt = document.getElementById('submit1');
    let form = document.getElementById('loadFile')
    sbmt.addEventListener('click',function(e){
      e.preventDefault();
      var formData = new FormData(form);
      formData.append("uploadFile", form.submit);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/index.php');
      xhr.send(formData);
    });
  });
</script>
</body>
</html>