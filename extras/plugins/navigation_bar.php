<?
function show_nav($elem = "li", $elem_class="navbar_element", $a_class="navbar_link") {

  $nav_bar_content = cache_retrieve('plugin_nav_bar');
  if($nav_bar_content == NULL ) {
   if ($elem === 'li') {$nav_bar_content .= "<ul>";}
   if (file_exists('./content/navigation.txt')) {
      $pages_list = fopen('./content/navigation.txt', "r");
      if (!$pages_list) {
         $nav_bar_content .= '<' . $elem . ' class="' . $elem_class . '"><a href="' . sculpt_special_link("Home") . '" class="' . $a_class . '">Home</a></' . $elem . '>';
      } else {
         //First item is index
         $nav_item = fgets($pages_list);
         $nav_bar_content .= '<' . $elem . ' class="' . $elem_class . '"><a href="' . sculpt_special_link("Home") . '" class="' . $a_class . '">' . $nav_item . '</a></' . $elem . '>';
         //loop through rest
         while(!feof($pages_list)) {
            $nav_item = trim(fgets($pages_list));
            if(page_is_registered($nav_item)) {
               $nav_bar_content .= '<' . $elem . ' class="' . $elem_class . '"><a href="' . sculpt_special_link($nav_item) . '" class="' . $a_class . '">' . $nav_item . '</a></' . $elem . '>';
            } else {
               $nav_link = strtolower($nav_item);
               $nav_link = str_replace(" ", "_", $nav_link);
               $nav_bar_content .= '<' . $elem . ' class="' . $elem_class . '"><a href="' . sculpt_page_url($nav_link) . '" class="' . $a_class . '">' . $nav_item . '</a></' . $elem . '>';
            }
         }
         fclose($pages_list);
      }
   } else {
      $nav_bar_content .= '<' . $elem . ' class="' . $elem_class . '"><a href="' . sculpt_special_link("Home") . '" class="' . $a_class . '">Home</a></</' . $elem . '>';
   }
   if ($elem === 'li') {$nav_bar_content .= '</ul>';}
   echo $nav_bar_content;
   cache_data($nav_bar_content, 'plugin_nav_bar');
  } else {
    echo $nav_bar_content;
  }
}

?>