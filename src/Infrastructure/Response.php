<?php

namespace App\Infrastructure;

class Response {
	private const HEADERS = 'Content-Type: application/json';

	/**
	 * Send a JSON response.
	 *
	 * @param array $data
	 * @param int $status
	 * @return string
	 */
	public function sendJson( array $data, int $status = 200 ): string {
		http_response_code( $status );
		header( self::HEADERS );

		return json_encode( $data, JSON_PRETTY_PRINT );
	}
}
