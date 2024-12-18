<?php

use App\Application\Article;
use App\Infrastructure\Request;
use App\Infrastructure\Response;

// TODO A: Improve the readability of this file through refactoring and documentation.
// TODO B: Clean up the following code so that it's easier to see the different
// routes and handlers for the API, and simpler to add new ones.
// TODO C: Address performance concerns in the current code.
// that you would address during refactoring.
// TODO D: Identify any potential security vulnerabilities in this code.
// TODO E: Document this code to make it more understandable
// for other developers.

require_once __DIR__ . '/vendor/autoload.php';

$article = new Article();
// Using the Request class to handle incoming requests
$request = new Request();
$response = new Response();

try {
	switch ( $request->getMethod() ) {
		case 'POST':
			$requestBody = $request->getBody();
			$article->validateArticleCreationParams( $requestBody );
			$article->save( $requestBody['title'], $requestBody['body'], $requestBody['author'] );

			echo $response->sendJson( [ 'message' => 'Article created successfully' ] );

			break;
		case 'PUT':
			$requestBody = $request->getBody();
			$article->validateArticleCreationParams( $requestBody );
			$article->update( $requestBody['title'], $requestBody['body'], $requestBody['author'] );

			echo $response->sendJson( [ 'message' => 'Article updated successfully' ] );

			break;
		case 'GET':
			$title = $request->getQueryParam( 'title' );
			$prefixsearch = $request->getQueryParam( 'prefixsearch' );

			echo $response->sendJson( [ 'content' => $article->handleGetArticleRequest( $title, $prefixsearch ) ] );

			break;
		default:
			echo $response->sendJson( [ 'error' => 'Method not allowed' ], 405 );

			break;
	}
} catch ( \Exception $e ) {
	echo $response->sendJson( [ 'error' => $e->getMessage() ], 412 );
}
