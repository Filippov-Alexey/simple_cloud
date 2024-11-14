<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Видео</title>
  <link rel='stylesheet' href='css.css'>

  <style>
    #videoContainer {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 90vh;
      width: 100%;
      overflow: hidden;
    }

    video {
      width: 100%;
      height: 100%;
      object-fit: contain;
      max-width: 100vw;
      max-height: 95vh;
    }
    <?php include 'foot.php'?>

  </style>
</head>
<body>
<div class="bar">

<button class="back" onclick="window.location.href='index.php'">Домой</button>
<?php
// Проверка, если передан путь к директории
if (isset($_GET['dir'])) {
  $folderPath = $_GET['dir'];
} else {
  $folderPath = 'video';
}
$imagePath = $_GET['video'];
$lastSlashPosition = strrpos($imagePath, '/');
$parentPath = substr($imagePath, 0, $lastSlashPosition);
$parentPath = str_replace('video', '', $parentPath);
?>

<button class='back' onclick="window.location.href='index.php?dir=<?php echo $parentPath ?>'"> Назад</button>
</div>
<div id="videoContainer">
    <?php
      $videoPath = htmlspecialchars($_GET['video']);
      echo "<video autoplay controls id='videoPlayer'><source src='$videoPath' type='video/mp4'></video><br>";
      echo "<script>document.getElementById('videoPlayer').requestFullscreen();</script>";
    ?>
<script>
  // Подписываемся на событие окончания воспроизведения видео
  document.getElementById('videoPlayer').addEventListener('ended', function() {
    playNextVideo('next'); // вызываем функцию для перехода к следующему видео
  });

  function playNextVideo(direction) {
    <?php
    // Проверка, если передан путь к директории
    if (isset($_GET['dir'])) {
      $folderPath = $_GET['dir'];
    } else {
      $folderPath = 'video';
    }
    $videoPath = $_GET['video'];
    $lastSlashPosition = strrpos($videoPath, '/');
    $parentPath = substr($videoPath, 0, $lastSlashPosition);

    $videoFiles = glob($parentPath . "/*.{mp4,mov,avi,mkv,asf,flv}", GLOB_BRACE); // Получение списка видео файлов в папке
    $videoFiles = array_filter($videoFiles, 'is_file');
    $videoFiles = json_encode($videoFiles);
    ?>

    var currentVideoPath = "<?php echo $videoPath; ?>"; // Получение текущего пути к видео
    var videoFiles = <?php echo $videoFiles; ?>; // Получение списка видео файлов

    // Определение индекса текущего файла
    var currentVideoIndex = videoFiles.indexOf(currentVideoPath);

    // Определение индекса следующего файла в зависимости от направления
    var nextVideoIndex;
    if (direction === 'next') {
      nextVideoIndex = currentVideoIndex + 1;
    } else if (direction === 'previous') {
      nextVideoIndex = currentVideoIndex - 1;
    }

    // Обработка граничных условий
    if (nextVideoIndex < 0) {
      nextVideoIndex = videoFiles.length - 1;
    } else if (nextVideoIndex >= videoFiles.length) {
      nextVideoIndex = 0;
    }

    // Получение следующего пути к видео файлу
    var nextVideoPath = videoFiles[nextVideoIndex];

    // Если следующего видео нет, переходим к первому видео
    if (!nextVideoPath) {
      nextVideoPath = videoFiles[0];
    }

    // Добавляем расширение видео файла, если не указано
    var nextVideoExtension = nextVideoPath.substring(nextVideoPath.lastIndexOf('.')).toLowerCase();
    if (![".avi", ".mkv", ".asf", ".mp4", ".flv", ".mov"].includes(nextVideoExtension)) {
      nextVideoPath += ".mp4";
    }

    // Используйте следующую строку кода для воспроизведения следующего видео
    document.getElementById('videoPlayer').src = nextVideoPath;
    document.getElementById('videoPlayer').play();

    // Обновление URL в поисковой строке и истории браузера
    var nextVideoURL = window.location.pathname + '?video=' + encodeURIComponent(nextVideoPath);
    history.pushState(null, null, nextVideoURL);
    location.reload();
  }
</script>
</div>
<div class="nav">
    <button class='back' onclick="playNextVideo('previous')">Назад</button>
    <button class='back' onclick="playNextVideo('next')">Вперед</button>
  </div>
  <script>
  function playNextVideo(direction) {
    <?php
    // Проверка, если передан путь к директории
    if (isset($_GET['dir'])) {
      $folderPath = $_GET['dir'];
    } else {
      $folderPath = 'video';
    }
    $videoPath = $_GET['video'];
    $lastSlashPosition = strrpos($videoPath, '/');
    $parentPath = substr($videoPath, 0, $lastSlashPosition);

    $videoFiles = glob($parentPath . "/*.{mp4,mov,avi,mkv,asf,flv}", GLOB_BRACE); // Получение списка видео файлов в папке
    $videoFiles = array_filter($videoFiles, 'is_file');
    $videoFiles = json_encode($videoFiles);
    ?>

    var currentVideoPath = "<?php echo $videoPath; ?>"; // Получение текущего пути к видео
    var videoFiles = <?php echo $videoFiles; ?>; // Получение списка видео файлов

    // Определение индекса текущего файла
    var currentVideoIndex = videoFiles.indexOf(currentVideoPath);

    // Определение индекса следующего файла в зависимости от направления
    var nextVideoIndex;
    if (direction === 'next') {
      nextVideoIndex = currentVideoIndex + 1;
    } else if (direction === 'previous') {
      nextVideoIndex = currentVideoIndex - 1;
    }

    // Обработка граничных условий
    if (nextVideoIndex < 0) {
      nextVideoIndex = videoFiles.length - 1;
    } else if (nextVideoIndex >= videoFiles.length) {
      nextVideoIndex = 0;
    }

    // Получение следующего пути к видео файлу
    var nextVideoPath = videoFiles[nextVideoIndex];

    // Если следующего видео нет, переходим к первому видео
    if (!nextVideoPath) {
      nextVideoPath = videoFiles[0];
    }

    // Добавляем расширение видео файла, если не указано
    var nextVideoExtension = nextVideoPath.substring(nextVideoPath.lastIndexOf('.')).toLowerCase();
    if (![".avi", ".mkv", ".asf", ".mp4", ".flv", ".mov"].includes(nextVideoExtension)) {
      nextVideoPath += ".mp4";
    }

    // Используйте следующую строку кода для воспроизведения следующего видео
    document.getElementById('videoPlayer').src = nextVideoPath;
    document.getElementById('videoPlayer').play();

    // Обновление URL в поисковой строке и истории браузера
    var nextVideoURL = window.location.pathname + '?video=' + encodeURIComponent(nextVideoPath);
    history.pushState(null, null, nextVideoURL);
    location.reload();
  }
</script> 

</body>
</html>
