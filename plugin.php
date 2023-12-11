<?php

/*

Plugin Name: detect-mobile-device
Plugin URI: https://github.com/guessi/yourls-mobile-detect
Description: A simple plugin for converting query string by device type
Version: 3.0.0
Author: guessi
Author URI: https://github.com/guessi

*/

/**
 *
 * Upstream Library Included:
 * - https://github.com/serbanghita/Mobile-Detect/blob/3.74.3/src/MobileDetect.php
 *
 * License:
 * - https://github.com/serbanghita/Mobile-Detect/blob/3.74.3/LICENSE
 *
 */
require_once 'MobileDetect.php';


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
    return ! yourls_keyword_is_free( yourls_sanitize_keyword( $keyword ) );
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
    /**
     * do not apply mobile detection for valid user
     * or it will break "stats" feature: http://short.en/keyword+ (endwith +)
     */
    if( yourls_is_valid_user() === true ) {
        return $keyword;
    }

    $detect = new \Detection\MobileDetect;
    $result = $keyword;

    if ($detect->is('iOS') || $detect->is('iPadOS')) {
        if (is_link_defined( 'ios' . $keyword )) {
            $result = 'ios' . $keyword;
        }
    }

    if ($detect->is('AndroidOS')) {
        if (is_link_defined( 'androidos' . $keyword )) {
            $result = 'androidos' . $keyword;
        }
    }

    return $result;
}
