<?php

namespace Controllers\ForgotPassword;

use Request\Request;
use Services\ForgotPasswordService;

class ForgotPasswordSubmitController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var ForgotPasswordService
     */
    private $forgotPasswordService;

    /**
     * ForgotPasswordSubmitController constructor.
     * @param Request $request
     * @param ForgotPasswordService $forgotPasswordService
     */
    public function __construct(Request $request, ForgotPasswordService $forgotPasswordService)
    {
        $this->request = $request;
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function submit() {
        $this->markForgotPasswordSent();
        $params = $this->request->getParams();
        $this->forgotPasswordService->forgotPassword($params["email"]);
        return [
            "redirect:/forgotpass", [
            ]
        ];
    }

    public function markForgotPasswordSent()
    {
        $this->request->getSession()->put("sentPassword", 1);
    }

}