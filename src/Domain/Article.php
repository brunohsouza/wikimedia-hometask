<?php

namespace App\Domain;

/**
 * This class represents an article.
 */
class Article {
	private $uid;

	private $title;

	private $body;

	private $author;

	private $created_at;

	private $updated_at;

	/**
	 * Function to initialize the article object.
	 * @param string $title
	 * @param string $body
	 * @param string $author
	 */
	public function __construct( string $title, string $body, string $author ) {
		$this->setUid();
		$this->title = $title;
		$this->body = $body;
		$this->author = $author;
		$this->setCreatedAt( $title );
		$this->setUpdatedAt( $title );
	}

	public function setUid(): void {
		$this->uid = uniqid( 'article_', true );
	}

	/**
	 * Set the created_at property based on the file creation date.
	 * @param string $title
	 * @return void
	 */
	public function setCreatedAt( string $title ): void {
		if ( file_exists( sprintf( 'articles/%s', $title ) ) ) {
			$this->created_at = date( 'Y-m-d H:i:s', filectime( sprintf( 'articles/%s', $title ) ) );
		}
	}

	/**
	 * Set the updated_at property based on the latest file modification.
	 * @param string $title
	 * @return void
	 */
	public function setUpdatedAt( string $title ): void {
		if ( file_exists( sprintf( 'articles/%s', $title ) ) ) {
			$this->updated_at = date( 'Y-m-d H:i:s', filemtime( sprintf( 'articles/%s', $title ) ) );
		}
	}
}
