<?php

// TODO A: Improve the readability of this file through refactoring and documentation.

// TODO B: Review the HTML structure and make sure that it is valid and contains
// required elements. Edit and re-organize the HTML as needed.

// TODO C: Review the index.php entrypoint for security and performance concerns
// and provide fixes. Note any issues you don't have time to fix.

// TODO D: The list of available articles is hardcoded. Add code to get a
// dynamically generated list.

// TODO E: Are there performance problems with the word count function? How
// could you optimize this to perform well with large amounts of data? Code
// comments / psuedo-code welcome.

// TODO F: Implement a simple unit test to ensure the correctness of different parts
// of the application.

use App\Application\Article;
use App\Infrastructure\Request;

require_once __DIR__ . '/vendor/autoload.php';

// Initialize the Article and Request classes
$article = new Article();
$request = new Request();

// Get the title from the query parameters if it is set
$title = $request->getQueryParam( 'title' );
$body = '';

// Separating the header and body HTML content for better readability
echo sprintf( file_get_contents( './template/html/header.html' ), $title );

// If a title is set in the query parameters, fetch the article content
if ( $request->getQueryParam( 'title' ) ) {
	$title = htmlentities( $request->getQueryParam( 'title' ) );
	$body = $article->fetchByTitle( $title );
}

// Get the word count of all articles
$wordCount = $article->countWordsInArticlesDirectory() . " words written";

// Separating the body HTML content for better readability.
echo sprintf( file_get_contents( './template/html/body.html' ), $wordCount, $title, $body, $title, $body );

// Get the list of articles and display them in the list
$listOfArticles = $article->getListOfArticles();
foreach ( $listOfArticles as $item ) {
	echo sprintf( file_get_contents( './template/html/listOfArticles.html' ), $item, $item );
}

// Including the footer HTML content for the page
echo file_get_contents( './template/html/footer.html' );

// If the request is a POST request, save the article
if ( $request->getBody() ) {
	$article->save( $request->getBody()['title'], $request->getBody()['body'] );
}
