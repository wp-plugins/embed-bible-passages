=== Embed Bible Passages ===
Contributors: drmikegreen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XR9J849YUCJ3A
Tags: inline Bible passages, shortcode, Bible, Book of Common Prayer, Lectionary, daily readings, ESV Bible Web Service
Requires at least: 2.7
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides the ability to embed Bible Readings into a post or page using shortcode.

== Description ==

Provides the ability to embed Bible readings from the ESV Bible Web Service (http://www.esvapi.org/api#readingPlanQuery) into a post or page using shortcode of the form [embed_bible_passage reading_plan='bcp']. See the screenshots for an example of how to use this tag.

The values of reading_plan can be:
    bcp						- Book of Common Prayer
    lsb						- Literary Study Bible
    esv-study-bible			- ESV Study Bible
    every-day-in-the-word	- Every Day in the Word
    one-year-tract			- M'Cheyne One-Year Reading Plan
    outreach				- Outreach
    outreach-nt				- Outreach New Testament
    through-the-bible		- Through the Bible in a Year
The default reading plan is bcp.

Note that only the bcp and through-the-bible options have been tested for this plugin. The other options are provided by the ESV Bible Web Service and should also work.

For more information about these reading plans, please see the ESVBible.org Devotions area (http://www.esvbible.org/search/?q=devotions).

The page opens with the plan reading for the current date. An optional date picker calendar is available to enable users to choose readings for other dates.

A tag to embed the current date is also available [embed_passage_date], although this is deprecated in favor of using the date picker calendar.

The readings are provided with a link to an audio file to enable users to listen to the readings. For iPad and iPhone this file is in mp3 format. For all other systems it is in the ESV Bible Web Service API default Flash format.

Copyright 2011-2013 M.D. Green, SaeSolved:: LLC

== License ==

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

== Installation ==

1. Apply for an access code at http://www.esvapi.org/signup.

1. Extract the embed-bible-passages folder and place in the wp-content/plugins folder. Enable the plugin.

1. Enter your access code at your site's WordPress Dashboard under Settings -> Embed Bible Passages.

1. Select whether or not to provide the ability for users to select passages for days other than the current day by clicking on a calendar.

1. Select whether or not to optionally show a "Powered by" attribution at the bottom of pages.

1. Create pages and/or posts containing the shortcode of the form [embed_bible_passage reading_plan='bcp'].

NOTE THAT THE COPYRIGHT NOTICE FROM THE SOURCE OF THE TEXT CROSSWAY BIBLE MUST BE KEPT ON THE PAGE (protected variable $esv_copyright).

== Frequently Asked Questions ==

= Where can I see working examples of this plugin? =

http://resurrectionstl.org/prayer-2/daily-lectionary/

http://www.thebiblechallenge.org/

== Screenshots ==

1. Sample input for page of Through the Bible in a Year.

2. Sample result for page of Through the Bible in a Year.

3. Settings page.

== Upgrade Notice ==

= 1.1 =
This version changes loading readings from jQuery.load() to the WordPress AJAX system (http://codex.wordpress.org/AJAX_in_Plugins).

= 1.0 =
This version cleans up version contol issues in order to bring the plugin to version 1.0 status.

= 0.71 =
This version corrects committment of revision to repository in order to include new file created for version 0.7.

= 0.7 =
This version implements:

1. IMPORTANT CHANGE: A correction to the shortcode naming system: The shortcode is now [embed_bible_passage reading_plan='bcp']. (reading-plan has been changed to reading_plan. See http://codex.wordpress.org/Shortcode_API.)

1. The ability to use other than the default reading plan.

1. An optional ability for users to select passages for days other than the current day by clicking on a calendar.

1. An mp3 audio file is used for iPhone and iPad, rather than the default Flash audio file.

= 0.211 =
This version improves the documentation and corrects the link in the "Powered by" attribution at the bottom of pages.

= 0.21 =
This version adds the ability to optionally show a "Powered by" attribution at the bottom of pages.

== Changelog ==

= 1.1 =
Change loading readings from jQuery.load() to the WordPress AJAX system (http://codex.wordpress.org/AJAX_in_Plugins).

= 1.0 =
Cleanup to bring to version 1.0 status.

= 0.71 =
Correction to committment of revision to repository in order to include new file created for version 0.7.

= 0.7 =
1. IMPORTANT CHANGE: A correction to the shortcode naming system: The shortcode is now [embed_bible_passage reading_plan='bcp']. (reading-plan has been changed to reading_plan. See http://codex.wordpress.org/Shortcode_API.)

1. The ability to use other than the default reading plan.

1. An optional ability for users to select passages for days other than the current day by clicking on a calendar.

1. An mp3 audio file is used for iPhone and iPad, rather than the default Flash audio file.

= 0.211 =
Improves the documentation and corrects the link in the "Powered by" attribution at the bottom of pages.

= 0.21 =
Adds the ability to optionally show a "Powered by" attribution at the bottom of pages.
