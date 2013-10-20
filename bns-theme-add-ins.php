<?php
/*
Plugin Name: BNS Theme Add-Ins
Plugin URI: http://buynowshop.com/plugins/bns-theme-add-ins/
Description: A collection of functions and code that can be used to extend the capabilities of WordPress Parent-Themes and Child-Themes.  
Version: 0.7
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
Textdomain: bns-tai
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Theme Add-Ins
 * A collection of functions and code that can be used to extend the
 * capabilities of WordPress Themes and Child-Themes.
 * 
 * Adds `BNS Extra Theme Headers`
 * Adds `BNS Theme Support`
 * Adds `BNS Readme Menu Item`
 * Adds `BNS Changelog Menu Item`
 *
 * @since   September 30, 2011
 *
 * @internal REQUIRES the theme use support.txt
 * @internal REQUIRES the theme use readme.txt
 * @internal REQUIRES the theme use changelog.txt
 *
 * Copyright 2011-2013  Edward Caissie  (email : edward.caissie@gmail.com)
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
 * @version 0.5
 * @date    November 13, 2012
 *
 * @version 0.6
 * @date    February 15, 2013
 * Added code block termination comments
 *
 * @version 0.7
 * @date    October 20, 2013
 */

class BNS_Theme_Add_Ins {

    /** Constructor */
    function __construct(){

        /**
         * Check installed WordPress version for compatibility
         * @internal    Requires WordPress version 3.5
         * @internal    @uses wp_html_allowed_html
         */
        global $wp_version;
        $exit_message = 'BNS Theme Add Ins requires WordPress version 3.5 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please Update!</a>';
        if ( version_compare( $wp_version, "3.5", "<" ) ) {
            exit ( $exit_message );
        } /** End if */

        /** Add Extra Theme Headers */
        add_filter( 'extra_theme_headers', array( $this, 'extra_theme_headers' ) );

        /** IMPORTANT! Collect `Theme Data` after filtering the theme header */
        /** Add Theme Support Menu Item */
        add_action( 'admin_menu', array( $this, 'support_menu_item' ) );
        /** Add Readme Menu Item */
        add_action( 'admin_menu', array( $this, 'readme_menu_item' ) );
        /** Add Changelog Menu Item */
        add_action( 'admin_menu', array( $this, 'changelog_menu_item' ) );

    }

    /**
     * Extra Theme Headers
     * Add additional useful header data fields
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     * @date    September 17, 2011
     *
     * @internal Extra Theme Headers may become deprecated if the following core
     * trac ticket is approved: http://core.trac.wordpress.org/ticket/16395
     *
     * @version 0.6
     * @date    February 15, 2013
     * Added 'WordPress Tested Version' and 'WordPress Required Version' support
     */
    function extra_theme_headers( $headers ) {
        if ( ! in_array( 'WordPress Tested Version', $headers ) ) {
            $headers[] = 'WordPress Tested Version';
        } /** End if - not in array */

        if ( ! in_array( 'WordPress Required Version', $headers ) ) {
            $headers[] = 'WordPress Required Version';
        } /** End if - not in array */

        if ( ! in_array( 'Template Version', $headers ) ) {
            $headers[] = 'Template Version';
        } /** End if - not in array */

        return $headers;

    } /** End function - extra theme headers */


    /**
     * Support Menu Item
     * Check Template Version of Child-Theme versus Parent-Theme version; and
     * displays a default Theme support message, or reads a `support.txt` file and
     * displays its contents.
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     *
     * @version 0.2
     * @date    October 22, 2011
     * Formerly known as 'BNS Child-Theme Version Control'
     */
    function support_menu_item() {

        /** Globalize these variables for use in other functions */
        global $bns_menu_title;
        global $bns_parent_theme_data;
        global $bns_lower_case;
        global $bns_theme_data;

        $bns_theme_data = wp_get_theme();
        $bns_lower_case = strtolower( preg_replace( "/[ ]/", "-", $bns_theme_data->get( 'Name' ) ) );

        if ( is_child_theme() ) {

            /* Get Theme Data of the Parent-Theme */
            $bns_parent_theme_data = $bns_theme_data->parent();

            /** Set Menu Title based on version check results */
            $bns_menu_title = ( $bns_theme_data['Template Version'] !== $bns_parent_theme_data['Version'] )
                    ? 'Version Warning'
                    : 'Version Checked';

        } else {

            $bns_menu_title = 'Support';

        } /** End if - is child theme */

        /** Set variable for future usage ... globalize if needed. */
        add_theme_page(
            'BNS Theme Version Control',
            $bns_theme_data->get( 'Name' ) . ' ' . $bns_menu_title,
            'manage_options',
            'bns-theme-version-control',
            array( $this, 'version_issue' )
        );

    } /** End function - support menu item */


