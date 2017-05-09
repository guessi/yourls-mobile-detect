<?php

/*

Plugin Name: detect-mobile-device
Plugin URI: https://github.com/guessi/yourls-mobile-detect
Description: A simple plugin for converting query string by device type
Version: 1.0.0
Author: guessi
Author URI: https://github.com/guessi

*/

/**
 *
 * Upstream Library Included:
 * - https://github.com/serbanghita/Mobile-Detect/blob/2.8.25/Mobile_Detect.php
 *
 * License: https://github.com/serbanghita/Mobile-Detect
 *
 */
require_once('Mobile_Detect.php');


/**
 *
 *  no direct call
 *
 */
if( !defined( 'YOURLS_ABSPATH' ) ) die();


yourls_add_filter( 'get_request', 'detect_mobile_device' );

/**
 *
 * Check for request query string existence
 *
 * @param    string   $keyword The query string
 * @return   boolean  If query string have shorten url for strtolower($ostype) . $keyword
 *
 */
function is_link_defined( $keyword ) {
    global $ydb;

    $keyword = yourls_sanitize_keyword( $keyword );
    $existence = false;

    if ( $_SERVER['REQUEST_URI'] != '/' ) {
      $existence = $ydb->get_var( "SELECT COUNT(`keyword`) FROM ". YOURLS_DB_TABLE_URL ." WHERE LOWER(`keyword`) = LOWER('$keyword');" );
    }

    return $existence;
}


/**
 *
 * Return query result by device type
 *
 * @param    string   $keyword The query string
 * @return   string   strtolower($ostype) . $keyword
 *
 */
function detect_mobile_device( $keyword ) {
    $detect = new Mobile_Detect;
    $result = $keyword;

    if ( $detect->is('iOS') ) {
        if (is_link_defined( 'ios' . $keyword )) {
            $result = 'ios' . $keyword;
        }
    }

    if ( $detect->is('AndroidOS') ) {
        if (is_link_defined( 'androidos' . $keyword )) {
            $result = 'androidos' . $keyword;
        }
    }

    return $result;
}
