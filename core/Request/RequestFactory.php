<?php

namespace Request;

use ServiceContainer;
use FileSystem\UploadedFile;

class RequestFactory {

    public static function createFromGlobals(ServiceContainer $container) {
        $files = [];
        foreach ($_FILES as $name => $file) {
            $files[$name] = new UploadedFile($file["name"], $file["tmp_name"], $file["error"]);
        }
        return new Request($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"], $container->get("session"), file_get_contents('php://input'), getallheaders(), $_COOKIE, array_merge($_GET, $_POST), 
            $files);
    }

}