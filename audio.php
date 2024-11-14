<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Аудио</title>
  <link rel='stylesheet' href='css.css'>

  <style>
    #audioContainer {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 95vh;
      position: relative;
      overflow: hidden;
    }

    audio {
      width: 100%;
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
  $folderPath = 'audio';
}
$audioPath = $_GET['audio'];
$lastSlashPosition = strrpos($audioPath, '/');
$parentPath = substr($audioPath, 0, $lastSlashPosition);
$parentPath = str_replace('audio', '', $parentPath);
?>

<button class='back' onclick="window.location.href='index.php?dir=<?php echo $parentPath ?>'"> Назад</button>
</div>
  <div id="audioContainer">
    <?php
      $audioPath = $_GET['audio'];
      echo "<audio autoplay controls id='audioPlayer' onended='playNextAudio()'><source src='$audioPath'></audio><br>";
    ?>
<script>
  // Подписываемся на событие окончания воспроизведения аудио
  document.getElementById('audioPlayer').addEventListener('ended', function() {
    playNextAudio(); // вызываем функцию для перехода к следующему аудио
  });

  function playNextAudio() {
    // Здесь добавьте код для получения URL следующего аудио
    <?php
    // Проверка, если передан путь к директории
    if (isset($_GET['dir'])) {
      $folderPath = $_GET['dir'];
    } else {
      $folderPath = 'audio';
    }
    $audioPath = $_GET['audio'];
    $lastSlashPosition = strrpos($audioPath, '/');
    $parentPath = substr($audioPath, 0, $lastSlashPosition);

    $audioFiles = glob($parentPath . "/*.{mp3,wav,ogg}", GLOB_BRACE); // Получение списка аудио файлов в папке
    $audioFiles = array_filter($audioFiles, 'is_file');
    $audioFiles = json_encode($audioFiles);
    ?>

    var currentAudioPath = "<?php echo $audioPath; ?>"; // Получение текущего пути к аудио
    var audioFiles = <?php echo $audioFiles; ?>; // Получение списка аудио файлов

    // Определение индекса текущего файла
    var currentAudioIndex = audioFiles.indexOf(currentAudioPath);

    // Получение следующего индекса файла
    var nextAudioIndex = currentAudioIndex + 1;

    // Если текущий файл последний, переходим к первому
    if (nextAudioIndex >= audioFiles.length) {
      nextAudioIndex = 0;
    }

    // Получение следующего пути к аудио файлу
    var nextAudioPath = audioFiles[nextAudioIndex];

    // Если следующего аудио нет, переходим к первому аудио
    if (!nextAudioPath) {
      nextAudioPath = audioFiles[0];
    }

    // Добавляем расширение аудио файла, если не указано
    var nextAudioExtension = nextAudioPath.substring(nextAudioPath.lastIndexOf('.')).toLowerCase();
    if (![".mp3", ".wav", ".ogg"].includes(nextAudioExtension)) {
      nextAudioPath += ".mp3";
    }

    // Используйте следующую строку кода для воспроизведения следующего аудио
    document.getElementById('audioPlayer').src = nextAudioPath;
    document.getElementById('audioPlayer').play();

    // Обновление URL в поисковой строке и истории браузера
    var nextAudioURL = window.location.pathname + '?audio=' + encodeURIComponent(nextAudioPath);
    history.pushState(null, null, nextAudioURL);
    location.reload();
  }
</script>
<div class="nav">
    <button class='back' onclick="playNextAudio('previous')">Назад</button>
    <button class='back' onclick="playNextAudio('next')">Вперед</button>
  </div>
  <script>
  function playNextAudio(direction) {
    <?php
    // Проверка, если передан путь к директории
    if (isset($_GET['dir'])) {
      $folderPath = $_GET['dir'];
    } else {
      $folderPath = 'audio';
    }
    $audioPath = $_GET['audio'];
    $lastSlashPosition = strrpos($audioPath, '/');
    $parentPath = substr($audioPath, 0, $lastSlashPosition);

    $audioFiles = glob($parentPath . "/*.{mp3,wav,ogg}", GLOB_BRACE); // Получение списка аудио файлов в папке
    $audioFiles = array_filter($audioFiles, 'is_file');
    $audioFiles = json_encode($audioFiles);
    ?>

    var currentAudioPath = "<?php echo $audioPath; ?>"; // Получение текущего пути к аудио
    var audioFiles = <?php echo $audioFiles; ?>; // Получение списка аудио файлов

    // Определение индекса текущего файла
    var currentAudioIndex = audioFiles.indexOf(currentAudioPath);

    // Определение индекса следующего файла в зависимости от направления
    var nextAudioIndex;
    if (direction === 'next') {
      nextAudioIndex = currentAudioIndex + 1;
    } else if (direction === 'previous') {
      nextAudioIndex = currentAudioIndex - 1;
    }

    // Обработка граничных условий
    if (nextAudioIndex < 0) {
      nextAudioIndex = audioFiles.length - 1;
    } else if (nextAudioIndex >= audioFiles.length) {
      nextAudioIndex = 0;
    }

    // Получение следующего пути к аудио файлу
    var nextAudioPath = audioFiles[nextAudioIndex];

    // Если следующего аудио нет, переходим к первому аудио
    if (!nextAudioPath) {
      nextAudioPath = audioFiles[0];
    }

    // Добавляем расширение аудио файла, если не указано
    var nextAudioExtension = nextAudioPath.substring(nextAudioPath.lastIndexOf('.')).toLowerCase();
    if (![".mp3", ".wav", ".ogg"].includes(nextAudioExtension)) {
      nextAudioPath += ".mp3";
    }

    // Используйте следующую строку кода для воспроизведения следующего аудио
    document.getElementById('audioPlayer').src = nextAudioPath;
    document.getElementById('audioPlayer').play();

    // Обновление URL в поисковой строке и истории браузера
    var nextAudioURL = window.location.pathname + '?audio=' + encodeURIComponent(nextAudioPath);
    history.pushState(null, null, nextAudioURL);
    location.reload();
  }
</script>

  </div>

</body>
</html>