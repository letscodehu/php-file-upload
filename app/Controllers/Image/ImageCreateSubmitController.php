<?php 

namespace Controllers\Image;

use Request\Request;
use Response\Redirect;
use Services\PhotoService;
use Validation\Validator;

class ImageCreateSubmitController {

    private $basePath;
    private $request;
    private $photoService;
    private $validator;

    public function __construct(string $basePath, Request $request, PhotoService $photoService, Validator $validator) {
        $this->basePath = $basePath;
        $this->validator = $validator;
        $this->request = $request;
        $this->photoService = $photoService;
    }

    public function submit() {
        
        $targetDir = $this->basePath. "/storage/";        
        try {
            $title = $this->request->getParam("title");
            $file = $this->request->getFile("file");
            $violations = $this->validate($title, $file);
            if (count($violations) !== 0) {
                return Redirect::to("/image/add")->with("violations", $violations);
            }
            $targetFile = uniqid($targetDir, true). ".png";
            $file->moveTo($targetFile);
            $photo = $this->photoService->createImage($title, "/private/" . basename($targetFile));
            return Redirect::to("/image/". $photo->getId());
        } catch (\RuntimeException $ex) {
            logMessage("ERROR", $ex->getMessage());
            // put some error flag to session
            return Redirect::to("/image/add");

        }
        
    }

    private function validate($title, $file) {
        return $this->validator->validate([
            "required|min:5|max:255" => $title,
            "required|image" => $file
        ]);
    }

}