## Functions

These functions can be used both in themes and plugins.

**sculpt_system(_system property_)**
:   Prints out a Sculpt system property. Valid Property options are: `path, content_path, default_look, default_error_path, themes_path, pages_path, error_path, plugin_path`

**site_title()**
:   Prints out the website title.

**site_url()**
:   Prints out the website url.

**site_tagline()**
:   Prints out the site tagline, if one is set.

**sculpt_page_url(_page name_)**
:   Get the URL for the provided page name. This takes into account URL rewriting, and will use the pages registered address if the page is registered.

**page_title()**
:   Prints out the page name.

**theme_location()**
:   Prints out the location of the current theme.

**page_is_registered(_page name_)**
:   Returns `true` if a page is registered. Allows you to check if a page is registered before referencing it.

**register_page(_page name_, _page location_)**
:   Register a special page. This lets you redirect a page name to any location you specify. This can be used when you want your navigation, or the address in the address bar to say one thing, but use something else. For example, having 'Welcome' in your nav bar send you your home page. These pages can be referenced directly in a plugin or theme using the function `sculpt_special_link();`

**is_text_engine_registered(_engine name_)**
:   Returns `true` if a text engine is registered. Allows you to check if a text engine is registered before referencing it.

**register_text_engine(_text engine name_, _text engine location_)**
:   Registers a PHP file as a text engine. This allows you to define a page to be rendered as you choose, without the involvement of the default framework.

**show_error(_error name/number_)**
:   Displays an error with _error name/number_. If there is a custom error for the error number in the errors folder, that error content will be displayed.

**cache_data(_data_, Optional: _key_)**
:   Caches the data in the variable you provide. Key is optional. If no key is provided, it returns a unique identifier as the key that you can store and reference again when needed.

**cache_retrieve(_key_)**
:   Returns the data stored with the provided key if it exists. If the data does not exist, it will return NULL.

