<?php

class AuthorController extends Controller
{
  public function __construct($db_link)
  {
    parent::__construct($db_link, AuthorModel::class, ['name']);
  }
}
