<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Archive Files</title>
  <link rel='stylesheet' href='css.css'>

  <style>
    #archiveContainer {
      height: 88vh;
    }

    /* CSS styling for displaying the archive content */
    .archive-content {
      margin: 0px;
      width: 100%;
      height: 100%;
      max-height: 100%;
      overflow-y: auto;
      background-color: #ffffff;
      border: 1px solid #cccccc;
      padding: 10px;
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
    if (isset($_GET['dir'])) {
        $folderPath = $_GET['dir'];
    } else {
        $folderPath = 'archive';
    }
    $archivePath = $_GET['archive'];
    $parentPath = dirname($archivePath);
    ?>

    <button class='back' onclick="window.location.href='index.php?dir=<?php echo $parentPath ?>'">Назад</button>
</div>
<div id="archiveContainer">
  <?php
    $archivePath = $_GET['archive'];

    // Create ZipArchive object
    $zip = new ZipArchive;

    // Open the archive
    if ($zip->open($archivePath) === true) {
        // Get the number of files in the archive
        $numFiles = $zip->numFiles;
        
        // Loop through each file in the archive
        for ($i = 0; $i < $numFiles; $i++) {
            // Get the name of the file
            $fileName = $zip->getNameIndex($i);
            
            // Display the file name
            echo "<p>$fileName</p>";
        }
        
        // Close the archive
        $zip->close();
    } else {
        // Display error message if unable to open the archive
        echo "<div class='archive-content'>Unable to open the archive.</div>";
    }
  ?>
</div>
</body>
</html>
