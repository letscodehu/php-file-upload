<?php

namespace FileSystem;

class UploadedFile implements File {

    private $name;
    private $temporaryName;
    private $error;

    public function __construct($name, $temporaryName, $error) {
        $this->name = $name;
        $this->temporaryName = $temporaryName;
        $this->error = $error;
    }

    public function error() {
        return $this->error;
    }

    public function getName() {
        return $this->name;
    }

    public function getTemporaryName() {
        return $this->temporaryName;
    }

    public function moveTo($target) {
        move_uploaded_file($this->temporaryName, $target);
        return new LocalFile($target);
    }

}