    /**
     * Version Issue
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     *
     * @uses    apply_filters
     * @uses    esc_html__
     * @uses    get_stylesheet_directory
     * @uses    get_stylesheet_directory_uri
     * @uses    is_child_theme
     * @uses    wp_kses
     * @uses    wp_kses_allowed_html
     *
     * @version 0.5
     * @date    November 13, 2012
     * Added filter 'bns_tai_version_issue'
     * PHPDocs updated
     *
     * @version 0.7
     * @date    October 20, 2013
     * Added sanitation to string outputs
     */
    function version_issue() {

        global $bns_lower_case;
        global $bns_menu_title;
        global $bns_parent_theme_data;
        global $bns_theme_data;
        $text = '';

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
            $text .= 'For more information or help, please feel free to visit the <a href="' . $bns_parent_theme_data['ThemeURI'] . '">' . $bns_parent_theme_data['Name'] . ' home page</a> or the <a href="' . $bns_theme_data['ThemeURI'] . '">' . $bns_theme_data['Name'] . ' home page</a>';
            $text .= '<br /><br />';
            $text .= '</div><!-- .updated -->';
            $text .= '<br />';

        } elseif ( $bns_menu_title == 'Version Checked' ) {

            $text = '<br />';
            $text .= '<h1>' . $bns_menu_title . '</h1>';
            if ( is_child_theme() ) {

                $text .= 'The "Template Version" of the ' . $bns_theme_data['Name'] . ' Child-Theme appears to be the same as the ' . $bns_parent_theme_data['Name'] . ' "Parent-Theme Version" you have installed.';
                $text .= '<br /><br />';
                $text .= 'The "Template Version" and "Parent-Theme Version" numbers should be the same, or you may experience unexpected results with the ' . $bns_theme_data['Name'] . ' Child-Theme.';
                $text .= '<br /><br />';
                $text .= 'Thank you for keeping both themes up-to-date, or keeping both themes synchronized, as the case may be.';
                $text .= '<br /><br />';

            } /** End if - is child theme */

        } else {

            if ( is_readable( get_stylesheet_directory() . '/support.txt' ) ) {

                /** Get a file into an array. */
                $text_lines = file( get_stylesheet_directory_uri() . '/support.txt' );

                /** Loop through our array, show HTML source as HTML source */
                foreach ( $text_lines as $support_text ) {
                    $text .= sprintf( esc_html__( '<span class="%1$s-readme-text">%2$s</span>', 'bns-tai' ), $bns_lower_case, $support_text ) . "<br />\n";
                } /** End foreach */

            } else {

                $text .= 'For more information or help, please feel free to visit ';

                if ( is_child_theme() ) {
                    $text .= 'the <a href="' . $bns_parent_theme_data['ThemeURI'] . '">' . $bns_parent_theme_data['Name'] . ' home page</a> or ';
                } /** End if - is child theme */

                $text .= 'the <a href="' . $bns_theme_data['ThemeURI'] . '">' . $bns_theme_data['Name'] . ' home page</a>';
                $text .= '<br /><br />';

            } /** End if - is readable */
        }

        /** @var $text - clean up the text string being passed */
        $text = wp_kses( $text, wp_kses_allowed_html( 'post' ) );

