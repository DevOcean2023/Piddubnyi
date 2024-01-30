=== Parent Theme ===
Requires PHP: 8.0.0
Requires at least: WordPress 6.2.0

== Description ==
The basis for the development of themes in the company DOV. Contains functions that simplify development and
help to comply with development standards.

= Required Plugins =
Advanced Custom Fields PRO
Classic Editor

= Recommended Plugins =
Gravity Forms
Intuitive Custom Post Order
Regenerate Thumbnails
WordPress Importer
WP Retina 2x
Yoast Duplicate Post
Yoast SEO

= Recommended Local Plugins =
Query Monitor plugin for WordPress - almost certainly helps to avoid mistakes and understand what is happening
Rewrite Rules Inspector - sometimes can be very useful
WordPress Exporter - you can export individual pages made locally
User Switching plugin for WordPress - you can switch to any user

= Instructions =
# Functionality
This theme does not need to be modified directly, you need to create a child theme and work in it.

You can override all functions in the functions.php file of the child theme or if there is a lot of code
then break it into files, and put them in the inc directory and connect in functions.php.
You can override the classes by putting them in the inc folder, the classes in this folder will be loaded
automatically in the function.php of the parent (this) theme.

Everything related to the child theme needs to be written to the theme-functions.php file at this moment
loaded all the functionality of the parent theme.

Once again, the functions and classes of the parent theme are not available in the functions.php of the child theme,
so we use theme-functions.php, but if you need to redefine something in
parent use functions.php.

# Flexible Content
We adhere to the block model, that is,
everything on the page between the header and the basement is divided into separate blocks.
Blocks should be clearly separated in layout. For each block, you will need to create a template file,
Group Field for him and will use the Flexible Content template.
The Flexible Content template selects from all Group Fields those with a header starting with "FC:"
collects from them one field of type Flexible Content.

That is, you need to create a Group Field with a heading like "FC: Text & Image",
and create all the fields for the block. The block template itself will then be called "text-image.php" and
should be in the templates / flexible-content folder.

For more details on how a block template should be formed, see templates / flexible-content / -demo.php
In the same folder there are several examples of templates, Groups Field for them can be viewed in the data folder.

# Header, footer and general settings
For global settings, there is a settings page, in which tabs are divided into logical blocks.
As a rule, everything fits there. settings are usually called dov_ {tab_name} _ {settings_name}
But if there are a lot of settings then create a subpage.
Examples of caps and basements, with useful functions can be found in this thread.

# Blog
All sites have a blog, although sometimes it may be called in some other way "News".
In general, this is posts_page, it needs to be configured. For its display,
and for all other archives, the archive.php template is responsible
As a rule, you do not need to make changes to it, if there is no design for it, it is simply styled by a typesetter.

# Not a Page
This is a template for a page that serves to combine subpages, but it is not available,
it is redirected to the first child.

# Formatting
In general, you need to adhere to WP standards
https://make.wordpress.org/core/handbook/best-practices/coding-standards/
To do this, use:
- editorconfig - a little help with formatting
- eslint-config-wordpress - will prompt in JS
	- https://github.com/WordPress-Coding-Standards/eslint-config-wordpress
- phpcs.xml - very much needed, will not let you deviate from the standard
	- https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
	- https://kellenmace.com/set-up-php-codesniffer-in-phpstorm-with-wordpress-coding-standards/
- PHPStorm-WP-DOV.xml Code Style settings for PHPStorm

# Last but important, if you make changes, please describe in Changelog what has changed and change the version.
If something is fixed a little, change the last digit.
If you added something or fixed a bug, change the second one.
Well, if something breaks compatibility, then change the first one.
Then everyone will know what has changed.

# Block Naming
Blocks should be tried not to be named according to content, for example, our works or about us,
but according to structural elements.
For example, if there is textual content and an image on the side, then you can name the "Text & Image" file
text-image.php, but not always possible, then according to the content.

If the block consists of separate repeating elements, then it is better to call it blocks- and some
modifier, if these are links to internal pages, then the "Blocks: Pages" file is blocks-pages.php or
for example, there are company employees then the "Blocks: Team" file blocks-team.php.
If a slider is present in the block, then it is better to add a slider to the name, for example, "Testimonials Slider
file testimonials-slider.php
There are also well-established names "Banner", "Title Page", "Logos", "Gallery"

