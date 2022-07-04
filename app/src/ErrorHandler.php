<?php

class ErrorHandler {

  public static function handleError(int $error_number, string $error_message, string $file, $line) {
    throw new ErrorException($error_message, 0, $error_number, $file, $line);
  }

  public static function handleException(Throwable $exception): void {
    http_response_code(500);
    echo json_encode([
      "code" => $exception->getCode(),
      "message" => $exception->getMessage(),
      "file" => $exception->getFile(),
      "line" => $exception->getLine()
    ]);
  }
}