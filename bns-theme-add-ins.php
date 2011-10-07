<?php
/*
Plugin Name: BNS Theme Add-Ins
Plugin URI: http://buynowshop.com/plugins/
Description: A collection of functions and code that can be used to extend the capabilities of WordPress Parent-Themes and Child-Themes.  
Version: 0.2
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/* BNS Theme Add-Ins
 * A collection of functions and code that can be used to extend the
 * capabilities of WordPress Themes and Child-Themes.
 * 
 * Add `BNS Extra Theme Headers`
 * Add `BNS Child-Theme Version Control`
 * Add `BNS Readme Menu Item`
 * Add `BNS Changelog Menu Item`  
 * Remove `BNS Child-Theme TextDomain` and i18n (translation) support
 * Add `BNS Plugin TextDomain` and i18n (translation) support specifically for 'bns-theme-add-ins'
 * 
 * Initial Release: September 30, 2011  
 * 
 * @version: 0.2
 * @last revision: October 6, 2011
 * 
 * ----         
 *  
 * Copyright 2011  Edward Caissie  (email : edward.caissie@gmail.com)
 *
 * BNS Theme Add-Ins is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2, as
 * published by the Free Software Foundation.
 * 
 * You may NOT assume that you can use any other version of the GPL.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ----
 *     
 * Please Note: a readme.txt file and a changelog.txt file are required for the
 * code to work correctly. Failure to have these files in the root folder or
 * directory of the Theme may cause unexpected results and will most likely
 * cause errors to occur.
 * 
 **/   

/* BNS Extra Theme Headers
 * Add additional useful header data fields
 *
 * @package: BNS Theme Add-Ins
 * @version: 0.1
 * @date: September 17, 2011
 * 
 * NB: BNS Extra Theme Headers may become deprecated if the following core trac
 * ticket is approved: http://core.trac.wordpress.org/ticket/16395  
 *  
 **/
add_filter( 'extra_theme_headers', 'bns_extra_theme_headers' );
if ( ! function_exists( 'bns_extra_theme_headers' ) ){
  function bns_extra_theme_headers( $headers ) {
    	if ( ! in_array( 'Template Version', $headers ) )
    		$headers[] = 'Template Version';
    	return $headers;
  }
}    
// End: BNS Extra Theme Headers

/* BNS Child-Theme Version Control
 * Check Template Version of Child-Theme versus Parent-Theme version
 *
 * @package: BNS Theme Add-Ins 
 * @version: 0.1
 * @date: September 29, 2011
 * @revised: October 6, 2011
 *  
 **/
add_action('admin_menu', 'bns_theme_menu_item');

if ( ! function_exists( 'bns_theme_menu_item' ) ) {
  function bns_theme_menu_item() {
      // Globalize these variables for use in other functions
      global $bns_menu_title;
      global $bns_parent_theme_data;
      global $bns_lower_case;
      global $bns_theme_data;

      // IMPORTANT! Collect `Theme Data` after filtering the theme header (see BNS Extra Theme Headers)
      $bns_theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' ); 
      $bns_lower_case = strtolower( preg_replace( "/[ ]/", "-", $bns_theme_data['Name'] ) );

      if ( is_child_theme() ) {
        /* Get Theme Data of the Parent-Theme */
        $bns_parent_theme_data = get_theme_data( get_template_directory() . '/style.css' );
        
        // Set Menu Title based on version check results
        $bns_menu_title = ( $bns_theme_data['Template Version'] !== $bns_parent_theme_data['Version'] )
          ? 'Version Warning'
          : 'Version Checked';
      } else {
        $bns_menu_title = __( 'Support', 'bns-theme-add-ins' );
      }
      
      // Set variable for future usage ... globalize if needed.
      add_theme_page( 'BNS Theme Version Control',
                      $bns_theme_data['Name'] . ' ' . $bns_menu_title,
                      'manage_options',
                      'bns-theme-version-control',
                      'bns_version_issue'
      );
  }
}