Modifications (field "Group Buttons")
Used to change the block, no need to cling to the page class, you always need to start from
block modifiers. If the layout designer is hooked on the page class, ask for a modifier.

# Relations
It is best to add true_false "All", and if false, display the relationship field in the admin panel.
This design is not suitable for all types, well suited for reviews, teams, but for example for pages
it’s better to create a repeater with the necessary fields, and not rely on the fields of the page itself. One side
It’s good when you can select a post and display its fields, but as practice shows, often at different
pages use different descriptions, pictures, etc. in the end it turns out you need to create fields for
type for different options and with confusing names. In general, if you are sure that the blocks will have
everywhere the same information do relationship, if not repeater

#Footer
Often in front of the footer there is one, two blocks that are repeated on all pages. Once upon a time we had
the standard is that the blocks in the basement should be editable for each page,
but now it seems we are moving away from this.
There are three approaches. To do just blocks, this will allow the use of such blocks anywhere, with
different contents or not to use at all, but you have to configure on each page. Or make
theme settings and pull from there, plus you need to add a switch so that you can turn it off
for a specific page. And the third combined create a block and create a default content for it
in the theme settings. In general, ask Natasha until there is a clearer standard :)
Archives & single
You need to look at the design if it is not block or contains a couple of blocks (header + content),
then it’s better to just create templates for custom types and fields for them. And if the design is block,
moreover, blocks with regular pages, then you need to connect the flex template to the template files and
specify these custom in its settings types.

# Breadcrumbs
Yoast SEO Plugin

# Images
The whole image must be customizable, all these are backgrounds, icons, just pictures, SVG I don’t even know what
yet, in general, everything. And all need to specify the size. But of course there are exceptions, but very rarely.
Indicate the size depending on the situation, focusing on layout,
usually need to be limited in width, but in repeating blocks it is better
set width + height + cover, and in the slider of small logos and icons width + height + FALSE.
For backgrounds, the default size is 1920x0, as a rule, for large blocks it can not be changed, but sometimes it can
you will need to limit the size, but this is rare and you need to look at the situation.

== Changelog ==

= 7.5.3 (2023-12-06)
* Temporarily rolled back HTML minification.
* Fixed php notice for image without sizes.

= 7.5.2 (2023-12-06)
* Added dov_get_svg and dov_the_svg functions.
* Added more flexibility in changing theme initialization. The main method is divided into several small methods, filters have been added to filter the initialized classes "the_init_classes" and "the_init_acf_classes".
* Added is feed conditional method, and updated is frontend conditional method.
* Added minify scripts.
* Added beautify html.
* Added "theme_security_headers" filter.
* Fixed duplicate defer attribute.
* Fixed class parameter for dov_the image field.
* Fixed typo.
* Move woo hook from functions.php to wc-functions.php.
* Move helpers script after head meta.

= 7.5.1 (2023-12-04)
* Added dov_get_style_max_inline_index filter.
* Added DOV_Dequeue_Scripts.
* Updated by line text.
* Updated visible page additional data.
* Updated privacy policy page.
* Updated delay and defer scripts.
* Fixed incorrect srcset for background.
* Fixed PHP notice in styles class.
* Fixed blog share image size attributes.
* Fixed blog share title level.
* Fixed page additional data for IOS.
* Fixed site map page.
* Fixed problem in fractional image sizes.
* Fixed enqueue woocommerce.js.
* Fixed class-dov-bam-content-base.php exclude "btn" class from adding content classes.
* Fixed menu cart scroll after close.
* Fixed menu cart close after added to cart.
* Fixed php notice for image without sizes.
* Change of priority in "class-dov-enqueue-styles-base.php".
* Set a permanent defer for jquery-core.
* Removed dependence on ACF.
* Removed check_size method.
* Removed old background methods get_bg_atts, get_attachment_image_x2_url and get_fit_bg_image.
* Deleted title attribute from wrap.
* Disabled defer scripts in admin panel.

