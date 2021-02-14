<?php

class ModelBook extends Model
{
    public function __construct($db)
    {
        parent::__construct($db, 'books');
    }
}
