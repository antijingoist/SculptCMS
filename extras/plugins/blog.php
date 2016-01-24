<?
  // Markdown blogging engine, as a plugin.
  include_once("blog/blog_settings.php");

  // How about instead of a special page, check current page in pagetext to see if it's registered. then get pagetext processes the page different.

  register_page($blog_page_title, 'index.php?p=Blog');
  register_text_engine($blog_page_title, "blog/blog_start.php");

  function blog_front_page() {
      global $blog_directory;

      if (file_exists($blog_directory)){
         $article_list = array_diff(scandir($blog_directory, 1), array('..', '.'));
         if(count($article_list) > 2){
            foreach ($article_list as $key=>$article) {
               $file_part = pathinfo($article);
               if ($file_part['extension'] === 'md' || $file_part['extension'] === 'html') {
                  $article_date = $file_part['filename'];

                  if($key === 0) {
                     echo '<div class=first_post>';
                     echo '<h2 class="blog_title"><a href=index.php?p=Blog&bp=' . $article_date . '>' . blog_get_title($article_date) . '</a></h2>';
                     blog_post($article_date, true);
                     echo '</div>';
                  } elseif ($key > 4) {
                     break;
                  } else {
                     echo '<h3 class="blog_title"><a href=index.php?p=Blog&bp=' . $article_date . '>' . blog_get_title($article_date) . '</a></h3>';
                  }
               }
            }
            echo '<p><a href="index.php?p=Blog&bp=archive">View Archives</a></p>';
         } else {
            show_error("blog_no_posts");
         }
      } else {
         show_error(404);
      }
  }

  function blog_post($post, $is_front = false) {
    global $blog_directory;
    global $date_format;
    $blog_post_file = $blog_directory . '/' . $post . ".md";
    if (file_exists($blog_post_file)){
       echo '<div class=blog_text>';
       if (!($is_front)) {
          echo '<p class="blog_timestamp">' . date($date_format, strtotime($post)) . '</p>';
          sculpt_parse_markdown_file($blog_post_file, false);
       } else {
          sculpt_parse_markdown_file($blog_post_file, true);
       }
       echo '</div>';
    }
    else {
       echo $blog_directory;
       show_error(404);
    }
  }

  function blog_archive($year, $month) {
    global $blog_directory;
    global $date_format;

    $page_data = cache_retrieve("blog_archives_" . $year . $month);
    if ($page_data == NULL) {
    $page_data = $page_data . '<h2>Archives For ' . date('F', mktime(0, 0, 0, $month, 10)) . ', ' . $year . '</h2>';
    $page_data = $page_data . '<p><a href="index.php?p=Blog&bp=archive">Back to Archive</a></p>';
    if (file_exists($blog_directory)){
        $article_list = glob($blog_directory . '/' . $year . $month . "*.md");
        foreach ($article_list as $key=>$article) {
          $file_part = pathinfo($article);
          if ($file_part['extension'] === 'md' || $file_part['extension'] === 'html') {
            $article_date = $file_part['filename'];
            $page_data = $page_data . '<h3 class="blog_title"><a href=index.php?p=Blog&bp=' . $article_date . '>' . blog_get_title($article_date) . '</a></h3>';
            $page_data = $page_data . '<span class="blog_post_date">' . date($date_format, strtotime($article_date)) . '</span>';
          }
        }
        echo $page_data;
        cache_data($page_data, "blog_archives_" . $year . $month);
      } else {
        show_error(404);
      }
    } else {
      echo $page_data;
    }
  }

  function blog_archives_list() {
    global $blog_directory;
    global $date_format;

    $yearmo = "";
    $prev_year = "";

    if (file_exists($blog_directory)){
      $page_data = cache_retrieve("blog_archives");
      if($page_data == NULL) {
        $article_list = glob($blog_directory . '/*.md');
        foreach ($article_list as $key=>$article) {
          $file_part = pathinfo($article);
          $year = substr($file_part['filename'], 0, 4);
          if (!($year === $prev_year)){
            $page_data = $page_data . '<h2>' . $year . '</h2>';
            $prev_year = $year;
          }

          $month = substr($file_part['filename'], 4, 2);
          if (!($year . $month === $yearmo)) {
              $page_data = $page_data . '<h3><a href=index.php?p=Blog&bp=archive&ayr=' . $year . '&amo=' . $month . '>';
              $page_data = $page_data . date('F', mktime(0, 0, 0, $month, 10));
              $page_data = $page_data . '</a></h3>';
              $yearmo = $year . $month;
          }
        }
        echo $page_data;
        cache_data($page_data, "blog_archives");
      } else {
        echo $page_data;
      }
    } else {
      show_error(404);
    }
  }

  function blog_get_title($post) {
     global $blog_directory;

     $blog_post_file = fopen($blog_directory . '/' . $post . ".md", 'r');
        $title = fgets($blog_post_file);
        $title = ltrim($title, '## ');
     fclose($blog_post_file);
     return $title;
  }

?>