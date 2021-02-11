<?php
ini_set('display_errors', '1');

require_once './utils/constants.php';
require_once './utils/utils.php';
require_once './routers/Router.php';
require_once './mvc-core/Model.php';
require_once './mvc-core/View.php';
require_once './mvc-core/Controller.php';
require_once './models/Author.php';
require_once './models/Book.php';
require_once './controllers/Author.php';
require_once './controllers/Book.php';

// [Database]
$mysql_link = mysqli_connect(
  DB_CONNECT_PROPS['hostname'],
  DB_CONNECT_PROPS['username'],
  DB_CONNECT_PROPS['password'],
  DB_CONNECT_PROPS['dbname']
);

if (mysqli_connect_error()) {
  $error_props = [
    'type' => 'MySQL connect error',
    'code' => mysqli_connect_errno(),
    'message' => mysqli_connect_error()
  ];
  exit(join(PHP_EOL, $error_props));
}

mysqli_set_charset($mysql_link, 'utf8');

create_db_table($mysql_link, 'authors', AUTHORS_TABLE_FIELDS);
create_db_table($mysql_link, 'books', BOOKS_TABLE_FIELDS);

// [Controllers]
$author_controller = new AuthorController($mysql_link);
$book_controller = new BookController($mysql_link);

// [Routers]
$router = new Router;
// Set router
// 0. Main route
$router->get('/', function () {
  Controller::renderPageWithTooltip('main');
});
// 1. Route /authors
$router->get('/authors', function () use ($author_controller) {
  $author_controller->updatePageTemplate('page-with-authors.php');
  $author_controller->getRecords();
});
// 2. Route /books
$router->get('/books', function () use ($book_controller) {
  $book_controller->updatePageTemplate('page-with-books.php');
  $book_controller->getRecords();
});
$router->post('/books', function () use ($author_controller, $book_controller) {
  $page_template = 'page-with-tooltip.php';
  // Add record about author into authors table
  $author_controller->updatePageTemplate($page_template);
  $author_controller->postRecord(['name' => $_POST['author']]);
  $_POST['author_id'] = $author_controller->getInsertId();
  // Add record about book into books table
  $book_controller->updatePageTemplate($page_template);
  $book_controller->postRecord();
});
$router->delete('/books', function () use ($book_controller) {
  $book_controller->updatePageTemplate('page-with-tooltip.php');
  $book_controller->removeRecord();
});
$router->patch('/books', function () use ($book_controller) {
  $book_controller->updatePageTemplate('page-with-tooltip.php');
  $book_controller->updateRecord();
});
// 3. Any routes
$router->all('*', function () {
  Controller::renderPageWithTooltip('page not found', 404);
});
// Run router
$router->use();