= 7.5.0 (2023-10-30)
* Added [styles] shortcode.
* Fixed acf wpautop.
* Fixed GF Multi ids upload field.
* Fixed GF plupload js.
* Fixed lazy styles.
* Fixed menu. The {menu-name}__sub-items_level_(top|sub) classname has been added to the menu.
* Fixed defer js, inline scripts were not deferred correctly.
* Fixed by, added BAM class names.
* Added rel="noopener" in all target blank links.
* Removed hooks.php.
* Removed GTM dependence on ACF.
* Disabled the Not a page class if ACF is disabled, since it depends on it.
* Restored the deletion of paragraphs in the editor before calling wpautop.
* Moved all ACF-related filters to the ACF class.
* Moved the tiny settings to the tiny class.

= 7.4.0 (2023-10-17)
* Added dov_get_attrs.
* Added defer in defer-dependent scripts.
* Added new DOV Images.
* Added is rest request.
* Added additional-data logic (content-visibility). Added scripts attributed method.
* Added enqueue file scripts and styles method.
* Added lazy load styles.
* Updated CSP, added ignore and dev properties, added clear log link.
* Update logo for picture.
* Update GTM.
* Updated Woo templates for last version.
* Updated is frontend, excluded rest request.
* Updated Woo inline script, moved to woocommerce.
* Updated CSP, added clear log link.
* Updated Referrer-Policy value to pass the frog test.
* Fixed X-Frame-Options for GF.
* Fixed DOV KSES.
* Fixed file connection priority after the after_setup_theme hook.
* Fixed social links image size.
* Fixed preview for posts.
* Fixed tabindex in main.
* Fixed mobile menu ID attribute.
* Fixed dov_get_attrs do not output empty attributes that should not be empty.
* Fixed empty tag slash in textarea and title fields.
* Fixed empty tag slash for meta and link tags on head.
* Fixed CSP.
* Fixed ignores.
* Deleted leftover SVG sprite logic.
* Deleted old blog.
* Deleted old dependencies from parent theme.
* Deleted defer from accessibe script.
* Move js helpers after meta charset.
* Move main inline styles after meta charset.

= 7.3.3 (2023-09-01)
* Added loop index.
* Added loop index for attribute.
* Added value for group fields.
* Added Security Headers (Removed class for HSTS).
* Added all google domains in CSP.
* Updated code standards.
* Updated Woo templates for last version.
* Fixed srcset in picture source.
* Fixed contact link in wrap.

= 7.3.2 (2023-08-22)
* Fixed replace trailing slash.

= 7.3.1 (2023-08-21)
* Added inline SVG logic in dov_the();
* Added picture logic in dov_the();
* Added picture data in KSES.
* Fixed replace trailing slash.
* Fixed menu item BAM class name.
* Fixed PHP notice.
* Fixed logo img attributes.
* Move default templates in parent theme.
* Code review.

= 7.3.0 (2023-08-16)
* New Blog
* Updated tabs and faq.
* Added enqueue block scripts and modules methods.
* Auto enqueue block styles.
* Added exists method in filesystem.
* Move woo defer scripts in wc-functions.
* Fixed card error in Woocommerce Version 7.9.0.
* Updated default CSP rules.
* Added HSTS header.
* Added preload init.
* Fixed CSP rout args.
* Fixed CookieYes preload.
* Added Web Components in dependencies for template script.
* Added new tabs (Web Component).
* Map init by data attribute.
* All sliders init by data attribute. WooCommerce slider move to woocommerce.js.

= 7.2.7 (2023-07-13)
* Added Accessible for Breadcrumb.
* Update FAQ.
* Update CSP.
* Fixed blog share.
* Fixed blog title levels.
* Changed social links default image size.
* Return footer links.

= 7.2.6 (2023-07-10)
* Added and change menu classes.

= 7.2.5 (2023-07-06)
* New menus.