        echo apply_filters( 'bns_tai_version_issue', sprintf( __( '%1$s', 'bns-tai' ), '<span id="bns-child-theme-version-control-text">' . $text . '</span>' ) );

    } /** End function - version issue */


    /**
     * Readme Menu Item
     * Include menu item to display theme readme text
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     *
     * @version 0.2
     * @date    October 21, 2011
     *
     * @internal `readme.txt` MUST exist *in* the Theme root directory/folder!
     */
    function readme_menu_item() {
        global $bns_lower_case;
        global $bns_theme_data;

        add_theme_page(
            $bns_theme_data['Name'] . ' ' . 'README',
            $bns_theme_data['Name'] . ' ' . __( 'README', 'bns-tai' ),
            'manage_options',
            $bns_lower_case . '-readme',
            array( $this, 'readme_text' )
        );

    } /** End function - readme menu item */


    /**
     * Readme Text
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     *
     * @uses    apply_filters
     * @uses    esc_html__
     * @uses    get_stylesheet_directory
     * @uses    get_stylesheet_directory_uri
     * @uses    wp_kses
     * @uses    wp_kses_allowed_html
     *
     * @version 0.5
     * @date    November 13, 2012
     * Added filter 'bns_tai_readme_text'
     * PHPDocs updates
     *
     * @version 0.7
     * @date    October 20, 2013
     * Added sanitation to string outputs
     */
    function readme_text() {

        global $bns_lower_case;
        global $bns_theme_data;

        if ( is_readable( get_stylesheet_directory() . '/readme.txt' ) ) {

            /** Get a file into an array. */
            $text_lines = file( get_stylesheet_directory_uri() . '/readme.txt' );

            /** Loop through our array, show HTML source as HTML source */
            $readme_text = '';

            foreach ( $text_lines as $text ) {
                $readme_text .= sprintf( esc_html__( '<span class="%1$s-readme-text">%2$s</span>', 'bns-theme-add-ins' ), $bns_lower_case, $text ) . "<br />\n";
            } /** End foreach */

            /** @var $readme_text - clean up the text string being passed */
            $readme_text = wp_kses( $readme_text, wp_kses_allowed_html( 'post' ) );

            echo apply_filters( 'bns_tai_readme_text', $readme_text );

        } else {

            echo '<div class="updated"><h2>readme.txt</h2>'
                    . sprintf( __( 'The %1$s "readme.txt" file either does not exist or is not readable.', 'bns-theme-add-ins' ), $bns_theme_data['Name'] )
                    . '<br /></div>';

        } /** End if - is readable */

    } /** End function - readme text */


    /**
     * BNS Changelog Menu Item
     * Include menu item to display theme readme text
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     *
     * @version 0.2
     * @date    October 21, 2011
     * @internal `changelog.txt` MUST exist *in* the Theme root directory/folder!
     */
    function changelog_menu_item() {
        global $bns_lower_case;
        global $bns_theme_data;

        add_theme_page(
            $bns_theme_data['Name'] . ' ' . 'Changelog',
            $bns_theme_data['Name'] . ' ' . __( 'Changelog', 'bns-tai' ),
            'manage_options',
            $bns_lower_case . '-changelog',
            array( $this, 'changelog_text' )
        );

    } /** End function - changelog menu item */


    /**
     * Changelog Text
     *
     * @package BNS_Theme_Add_Ins
     * @since   0.1
     *
     * @uses    apply_filters
     * @uses    esc_html__
     * @uses    get_stylesheet_directory
     * @uses    get_stylesheet_directory_uri
     * @uses    wp_kses
     * @uses    wp_kses_allowed_html
     *
     * @version 0.5
     * @date    November 13, 2012
     * Added filter 'bns_tai_changelog_text'
     * PHPDocs updates
     *
     * @version 0.7
     * @date    October 20, 2013
     * Added sanitation to string outputs
     */
    function changelog_text() {

        global $bns_lower_case;
        global $bns_theme_data;

        if ( is_readable( get_stylesheet_directory() . '/changelog.txt' ) ) {

            /** Get a file into an array. */
            $text_lines = file( get_stylesheet_directory_uri() . '/changelog.txt' );

            /** Loop through our array, show HTML source as HTML source */
            $changelog_text = '';
            foreach ($text_lines as $text) {
                $changelog_text .= sprintf( esc_html__( '<span class="%1$s-changelog-text">%2$s</span>', 'bns-tai' ), $bns_lower_case, $text ) . "<br />\n";
            }

            /** @var $changelog_text - clean up the text string being passed */
            $changelog_text = wp_kses( $changelog_text, wp_kses_allowed_html( 'post' ) );

            echo apply_filters( 'bns_tai_changelog_text', $changelog_text );

        } else {

            echo '<div class="updated"><h2>changelog.txt</h2>'
                    . sprintf( __( 'The %1$s "changelog.txt" file either does not exist or is not readable.', 'bns-tai' ), $bns_theme_data['Name'] )
                    . '<br /></div>';

        } /** End if - is readable */

    } /** End function - changelog text */


} /** End class */


/** @var $bns_theme_add_ins - instantiate the class */
$bns_theme_add_ins = new BNS_Theme_Add_Ins();