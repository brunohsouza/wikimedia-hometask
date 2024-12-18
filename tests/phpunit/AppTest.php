<?php

namespace Tests;

use App\Application\Article;

/**
 * @coversDefaultClass Article
 */
class AppTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers ::fetch
	 */
	public function testFetch() {
		$app = new Article();
		$x = $app->fetch( [ 'title' => 'Foo' ] );
		$this->assertStringContainsString( 'Use of metasyntactic variables', $x );
	}
}