= 7.2.4 (2023-06-20)
* Added Content Security Policy class.
* Added get/post_request in DOV IS.
* Added new social links.
* Added beautify-html in MU DOV helpers.
* Added fetchpriority high to login.
* Added BAM classes in content.
* Added SVG in KSES.
* Added preload functional.
* Added dov_add_filter_once functions.
* Updated attrs logic in dov_the functions.
* Updated map classname.
* Update defaults pages.
* Updated gitignore.
* Updated BAM menu.
* Fixed wrap for contact link (target).
* Fixed remove image wrap p, if image before div.
* Fixed dev helpers condition.
* Fixed delay scripts function name.
* Fixed by link $args.
* Fixed delay scripts priority, add auto force and fixed dom ready.
* Fixed GTM priority and removed local script.
* Fixed wpautop priority for shortcode.
* Disabled jquery if no one depends on him.
* Disable anchor for GF.
* Deleted GDPR.
* Removed enqueue search results.
* Removed local script from AccessiBe.
* Removed widgets support.
* Removed wptexturize filters.
* Allow jQuery for GF opt.
* Setup wrap_line_length in HTML formatter.

= 7.2.3 (2023-05-03)
* Fixed repeated triggering of the script in the Delay class.

= 7.2.2 (2023-04-18)
* Added delay script by activity and view.
* Added juicer helper class.
* Fixed DOV KSES wrapper tags.

= 7.2.1 (2023-04-09)
* GA&GTM now load with themeUserActiveActionLoadScript.
* GA&GTM added JS names.
* GA&GTM remove test mode and not get fields on not production env.
* AccessiBe now load with themeUserActiveAction.
* Added file_name parameter in local script.
* Added themeUserActiveAction,themeLoadScript, themeUserActiveActionLoadScript
* Update default templates and styles.
* Automatically added support revision on CPT.
* Fixed enqueue search styles.
* Update fonts now use fonts-all.js.

= 7.2.0 (2023-04-01)
* All hooks except those that will be removed in the next version have been moved to classes.
* Fix GF load optimization for FlyWheel.
* Replace trailing slash in self-closing tags.
* Update default templates and styles.
* Disabled WP fatal error handler for non production site.

= 7.1.5 (2023-03-26)
* Added shipping total in Woo checkout.
* Changed Woo city field label.
* Added revisions in sample СPT.
* Update default templates and styles.

= 7.1.4 (2023-03-20)
* The Accessibility menu is now only enabled for the header and the links in the footer, disabled for footer main menu.
* Added $args parameter in dov_the_nav().
* Fixed woo address 2 field label visible.

= 7.1.3 (2023-03-13)
* Fixed SVG background image PHP notice.
* Fixed Woo SVG image PHP notice.
* Fixed BAM menu additional class name.
* Fixed empty wp_scripts->queue in enqueue timeout.
* Fixed jQuery name in admin bar.
* Fixed dov kses.
* Fixed format.
* Update default templates.

= 7.1.2 (2023-02-27)
* Fixed image empty srcset.
* Fixed site map block template.
* Added connection of styles for breadcrumbs.
* Added Skip to content link.
* Updated woocommerce templates.
* Improved escape output and added DOV_KSES class.
* Deprecated woocommerce is_ajax.

= 7.1.1 (2023-02-24)
* Fixed BEM nav menu __item classname.
* Fixed add defer in main.js.
* Fixed save only unique fonts in preload, since duplicates sometimes come to REST.
* Fixed font name regular expression as it doesn't work on some servers.
* Update .wpe-pull-ignore, added .tmp and .quarantine folder.

= 7.1.0 =
* Fixed GF multiple IDs for 2.7 version.
* Fixed REST API registration warning.
* Updated DOV GF Optimizations plugin.
* Rename breadcrumb html class name.
* Rename breadcrumb is helper method. (Can break, but hardly anyone used)
* Change get_environment is helper method visible.
* Removed DOV_Theme::put_file_contents in favor DOV_Filesystem::put_file_contents.
* Optimization enqueue delay scripts.
* Added page speed action for test theme only.
* Removed kint from profile and production.
* Added dequeue class.
* Update preload logo.
* Update preload fonts.
* Fixed local and preload js Accessibe plugin.
* Optimization autoload classes.
* Rewrite function hooks to class methods in enqueue scripts.

= 7.0.5 =
* Added DOV GF Optimizations plugin.
* Added ignore twentytwentythree.
* Added ignore local info files.
* DOV plugins are moved to a separate folder, and restructured to be more standards compliant.
* Removed DOV_GF_Load_Optimization from theme.
* Fixed php 8 for Code Snuffer standards.

