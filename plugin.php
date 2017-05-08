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
    $protocol = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";

    if ( function_exists('curl_exec') ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $protocol . $_SERVER["HTTP_HOST"] . '/' . $keyword );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

        if ( $httpCode == 301 || $httpCode == 302 || $httpCode == 200) {
            if ( ! strcmp($protocol . $_SERVER["HTTP_HOST"], rtrim($redirect, '/')) ) {
                return false;
            }
        }

        curl_close($ch);
    }
    return true;
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
