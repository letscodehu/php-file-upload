<?php

namespace Middleware;

use Request\Request;
use Response\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CsrfMiddleware implements Middleware {

    /**
     * @var CsrfTokenManager
     */
    private $csrf;

    public function __construct(CsrfTokenManager $csrf)
    {
        $this->csrf = $csrf;
    }

    function process(Request $request, Response $response, callable $next)
    {
        if ($request->getMethod() === 'POST' && $this->tokenIsInvalid($request)) {
            return new Response("CSRF token is not present!", [], 403, "Forbidden");
        }
        return $next($request, $response);
    }

    private function tokenIsInvalid(Request $request) {
        return !$this->csrf->isTokenValid(new CsrfToken('_csrf', $request->getParam('_csrf')));
    }

}