if ( ! function_exists( 'bns_version_issue' ) ) {
  function bns_version_issue() {
      global $bns_menu_title;
      global $bns_parent_theme_data;
      global $bns_theme_data;
      
      if ( $bns_menu_title == 'Version Warning' ) {
          
        $text = '<br />';
        $text .= '<div class="updated">';
        $text .= '<h1>' . $bns_menu_title . '</h1>';
        $text .= 'The "Template Version" of the ' . $bns_theme_data['Name'] . ' Child-Theme and the version of the ' . $bns_parent_theme_data['Name'] . ' Theme you have installed are not the same.';
        $text .= '<br /><br />';    
        $text .= 'Please note this version of the ' . $bns_theme_data['Name'] . ' Child-Theme was made for use with the ' . $bns_parent_theme_data['Name'] . ' (Parent-Theme) version: ' . $bns_theme_data['Template Version'];
        $text .= '<br />';
        $text .= '... and the version of ' . $bns_parent_theme_data['Name'] . ', the "Parent-Theme", you have installed is: ' . $bns_parent_theme_data['Version'];
        $text .= '<br /><br />';
        $text .= 'These version numbers should be the same, or you may experience unexpected results with the ' . $bns_theme_data['Name'] . ' Child-Theme.';
        $text .= '<br /><br />';    
        $text .= 'For more information or help, please feel free to visit the <a href="' . $bns_parent_theme_data['URI'] . '">' . $bns_parent_theme_data['Name'] . ' home page</a> or the <a href="' . $bns_theme_data['URI'] . '">' . $bns_theme_data['Name'] . ' home page</a>';
        $text .= '<br /><br />';        
        $text .= '</div><!-- .updated -->';
        $text .= '<br />';
        
      } else {
      
        $text = '<br />';
        $text .= '<h1>' . $bns_menu_title . '</h1>';
        if ( is_child_theme() ) {        
          $text .= 'The "Template Version" of the ' . $bns_theme_data['Name'] . ' Child-Theme appears to be the same as the ' . $bns_parent_theme_data['Name'] . ' "Parent-Theme Version" you have installed.';
          $text .= '<br /><br />';        
          $text .= 'The "Template Version" and "Parent-Theme Version" numbers should be the same, or you may experience unexpected results with the ' . $bns_theme_data['Name'] . ' Child-Theme.';
          $text .= '<br /><br />';    
          $text .= 'Thank you for keeping both themes up-to-date, or keeping both themes synchronized, as the case may be.';
          $text .= '<br /><br />';
        }
        $text .= 'For more information or help, please feel free to visit ';
        if ( is_child_theme() ) {        
          $text .= 'the <a href="' . $bns_parent_theme_data['URI'] . '">' . $bns_parent_theme_data['Name'] . ' home page</a> or ';
        }
        $text .= 'the <a href="' . $bns_theme_data['URI'] . '">' . $bns_theme_data['Name'] . ' home page</a>';
        $text .= '<br /><br />';    
      }        
      
      $text = sprintf( __( '%1$s', 'bns-theme-add-ins' ), '<span id="bns-child-theme-version-control-text">' . $text . '</span>' );
      echo $text;
  }
}
/* End: BNS Child-Theme Version Control */

/* BNS Readme Menu Item
 * Include menu item to display theme readme text 
 * 
 * @package: BNS Theme Add-Ins
 * @version: 0.1
 * @date: September 30, 2011
 * @revised: October 6, 2011
 * 
 * Requirement: `readme.txt` MUST exist *in* the Theme root directory/folder!
 *  
 */
add_action( 'admin_menu', 'bns_readme_menu_item' );

// Add menu item
if ( ! function_exists( 'bns_readme_menu_item' ) ) {
  function bns_readme_menu_item() {
      global $bns_lower_case;
      global $bns_theme_data;      

      add_theme_page( $bns_theme_data['Name'] . ' ' . 'README',
                      $bns_theme_data['Name'] . ' ' . __( 'README', 'bns-theme-add-ins' ),
                      'manage_options',
                      $bns_lower_case . '-readme',
                      'bns_readme_text'
      );
  }
}

