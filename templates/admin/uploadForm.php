<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

<?php

?>

  <body>
    <form action="admin.php?action=uploadNewImage" method="post" style="width: 50%;" enctype="multipart/form-data">
	  <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
	  
      <input type="file" name="file">
      
	  <div class="buttons">
          <input type="submit" name="upload" value= <?=LANG_UI_UPLOAD_FILE?>>
		  <input type="submit" formnovalidate name="cancel" value= <?=LANG_UI_CANCEL?> />
      </div>
	  
    </form>
    <?php
    // если была произведена отправка формы
    if(isset($_FILES['file'])) {
      // проверяем, можно ли загружать изображение
      $check = Upload::can_upload($_FILES['file']);
    
      if($check === true){
        // загружаем изображение на сервер
		$path = UPLOAD_IMG_PATH;
 
		// Получаем расширение загруженного файла
		$extension = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
		
		// Генерируем уникальное имя файла с этим расширением
		$filename = Upload::getRandomFileName($path, $extension);
		
		// Собираем адрес файла назначения
		$target = $path . '/' . $filename . '.' . $extension;
		
		
		$results['upload'] = $_FILES['file']['name'];
		$results['upload'] = $filename;
		$results['upload'] = $target;
		
		move_uploaded_file($_FILES['file']['tmp_name'], $target);
		
        echo "<strong>" . LANG_FILE_SUCCESS_UPL . "</strong>";
      }
      else{
        // выводим сообщение об ошибке
        echo "<strong>$check</strong>";  
      }
    }
    ?>
  </body>


<?php include "templates/include/footer.php" ?>