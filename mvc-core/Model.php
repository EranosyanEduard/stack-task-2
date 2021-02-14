<?php

class Model
{
    private mysqli $db_link;
    private string $table_name;

    public function __construct($db_link, $table_name)
    {
        $this->db_link = $db_link;
        $this->table_name = $table_name;
    }

    public function deleteRecord($record_id)
    {
        return $this->sendQuery("DELETE FROM $this->table_name WHERE id=$record_id");
    }

    public function insertRecord($expression)
    {
        return $this->sendQuery("INSERT INTO $this->table_name SET $expression");
    }

    public function selectRecords($condition)
    {
        $expression = $condition ? " WHERE $condition" : '';
        return $this->sendQuery("SELECT * FROM $this->table_name" . $expression);
    }

    public function updateRecord($record_id, $expression)
    {
        return $this->sendQuery("UPDATE $this->table_name SET $expression WHERE id=$record_id");
    }

    private function sendQuery($query)
    {
        return mysqli_query($this->db_link, $query);
    }
}
