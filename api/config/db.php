<?php
class Database {
    private $host = "localhost";
    private $db_name = "saiitech";
    private $username = "user";
    private $password = "12345678";

    public function connect() {
        try {
            return new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}