// read and write `readme.txt`
if ( ! function_exists( 'bns_readme_text' ) ) {
  function bns_readme_text() {
      global $bns_lower_case;
      global $bns_theme_data;        

      if ( is_readable( get_stylesheet_directory() . '/readme.txt' ) ) {        
        // Get a file into an array.
        $text_lines = file( get_stylesheet_directory_uri() . '/readme.txt' );
  
        // Loop through our array, show HTML source as HTML source
        $readme_text = '';
        foreach ($text_lines as $text) {
          $readme_text .= sprintf( __( '<span id="' . $bns_lower_case . '-readme-text">%1$s</span>', 'bns-theme-add-ins' ), $text ) . "<br />\n";
        }
        echo $readme_text;
      } else {
        echo '<div class="updated"><h2>readme.txt</h2>'
          . sprintf( __( 'The %1$s "readme.txt" file either does not exist or is not readable.', 'bns-theme-add-ins' ), $bns_theme_data['Name'] )
          . '<br /></div>';        
      }
  }
}
// End: BNS Readme Menu Item

/* BNS Changelog Menu Item 
 * Include menu item to display theme readme text
 * 
 * @package: BNS Theme Add-Ins
 * @version: 0.1
 * @date: September 30, 2011
 * @revised: October 6, 2011
 * 
 * Requirement: `changelog.txt` MUST exist *in* the Theme root directory/folder!
 *  
 */
add_action( 'admin_menu', 'bns_changelog_menu_item' );

// Add menu item
if ( ! function_exists( 'bns_changelog_menu_item' ) ) {
  function bns_changelog_menu_item() {
      global $bns_lower_case;
      global $bns_theme_data;      

      add_theme_page( $bns_theme_data['Name'] . ' ' . 'Changelog',
                      $bns_theme_data['Name'] . ' ' . __( 'Changelog', 'bns-theme-add-ins'),
                      'manage_options',
                      $bns_lower_case . '-changelog',
                      'bns_changelog_text'
      );
  }
}

// read and write `changelog.txt`
if ( ! function_exists( 'bns_changelog_text' ) ) {
  function bns_changelog_text() {
      global $bns_lower_case;
      global $bns_theme_data;        
        
      if ( is_readable( get_stylesheet_directory() . '/changelog.txt' ) ) {
        // Get a file into an array.
        $text_lines = file( get_stylesheet_directory_uri() . '/changelog.txt' );
  
        // Loop through our array, show HTML source as HTML source
        $changelog_text = '';
        foreach ($text_lines as $text) {
          $changelog_text .= sprintf( __( '<span id="' . $bns_lower_case . '-changelog-text">%1$s</span>', 'bns-theme-add-ins' ), $text ) . "<br />\n";
        }
        echo $changelog_text;
      } else {
        echo '<div class="updated"><h2>changelog.txt</h2>'
          . sprintf( __( 'The %1$s "changelog.txt" file either does not exist or is not readable.', 'bns-theme-add-ins' ), $bns_theme_data['Name'] )
          . '<br /></div>';        
      }
  }
}
// End: BNS Changelog Menu Item

/* BNS Plugin TextDomain
 * Make available for translation
 * 
 * @package: BNS Theme Add-Ins
 * @version: 0.1
 * @date: September 28, 2011
 * @revised: October 6, 2011
 *
 * Note: Translation files are expected to be found in the plugin root folder / directory.
 */
global $bns_lower_case;
$bns_plugin_path = get_home_url() . '/wp-content/plugins/' . $bns_lower_case;
load_plugin_textdomain( 'bns-theme-add-ins', $bns_plugin_path );
$locale = get_locale();
$locale_file = get_stylesheet_directory() . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
    require_once( $locale_file );
// End: BNS Plugin TextDomain
?>
<?php /* Last revised October 7, 2011 v0.2 */ ?>