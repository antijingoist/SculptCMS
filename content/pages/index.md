## Sculpt CMS

Sculpt CMS is a flat-file CMS designed for straight-forward usage. There is no special markup, and no extra steps. Simply drop your markdown files into the `content/pages` directory to begin. The page title is the file title. 

Sculpt is compatible with Markdown Extra, and uses PHPFastCache, Parsedown, and ParsedownExtra.

### Settings
The Sculpt settings are kept in `system/settings.php`. There are a few options available: 

#### Site settings 
$website_name
:   Your Website's name

$website_tagline
:   A tagline for your website. This will show in themes that make use of one.

$website_url
:   The home URL of your website.

$website_theme
:   The name of the theme you want to use. Themes must be in your `extras/themes` directory. For example:
      ```
        $website_theme = "gwsk";
      ```

$email_address
:   If your theme makes use of this, your email address.

$enable_html_support
:   when set to true, will allow you to use both HTML and MD files for your site.


#### Cache Settings:
$clear_all_caches
:   Set to `true`, and refresh your site to clear all caches. Set to `false` to rebuild the cache.

$cache_time_days
:   Time, in days, to keep the cache.

### Plugins
Sculpt is very bare bones. Even the navigation is an optional plugin. You can enable plugins in the `extras/plugins/enabled.php` file. To enable the navigation plugin, for example, add the following line: 

    enable_plugin("navigation_bar");

### Blog Plugin
Every CMS needs a blog. To use the blog plugin with Sculpt, add the following line to the `extras/plugins/enabled.php` file: 

    enable_plugin("blog");

Blog pulls it's content from the `content/blog` directory. Files must be Markdown files. The Title of the Blog post is the first line of the Markdown file. The content is the rest. The files are named with the intended timestamp of publication, in the below format:

    YYYYMMDDHHMM.md
    [4 digit year][two digit month][two digit day][2 digit hours (24 hour time)][2 digit minutes].md

Blog will show your most recent post on the front page with a few recent titles, and a link to Archives. The archives are automatically generated and cached, using your settings.

Blog settings are kept in `extras/plugins/blog/blog_settings.php`. The supported settings are:

$blog_page_title
:  

$blog_directory
:   Set to the directory you keep your blog files in. Recommended that you do not change this. Default value is: 
    ```
    $blog_directory = sculpt_system("content_path") . 'blog';
    ```

$show_dates
:  

$date_format
:   Sets the date format for your blog posts. Default value is: 
    ```
    'l jS \of F Y h:i:s A'
    ```