= 7.0.4 =
* Fixed space in footer by.
* Fixed not a page redirect on search page.

= 7.0.3 =
* Fixed enqueue defaults styles on search page.

= 7.0.2 =
* Fixed saving ACf json files.

= 7.0.1 =
* Fixed an error in the console, when loading forms.

= 7.0.0 =
* New folder structure.
** The images, fonts, css and js moved to assets folder.
** Parts of templates moved to folder template-parts.
** The templates folder is only used for templates.
** Classes moved to the classes folder.
** The inc folder is used to include dependencies other than classes.
** JS and CSS are now configured in the inc/scripts-and-styles.php file.
* Removed languages folder, because it are outdated.
* Removed old demos.
* Removed deprecated functions.
* Removed cache wrappers.
* Removed permission to use short array syntax.
* Removed permission not to escape output.
* Added BAM menus.
* All wrappers have been removed from the header and footer templates, only the content remains.
* Changed text domain from parent-theme to theme.

= 6.0.0 =
* This version was a dead end and was maintained in a different repository.

= 5.9.0 =
* Added GF type field classnames.
* Added preload logo.
* Added GDRP optimisations.
* Added local extend scripts.
* Added delayed scripts.
* Added defer scripts.
* Added block styles.
* Added AccessiBe plugin optimisation.
* Removed the connection of a single style file.
* Added connection of a separate file for the blog.
* Local GA.
* Updated GF optimisations.
* Fixed GF multiple IDs redirect error.
* Added GF type field classnames.
* Updated admin bar.
* Fixed extra quotes in link header for fonts.

= 5.8.2 =
* Updated local fonts.

= 5.8.1 =
* Updated DOV By.

= 5.8.0 =
* Added DOV_Accessibility_Menu.
* Added $args parameter in dov_get_the_nav().

= 5.7.0 =
* Added sub themes supports.
* Added filter dov_get_disabled_widgets.

= 5.6.10 =
* Added local fonts.
* Woo Checkout blocks remove replace address checkbox, since without it, the work of switching the address is disrupted,
 we decided to hide it with styles.
* Woo Checkout blocks fixed self to static.
* Woo Checkout blocks get_formatted_address always returned the address from the account.
* dov_get_supported_video_embed_url fixed adding custom parameters after default parameters.
* Added an ID to GA scripts so that they are correctly loaded into when loading Gravity.
* Added DOV_WC_Group_Add_To_Cart_AJAX.
* Enqueue jQuery before all scripts in footer.
* Fixed favicon for https or http site url.
* Allow microsoft ico format for uploads.

= 5.6.9 =
* Change data for default GF notifications

= 5.6.8 =
* Fixed by

= 5.6.7 =
Fixed gf acf filed empty select option, replaced the constant check with a class, it seems more correct.

= 5.6.6 =
Fixed skipped a constant that was removed, replaced with development environment

= 5.6.5 =
Fixed auto title level if first title empty
Fixed admin bar padding for new WP version
Fixed GF optimization minimize scripts and output header over buffer
Fixed GF new version scripts enqueue
Removed WP styles variables
Admin bar is now always enabled for roles that support content editing
Changed header and footer from app
* Changes related to moving to WPEngine
Removed the installation, since now the site is created from a template
Disabled cache
Changed constants

= 5.6.4 =
Fixed DOV Fields if link is not array
Fixed Global Site Tag sprintf invalid count arguments
Fixed get_post_id_taking_into_preview for $_GET['page_id'] variable
Fixed set_editor_styles if Gutenberg enabled
Disabled check Gutenberg required for theme
Removed favicon from customizer
Changed assets url, get_theme_file_uri replaced by get_stylesheet_directory_uri

= 5.6.3 =
Fixed attributes for images, backgrounds and logo
Set jpeg quality 100

= 5.6.2 =
* Updated GF load optimization
* Fixed GF Multiple IDs
* Fixed social links

= 5.6.1 =
* Fixed GF Multiple IDs

= 5.6.0 =
* New By
* Logo loading eager for header
* Logo added sizes attribute
* jQuery from theme
* GF load optimization
* Fixed GF multiple ids
* Disabled WC block styles

