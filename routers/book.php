<?php
require_once './controllers/Book.php';

if (isset($mysql_link)) {
    $book_router = new Router;
    $controller_book = new ControllerBook($mysql_link);

    $book_router->get('/', function () use ($controller_book) {
        $controller_book->getBooks();
    });

    if (isset($controller_author)) {
        $book_router->post('/', function () use ($controller_author, $controller_book) {
            $author = $controller_author->includeAuthor();
            if ($author) {
                $_POST['author_id'] = $author['id'];
            } else {
                $controller_author->addAuthor();
                $_POST['author_id'] = $controller_author->getInsertId();
            }
            $controller_book->addBook();
        });
    }

    $book_router->delete('/', function () use ($controller_book) {
        $controller_book->removeBook();
    });

    $book_router->patch('/', function () use ($controller_book) {
        $controller_book->updateBook();
    });
}
