<?php
function deleteFolder($folderPath) {
  // Проверяем, что папка существует и это не корневая папка
  if (is_dir($folderPath) && $folderPath !== '/') {
    $files = glob($folderPath . '/*');
    
    foreach ($files as $file) {
      if (is_file($file)) {
        // Удаляем файл
        unlink($file);
      } elseif (is_dir($file)) {
         // Удаляем вложенную папку. Вам также может потребоваться добавить проверку на вложенные папки, если вам нужно удалить все вложения.
         deleteFolder($file);
      }
    }
    
    // Удаляем папку
    rmdir($folderPath);
    
    echo "Папка успешно удалена";
  } else {
    echo "Папка не существует или нельзя удалить корневую папку";
  }
}

// Проверяем, передан ли путь к директории через POST-запрос
if (isset($_POST['dir'])) {
  $folderPath = $_POST['dir'];
  deleteFolder($folderPath);
} 
?>
