<?php
/**
 *
 * Controller for the DYN DNS "Service"
 *
 * @author dennisstinauer<dennis@stinauer.net>
 * @package routes_dns
 */

require_once '.config.php';
require_once 'sites/abstract-class-subdomain-handler.php';
require_once 'sites/class-interface-nonagon-dev.php';

// Check Secret.
if ( ! defined( 'DNS_SECRET' ) || ! isset( $_GET['secret'] ) || empty( $_GET['secret'] ) || DNS_SECRET !== base64_decode( $_GET['secret'] ) ) {
	http_response_code( 418 );
	die();
}

// GET AND DECODE THE IPs.
$ipv4_b64 = $_GET['ipv4'];
$ipv6_b64 = $_GET['ipv6'];

if ( isset( $ipv4_b64 ) && ! empty( $ipv4_b64 ) ) {
	$ipv4 = base64_decode( $ipv4_b64 );
}
if ( isset( $ipv6_b64 ) && ! empty( $ipv6_b64 ) ) {
	$ipv6 = base64_decode( $ipv6_b64 );
}

// FAIL IF IPS NOT FOUND.
if ( ! isset( $ipv4 ) || ! isset( $ipv6 ) ) {
	die( 'IPS nicht gefunden.' );
}

// REGISTER SITES.
$interface_nonagon_dev = Interface_Nonagon_Dev::update_records( $ipv4, $ipv6 );
$jelly_nonagon_dev     = Jelly_Nonagon_Dev::update_records( $ipv4, $ipv6 );
$matrix_nonagon_dev    = Matrix_Nonagon_Dev::update_records( $ipv4, $ipv6 );
$tandoor_nonagon_dev   = Tandoor_Nonagon_Dev::update_records( $ipv4, $ipv6 );

if ( $interface_nonagon_dev && $jelly_nonagon_dev && $matrix_nonagon_dev && $tandoor_nonagon_dev ) {
	http_response_code( 200 );
}