= 5.5.12 =
* Revert acf-json in child theme
* Rename site-name folder to child-theme
* Fixed acf fields request before acf/init
* Fixed gravity forms in cache don`t include js
* Fixed vimeo video functions
* Added 1440 endpoint in backgrounds

= 5.5.11 =
* Removed wptexturize from acf content
* Removed classname field for Flexible Template
* Enabled title for social links in footer

= 5.5.10 =
* Fixed dov_get_the_excerpt remove the space if the number of circumcision fell on it
* Add post_password_required for Flexible Content template

= 5.5.9 =
* Removed the restriction on the location of the menu and the restriction on the depth of the menu

= 5.5.8 =
* Fixed nested dov_loop()

= 5.5.7 =
* Fix woo quantity allow_in_product_page

= 5.5.6 =
* Fixed dov_remove_filter_for_class
* Fixed Fatal PHP error if use get_post_permalink() with null $post
* Code review DOV_Tax

= 5.5.5 =
* Fixed found errors related to the Gravity Forms update.
* Fixed saving the "All" option for the relationship field.
* Fixed the display in the preview.
* Fixed incompatibility with WPML.
* Fixed admin bar print footer scripts.
* Updated the log.
* Updated the cache.
* Updated the font loader script.
* Removed inline SVG sprite support.
* Added an iframe to the allowed tags for KSES.
* Added theme.url JS variable.

= 5.5.4 =
* Added DOV_WC_Video_Thumbnail
* Added a size parameter for cover background
* Added We Accept card icons in cart
* Fix theme cache now clear after updated options, menus or ACF fields
* Fix DOV_Yoast_SEO_Score_Fix clear all buffers
* Fix Woo thank_you_background

= 5.5.3 =
* Fix DOV_Fix_GF_Multiple_IDs for GF init before jQuery in footer
* Fix twice QM empty image size error log
* Add js for new way fonts loader
* Performance improvements
* Remove Dashicon cache
* Remove wp_clean_themes_cache
* Experiment with partial theme caching

= 5.5.2 =
* Fix regenerate thumbnails with cover crop
* PHPCS exclude "Disallow Short Ternary" and "Disallow Short Array Syntax"

= 5.5.1 =
* Fix DOV_Yoast_SEO_Score_Fix. Due to the fact that the rating was performed first, there was no H1 on the pages

= 5.5.0 =
* Added Woo Default Helpers
* Added DOV_GA_GTM, now Google Analytics and Google Taf Manager can be added in the site settings, on the API tab
* Added the ability to display the cart amount in the menu link for the cart
* Redesigned DOV_ACF_Google_Maps_API to use a key in JS
* Removed duplicate ACF JSON, header & footer as they are in the child theme

= 5.4.8 =
* Added DOV_Yoast_SEO_Score_Fix
* Added DOV_Fix_GF_Multiple_IDs

= 5.4.7 =
* GTM field added
* Fix GF scripts to footer

= 5.4.6 =
* DOV_Importer & DOV_XLSX_CSV now saves the processed lines to a file, so as not to do it again if the data file has not changed
* DOV_Importer added log level "progress", this is an alias for "init", just in some cases it is more descriptive
* DOV_List_Table fixed filtering, now you can specify zero as a value for the filter
* Add dov_put_file_content function to write data correctly to a file

= 5.4.5 =
* Fix missing space between fields in search due to this word stuck together

= 5.4.4 =
* Fix loop now it will not print a wrapper if no fields are filled in the group
* Fix XMLReader open fix static method

= 5.4.3 =
* Fix title always returned HTML even when the title is empty

= 5.4.2 =
* Now SVG images are displayed inline and through a symbol to avoid duplicate IDs and other problems
* SVG dimensions are now calculated based on the viewBox if dimensions are not specified
* Fix social links title field - after updating, you need to re save the theme settings
* Applies shortcodes to the title field output
* Added dov_get_file_content function to get file content correctly

= 5.4.1 =
* Add DOV_GTM
* JQuery connected from google CDN to footer
* Gravity Forms scripts moved to footer
* Deleted ChromePhp
* Deleted PHPStorm-WP-DOV.xml
* Fixed extra quote in dov_wrap
* Recaptcha now does not allow submitting a form without JS
* Defaults to a more consistent look(dov_the, esc_attr_e...)
* All files are auto-formatted PHPStorm PHP WordPress Style

= 5.4.0 =
* Add background object fit
* Add dov_image_init_get_bg_fit_endpoints hook for change background object fit endpoints
* Add images sizes in admin select
* Add title h1 in blog archive
* Add post state for sitemap page
* Fix single post translators text

= 5.3.1 =
* Add init admin notice
* Add html5 full support
* Fix WP_Error log message
* Fix social link gaps
* Fix margin admin bar in mobile
* Fix display sub total price in menu cart
* Fix phpstorm notice

= 5.3.0 =
* Add dov_the_as & dov_get_as to get images from the background field and vice versa
* Add dov_has to check multiple fields
* Strip all tags in except
* Woo add new image size for menu cart
* Other small fix

= 5.2.0 =
* Add title level select. By default, it is disabled and enabled for SeoDog projects. You can disabled auto level for the filter "dov_acf_title_only_auto_level".
* Restore image sizes attributes for lazy load images.
* Set "thumbnail" background size in helper.js

= 5.1.2 =
* Fix set_video_url_in_full_gallery_size nullable error
* Add dov_importer_get_rows for change import rows
* Add support is_sub_loop for options

= 5.1.1 =
* Fix DOV pagination
* Fix vimeo video URL

= 5.1.0 =
* Add DOV_Fields class & functions for new template logic. See -new-demo.php
* ACF helper allow "on" in titles
* Add DOV_User_Avatar
* Add ACF Background Field
* Add ACF Relationship "All" checkbox
* Add ACF WYSIWYG Height field
* Update js helpers(default title & open add field, inactivate field for FC:, update types logic)
* Fix ACF Contact Link input type

= 5.0.5 =
* Add dov_remove_filter_for_class function
* Add & update woo classes
* Fix ACF Social Links Field - warning undefined index value

= 5.0.4 =
* Added the ability to specify the display fields by filter dov_flex_display
* Added SVG support
* Added title field in ACF Social Links field
* Added woocommerce started files
* Log 'shutdown' is hung on WP action 'shutdown' instead of register_shutdown_function
* Admin bar can now be minimized even if there are errors
* Fix QM errors in the admin bar
* Fix ACF Contact Link Field + remove icons
* Now all ACF pages are available, just hidden from the menu

= 5.0.3 =
* Added the ability to specify the block class by filter dov_flex_block_class
* Added the ability to specify the body class by filter dov_flex_body_class
* Added add_theme_support( 'html5' );

= 5.0.2 =
* New DOV_Log: identical messages are recorded once a day, PHP shutdown errors are logged, hash & prefix in filenames

= 5.0.1 =
* Remove pagination plugin, use dov_the_pagination()
* Extend & fix DOV_Importer
* Add DOV_Admin_Notices, DOV_List_Table

= 5.0.0 =
* ACF Local JSON
* Added more stringent typing
* New helpers added, but somewhat removed
* Changing the order of properties in DOV_ACF_Flex_Content::the_content()
* Remove DOV_ACF_Map_Zoom_Field, DOV_Schema
* Add DOV_Importer, DOV_Mail, DOV_XLSX_CSV
* Fix readme
* Fix import posts for install
* Fix .editorconfig
* Fix image crop size

= 4.0.3 =
* Add support shortcode in title and links
* Fix inspections, cleanup code

= 4.0.2 =
* Add replaced [] link title in DOV_ACF_Contact_Link_Field
* Add DOV_Log
* Disable DOV TAX default term
* Disable wp_fatal_error_handler

= 4.0.1 =
* Add seo title hook
* Added functionality for setting multiple replacements in a title field
* Fix dov_get_the_title
* Exclude JS files from phpcs

= 4.0.0 =
* New field types "Social Links" and "Copyright"
* New logic for author & seo links see
* Change function dov_the_socials_links for new field type
* Remove old social links json
* Refactoring helpers function, some function is deprecated see inc/deprecated.php
* Refactoring header & footer
* Replace "-" to space for labels in DOV_Tax & DOV_CPT
* Disable wpautop for editor
* Disable DOV_Schema if WPSEO plugin enabled
* Fix inspections, cleanup code

= 3.0.1 =
* Change All on SEO to WP SEO
* Disable forms field, if GF not activate
* Fix social link JSON
* Favicon allow all types
* Admin bar check can
* Fix map filter
* Add wpautop param in dov_get_copyright
* Fix remove ver in scripts and styles

= 3.0.0 =
* June 11, 2019
* Rename theme
* Rename global js variables
* Remove author
* Remove license
* Change textdomain & update translate files

= 2.0.2 =
* June 04, 2019
* Fix DOV_Tax default taxonomy labels
* Fix media links in DOV_Extend_WPLink
* Add default value $class in dov_the_titles functions
* Add typing

= 2.0.1 =
* May 22, 2019
* Fix DOV_Tax default taxonomy & labels
* Add a page to main query if rewrite rules have worked but have not found page(404).
* Fix name: dov_file_link_format >>> _hook_file_link_format

= 2.0.0 =
* May 21, 2019
* The default templates are changed and there is not yet a mechanism to support them from version to version.
  I decided that it is better if you need to update the theme and break the templates,
  then before that you can transfer them to the child theme
* dov_the_socials_links function has changed under the new standard and will break the layout in the old theme;
  when upgrading to this version, you can override the function in the child theme
* DOV_YouTube::get_image changed, you can now set the image before getting it from YouTube
* Add default text domain in phpcs.xml
* Fix ACF Map Zoom default zoom value
* Remove data-btn from Extend WP Link
* Refactoring
* Requires WordPress 5.2.0

= 1.1.1 =
* May 12, 2019
* Update ACF JSON: add footer logo, set phone type, new standards social links and favicon allow svg
* Fix replace wrappers in flex content
* Add thumbnails support in registration cpt
* Fix labels and add special labels arguments in registration cpt
* Add $attr in image output functions
* Update dov_the_socials_links for new standards
* Other small fix and format

= 1.1.0 =
* May 07, 2019
*
  !!! Attention !!!
  I took <main> from templates to the header and footer, and there may be problems during the update.
  This is done so that if you need to insert anything on all pages in <main>, you do not have to override templates.
  And updated default templates blog for the latest standard
  This may break default templates(archive, single, page, 404, search).
  To fix this you will need to copy the old default templates to a child theme.

* Fix version
* Add DOV_DISABLE_COMMENTS for enable comments menu
* Hide bar if not installed Query Monitor
* Check pages.xml and "Hello world!" before installation
* Activate QM during installation

= 1.0.9 =
* May 02, 2019
* Fix seodogs link
* Update header.php

= 1.0.8 =
* April 24, 2019
* Fix js undefined acf for extend link
* Fix replace prefix title acf group
* Change acf json

= 1.0.7 =
* April 4, 2019
* Load google map js if get map
* Add custom class to contact link

= 1.0.6 =
* March 29, 2019
* Fix admin output value acf form field
* Fix invisible reload error first render
* Fix notice in acf tools
* Format code for DOV_YouTube
* Change 2x bg logic, Fix default sizes, Use DOV_Images in helpers

= 1.0.5 =
* March 28, 2019
* Fix dov_flex_replace_wrappers filter

= 1.0.4 =
* March 26, 2019
* Fix sql by get forms field
* Fix DOV_Extend_WPLink return format
* Change prefix for hook functions(dov_ to _hook_). This for clear dov_ IDE code completion
* Add setting for install by Gravity and Retina Plugins
* Remove gform_confirmation_anchor filter
* Show admin bar in development and local sites
* Set Gravity Forms license key

= 1.0.3 =
* March 25, 2019
* Fix ACF Map Zoom JS
* Fix ACF replace flex-content wraps
* Add dov_the_title for output titles
* Add dov.pot for translate
* Moved .editorconfig to themes folder
* Exclude app folder from phpcs.xml

= 1.0.2 =
* March 19, 2019
* Fix ACF add helper title format
* Fix ACF export flex-content
* Fix ACF replace flex-content headers and wraps
* Fix login styles logo size
* Add link_color in login styles
* Add const DOV_SAMPLES for hide sample local json data

= 1.0.1 =
* Released: March 18, 2019

Initial release
