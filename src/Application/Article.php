<?php

namespace App\Application;

// TODO: Improve the readability of this file through refactoring and documentation.

use App\Domain\Article as ArticleDomain;

/**
 * This class in the old App.php one. It is responsible for handling the article
 * related operations.
 * It was refactored to change the name to Article as it is more descriptive.
 */
class Article {

	/**
	 * @var ArticleDomain
	 */
	private $article;

	public function __construct() {
		$this->article = new ArticleDomain();
	}

	/**
	 * Function to validate the article creation parameters.
	 * The title and body are required fields.
	 * @param array $requestBody
	 */
	public function validateArticleCreationParams( $requestBody ): void {
		if ( !$requestBody['title'] || empty( $requestBody['title'] ) !== null ||
			!$requestBody['body'] || empty( $requestBody['body'] ) !== null
		) {
			throw new \InvalidArgumentException( 'Title and body are required fields.' );
		}
	}

	/**
	 * Function to handle the article saving operation.
	 * The author parameter is optional and should be available only for logged-in users.
	 * @param string $title
	 * @param string|null $body
	 * @param string|null $author
	 */
	public function save( string $title, string $body, ?string $author = null ): void {
		$this->article->save( $title, $body, $author );
	}

	/**
	 * Function to handle the article updating operation.
	 * @param string $title
	 * @param string|null $body
	 * @param string|null $author
	 */
	public function update( string $title, ?string $body = null, ?string $author = null ): void {
		$this->article->update( $title, $body, $author );
	}

	/**
	 * Function to fetch the article content by title.
	 * @param string $title
	 * @return string
	 */
	public function fetchByTitle( string $title ): string {
		return $this->article->fetchByTitle( $title );
	}

	/**
	 * Function to get the list of articles in the directory.
	 * @return array
	 */
	public function getListOfArticles(): array {
		return $this->article->getListOfArticles();
	}

	/**
	 * Function to get the list of articles by prefix search.
	 * @param string $prefixSearch
	 * @return array
	 */
	public function getListOfArticlesByPrefixSearch( string $prefixSearch ): array {
		return $this->article->getListOfArticlesByPrefixSearch( $prefixSearch );
	}

	/**
	 * Function to handle the get article request.
	 * There are three different cases for GET requests
	 * 1. If there is no title or prefixsearch query parameter, return the list of articles
	 * 2. If there is a prefixsearch query parameter, return the list of articles that match the prefix
	 * 3. If there is a title query parameter, return the content of the article with that title
	 * @param string|null $title
	 * @param string|null $prefixsearch
	 * @return array|string
	 */
	public function handleGetArticleRequest( ?string $title = null, ?string $prefixsearch = null ) {
		if ( !$title && !$prefixsearch ) {
			return $this->getListOfArticles();
		}

		if ( $prefixsearch !== null ) {
			return $this->getListOfArticlesByPrefixSearch( $prefixsearch );
		}

		return $this->fetchByTitle( $title );
	}

	public function countWordsInArticlesDirectory(): string {
		return $this->article->countWordsInArticlesDirectory();
	}
}
