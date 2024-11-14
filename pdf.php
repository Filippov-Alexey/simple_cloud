<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PDF Files</title>
  <link rel='stylesheet' href='css.css'>

  <style>
    .pdf-content {
      margin: 0px;
      width: 100vw;
      height: 94.4vh;
      background-color: #ffffff;
      bottom: 0px;
    }

    #pdf-content {
      margin: 0px;
      width: 100vw;
      position: relative;
      height: 100%;
      bottom: 0px;
    }

    .nav {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 3vh;
      display: flex;
    }

    .nav button {
      margin: 0;
      width: 50%;
      text-decoration: none;
      color: #f46;
      position: relative;
      background-color: transparent;
      border: none;
    }
  </style>
</head>
<body>
<div class="bar">
    <button class="back" onclick="window.location.href='index.php'">Домой</button>
    <?php
    // Checking if a directory path is provided
    if (isset($_GET['dir'])) {
    $folderPath = $_GET['dir'];
    } else {
    $folderPath = 'pdf';
    }
    $pdfPath = $_GET['pdf'];
    $lastSlashPosition = strrpos($pdfPath, '/');
    $parentPath = substr($pdfPath, 0, $lastSlashPosition);
    $parentPath = str_replace('pdf/', '', $parentPath);
    ?>

    <button class='back' onclick="window.location.href='index.php?dir=<?php echo $parentPath ?>'"> Назад</button>
</div>
<div id="pdfContainer">
  <?php
    $pdfPath = $_GET['pdf'];
    // Displaying the PDF content
    if (is_readable($pdfPath)) {
      echo "<embed class='pdf-content' src='$pdfPath' type='application/pdf'></embed>";
    } else {
      echo "<div class='pdf-content'>PDF file could not be displayed.</div>";
    }
  ?>
</div>
</body>
</html>