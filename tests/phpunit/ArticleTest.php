<?php

namespace Tests;

use App\Application\Article;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Article
 */
class ArticleTest extends TestCase {

	/**
	 * @var Article
	 */
	private $article;

	public function setUp(): void {
		parent::setUp();

		$this->article = new Article();
	}

	/**
	 * @covers ::fetchByTitle
	 */
	public function testFetchArticleByTitle() {
		$article = $this->article->fetchByTitle( 'Blah' );

		$this->assertStringContainsString( 'Blah', $article );
	}

	/**
	 * @covers ::validateArticleCreationParams
	 */
	public function testValidateArticleCreationParams() {
		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Title and body are required fields.' );

		$this->article->validateArticleCreationParams( [ 'title' => '' ] );
	}
}
