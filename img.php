<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Изображения</title>
  <link rel='stylesheet' href='css.css'>

  <style>
    #imageContainer {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 90vh;
      width: 100%;
      overflow: hidden;
    }

    #image{
      width: 100%;
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
  $folderPath = 'img';
}
$imagePath = $_GET['img'];
$lastSlashPosition = strrpos($imagePath, '/');
$parentPath = substr($imagePath, 0, $lastSlashPosition);
$parentPath = str_replace('img/', '', $parentPath);
?>

<button class='back' onclick="window.location.href='index.php?dir=<?php echo $parentPath ?>'"> Назад</button>
</div>
<div id="imageContainer">
  <?php
    $imagePath = realpath($_GET['img']);
    echo "<img id='image' src='$imagePath'>";
    ?>
    </div>

  <div class="nav">
    <button class='back' onclick="playNextImage('previous')">Назад</button>
    <button class='back' onclick="playNextImage('next')">Вперед</button>
  </div>
  <script>
  function playNextImage(direction) {
    <?php
    // Проверка, если передан путь к директории
    if (isset($_GET['dir'])) {
      $folderPath = $_GET['dir'];
    } else {
      $folderPath = 'img';
    }
    $imagePath = $_GET['img'];
    $lastSlashPosition = strrpos($imagePath, '/');
    $parentPath = substr($imagePath, 0, $lastSlashPosition);

    $imageFiles = glob($parentPath . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE); // Получение списка файлов изображений в папке
    $imageFiles = array_filter($imageFiles, 'is_file');
    $imageFiles = json_encode($imageFiles);
    ?>

    var currentImagePath = "<?php echo $imagePath; ?>"; // Получение текущего пути к изображению

    // Определение индекса текущего файла
    var imageFiles = <?php echo $imageFiles; ?>;
    var currentImageIndex = imageFiles.indexOf(currentImagePath);

    // Определение индекса следующего файла в зависимости от направления
    var nextImageIndex;
    if (direction === 'next') {
      nextImageIndex = currentImageIndex + 1;
    } else if (direction === 'previous') {
      nextImageIndex = currentImageIndex - 1;
    }

    // Обработка граничных условий
    if (nextImageIndex < 0) {
      nextImageIndex = imageFiles.length - 1;
    } else if (nextImageIndex >= imageFiles.length) {
      nextImageIndex = 0;
    }

    // Получение следующего пути к изображению
    var nextImagePath = imageFiles[nextImageIndex];

    // Если следующего изображения нет, переход к первому изображению
    if (!nextImagePath) {
      nextImagePath = imageFiles[0];
    }

    // Используйте следующую строку кода для отображения следующего изображения
    document.getElementById('image').src = nextImagePath;

    // Обновление URL-адреса с новым путем изображения
    var nextImageURL = window.location.pathname + '?img=' + encodeURIComponent(nextImagePath);
    history.pushState(null, null, nextImageURL);
    location.reload();
  }
</script>
 
</div>

</body>
</html>
