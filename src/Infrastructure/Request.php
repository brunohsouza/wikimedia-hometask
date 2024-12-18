<?php

namespace App\Infrastructure;

/**
 * This class is responsible for handling all incoming requests.
 */
class Request {

	const HEADERS = 'Content-Type: application/json';

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
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function post( $key ): ?string {
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
	 *
	 * @return false|string
	 */
	public function getBody(): string {
		return file_get_contents( 'php://input' );
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
	 * Get a specific segment from the URI.
	 *
	 * @param int $index
	 * @return string|null
	 */
	public function getSegment( int $index ): ?string {
		$segments = $this->getSegments();

		return $segments[$index] ?? null;
	}

	/**
	 * Sanitize a string input.
	 *
	 * @param string $stringInput
	 * @param bool $isPost
	 * @return string|null
	 */
	private function sanitizeStringInput( string $stringInput, bool $isPost = false ): ?string {
		$inputType = $isPost ? INPUT_POST : INPUT_GET;

		$stringInput = filter_input( $inputType, $stringInput, FILTER_SANITIZE_STRING );
		$stringInput = filter_input( $inputType, $stringInput, FILTER_FLAG_EMPTY_STRING_NULL );
		$stringInput = filter_input( $inputType, $stringInput, FILTER_SANITIZE_SPECIAL_CHARS );

		return $stringInput;
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
	public function getQueryParam( $key ) {
		$query = parse_url( $this->getUri(), PHP_URL_QUERY );
		parse_str( $query, $params );
		return $this->sanitizeStringInput( $params[$key] ?? null );
	}

	/**
	 * Check if a query parameter exists in the URI.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasQueryParam( $key ) {
		$query = parse_url( $this->getUri(), PHP_URL_QUERY );
		parse_str( $query, $params );

		return isset( $params[$key] );
	}

}

