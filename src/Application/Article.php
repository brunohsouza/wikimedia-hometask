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

	public function __construct()
	{
		$this->article = new ArticleDomain();
	}

	/**
	 * Function to handle the article saving operation.
	 * @param string $title
	 * @param string|null $body
	 * @param string|null $author
	 */
	public function save( string $title, ?string $body = null, ?string $author = null ): void {
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
	 * Function to get the list of articles.
	 * @return array
	 */
	public function getListOfArticles(): array {
		return $this->article->getListOfArticles();
	}
}
