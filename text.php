<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Текстовые файлы</title>
  <link rel='stylesheet' href='css.css'>

  <style>
    #textContainer {
      height: 88vh;
    }

    pre {
      margin: 0px;
      width: 100%;
      max-height: 100%;
      white-space: pre-wrap;
    }

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
    $folderPath = 'text';
    }
    $textPath = $_GET['text'];
    $lastSlashPosition = strrpos($textPath, '/');
    $parentPath = substr($textPath, 0, $lastSlashPosition);
    $parentPath = str_replace('text/', '', $parentPath);
    ?>

    <button class='back' onclick="window.location.href='index.php?dir=<?php echo $parentPath ?>'"> Назад</button>
</div>
<div id="textContainer">
  <?php
    $textPath = $_GET['text'];
    echo "<pre id='text'>" . htmlentities(file_get_contents($textPath)) . "</pre>";
  ?>
</div>
</body>
</html>