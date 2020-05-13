<?php 

namespace Controllers\Image;

use Request\Request;

class ImageCreateSubmitController {

    private $basePath;
    private $request;

    public function __construct($basePath, Request $request) {
        $this->basePath = $basePath;
        $this->request = $request;
    }

    public function submit() {
        // handle uploaded file
        $targetDir = $this->basePath. "/storage/";        
        try {
            $file = $this->request->getFile("fil");
            switch($file->error()) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException("No file sent.");
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException("Exceeded filesize limit.");
                default:
                    throw new \RuntimeExcpetion("Unknown errors.");
            }
            $targetFile = $targetDir. basename($file->getName());
            $check = getimagesize($file->getTemporaryName());
            if ($check !== false) {
                $file->moveTo($targetFile);
                // create image record
                // redirect to created image
                return [
                    "redirect:/", [
                    ]
                ];    
            } else {
                throw new \RuntimeException("File is not an image!");
            }
            
        } catch (\RuntimeException $ex) {
            logMessage("ERROR", $ex->getMessage());
            // put some error flag to session
            return [
                "redirect:/image/add", []
            ];

        }
        
    }

}