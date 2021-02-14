<?php

class ModelAuthor extends Model
{
    public function __construct($db)
    {
        parent::__construct($db, 'authors');
    }
}
