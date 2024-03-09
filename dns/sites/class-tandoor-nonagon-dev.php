<?php
/**
 * Config file for the interface.nonagon.dev subdomain.
 *
 * @author dennisstinauer<dennis@stinauer.net>
 * @package routes_dns_sites
 */

/**
 * Class to handle the registration of interface.nonagon.dev subdomain.
 */
class Tandoor_Nonagon_Dev extends Subdomain_Handler {
	/**
	 * Update Records interface method.
	 *
	 * @param array $values associative array of values to update. in this case "ipv4" and "ipv6".
	 * @return boolean true or false depending on success.
	 */
	public static function update_records( $values ) {
		if ( ! defined( 'DNS_AUTH_API_TOKEN' ) || ! defined( 'DNS_ZONE_ID_NONAGON_DEV' ) ) {
			return false;
		}

		$auth_api_token = DNS_AUTH_API_TOKEN;
		$zone_id        = DNS_ZONE_ID_NONAGON_DEV;

		// Instanciate the Subdomain Handler.
		$interface = new Tandoor_Nonagon_Dev( $auth_api_token, $zone_id );

		// update the records.
		if ( isset( $values['ipv4'] ) && ! empty( $values['ipv4'] ) ) {
			$ipv4_update = $interface->update_record( $interface->get_record( 'tandoor', 'A' ), $values['ipv4'] );
		}

		return $ipv4_update;
	}
}
