<?php

class TaskController
{
    private TaskGateway $gateway;
    
    public function __construct(TaskGateway $gateway) {
        $this->gateway = $gateway;
    }
    
    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null) {
            
            if ($method == "GET") {
                
                echo json_encode($this->gateway->getAll());
                
            } elseif ($method == "POST") {
                
                echo "create";
            } else {
                
                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {
            
            $task = $this->gateway->get($id);
            if (!$task) {
                $this->respondNotFound($id);
            }

            switch ($method) {
                
                case "GET":
                    echo json_encode($this->gateway->get($id));
                    break;
                
                case "PATCH":
                    echo "update $id";
                    break;
                    
                case "DELETE":
                    echo "delete $id";
                    break;
                    
                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }
    
    private function respondMethodNotAllowed(string $allowed_methods): void {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function respondNotFound(string $id): void {
        http_response_code(404);
        echo json_encode(["message" => "Task with id $id not found"]);
    }
}










