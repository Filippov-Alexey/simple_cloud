<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Каталог файлов</title>
  <link rel='stylesheet' href='css.css'>
</head>

<style>
  table {
    border-collapse: collapse;
  }

  table, th, td {
    border: 1px solid black;
  }

  th, td {
    padding: 0;
    text-align: center;
  }

  .link-button {
    width: 100%;
    height: 100%;
    color: blue;
    font-size: 30px;
    background-color: transparent;
    border: none;
    padding: 0; /* Удаление отступов */
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
  }
  
  .link-button:hover {
    background-color: #5f5; /* Цвет фона при наведении */
    cursor: pointer;
  }

  .square-cell {
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    overflow: hidden;
  }

  .square-cell video {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  h1,h3{
    color: #000;
    font: size 16em;
  }

input{
  height: 3vh;
}
.file-upload-button {
  padding: 10px 20px;
  font-size: 16px;
}
h2,h1{
  margin: 5px;
}

#deleteButton{
  background-color: transparent;
}

</style>
<body>
  <h1>Каталог файлов</h1>
  
  <?php
if (isset($_GET['dir'])) {
    // Если параметр "dir" уже присутствует в URL
    $data = $_GET['dir'];
} else {
    // Если параметр "dir" отсутствует в URL
    $data = 'date';
    // $data = 'date';
    // Получаем текущий URL
    $url = $_SERVER['REQUEST_URI'];
    // Проверяем, содержит ли URL "index.php"
    if (stripos($url, 'index.php') !== false) {
        // URL содержит "index.php", добавляем параметр "dir" к URL без "index.php"
        $url = str_replace('index.php', '', $url) . '?dir=' . $data;
    } else {
        // URL не содержит "index.php", добавляем "index.php" и параметр "dir" к URL
        $url = $url . 'index.php?dir=' . $data;
    }
    // Перенаправляем на обновленный URL
    header('Location: ' . $url);
    exit;
}
?>

<?php
$folderPath = $data;
echo '<div style="display: flex; align-items: center;">
  <h2 style="margin-right: 10px;">' . $folderPath . '</h2>';
if ($folderPath != $data && $folderPath != $data+'/torrent') {
    echo '<button id="deleteButton">Удалить</button>';
}
echo '</div>';
?>
<?php

$files = scandir($folderPath);
?>

<script>
document.getElementById('deleteButton').addEventListener('click', function() {
  var currentURL = window.location.href;
  var currentDirectory = decodeURIComponent(currentURL.split('?dir=')[1]);
  var directoryArray = currentDirectory.split('/');
  var folderToDelete = directoryArray[directoryArray.length - 1]; // Имя текущей папки
  
  // Удалить текущую папку из массива папок
  directoryArray.splice(-1, 1);
  
  // Обновить URL с новым путем
  var parentDirectory = directoryArray.join('/');
  var encodedParentDirectory = encodeURIComponent(parentDirectory);
  var newURL = '?dir=' + encodedParentDirectory;
  var decodedNewURL = decodeURIComponent(newURL);
  history.replaceState(null, null, decodedNewURL); // Изменение URL без перезагрузки страницы
  
  // Выполнение AJAX-запроса на удаление папки
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'delete.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      location.reload();
    }
  };
  xhr.send('dir=' + encodeURIComponent(currentDirectory));
});
</script>

<form action="upload.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="targetFile" value="<?php echo str_replace("/?dir=", "", $_SERVER['REQUEST_URI']); ?>">
  <input type="file" name="fileToUpload" id="fileToUpload" class="file-upload-button">
  <input type="submit" value="Загрузить файл" name="submit">
</form>

<form action="create.php" method="post">
  <input type="hidden" name="targetDir" value="<?php echo str_replace("/?dir=", "", $_SERVER['REQUEST_URI']); ?>">
  <input type="text" name="dirName" placeholder="Имя папки">
  <input type="submit" value="Создать папку" name="submit">
</form>
 
<table style='width:100%;'>
    <?php
      $parentPath = dirname($folderPath);
      if ($parentPath!='.'){
        echo "<tr><td colspan='4'><button class='back' onclick=\"window.location.href='?dir=$parentPath'\">Назад</button></td></tr>";
      }

    $cellCount = 0; // переменная для подсчета количества ячеек в строке
    $tableData = [];
    foreach ($files as $file) {
      if ($file != "." && $file != ".." && !fnmatch("*.part", $file)) {
        $filePath = $folderPath . '/' . $file;
        $fileType = mime_content_type($filePath);
    
        $tableData[] = [
          'file' => $file,
          'isDir' => is_dir($filePath),
          'filePath' => $filePath,
          'fileType' => $fileType
        ];
      }
    }
    
    // Сортировка: сначала папки, затем файлы
    usort($tableData, function ($a, $b) {
      if ($a['isDir'] && !$b['isDir']) {
        return -1;
      } elseif (!$a['isDir'] && $b['isDir']) {
        return 1;
      } else {
        return strnatcasecmp($a['file'], $b['file']); // сортировка по имени файла (не учитывая регистр)
      }
    });
    
    $cellCount = 0; // переменная для подсчета количества ячеек в строке
    foreach ($tableData as $data) {
      $file = $data['file'];
      $filePath = $data['filePath'];
      $fileType = $data['fileType'];
    
      if (is_dir($filePath)) {
        // Если файл - директория, выводим ссылку на эту директорию
        echo "<td><button class='link-button' onclick=\"window.location.href='?dir=$filePath'\">$file</button></td>";
      } else if (strpos($fileType, 'video/') === 0) {
        // Если тип файла видео, переходим на страницу video.php
        echo "<td><button class='link-button' onclick=\"window.location.href='video.php?video=$filePath'\">$file</button></td>";
      } else if (strpos($fileType, 'image/') === 0) {
        // Если тип файла изображение, переходим на страницу img.php
        echo "<td><button class='link-button' onclick=\"window.location.href='img.php?img=$filePath'\">$file</button></td>";
      } else if (strpos($fileType, 'text/') === 0) {
        // Если тип файла текстовый, переходим на страницу text.php
        echo "<td><button class='link-button' onclick=\"window.location.href='text.php?text=$filePath'\">$file</button></td>";
      } else if (strpos($fileType, 'audio/') === 0) {
        // Если тип файла аудио, переходим на страницу audio.php
        echo "<td><button class='link-button' onclick=\"window.location.href='audio.php?audio=$filePath'\">$file</button></td>";
      } else if (strpos($fileType, 'application/pdf') === 0) {
        // Если тип файла PDF, переходим на страницу pdf.php
        echo "<td><button class='link-button' onclick=\"window.location.href='pdf.php?pdf=$filePath'\">$file</button></td>";
      } else if (strpos($fileType, 'application/zip') === 0) {
        // Если тип файла PDF, переходим на страницу pdf.php
        echo "<td><button class='link-button' onclick=\"window.location.href='archive.php?archive=$filePath'\">$file</button></td>";
      } else {
        // Если тип файла не является видео, изображением или текстом, просто отображаем ссылку на файл
        echo "<td><button class='link-button' onclick=\"window.location.href='$filePath'\">$file</button></td>";
      }
    
      $cellCount++;
      if ($cellCount == 4) {
        // Если достигнуто 4 ячейки в строке, закрываем строку и открываем новую
        echo "</tr><tr>";
        $cellCount = 0; // сбрасываем счетчик ячеек
      }
    }
    ?>
    </table>
</body>
</html>
