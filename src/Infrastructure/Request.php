<?php

namespace App\Infrastructure;

/**
 * This class is responsible for handling all incoming requests.
 */
class Request {

	private const HEADERS = 'Content-Type: application/json';

	/**
	 * Get a value from the $_GET superglobal and sanitize it.
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function get( string $key ): ?string {
		if ( $this->hasGet( $key ) ) {
			return $this->sanitizeStringInput( $_GET[$key] );
		}

		return null;
	}

	/**
	 * Get a value from the $_POST superglobal and sanitize it.
	 * The return type is missing here as it should use union_types,
	 * but it is not available in the current PHP version on this project
	 *
	 * @param string $key
	 * @return array|null
	 */
	public function post( $key ) {
		if ( $this->hasPost( $key ) ) {
			if ( is_array( $_POST[$key] ) ) {
				return $this->sanitizeArrayInput( $_POST[$key] );
			}
			return $this->sanitizeStringInput( $_POST[$key], true );
		}

		return null;
	}

	/**
	 * Check if a key exists in the $_GET superglobal.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasGet( string $key ): bool {
		return isset( $_GET[$key] );
	}

	/**
	 * Check if a key exists in the $_POST superglobal.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasPost( $key ): bool {
		return isset( $_POST[$key] );
	}

	/**
	 * Get the request method.
	 *
	 * @return string
	 */
	public function getMethod(): string {
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Get the request headers.
	 *
	 * @return array|false
	 */
	public function getHeaders() {
		header( self::HEADERS );

		return getallheaders();
	}

	/**
	 * Get the request body.
	 * @return array
	 */
	public function getBody(): array {
		return json_decode( file_get_contents( 'php://input' ), true );
	}

	/**
	 * Get the request URI.
	 *
	 * @return string
	 */
	public function getUri(): string {
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Get the URI segments.
	 *
	 * @return array
	 */
	public function getSegments(): array {
		$uri = parse_url( $this->getUri() );

		return explode( '/', $uri['path'] );
	}

	/**
	 * Sanitize a string input.
	 *
	 * @param string $stringInput
	 * @param bool $isPost
	 * @return string|null
	 */
	private function sanitizeStringInput( string $stringInput, bool $isPost = false ): ?string {
		return filter_var(
			$stringInput,
			FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS
		);
	}

	/**
	 * Sanitize an array input if the input is an array.
	 *
	 * @param array $arrayInput
	 * @return array
	 */
	private function sanitizeArrayInput( array $arrayInput ): array {
		return array_map( function ( $value ) {
			return $this->sanitizeStringInput( $value );
		}, $arrayInput );
	}

	/**
	 * Get a query parameter from the URI.
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function getQueryParam( string $key ): ?string {
		$query = parse_url( $this->getUri(), PHP_URL_QUERY );
		if ( !$query ) {
			return null;
		}

		parse_str( $query, $params );

		return $this->sanitizeStringInput( $params[$key] ?? null );
	}

}
