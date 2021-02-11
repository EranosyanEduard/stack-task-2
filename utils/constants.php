<?php

define('DB_CONNECT_PROPS', [
  'hostname' => 'localhost',
  'username' => 'root',
  'password' => 'root',
  'dbname' => 'library'
]);

define('AUTHORS_TABLE_FIELDS', [
  'id TINYINT AUTO_INCREMENT PRIMARY KEY',
  'name TINYTEXT'
]);

define('BOOKS_TABLE_FIELDS', [
  'id TINYINT AUTO_INCREMENT PRIMARY KEY',
  'name TINYTEXT',
  'publication_date DATE',
  'author_id TINYINT',
  'author TINYTEXT'
]);
