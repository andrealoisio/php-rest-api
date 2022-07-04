<?php

class TaskGateway {
    private PDO $conn;
    
    public function __construct(Database $database) {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array {
        $sql = "SELECT *
                FROM task
                ORDER BY id";
                
        $stmt = $this->conn->query($sql);

        return array_map(function($row){
            $row["is_completed"] = (bool) $row["is_completed"];
            return $row;
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));

    }

    public function get(string $id) {
        $sql = "SELECT *
                FROM task
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            $data["is_completed"] = (bool) $data["is_completed"];
        }

        return $data;
    }

    public function create(array $data): string {
        $sql = "INSERT INTO task (name, priority, is_completed)
                VALUES (:name, :priority, :is_completed)";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(":name", $data["name"]);

        if (empty($data["priority"])) {
            $statement->bindValue(":priority", null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(":priority", $data["priority"], PDO::PARAM_INT);
        }
        $statement->bindValue(":is_completed", $data["is_completed"] ?? false, PDO::PARAM_BOOL);

        $statement->execute();

        return $this->conn->lastInsertId();

    }
}
