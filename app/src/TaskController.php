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
                // print_r($_POST);
                // echo "create";
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    $this->respondUnprocessableEntity($errors);
                    return;
                }

                $new_task_id = $this->gateway->create($data);
                $this->respondCreated($new_task_id);
            } else {
                
                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {
            
            $task = $this->gateway->get($id);
            if (!$task) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                
                case "GET":
                    echo json_encode($task);
                    break;
                
                case "PATCH":
                    $data = (array) json_decode(file_get_contents("php://input"), true);

                    $errors = $this->getValidationErrors($data, false);
    
                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }
                    // echo "update $id";
                    $this->gateway->update($id, $data);
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

    private function respondCreated(string $id): void {
        http_response_code(201);
        echo json_encode(["message" => "Task created", "id" => $id]);
    }

    private function respondUnprocessableEntity(array $errors): void {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function getValidationErrors(array $data, bool $is_new = true) {
        $errors = [];
        if ($is_new && empty($data["name"])) {
            $errors[] = "name is required";
        }
        if (!empty($data["priority"])) {
            if (filter_var($data["priority"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "priority must be an integer";
            }
        }
        return $errors;
    }
}










