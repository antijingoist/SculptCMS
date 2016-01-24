Themes & Plugins
---
### Themes
Sculpt comes with a Google Web Starter Kit theme, and has a built in, basic theme that it falls back to if there is a significant issue with the theme you intend on using (for instance: theme does not exist).

Themes are installed into the `extras/themes` folder. The theme folder name should be the theme name. You can enable your theme by changing the `$website_theme` variable in the `system/settings.php` file.

Themes require an index.php that contains a `page_text($current_page);`. This is where the rendered Markdown text for each page will be placed. You will generally not need additional php files. You can also use additional functions where needed [(see Functions)](index.php?p=functions).

### Plugins
There are 3 plugins that come with Sculpt: blog, navigation_bar and rand_image.

Random Image (rand_image) is a sample plugin that makes a new function available to the user: `rand_image();`. This function simply checks a folder for files (it assumes all the files are images), and inserts an image tag for a random image from the folder. Source code is below: 

```
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
```