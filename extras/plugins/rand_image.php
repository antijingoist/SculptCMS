<?php

   //**************************************************************************************
   // Random Image.
   // Place images in content/images/rotating
   // call using rand_image(); in your theme file.
   //
   //***************************************************************************************

   function rand_image() {
      $directory = sculpt_system("content_path") . '/images/rotating/';
      if (file_exists($directory)){
         $image_list = array_diff(scandir($directory), array('..', '.'));
         // find out if count includes empty items
         if (count($image_list) > 2) {
            $selected_picture = rand(2, count($image_list) - 1);
            echo '<img src="' . $directory . $image_list[$selected_picture] . '" />';
         }
      }
   }

?>