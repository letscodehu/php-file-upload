<?php 

namespace Controllers\Image;

class ImageCreateSubmitController {

    private $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
    }

    public function submit() {
        // handle uploaded file
        $targetDir = $this->basePath. "/storage/";
        try {
            switch($_FILES['file']['error']) {
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
            $targetFile = $targetDir. basename($_FILES["file"]["name"]);
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if ($check !== false) {
                move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);
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