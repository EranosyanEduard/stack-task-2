<?php

class BookModel extends Model
{
  public function __construct($db_link)
  {
    parent::__construct($db_link, 'books');
  }
}
