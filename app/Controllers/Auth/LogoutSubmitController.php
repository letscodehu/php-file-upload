<?php

namespace Controllers\Auth;

use Services\AuthService;

class LogoutSubmitController {

    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    function submit() {
        $this->authService->logout();
        return [
            "redirect:/", [
            ]
        ];
    }

}

