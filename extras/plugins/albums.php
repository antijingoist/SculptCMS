<?
  register_page("albums", 'index.php?p=albums');
  register_text_engine("albums", "albums/album_page.php");
  register_stylesheet(sculpt_system("plugin_path") . "albums/album_styles.css");
  register_script(sculpt_system("plugin_path") . "albums/album.js");

  function album_categories_list($active_album) {

    $directory = sculpt_system("content_path") . 'albums';
    $page_data = '';
    $page_data .= '<ul>';
    if (file_exists($directory)){
      $categories = glob($directory . '/*' , GLOB_ONLYDIR);

      foreach ($categories as $category) {
        $category_parts = pathinfo($category);
        $category_name = $category_parts['filename'];
        if (!($active_album)) {
          $active_album = $category_name;
        }

        if ($category_name === $active_album) {
          $category_class = "active_tab";
        } else {
          $category_class = "inactive_tab";
        }

        $page_data .= '<li class="' . $category_class . '"><a href=index.php?p=albums&sac=' . $category_name . '>' . ucfirst($category_name) . '</a></li>';
      }
    }
    $page_data .= '</ul>';
    echo ($page_data);
    return $active_album;
  }

  function album_content($category_name) {
    $page_data = "";
    $photos_directory = sculpt_system("content_path") . 'albums/' . $category_name;
    if($category_name){
      if(file_exists($photos_directory)) {
        $page_data .= '<h2>' . ucfirst($category_name) . '</h2>';
        if (file_exists($photos_directory . '/index.md')) {
          echo $page_data;
          sculpt_parse_markdown_file($photos_directory . '/index.md');
          $page_data = "";
        }
        $page_data .= '<div id="photos">';

        $photos = glob($photos_directory . '/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}', GLOB_BRACE);

        foreach ($photos as $photo) {
          $photo_info = pathinfo($photo);
          $photo_name = $photo_info['filename'];
          $page_data .= album_image_thumb($photo, $photo_name);
        }

        $page_data .= '</div>';
        echo $page_data;
      }
    } else {

    }
  }

  function album_image_thumb($image_path, $image_name) {
    $thumbnail_piece = '';
    $thumbnail_piece .= '<div class="thumbnail">' .
                        '<div class="image">' .
                        '<a onclick="showLightbox(\'' . $image_path . '\')">' .
                        '<img class="thumb" src="' . $image_path . '"/></a></div>' .
                        '<div class="caption">' . $image_name . '</div></div>';

    return $thumbnail_piece;
  }
?>