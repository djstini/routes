<?php
/**
 * Controller for the DYN DNS "Service"
 * 
 * @author dennisstinauer<dennis@stinauer.net>
 */

 require_once '.dnsconfig.php';
 require_once 'sites/abstract-class-subdomain-handler.php';
 require_once 'sites/class-interface-nonagon-dev.php';

// Check Secret 
if( ! defined('DNS_SECRET') || DNS_SECRET !== base64_decode($_GET['secret'])){
   header('HTTP/1.1 404 Not Found');
   die('NOT AUTHORIZED');
}

$interface_nonagon_dev = Interface_Nonagon_Dev::factory('193.159.141.232', '2003:c1:c7ff:10e:1eed:6fff:fe7a:ff1f');
if ( false !== $interface_nonagon_dev ){
   $interface_nonagon_dev->update_records(); 
} else {
   echo "lol :3";
}