<?php
if (isset($_POST["submit"])) {
  $dirName = $_POST["dirName"];
  $targetDir = $_POST["targetDir"]."/";
  if (empty($dirName)) {
    echo "Пожалуйста, введите имя папки.";
  } elseif (is_dir($targetDir)) {
    echo "Целевая директория недействительна.";
  } else {
    $dirPath = $targetDir . $dirName;
    $command = 'sh /$home/wem/create.sh ' . $dirPath;
    exec($command, $output, $return_var);
  
    // Проверяем код возврата
    if ($return_var === 0) {
      echo "Папка успешно создана с помощью sh-файла.";
      // Обработка вывода, если необходимо
      print_r($output);
    } else {
      echo "Произошла ошибка при вызове sh-файла.";
    }
  }
 
}
?>
<script>
  window.location.href='<?php echo $targetDir?>'
</script>