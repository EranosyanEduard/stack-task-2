<?php

function create_db_table($db_link, $table_name, $table_fields)
{
  $query = "CREATE TABLE IF NOT EXISTS $table_name (" . join(',', $table_fields) . ")";
  if (!mysqli_query($db_link, $query)) {
    print 'MySQL query error: ' . mysqli_error($db_link);
  }
}
