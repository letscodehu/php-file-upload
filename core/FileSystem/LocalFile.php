<?php

namespace FileSystem;

class LocalFile implements File {

    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function moveTo($target) {
        rename($name, $target);
    }

}