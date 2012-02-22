=== WP Multilingual Sitemap ===
Contributors: adiaz
Donate link: http://code.google.com/p/wp-multilingual-sitemap/
Tags: sitemap, posts, pages, categories, shortcode, wpml, translation, accesibility, html, html5
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 0.1

Allows creating complete multilingual sitemaps of your entire blog.

== Description ==
**WP Multilingual Sitemap is a highly customizable WordPress plugin that allows displaying, in posts and pages, an HTML sitemap of: pages, posts and posts ordered by categories.**	
	
= CMS Features =
 
 * Sitemap settings are set through a **shortcode** added in a post or page
 * Support for pages, posts and custom posts 
 * Support for native WordPress functions parameters
 * No data added to the database

= Multilingual Features =
 * **WPML translations fully compatible**
 * Display sitemaps in different languages without changing the shortcodes
 * Built-in plugin localization without .mo files

= Accesibility Features =
 * Level Triple-A Conformance to Web Content Accessibility Guidelines 1.0
 * HTML5 validation

== Examples ==
Here you can find some examples of use:
= Sitemap Pages (`[wpms-pages]`) =

* Display pages with a depth limit of 2 and exclude page ID 25

	`[wpms-pages depth=2 exclude=25]`

* Display pages with only children and grandchildren of the current page

	`[wpms-pages child_of=CURRENT]`

* Display pages with the page modified date and pages sorted by the menu order number.

	`[wpms-pages show_date=modified sort_column=menu_order]`

= Sitemap Posts ([wpms-posts]) =

* Display 3 posts from a category with ID 50 ordered by title

	`[wpms-posts category=50 numberposts=3 orderby=title]`

* Display all private custom 'movie' posts with the list title "Movies"

	`[wpms-posts post_type=movie post_status=private title_li=Movies]`

* Display posts in all languages (WPML)

	`[wpms-posts suppress_filters=1]`

= Sitemap Posts by Categories ([wpms-categories-posts]) =

* Display posts of just 5 categories

	`[wpms-categories-posts number=5]`

* Display posts of only top categories

	`[wpms-categories-posts depth=1]`

* Display posts of categories whose parent's category ID is 40

	`[wpms-categories-posts child_of=40]`

== Available Parameters ==

= Codex =

* [Template Documentation for the `wp_list_pages` function](http://codex.wordpress.org/Function_Reference/wp_list_pages): use this with `[wpms-pages]` shortcode
* [Template Documentation for the `get_posts` function](http://codex.wordpress.org/Template_Tags/get_posts): use this with `[wpms-posts]` and `[wpms-categories-posts]` shortcodes
* [Template Documentation for the `wp_list_categories` function](http://codex.wordpress.org/Template_Tags/wp_list_categories): use this with `[wpms-categories-posts]` shortcode

= Custom =

In addition, for the `[wpms-posts]` shortcode, you can set another two params:

* _**'title_li'**_: (string) the title and style of the outer list item. Defaults to "Posts". If empty, the title will be not displayed.
* _**'style'**_: style to display the categories list. The value 'list' displays the categories as list items while empty value generates no special display method (the list items are separated by `<br>` tags). The default value is list (creates list items for an unordered list). 

For the latest information visit the website: [http://code.google.com/p/wp-multilingual-sitemap/](http://code.google.com/p/wp-multilingual-sitemap/ "Wordpress Multilingual Sitemap")

== Frequently Asked Questions ==

**There are no questions yet.**
 
 
== Installation ==
1. Upload the entire directory from the downloaded zip file into the /wp-content/plugins/ folder.
2. Activate the "WordPress Multilingual Sitemap" plugin through the 'Plugins' menu in WordPress.
3. Add the shortcode(s) to the page(s) / post(s) of your choice.
		
		
== Screenshots ==
1. Shortcodes in the editor (WPML not installed)
2. Plugin running in the Twentyten WordPress theme (WPML not installed)
3. Shortcodes in the editor - English (WPML installed)
4. Plugin running in the Twentyten WordPress theme - English (WPML installed)
5. Shortcodes in the editor - Galician (WPML installed)
6. Plugin running in the Twentyten WordPress theme - Galician (WPML installed)


== Upgrade Notice ==

This plugin requires WordPress 2.8 or higher. If you use WordPress 2.7 or below, you will need to upgrade WordPress.

	
== Changelog ==

Release Notes for WordPress Multilingual Sitemap
-------------------------------------------

= v0.1  (2010-09-20) =

	[+] Initial release of WordPress Multilingual Sitemap plugin.

	
== Follow us on Twitter ==

[http://twitter.com/alvarodp](http://twitter.com/alvarodp)