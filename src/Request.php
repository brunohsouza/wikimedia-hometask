<?php

namespace App;

/**
 * Class Request
 * This class is responsible for handling all incoming requests.
 */
class Request {

	const HEADERS = 'Content-Type: application/json';

	public function get( $key ) {
		if ( $this->hasGet( $key ) ) {
			return $this->sanitizeStringInput( $_GET[$key] );
		}

		return null;
	}

	public function post( $key ) {
		if ( $this->hasPost( $key ) ) {
			return $this->sanitizeStringInput( $_POST[$key], true );
		}

		return null;
	}

	public function hasGet( $key ) {
		return isset( $_GET[$key] );
	}

	public function hasPost( $key ) {
		return isset( $_POST[$key] );
	}

	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getHeaders() {
		header( self::HEADERS );

		return getallheaders();
	}

	public function getBody() {
		return file_get_contents( 'php://input' );
	}

	public function getUri() {
		return $_SERVER['REQUEST_URI'];
	}

	public function getSegments() {
		$uri = parse_url( $this->getUri() );

		return explode( '/', $uri['path'] );
	}

	public function getSegment( $index ) {
		$segments = $this->getSegments();

		return $segments[$index] ?? null;
	}

	private function sanitizeStringInput( $stringInput, $isPost = false ) {
		$inputType = $isPost ? INPUT_POST : INPUT_GET;

		$stringInput = filter_input( $inputType, $stringInput, FILTER_SANITIZE_STRING );
		$stringInput = filter_input( $inputType, $stringInput, FILTER_FLAG_EMPTY_STRING_NULL );
		$stringInput = filter_input( $inputType, $stringInput, FILTER_SANITIZE_SPECIAL_CHARS );

		return $stringInput;
	}

	public function getQueryParam( $key ) {
		$query = parse_url( $this->getUri(), PHP_URL_QUERY );
		parse_str( $query, $params );

		return $params[$key] ?? null;
	}

	public function hasQueryParam( $key ) {
		$query = parse_url( $this->getUri(), PHP_URL_QUERY );
		parse_str( $query, $params );

		return isset( $params[$key] );
	}

}

