<?php
/**
 * Define Abstract Class here that will be used in the Subdomain Classes.
 *
 * @author dennisstinauer<dennis@stinauer.net>
 * @package routes_dns_sites
 */

/**
 * Class to handle the registration of interface.nonagon.dev subdomain.
 */
abstract class Subdomain_Handler {

	/**
	 * The API TOKEN
	 *
	 * @var string
	 */
	private string $auth_api_token;

	/**
	 * The Zone ID
	 *
	 * @var string
	 */
	private string $zone_id;

	/**
	 * The Time to Live
	 *
	 * @var int
	 */
	private int $ttl = 600;

	/**
	 * Existing Records Array
	 *
	 * @var array
	 */
	private array $existing_records;

	/**
	 * The Constructor.
	 *
	 * @param string $auth_api_token hetzner api token.
	 * @param string $zone_id hetzner DNS zone id.
	 */
	public function __construct( $auth_api_token, $zone_id ) {
		$this->auth_api_token = $auth_api_token;
		$this->zone_id        = $zone_id;

		$existing_records = $this->get_all_records();
		if ( false === $existing_records ) {
			die( 'ERROR GETTING EXISTING ZONEFILE ENTRIES' );
		}
		$this->existing_records = $existing_records;
	}

	/**
	 * Send the Record update to Hetzner.
	 *
	 * @param string $record the record object.
	 * @param string $new_record_value the updated value.
	 * @return boolean true on succes, false on fail.
	 */
	protected function update_record( $record, $new_record_value ) {
		// get cURL resource.
		$ch = curl_init();

		// set url.
		curl_setopt( $ch, CURLOPT_URL, 'https://dns.hetzner.com/api/v1/records/' . $record->id );

		// set method.
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );

		// return the transfer as a string.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		// set headers.
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
				'Auth-API-Token: ' . $this->auth_api_token,
			)
		);

		// json body.
		$json_array = array(
			'value'   => $new_record_value,
			'ttl'     => $this->ttl,
			'type'    => $record->type,
			'name'    => $record->name,
			'zone_id' => $this->zone_id,
		);
		$body       = json_encode( $json_array );

		// set body.
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $body );

		// send the request and save response to $response.
		$response = curl_exec( $ch );

		// stop if fails.
		if ( ! $response ) {
			die( 'Error: "' . curl_error( $ch ) . '" - Code: ' . curl_errno( $ch ) );
		}

		$status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		echo 'HTTP Status Code: ' . curl_getinfo( $ch, CURLINFO_HTTP_CODE ) . PHP_EOL;
		echo 'Response Body: ' . $response . PHP_EOL;

		// close curl resource to free up system resources.
		curl_close( $ch );

		if ( 200 === $status ) {
			return true;
		}
		return false;
	}

	/**
	 * Return all available Records from the Hetzner API
	 *
	 * @return boolean/array array of record objects on success, false on fail.
	 */
	private function get_all_records() {
		// get cURL resource.
		$ch = curl_init();

		// set url.
		curl_setopt( $ch, CURLOPT_URL, 'https://dns.hetzner.com/api/v1/records?zone_id=' . $this->zone_id );

		// set method.
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );

		// return the transfer as a string.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		// set headers.
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Auth-API-Token: ' . $this->auth_api_token,
			)
		);

		// send the request and save response to $response.
		$response = curl_exec( $ch );

		// stop if fails.
		if ( ! $response ) {
			die( 'Error: "' . curl_error( $ch ) . '" - Code: ' . curl_errno( $ch ) );
		}

		// check if response is successful.
		if ( 200 == curl_getinfo( $ch, CURLINFO_HTTP_CODE ) ) {
			// close curl resource to free up system resources.
			curl_close( $ch );
			return json_decode( $response )->records;
		}

		return false;
	}

	/**
	 * Returns record objects by name and type.
	 * A Record Object contains the following attributes:
	 *  - id
	 *  - type
	 *  - name
	 *  - value
	 *  - zone_id
	 *  - created
	 *  - modified
	 *
	 * @param string $record_name the record name to get.
	 * @param string $record_type the record type to get.
	 * @return object $record the record object.
	 */
	protected function get_record( $record_name, $record_type ) {
		foreach ( $this->existing_records as $record ) {
			if ( $record->name === $record_name && $record->type === $record_type ) {
				return $record;
			}
		}
	}

	/**
	 * Force Child-Classes to implement the update_records static function.
	 * This is supposed to do combine the factory and the update call.
	 *
	 * @param array $values the values to update.
	 * @return boolean true/false depending on success.
	 */
	abstract public static function update_records( $values);
}
