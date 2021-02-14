<?php
require_once './models/Author.php';

class ControllerAuthor extends Controller
{
    public function __construct($db)
    {
        parent::__construct(
            $db,
            ModelAuthor::class,
            ['name'],
            ['main' => 'page-with-authors.php']
        );
    }

    public function addAuthor()
    {
        $this->updatePageTemplate('fallback');
        $this->postRecord(['name' => $_POST['author']]);
    }

    public function getAuthors()
    {
        $this->updatePageTemplate('main');
        $this->getRecords();
    }

    public function includeAuthor(): ?array
    {
        return $this->fetchRecordFromTable(['name' => $_POST['author']]);
    }
}
