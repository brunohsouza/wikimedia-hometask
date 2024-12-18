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

if ( !$request->hasQueryParam( 'title' ) && !$request->getQueryParam( 'prefixsearch' ) ) {
	echo json_encode( [ 'content' => $article->getListOfArticles() ] );
} elseif ( $request->hasQueryParam( 'prefixsearch' ) ) {
	$list = $article->getListOfArticles();
	$ma = [];

	foreach ( $list as $ar ) {
		if ( strpos( strtolower( $ar ), strtolower( $request->getQueryParam( 'prefixsearch' ) ) ) === 0 ) {
			$ma[] = $ar;
		}
	}

	$response->sendJson( [ 'content' => $ma ] );
} else {
	echo json_encode( [ 'content' => $article->fetch( $_GET ) ] );
}
