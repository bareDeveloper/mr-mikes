=== Countdown Timer Ultimate Pro ===
Contributors: wponlinesupport, anoopranawat, pratik-jain
Tags: countdown timer, timer, timer countdown, countdown, event countdown timer, animated countdown timer, birthday countdown, clock, count down, countdown, countdown clock, countdown form, countdown generator, countdown system, countdown timer, countdown timer, date countdown, event countdown, flash countdown, html5 countdown, jQuery countdown, time counter, website countdown, wp countdown, wp countdown timer
Requires at least: 5.2
Tested up to: 6.1
Requires PHP: 5.4
Stable Tag: 2.2

A quick, easy way to add and display responsive Countdown timer on your website.

== Description ==
A very simple plugin to add countdown timer to your website. Countdown timer allow you to create nice and functional Countdown timer just in a few minutes. 
This is the best way to create beautiful Countdown for your users. You can use our Countdown timer in your posts/pages.
Integrate your WooCommerce and EDD Coupons with Countdown Timer Ultimate Pro and let the users to know when the coupon will Expire.

Check [DEMO](https://demo.essentialplugin.com/prodemo/countdown-timer-ultimate-pro/) for more details.

You can create multiple countdown timer and display them with shortcode. The easiest way to place your full customizable HTML5 Countdown Timer.

**This plugin contain one shortcode**
<code>[wpcdt-countdown id="1"]</code>
Where you can display timer.

= Shortcode Examples =

= Here is the shortcode example =
<code>[wpcdt-countdown id="1"]</code>

= Complete shortcodes with all parameters =
<code>[wpcdt-countdown id="1"]</code>
<code>[wpcdt_timer timer_id="123"]</code>
<code>[wpcdt_pre_text]Some text here.[/wpcdt_pre_text]</code>

* **ID:** [wpcdt-countdown id="1"] (Timer id for which you want to display timer. This parameter is required.)

= Template code is =
<code><?php echo do_shortcode('[wpcdt-countdown id="1"]'); ?></code>
<code><?php echo do_shortcode('[wpcdt_timer timer_id="123"]'); ?></code>

= Features of WordPress Countdown Timer Ultimate =
* Simple Timer Shortcode 
* Pre Text Timer Shortcode 
* Schedule Timer
* Evergreen Timer
* Recurring Timer (Daily, Weekly, Custom)
* Fully Responsive WordPress Countdown timer.
* Ability to create unlimited Countdowns timer.
* Ability to create Countdown in pages/posts.
* Template code.
* Ability to change background color and width.
* Ability to change rotating circle background color and width.
* Option to show/hide Days, hours, minutes and seconds.
* Option to set different background colors for Days, hours, minutes and seconds.
* Integrate with WoCommerce and EDD coupons.
* Elementor, Beaver and SiteOrigin, Divi, Fusion Page Builder Native Support.


== Installation ==

1. Upload the 'Countdown Timer Ultimate Pro' folder to the '/wp-content/plugins/' directory.
2. Activate the "Countdown Timer Ultimate" list plugin through the 'Plugins' menu in WordPress.
3. Add a new page and add desired short code in that.

== Changelog ==

= 2.2 (03, Nov 2022) =
* [*] New – Added revision support to countdown post type.
* [*] Update - Added nonce security to plugin reset setting.
* [*] Update - Use escaping functions for better security.
* [*] Update - Make known IFRAME like YouTube, Vimeo etc to responsive in completion text.
* [*] Update - Update latest license code files.
* [*] Check compatibility to WordPress version 6.1
* [*] Template File - completion-text.php file has been changed. If you have an override template file in your theme then verify with the latest copy.

= 2.1 (17, May 2022) =
* [*] Fix - Fixed an issue with Countdown Timer WooCommerce Product meta box.
* [*] Fix - Fixed 'Evergreen Timer' is not taking global Start & Expiry Date Time.

= 2.0 (29, March 2022) =
* [*] Update - Used esc_html function instead of _e() for better security.
* [*] Update - Update plugin license files.
* [*] Fix - Fixed an issue with 'Weekly Timer' when timer have a same "Week Start" and "Week End". Now it will take a week range.
* [*] Fix - Fixed 'Evergreen Timer' is not initializing when we change timer design.

= 1.9 (17, Nov 2021) =
* [*] Update - Taken care of data sanitization and escaping for better security.
* [*] Update - Update new website branding related changes in plugin. 
* [*] Update - Update latest license code files.
* [*] Update - JavaScript syntax for jQuery 3.0 and higher with compatibility to WordPress version 5.6.
* [*] Tweak - Code optimization and performance improvements.

= 1.8 (19, July 2021) =
* [*] Fix - Resolved countdown timer initialize issue when timer position is "Above Timer" and 'is_catch' parameter is set to true.
* [*] Fix - Digit color, Font color and Label color are not applying to Simple Timer design.
* [*] Template File - loop-start.php and loop-end template files have been updated. If you have an override template file then verify with the latest copy.
* [*] Check compatibility with WordPress 5.8.

= 1.7 (02, Jun 2021) =
* [+] New - Added Recurring Timer functionality. Like Daily, Weekly, Custom.

= 1.6 (13, May 2021) =
* [*] Fix - Resolve simple timer initialize issue.

= 1.5 (12, May 2021) =
* [*] Fix - Resolved page builders native support issue.

= 1.4 (07, May 2021) =
* [+] New - Added Timer start date functionality. Now user can schedule timer easily.
* [+] New - Added evergreen timer functionality.
* [+] New - Added a new shortcode for simple timer. Now user can display only timer where every they want like in popup, info bar and etc.
* [+] New - Added a new shortcode for pre text for timer.
* [+] New - Added Elementor page builder native support.
* [+] New - Added Beaver page builder native support.
* [+] New - Added SiteOrigin page builder native support.
* [+] New - Added Divi page builder native support.
* [+] New - Added Fusion page builder native support.
* [+] New - Timer with WooCommerce compatibility for single product page & shop page.
* [+] New - Timer with EDD (Easy Digital Download) compatibility for single product page.
* [+] New - Click to copy the shortcode.
* [-] Remove - Coupon functionality for WooCommerce and Easy Digital Download.
* [+] Update - Timer works perfectly when any cache plugin is active.
* [*] Update - License code for usage. Now user/agency can hide license page or license info from the page.
* [*] Update - Minor JS and CSS file updated.
* [*] Tweak - Code optimization and performance improvements.

= 1.3 (28, Nov 2018) =
* [+] New - Added Templating feature. Now you can override plugin design from your current theme!!
* [*] Fix - Fatal error on some country timezone.
* [*] Code Optimize.

= 1.2 (04, March 2018) =
* [+] Added new setting 'Timer Font Color'.
* [+] Added 'extra_class' shortcode parameter in plugin shortcode. Now you can add your extra class and use it for custom designing.
* [*] Resolved security vulnerability issue with timer Completion Text.
* [*] Resolved Time critical issue when user forcefully change machine time.
* [*] Updated 'Completion Text' text area with WordPress editor. Now you can design completion text as you want.
* [*] Updated 'Timer Background Color' to optional field.
* [*] Updated settings sanitize functions.
* [*] Enhanced plugin performance.
* [*] Fixed minor issues.

= 1.1 (06, Sep 2017) =
* [*] Resolved Time Zone critical issue.
* [*] Resolved 'Completion Text' does not display in 'Shadow Clock' after time finish.
* [+] Added Month and Year drop down in calendar so user easily navigate.
* [*] Fix 'Completion Text' and Clock disappear issue on page reload when time is finished.
* [*] Some bug fixes and improvement.

= 1.0.1 (29, March 2017) =
* [*] Resolved timer Time Zone issue. Now WordPress timezone will be used rather then machine time.
* [*] Updated plugin translation code. Now user can put plugin languages file in WordPress 'language' folder so plugin language file will not be loss while plugin update.

= 1.0 =
* Initial release.