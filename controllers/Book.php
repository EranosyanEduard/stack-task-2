<?php
require_once './models/Book.php';

class ControllerBook extends Controller
{
    public function __construct($db)
    {
        parent::__construct(
            $db,
            ModelBook::class,
            ['name', 'publication_date', 'author_id', 'author'],
            ['main' => 'page-with-books.php']
        );
    }

    public function addBook()
    {
        $this->updatePageTemplate('fallback');
        $this->postRecord();
    }

    public function getBooks()
    {
        $this->updatePageTemplate('main');
        $this->getRecords();
    }

    public function removeBook()
    {
        $this->updatePageTemplate('fallback');
        $this->removeRecord();
    }

    public function updateBook()
    {
        $this->updatePageTemplate('fallback');
        $this->updateRecord();
    }
}
