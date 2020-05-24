<?php 

namespace Controllers\Image;

use Session\Session;

class ImageCreateFormController {

    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function show() {
        $violations = $this->session->flash()->get("violations");
        return [
            "add", [
                "title" => "Add new photo",
                "violations" => $violations
            ]
        ];    
    }

}