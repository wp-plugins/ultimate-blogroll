=== Plugin Name ===
Contributors: jensg
Donate link: http://ultimateblogroll.gheerardyn.be
Tags: blogroll, links, link, link manager, manager, linkpartner, exchange, repricoral, backlink, partner, manage, counter, admin, seo, receive Links, exchange links
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 2.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable your visitors to submit a linktrade and keep track of the in and outlinks. It is the ultimate link manager available.

== Description ==

Ultimate Blogroll is a plugin which enables your visitors to submit a linktrade. Your visitors can add their own website and you can keep track of the in- and outlinks.

    * Let your visitors add a linktrade
    * Give an overview of the linktrades including the stats
    * Order by different parameters
    * Easily approve or discard a new linktrade
    * Check for a reciprocal link
    * Prevent spam with Recaptcha
    * Import/export links into/from Wordpress
    * Handy Wizard/Installation process to get you started
    * ...

Now available in: English, Dutch, Spanish, Hungarian, Russian, Norwegian

Credits:<br />
Spanish translation: Dennis Vera (http://tuguiaweb.net)<br />
Hungarian translation: Nora Erdelyi (http://locoling-club.com)<br />
Russian translation: Nickolay Avdeev<br />
Norwegian translation: Rune Kristoffersen (http://www.sportsmarkedet.com )

== Installation ==
There is a wizard available which will guide you thought the installation process.

== Frequently Asked Questions ==

= Can I import my wordpress linkpartners =

Sure, you can import your linkpartners. If you don't like Ultimate Blogroll you can even export them back to wordpress.

= Are the statics in realtime? =

No, they are calculated once every hour. This plugin is designed for large sites with a huge amount of visitors.

== Screenshots ==
1. Add linkpartner
2. Overview
3. Settings
4. Import/export linkpartners
5. Wizard
6. Widget controller

== Changelog ==
= 2.5.2 =
* Fixed a name collision (admin_menu)
* Added 'Errors:' to the po localization file

= 2.5.1 =
* Turned off error reporting

= 2.5 =
* Fixed bugs, credits to: syndrael for reporting

= 2.4.5 =
* Fixed blank page

= 2.4.4 =
* Improved importing old settings to determine which page is the Ultimate Blogroll page

= 2.4.3 =
* Removed namespaces, since this is php 5.3 and apparently a lot of people don't update their php and are using an unsecured server
* Fixed a bug for editing linkpartners inside admin panel

= 2.4.2 =
* Fixed addlink in widget
* Added namespaces to prevent name conflicts

= 2.4.1.1 =
* Restyled button in wizard

= 2.4 =
* Fixed Bugs: addlink, reCAPTCHA, Missing argument 2 for wpdb::prepare()
* Fixed styling issues: buttons look fine in the new Wordpress style

= 2.3 =
* Fixed "Fatal error: Call to undefined method Page::sendMail()" problem
* Added the Norwegian language

= 2.2.4 =
* Fixed stylesheet problem

= 2.2 =
* Fixed problems since 2.1 caused due to SVN not playing nice with GIT

= 2.1 =
* Added Italian and Bahasa Indonesia
* Fixed The function "Target: _blank" does not open a new window.
* Fixed Hiding website logo
* Fixed Sorting
* Moved assets into gui/assets

= 2.0 =
* <!--ultimate blogroll--> has been replaced by another system
* code has been redesigned and refactored
* gui rebuild
* bugs fixed

= 1.8.2 =
* Added curl support, thanks to Rob of abcblogcast
* Fixed bug checking for external links, there is no need in receiving a warning (ports not open) if you don't need the feature
* Curl support should fix the "Could not check for reciprocal website. Check if ports are open." warning

= 1.8.1 =
* Updated magic methods to be compatible with php 5.3
* Fixed some minor bugs

= 1.8 =
* Added russia
* Performance boost
* Added options for website image(logo)
* Fixed some minor bugs

= 1.7.6 =
* Added the hungarian language
* Fixed recaptcha bug
* Fixed error in admin panel, when you add a linkpartner manually
* Fixed some minor bugs

= 1.7.5 =
* Fixed some display issues (<?= <?php echo)
* Added the Spanish language
* Fixed linktrades did not save although successful message was shown
* Fixed some minor bugs

= 1.7.2 =
* Fixed No such file gui/recaptchalib.php

= 1.7.1 =
* Fixed widget title
* Enabled choice to require reciprocal link

= 1.7 =
* Fixed some conflicts with other plugins
* Relative paths changed into absolute path
* New way of saving settings

= 1.6.5 =
* Fixed Call to a member function UpdatePermalink() issue

= 1.6.4 =
* Fixed Recaptcha library issue

= 1.6.3 =
* Added the possibility to add images
* Fixed a bug in the wordpress import function

= 1.6.2 =
* Fixed some annoying minor bugs

= 1.6.1 =
* Minor bugfix in url (email)

= 1.6 =
* Import/export linktrades from/into Wordpress

= 1.5 =
* Multi-language
* Added a wizard/installation process
* Fixed some minor bugs
* Changed internal structure

= 1.0 =
* First version build, I am currently awaiting feedback

== Upgrade Notice ==
= 2.5.2 =
* Fixed a name collision (admin_menu)
* Added 'Errors:' to the po localization file