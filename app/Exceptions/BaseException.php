<?php

namespace App\Exceptions;

class BaseException extends \Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function reportAjax() {
        return [
            'code'      =>  $this->getCode(),
            'message'   =>  $this->getMessage(),
        ];
    }
}
