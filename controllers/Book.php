<?php

class BookController extends Controller
{
  public function __construct($db_link)
  {
    parent::__construct($db_link, BookModel::class, [
      'name',
      'publication_date',
      'author_id',
      'author'
    ]);
  }
}
