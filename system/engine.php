<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once ("./system/phpfastcache.php");
include './system/parsedown.php';
include './system/ParsedownExtra.php';
include './system/settings.php';
$fastCache = phpFastCache();
if ($clear_all_caches){
  $fastCache->clean();
}
$cache_time = 3600*24 * $cache_time_days; // Cache time is in seconds.

$enabled_plugins = array();
$enabled_stylesheets = array();
$enabled_scripts = array();
$current_page = _INPUT('p', 'index');
$registered_pages = array('Home' => sculpt_page_url('index'));
$registered_processors = array('system' => '');

include('./extras/plugins/enabled.php');

function sculpt_system($arg) {
   if (isset($arg)) {
      switch($arg) {
         case "path":
            return "./system/";
            break;
         case "content_path":
            return "./content/";
            break;
         case "default_look":
            return "appearance/armature/main.php";
            break;
         case "default_error_path":
            return "./system/errors/";
            break;
         case "themes_path":
            return "./extras/themes/";
            break;
         case "pages_path":
            return "./content/pages/";
            break;
         case "error_path":
            return "./content/errors/";
            break;
         case "plugin_path":
            return "./extras/plugins/";
            break;
         default:
            return "";
            break;
      }
   }
}

/*******************************************************************************
   Import Plugins
   *****************************************************************************/
foreach($enabled_plugins as $plugin) {
   $plugin_file = sculpt_system("plugin_path") . $plugin;

   if (file_exists($plugin_file)) {
      include(sculpt_system("plugin_path") . $plugin);
   }
}

/*******************************************************************************
   Template tags
   *****************************************************************************/

function sculpt_insert($arg) {

}

function site_title() {
   global $website_name;
   echo   $website_name;
}

function site_tagline() {
   global $website_tagline;
   echo $website_tagline;
}

function page_title() {
   global $current_page;
   $page_title = str_replace("_", " ", $current_page);

   echo $page_title;
}

function site_url() {
  global $website_url;

  echo $website_url;
}

function page_text($page) {
   if(page_is_registered($page) && !($page === "Home")) {
      if(file_exists(sculpt_special_link($page))) {
         include(sculpt_special_link($page));
      }
      if(is_text_engine_registered($page)) {
         include(text_engine_location($page));
      }
   } else {
      if (file_exists(sculpt_system("pages_path") . $page . '.md')) {
         $page_content = sculpt_system("pages_path") . $page . '.md';
         $the_result = sculpt_parse_markdown_file($page_content);
         switch ($the_result) {
         case 0:
            return true;
            break;
         case 404:
            show_error(404);
            break;
         }
      } elseif (file_exists(sculpt_system("pages_path") . $page . '.html') && $enable_html_support) {
         include (sculpt_system("pages_path") . $page . '.html');
      } else {
         show_error(404);
      }
   }
}

function theme_location() {
   global $website_theme;
   echo (sculpt_system("themes_path") . $website_theme . '/');
}

function show_error($error_code) {
   echo '<div class="pageError">';
   if (file_exists(sculpt_system("error_path") . $error_code . '.md')) {
      sculpt_parse_markdown_file(sculpt_system("error_path") . $error_code . '.md');
   } else {
      if (sculpt_parse_markdown_file(sculpt_system("default_error_path") . $error_code . '.md') > 0) {
         sculpt_system_error($error_code);
      }
   }
   echo '</div>';
}

// Template tags ***************************************************************/

function enable_plugin($plugin_name) {
   global $enabled_plugins;

   $enabled_plugins[] = $plugin_name . '.php';

   return true;
}

function register_stylesheet($stylesheet_full_path) {
   global $enabled_stylesheets;

   $enabled_stylesheets[] = $stylesheet_full_path;

   return true;
}


function register_script($script_full_path) {
   global $enabled_scripts;

   $enabled_scripts[] = $script_full_path;

   return true;
}

function print_stylesheets() {
  global $enabled_stylesheets;

  $stylesheet_data = "";
  foreach($enabled_stylesheets as $stylesheet) {
     if (file_exists($stylesheet)) {
        $stylesheet_data .= '<link rel="stylesheet" href="' . $stylesheet . '">';
     }
  }

  echo $stylesheet_data;
}

