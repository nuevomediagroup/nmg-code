=== TYPO3 Importer ===
Contributors: comprock
Donate link: http://typo3vagabond.com/about-typo3-vagabond/donate/
Tags: typo3, importer
Requires at least: 3.0.0
Tested up to: 3.3
Stable tag: 2.0.2

Easily import thousands of tt_news and tx_comments from TYPO3 into WordPress.

== Description ==
TYPO3 Importer brings your TYPO3 news, related media and comments into WordPress with minimal fuss. You can be as selective or open as you'd like for selecting which tt_news records to grab. Import can be interrupted and restarted later on.

Inline and related images will be added to the Media Library. The first image found is optionally set as the Featured Image for the post. Inline images will have their source URLs updated. If there's more than one related image, the [gallery] shortcode is optionally inserted into the post.

* Requires remote web and database access to the source TYPO3 instance.
* Comments will be tested for spam via Askimet if you have Askimet configured.
* Files and links will be appended to post content with optional shortcode wrappers, like [member]|[/member].  
* Post status override is possible, but hidden posts, will be set as Drafts.
* Opps and Restore options provide quick clean up and hiding of imports.

= TYPO3 Importer Options =
**TYPO3 Access**

* Website URL
* Database Host
* Database Name
* Database Username
* Database Password

**News Selection**

* News WHERE Clause
* News ORDER Clause
* News to Import
* Skip Importing News

**Import Options**

* Default Author
* Protected Post Password
* Override Post Status as...?
	* No Change
	* Draft
	* Publish
	* Pending
	* Future
	* Private
* Insert More Link?
	* No
	* After 1st paragraph
	* After 2nd paragraph
	* After 3rd paragraph
	* After 4th paragraph
	* After 5th paragraph
	* After 6th paragraph
	* After 7th paragraph
	* After 8th paragraph
	* After 9th paragraph
	* After 10th paragraph
* Set Featured Image?
* Insert Gallery Shortcode?
	* No
	* After 1st paragraph
	* After 2nd paragraph
	* After 3rd paragraph
	* After 4th paragraph
	* After 5th paragraph
	* After 6th paragraph
	* After 7th paragraph
	* After 8th paragraph
	* After 9th paragraph
	* After 10th paragraph
	* After content'
* Related Files Header
* Related Files Header Tag
	* None
	* H1
	* H2
	* H3
	* H4
	* H5
	* H6'
* Related Files Wrap
* Related Links Header
* Related Links Header Tag
	* None
	* H1
	* H2
	* H3
	* H4
	* H5
	* H6
* Related Links Wrap
* Approve Non-spam Comments?

**Testing Options**

* Don't Import Comments
* Don't Import Media
* Import Limit
* Debug Mode

**Oops**

* Convert Imported Posts to Private, NOW!

**Reset/Restore**

* Delete...
	* Prior imports
	* Imported comments
	* Unattached media
* Reset plugin

== Installation ==
1. Upload the `typo3-importer` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set TYPO3 access via Settings > TYPO3 Import Options
1. Import via Tools > TYPO3 Importer

== Frequently Asked Questions ==
= Can I sponsor importing TYPO3 pages? =
Yes. Any sponsoring would be greatly welcome. Please [donate](http://typo3vagabond.com/about-typo3-vagabond/donate/ "Help sponsor TYPO3 Importer") and let me know what's wanted

== Screenshots ==
1. Where to find TYPO3 Importer in Tools
2. TYPO3 Importer settings
3. TYPO3 news entries being imported

== Changelog ==
= trunk =
* screen-meta-links endif fix
* Make okay for WordPress 3.3
-

= 2.0.2 =
* Fix Settings reset
* Update languages

= 2.0.1 =
* Installation directions update
* Revise readme description
* Update TODOs
* Import meta keywords and descriptions for All in One SEO, Bizz themes, Headspace2, Thesis and Yoast's WordPress SEO
* Update Options > Settings verbiage
* Update TYPO3 Importer verbiage
* Set default author
* Enable debug mode to handle news_to_import directly for testing purposes 
* Ignore file:// sources, they're none existant except on original computer
* Apply display none to images with file:// based source
* Update text domain and language files

= 2.0.0 =
* Remove TYPO3 tx_comments approved requirement
* Add askimet_spam_checker to comment importing
* Position gallery shortcode in post content
* Position more links in post content
* Disallow single image galleries
* Migrate importing to one-at-a-time model
* Separate import and option screens
* Configure related files and links header text, tag and wrapper
* Enable custom news WHERE & ORDER clause
* Enable specific news uid import/skip
* Require TYPO3 access fields
* Check that TYPO3 site exists on Website URL
* Import related comments during each news import
* Remove comment threading since TYPO3 didn't support it
* Update screenshots
* Create users with emails based upon author name and domain if no email given
* Don't create users for authors with no email or name
* Create top right meta links between options and import screens
* Make best attempts to not duplicate authors as users
* Set keywords and descriptions for All in One SEO Pack

= 1.0.2 =
* Update description

= 1.0.1 =
* Update Changelog

= 1.0.0 =
* Update TYPO3 Importer settings screenshot
* update CHANGELOG
* Add force_private_posts(), Great for when you accidentially push imports live, but didn't mean to;
* Remove excess options labels
* fix options saving
* Force post status save as option; Select draft, publish and future statuses from news import; Set input defaults;
* Clarify plugin description; Add datetime to custom data; remove user_nicename as it prevents authors URLs from working;
* remove testing case
* prevent conflicting usernames
* update Peimic.com plugin URL

= 0.1.1 =
* set featured image from content source or related images
* seperate news/comment batch limits
* CamelCase to under_score
* rename batch limit var
* lower batch limit further, serious hang 10 when doing live imports
* lower batch limit due to seeming to hang
* correct plugin url
* revise recognition
* Validate readme.txt
* Inital import of "languages" directory
* add license; enable l18n

= 0.1.0 =
* Initial release

== Upgrade Notice ==
* None

== TODOs ==
* Convert related links like Getting the Most Out of Your LMS: http://www.2elearning.com/markets/executive-suite/top-stories/top-stories-item/article/getting-the-most-from-your-lms.html to <a href=http://www.2elearning.com/markets/executive-suite/top-stories/top-stories-item/article/getting-the-most-from-your-lms.html">Getting the Most Out of Your LMS</a>
