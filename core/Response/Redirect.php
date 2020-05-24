<?php

namespace Response;

class Redirect {

    private $target;
    private $flashMessages = [];

    public function __construct($target)
    {
        $this->target = $target;
    }

    public static function to($target) {
        return new self($target);
    }

    public function with($name, $value) {
        $this->flashMessages[$name] = $value;
        return $this;
    }

    public function getTarget() {
        return $this->target;
    }

    public function getFlashMessages() {
        return $this->flashMessages;
    }

}