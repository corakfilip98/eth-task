<?php
class Database {
    private $conn;

    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Greška pri povezivanju na bazu podataka: " . $this->conn->connect_error);
        }
    }

    public function close() {
        $this->conn->close();
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
