<?php

class ArrayException extends Exception{
    private $array = [];
    
    function __construct(array $array, string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message,$code,$previous);
        $this->array = $array;
    }
    public function getArray(): array {
        return $this->array;
    }
}