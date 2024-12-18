<?php

namespace App\Domain;

/**
 * This class represents an article.
 */
class Article {

	const ARTICLE_DIR = 'articles/';
	const DATE_FORMAT = 'Y-m-d H:i:s';

	private $title;

	private $body;

	private $author;

	private $createdAt;

	private $updatedAt;

	/**
	 * Set the createdAt property based on the file creation date.
	 * @param string $title
	 * @return void
	 */
	public function setCreatedAt( string $title ): void {
		if ( file_exists( sprintf( self::ARTICLE_DIR . '%s', $title ) ) ) {
			$this->createdAt = date( self::DATE_FORMAT, filectime( sprintf( self::ARTICLE_DIR . '%s', $title ) ) );
		}
	}

	/**
	 * Save the article to a file.
	 * @param string $title
	 * @param string|null $body
	 * @param string|null $author
	 * @throws \DomainException
	 * @return void
	 */
	public function save( string $title, ?string $body = null, ?string $author = null ): void {
		$filename = sprintf( self::ARTICLE_DIR . '%s', $title );

		if ( !file_exists( $filename ) ) {
			$this->title = $title;
			$this->body = $body;
			$this->author = $author;
			// Set the createdAt date only once with the current date
			$this->createdAt = ( ( new \DateTime() )->format( self::DATE_FORMAT ) );
		} else {
			$content = file_get_contents( $filename );
			[ $previousBody, $previousAuthor ] = explode( 'Author: ', $content );

			$this->title = $title;
			$this->body = $body ?? $previousBody;
			$this->author = $author ?? $previousAuthor;
			$this->setCreatedAt( $title );
		}
		$this->updatedAt = ( ( new \DateTime() )->format( self::DATE_FORMAT ) );

		file_put_contents( $filename, $this->body . PHP_EOL . 'Author: ' . $this->author );
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

	public function getListOfArticlesByPrefixSearch( string $prefixSearch ): array {
		$list = $this->getListOfArticles();
		$articles = [];

		foreach ( $list as $item ) {
			if ( stripos( $item, $prefixSearch ) === 0 ) {
				$articles[] = $item;
			}
		}

		return $articles;
	}
}
