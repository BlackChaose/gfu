<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 25.10.19
 * Time: 11:25
 */
require_once('vendor/autoload.php');

use GUIHelper\UploadFile;

ini_set('display_errors', '1');


if (isset($_SESSION['message']) && $_SESSION['message'])
{
    printf('<b>%s</b>', $_SESSION['message']);
    unset($_SESSION['message']);
}

if(!empty($_POST['get_upload_info'])){
    echo json_encode($_SESSION['upload_progress_178']);
    //todo add js for get current info - post requestn + timeout
    die;
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
    <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="178" />
    <input id="inputFile" type="file" name="upfile" required/>
    <input id="submit" type="submit" name="submit" value="Upload!"/>
    <div class="w3-light-grey">
        <div id="progressBar" class="w3-container w3-green w3-center" style="width:0%"><!-- filled by JS--></div>
    </div>
</form>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    //count file's hash
        let fileHash='';
    document.getElementById('inputFile').addEventListener('change', function () {
      var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
        file = this.files[0],
        chunkSize = 2097152,                             // Read in chunks of 2MB
        chunks = Math.ceil(file.size / chunkSize),
        currentChunk = 0,
        spark = new SparkMD5.ArrayBuffer(),
        fileReader = new FileReader();

      fileReader.onload = function (e) {
       // console.log('read chunk nr', currentChunk + 1, 'of', chunks);
        spark.append(e.target.result);                   // Append array buffer
        currentChunk++;

        if (currentChunk < chunks) {
          loadNext();
        } else {
         // console.log('finished loading');
          fileHash = spark.end();
         // console.info('computed hash', fileHash);  // Compute hash

        }
      };

      fileReader.onerror = function () {
        console.warn('spark-md5.min.js::: oops, something went wrong.');
      };

      function loadNext() {
        var start = currentChunk * chunkSize,
          end = ((start + chunkSize) >= file.size) ? file.size : start + chunkSize;

        fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
      }

      loadNext();
    });

    // end of count

    let sbmt = document.getElementById('submit');
    let form = document.getElementById('loadFile');
    let fileToUpload = document.getElementById('inputFile');

    sbmt.addEventListener('click', function (e) {
      e.preventDefault();
      var formData = new FormData(form);
      formData.append('upfile', form.submit);
      formData.append('submit',sbmt.value);
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            console.log('file uploaded!');
        }
      };
      xhr.open('POST', '/index.php');
      xhr.setRequestHeader("X-File-Name", fileToUpload.value.split("\\").pop());
      xhr.setRequestHeader("X-Hash", fileHash);
      xhr.send(formData);
    });
  });
</script>
<script type="text/javascript" src="js/spark-md5.min.js"></script>
</body>
</html>