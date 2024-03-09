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
require_once 'sites/class-jelly-nonagon-dev.php';
require_once 'sites/class-tandoor-nonagon-dev.php';
require_once 'sites/class-matrix-nonagon-dev.php';
require_once 'sites/class-minecraft-nonagon-dev.php';



// Check Secret.
if ( ! defined( 'DNS_SECRET' ) || ! isset( $_GET['secret'] ) || empty( $_GET['secret'] ) || DNS_SECRET !== base64_decode( $_GET['secret'] ) ) {
	http_response_code( 418 );
	die();
}

// GET AND DECODE THE IPs.
$ipv4_b64 = $_GET['ipv4'];

if ( isset( $ipv4_b64 ) && ! empty( $ipv4_b64 ) ) {
	$ipv4 = base64_decode( $ipv4_b64 );
}

// FAIL IF IPS NOT FOUND.
if ( ! isset( $ipv4 ) ) {
	die( 'IPS nicht gefunden.' );
}

$values = array('ipv4' => $ipv4 );

// REGISTER SITES.
$interface_nonagon_dev = Interface_Nonagon_Dev::update_records( $values );
$jelly_nonagon_dev     = Jelly_Nonagon_Dev::update_records( $values );
$matrix_nonagon_dev    = Matrix_Nonagon_Dev::update_records( $values );
$tandoor_nonagon_dev   = Tandoor_Nonagon_Dev::update_records( $values );
$minecraft_nonagon_dev = Minecraft_Nonagon_Dev::update_records( $values );

if ( $interface_nonagon_dev && $jelly_nonagon_dev && $matrix_nonagon_dev && $tandoor_nonagon_dev ) {
	http_response_code( 200 );
}
