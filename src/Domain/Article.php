<?php

namespace App\Domain;

/**
 * This class represents an article.
 */
class Article {

	const ARTICLE_DIR = 'articles/';
	const DATE_FORMAT = 'Y-m-d H:i:s';

	private $uid;

	private $title;

	private $body;

	private $author;

	private $created_at;

	private $updated_at;

	/**
	 * Function to initialize the article object.
	 * @param string|null $title
	 * @param string|null $body
	 * @param string|null $author
	 * @return void
	 */
	public function __construct( ?string $title = null, ?string $body = null, ?string $author = null ) {
		$this->setUid();
		$this->title = $title ?? '';
		$this->body = $body ?? '';
		$this->author = $author ?? '';
		$this->setCreatedAt( $title );
		$this->setUpdatedAt( $title );
	}

	public function setUid(): void {
		$this->uid = uniqid( self::ARTICLE_DIR, true );
	}

	/**
	 * Set the created_at property based on the file creation date.
	 * @param string $title
	 * @return void
	 */
	public function setCreatedAt( string $title ): void {
		if ( file_exists( sprintf( self::ARTICLE_DIR . '%s', $title ) ) ) {
			$this->created_at = date( self::DATE_FORMAT, filectime( sprintf( self::ARTICLE_DIR . '%s', $title ) ) );
		}
	}

	/**
	 * Set the updated_at property based on the latest file modification.
	 * @param string $title
	 * @return void
	 */
	public function setUpdatedAt( string $title ): void {
		if ( file_exists( sprintf( self::ARTICLE_DIR . '%s', $title ) ) ) {
			$this->updated_at = date( self::DATE_FORMAT, filemtime( sprintf( self::ARTICLE_DIR . '%s', $title ) ) );
		}
	}

	/**
	 * Save the article to a file.
	 * @return void
	 */
	public function save( string $title, ?string $body = null, ?string $author = null ): void {
		$filename = sprintf( self::ARTICLE_DIR . '%s', $this->title );

		if ( !file_exists( $filename ) ) {
			$this->title = $title;
			$this->body = $body;
			$this->author = $author;
			$this->setCreatedAt( $title );
			$this->setUpdatedAt( $title );
		}

		file_put_contents( $filename, json_encode( $this ) );
	}

	/**
	 * Update the article.
	 * @param string $title
	 * @param string|null $body
	 * @param string|null $author
	 * @return void
	 */
	public function update( string $title, ?string $body, ?string $author ): void {
		if ( !file_exists( sprintf( self::ARTICLE_DIR . '%s', $title ) ) ) {
			throw new \DomainException( 'Article with this title not found.' );
		}

		$this->save( $title, $body, $author );
	}

	/**
	 * Fetch the article content by title.
	 * @param string $title
	 * @return string
	 */
	public function fetchByTitle( string $title ): ?string {
		if ( file_exists( sprintf( self::ARTICLE_DIR . '%s', $title ) ) ) {
			return file_get_contents( sprintf( self::ARTICLE_DIR . '%s', $title ) );
		}

		return null;
	}

	/**
	 * Get the list of articles.
	 * @return array
	 */
	public function getListOfArticles(): array {
		return array_diff( scandir( self::ARTICLE_DIR ), [ '.', '..', '.DS_Store' ] );
	}
}
