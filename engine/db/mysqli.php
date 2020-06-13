<?php

class MySQLiCnt {

    private $connection;

    public function __construct($hostname, $username, $password, $database) {
        $this->connection = new mysqli($hostname, $username, $password, $database, '3306');

        if($this->connection->connect_error)
            die('Error connection to database');

        $this->connection->set_charset('utf8');
    }

    public function query($sql) {
        $query = $this->connection->query($sql);

        if(!$this->connection->errno) {
            if($query instanceof mysqli_result) {
                $data = [];

                while($row = $query->fetch_assoc())
                    $data[] = $row;

                $result = new stdClass();

                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : [];
                $result->rows = $data;

                $query->close();

                return $result;
            }

            return false;
        }

        die('Error while quering sql');
    }

    public function escape($value) {
        return $this->connection->real_escape_string($value);
    }

    public function __destruct() {
        $this->connection->close();
    }
}