function print_scripts() {
  global $enabled_scripts;

  $script_data = "";
  foreach($enabled_scripts as $script) {
     if (file_exists($script)) {
        $script_data .= '<script src="' . $script . '"></script>';
     }
  }

  echo $script_data;
}

function sculpt_parse_markdown_file($md_file, $no_title = false) {
   global $fastCache;
   global $cache_time;

   if (strpos($md_file, '../') === false){
      if (file_exists($md_file)) {
            $cacheKey = $md_file . $no_title;
            $page_text = $fastCache->get($cacheKey);
         if($page_text == NULL) {

            $markdownData = file_get_contents($md_file);
            if($no_title) {
               $markdownData = preg_replace('/^.+\n/', '', $markdownData);
            }
            $Parsedown = new ParsedownExtra();
            $page_text = $Parsedown->text($markdownData);
            $fastCache->set($cacheKey, $page_text, $cache_time);
            echo $page_text;
            return 0;
         } else {
            echo $page_text;
         }
      } else {
         return 404;
      }
   } else {
      return 404;
   }
}

function sculpt_system_error($error_code) {
   echo '<div class="systemError">';
   switch ($error_code) {
      case 404:
         echo '<h2>Install Error</h2>';
         echo '<p>This install is missing one or more files. Please contact the site owner, and tell them to copy the CMS files to their proper locations, or contact their support staff.</p>';
         break;
      default:
         echo '<h2>Unknown Error</h2>';
         echo '<p>An unknown error has occurred. Please contact the site owner, or contact their support staff.</p>';
         break;
   }
   echo '</div>';
}

function sculpt_special_link($page_name) {
   global $registered_pages;

   if(isset($registered_pages[$page_name])){
      return $registered_pages[$page_name];
   } else {
      return "";
   }
}


function register_page($page_name, $page_link) {
   global $registered_pages;
   if(isset($page_name) && isset($page_link)){
      $registered_pages[$page_name] = $page_link;
      return true;
   } else {
      return false;
   }
}

function page_is_registered($page_name) {
   global $registered_pages;
   if(isset($registered_pages[$page_name])){
      return true;
   } else {
      return false;
   }
}

function register_text_engine($processor_name, $processor_location) {
   global $registered_processors;
   if(isset($processor_name) && isset($processor_location)){
      $registered_processors[$processor_name] = $processor_location;
      return true;
   } else {
      return false;
   }
}

function is_text_engine_registered($engine_name) {
   global $registered_processors;
   if(isset($registered_processors[$engine_name])){
      return true;
   } else {
      return false;
   }
}

function text_engine_location($engine_name){
   global $registered_processors;

   if(isset($registered_processors[$engine_name])){
      return sculpt_system("plugin_path") . $registered_processors[$engine_name];
   } else {
      return "";
   }
}

function is_rewrite() {
  global $url_rewrite;

  return $url_rewrite;
}

function sculpt_page_url($page) {
  global $page_suffix;

  if (page_is_registered($page)) {
    return sculpt_special_link($page);
  }

  if (!(is_rewrite())) {
    return 'index.php?p=' . $page;
  } else {
    return $page . $page_suffix;

  }
}

function cache_data($data, $cache_key = "") {
  global $fastCache;
  global $cache_time;
  if ($cache_key == "") {
    $cache_key = uniqid("sculpt_");
  }
  $fastCache->set($cache_key, $data, $cache_time);
  return $cache_key;
}

function cache_retrieve($cache_key) {
  global $fastCache;
  return $fastCache->get($cache_key);
}

function _INPUT($name, $default)
{
   if (isset($_REQUEST[$name])) {
      return strip_tags($_REQUEST[$name]);
   }else{
      return $default;
   }
}


if (isset($website_theme)) {
   $theme_location = sculpt_system("themes_path") . $website_theme . '/index.php';
}

   if (file_exists($theme_location)){
      include($theme_location);
   }else{
      include sculpt_system("default_look");
   }


?>