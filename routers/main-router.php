<?php
require_once './mvc-core/Model.php';
require_once './mvc-core/Controller.php';
require_once './routers/Router.php';
require_once './controllers/main-route.php';
require_once './controllers/any-route.php';
require_once './routers/author.php';
require_once './routers/book.php';

$main_router = new Router;

$main_router->get('/', 'render_main_page');
if (isset($author_router)) $main_router->use('/authors', $author_router);
if (isset($book_router)) $main_router->use('/books', $book_router);
$main_router->all('*', 'render_err_page');
$main_router->on();
