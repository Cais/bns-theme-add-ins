=== BNS Theme Add-Ins ===
Contributors: cais
Donate link: http://buynowshop.com
Tags: admin, readme, changelog, child-themes
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 0.2.1

Extend the capabilities of WordPress Parent-Themes and Child-Themes.

== Description ==

A collection of functions and code that can be used to extend the capabilities of WordPress Parent-Themes and Child-Themes.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `bns-themes-add-ins` folder to the `/wp-content/plugins/` directory
2. Activate through the 'Plugins' menu.

-- or -

1. Go to 'Plugins' menu under your Dashboard
2. Click on the 'Add New' link
3. Search for "bns-theme-add-ins"
4. Install.
5. Activate through the 'Plugins' menu.

If you need more help, please read this article for further assistance: http://wpfirstaid.com/2009/12/plugin-installation/

== Frequently Asked Questions ==
Q: What does this plugin do?
A: There are currently several functions this plugin provides:

 * Add `BNS Extra Theme Headers`
 * Add `BNS Theme Support`
 * Add `BNS Readme Menu Item`
 * Add `BNS Changelog Menu Item`
 * Add `BNS Plugin TextDomain` and i18n (translation) support specifically for 'bns-theme-add-ins'

These functions make use of the (Child-)Theme name to preface menu items.

Q: Where can I get support for this plugin?
A: Feel free to visit the plugin home page: http://buynowshop.com/plugins/bns-theme-add-ins/ and leave a comment; or, feel free to use the Contact Us form here: http://BuyNowShop.com/contact-us/

Q: What is the best format for the text files to be written in?
A: Currently a standard text file is fine, although future versions may make use of 'markdown'. See this Wikipedia article for more information: http://en.wikipedia.org/wiki/Markdown. Also to note, ideally the WordPress 'markdown' parser will be used as well.

== Screenshots ==
* No screenshots currently available; you are welcome to make suggestions.

== Other Notes ==
* Copyright 2011  Edward Caissie  (email : edward.caissie@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2,
  as published by the Free Software Foundation.

  You may NOT assume that you can use any other version of the GPL.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  The license for this software can also likely be found here:
  http://www.gnu.org/licenses/gpl-2.0.html

* Please note, support may be available on the WordPress Support forums; but, it may be faster to visit http://buynowshop.com/plugins/bns-theme-add-ins/ and leave a comment with the issue you are experiencing.

* This plugin utilizes three text files if included with the active theme, although these files are not required for the plugin to work correctly they will enhance its functionality if they exist:
 * `readme.txt`
 * `changelog.txt`
 * `support.txt`

== Upgrade Notice ==
Please stay current with your WordPress installation, your active theme, and your plugins.

== Changelog ==
= Version 0.2.1 =
* Fix `BNS Theme Support` logic and functionality
* Correct inline documentation

= Version 0.2 =
* Released Oct 22, 2011
* Change $bns_textdomain to $bns_lower_case
* Remove `BNS Child-Theme TextDomain` and i18n (translation) support
* Add `BNS Plugin TextDomain` and i18n (translation) support specifically for 'bns-theme-add-ins'
* Correct `textdomain` issues and re-work the `BNS Child-Theme TextDomain` into `BNS Plugin TextDomain`
* i18n improvements in 'Readme Menu' and 'Changelog Menu' as well as change CSS containers from `id` to `class`
* Change `BNS Child-Theme Version Control` to `BNS Theme Support`; added functionality to read and display a `support.txt` file similar to the `readme` and `changelog` menu items.
* Minor inline documentation edits

= Version 0.1.1 =
* Change main file name to reflect Plugin name

= Version 0.1 =
* Initial Release.