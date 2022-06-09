<?php

class TaskGateway {
    private PDO $conn;
    
    public function __construct(Database $database) {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array {
        $sql = "SELECT *
                FROM task
                ORDER BY name";
                
        $stmt = $this->conn->query($sql);

        return array_map(function($row){
            $row["is_completed"] = (bool) $row["is_completed"];
            return $row;
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
        
    }
}
