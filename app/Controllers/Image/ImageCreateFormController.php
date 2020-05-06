<?php 

namespace Controllers\Image;

class ImageCreateFormController {

    public function show() {
        return [
            "add", [
                "title" => "Add new photo"
            ]
        ];    
    }

}