<?php
require_once './controllers/Author.php';

if (isset($mysql_link)) {
    $author_router = new Router;
    $controller_author = new ControllerAuthor($mysql_link);

    $author_router->get('/', function () use ($controller_author) {
        $controller_author->getAuthors();
    });
}
