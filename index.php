<?php
ini_set('display_errors', '1');
require_once './utils/constants.php';
require_once './utils/utils.php';

// [DB]
$mysql_link = mysqli_connect(
    DB_CONNECT_PROPS['hostname'],
    DB_CONNECT_PROPS['username'],
    DB_CONNECT_PROPS['password'],
    DB_CONNECT_PROPS['dbname']
);

if (mysqli_connect_error()) {
    $error_props = [
        'type' => 'MySQL connect error',
        'code' => mysqli_connect_errno(),
        'message' => mysqli_connect_error()
    ];
    exit(join(PHP_EOL, $error_props));
}

mysqli_set_charset($mysql_link, 'utf8');

create_db_table($mysql_link, 'authors', AUTHORS_TABLE_FIELDS);
create_db_table($mysql_link, 'books', BOOKS_TABLE_FIELDS);

// [Routers]
require_once './routers/main-router.php';
