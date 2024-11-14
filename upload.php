<?php
if (isset($_POST["submit"])) {
  $fileName = $_FILES["fileToUpload"]["name"];
  $uploadOk = 1;
  $targetFile = $_POST["targetFile"];
  $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
  $url=$targetFile;
  if ($fileExtension === "torrent") {
    $targetFile = 'date/torrent';
    $targetFile=  $targetFile."/".$fileName;
    // The file has a .torrent extension
    // Perform the necessary actions for .torrent files
  } else {
    $targetFile = $_POST["targetFile"];
    $targetFile=  $targetFile."/".$fileName;
    // The file does not have a .torrent extension
    // Perform the necessary actions for other file types
  }
  $substringToRemove = "/index.php?dir=";
$targetFile = str_replace($substringToRemove, "", $targetFile);
  // echo $targetFile;
  $url = substr($url, strpos($url, "dir=") + strlen("dir="));
  // echo $url;
  
  // Проверка, является ли файл действительным файлом
  if (!empty($_FILES["fileToUpload"]["tmp_name"])) {
    $check = $_FILES["fileToUpload"]["type"];
    if ($check !== false) {
      echo "Файл является действительным файлом - " . $check . ".";
      $uploadOk = 1;
    } else {
      echo "Файл не является действительным файлом.";
      $uploadOk = 0;
    }
  }

  // Проверка размера файла
  if ($_FILES["fileToUpload"]["size"] > 50000000) {
    echo "Файл слишком большой. Максимальный размер - 50MB.";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    echo "Файл не был загружен.";
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
      echo "Файл " . htmlspecialchars($fileName) . " успешно загружен.";
    } else {
      echo "Произошла ошибка при загрузке файла.";
    }
  }
}
?>
<script>
  window.location.href='index.php?dir=<?php echo $url?>'
</script>